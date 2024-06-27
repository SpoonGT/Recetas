<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpMenuCrudStoreProcedure extends Migration
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
CREATE PROCEDURE [dbo].[sp_menu_crud]
    @id INT = 0,
    @nombre NVARCHAR(75) NULL,
    @url NVARCHAR(250) NULL,
    @icono NVARCHAR(75) NULL,
    @menu_id INT NULL,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ultimo_id INT = 0;

    --CONSULTA OPCION 1  Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
        SELECT * FROM [dbo].[tbl_menu];
    END

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
        INSERT INTO [dbo].[tbl_menu] ([nombre], [url], [icono], [menu_id], [created_at], [created_by])
        VALUES (@nombre, @url, @icono, @menu_id, GETDATE(), @usuario);

        SET @ultimo_id = IDENT_CURRENT(N'[dbo].[tbl_menu]');

        EXECUTE sp_menu_crud
        @id = @ultimo_id,
        @nombre = null,
        @url = null,
        @icono = null,
        @menu_id = null,
        @usuario = NULL,
        @opcion = 5
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_menu]
        SET 
            [nombre] = @nombre,  
            [url] = @url,
            [icono] = @icono,
            [menu_id] = @menu_id,
            [updated_by] = @usuario,
            [updated_at] = GETDATE()
        WHERE [id] = @id;

        EXECUTE sp_menu_crud
        @id = @id,
        @nombre = null,
        @url = null,
        @icono = null,
        @menu_id = null,
        @usuario = NULL,
        @opcion = 5
    END

    --CONSULTA OPCION 4 Eliminamos el registro en la tabla.
    IF @opcion = 4 
    BEGIN
        DELETE FROM [dbo].[tbl_menu] WHERE [id] = @id;
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
        SELECT * FROM [dbo].[tbl_menu] WHERE [id] = @id;
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_menu_crud;");
    }
}
