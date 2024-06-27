<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpRolMenuConfigStoreProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(
            "
CREATE PROCEDURE [dbo].[sp_rol_menu_config]
    @rol_id INT = 0,
    @menu_id INT = 0,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    --CONSULTA OPCION 1 Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
        SELECT 
		T1.id AS 'rol_id', T1.nombre AS 'rol_nombre', 
		T2.id AS 'menu_id', T2.nombre AS 'menu_nombre', T2.url AS 'menu_url', T2.icono AS 'menu_icono', T2.menu_id AS 'menu_padre'
		FROM [dbo].[tbl_rol_menu] AS T0
		INNER JOIN [dbo].[tbl_rol] AS T1 ON T0.[rol_id] = T1.[id]
		INNER JOIN [dbo].[tbl_menu] AS T2 ON T0.[menu_id] = T2.[id]
        WHERE T1.[deleted_at] IS NULL
    END

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
        INSERT INTO [dbo].[tbl_rol_menu] ([rol_id], [menu_id], [created_at], [created_by])
        VALUES (@rol_id, @menu_id, GETDATE(), @usuario);

        EXECUTE sp_rol_menu_config
        @rol_id = @rol_id,
        @menu_id = @menu_id,
        @usuario = NULL,
        @opcion = 5
    END

    --CONSULTA OPCION 4 Eliminamos el registro en la tabla.
    IF @opcion = 4 
    BEGIN
        DELETE FROM [dbo].[tbl_rol_menu] WHERE [rol_id] = @rol_id AND [menu_id] = @menu_id;
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
        SELECT 
		T1.id AS 'rol_id', T1.nombre AS 'rol_nombre', 
		T2.id AS 'menu_id', T2.nombre AS 'menu_nombre', T2.url AS 'menu_url', T2.icono AS 'menu_icono', T2.menu_id AS 'menu_padre'
		FROM [dbo].[tbl_rol_menu] AS T0
		INNER JOIN [dbo].[tbl_rol] AS T1 ON T0.[rol_id] = T1.[id]
		INNER JOIN [dbo].[tbl_menu] AS T2 ON T0.[menu_id] = T2.[id]
		WHERE T0.[rol_id] = @rol_id AND T0.[menu_id] = @menu_id AND T1.[deleted_at] IS NULL;
    END

    --CONSULTA OPCION 6 Seleccionamos por id el registro en la tabla.
    IF @opcion = 6 
    BEGIN
		DECLARE @TempMenu AS TABLE 
		(id INT, nombre VARCHAR(75), url VARCHAR(250), icono VARCHAR(75), menu_id INT)

		DECLARE @FinalMenu AS TABLE 
		(id INT, nombre VARCHAR(75), url VARCHAR(250), icono VARCHAR(75), menu_id INT)

		INSERT INTO @TempMenu (id, nombre, url, icono, menu_id)
        SELECT 
		T2.id AS 'menu_id', T2.nombre AS 'menu_nombre', T2.url AS 'menu_url', T2.icono AS 'menu_icono', T2.menu_id AS 'menu_padre'
		FROM [dbo].[tbl_rol_menu] AS T0
		INNER JOIN [dbo].[tbl_rol] AS T1 ON T0.[rol_id] = T1.[id]
		INNER JOIN [dbo].[tbl_menu] AS T2 ON T0.[menu_id] = T2.[id]
		WHERE T1.[id] = @rol_id AND T2.[menu_id] = 0 AND T1.[deleted_at] IS NULL;

		DECLARE @id INT, @nombre VARCHAR(75), @url VARCHAR(250), @icono VARCHAR(75), @count INT = (SELECT COUNT(*) FROM @TempMenu);

		WHILE @count > 0
		BEGIN
			SELECT TOP 1 @id = [id], @nombre = [nombre], @url = [url], @icono = [icono], @menu_id = [menu_id] FROM @TempMenu;

			INSERT INTO @FinalMenu (id, nombre, url, icono, menu_id) VALUES (@id, @nombre, @url, @icono, @menu_id);
			INSERT INTO @FinalMenu (id, nombre, url, icono, menu_id)
			SELECT [id], [nombre], [url], [icono], [menu_id] FROM [dbo].[tbl_menu] WHERE [menu_id] = @id;

			DELETE TOP (1) FROM @TempMenu;
            SELECT @count = COUNT(*) FROM @TempMenu;
		END

		SELECT * FROM @FinalMenu;
    END

END
            "
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_rol_menu_config;");
    }
}
