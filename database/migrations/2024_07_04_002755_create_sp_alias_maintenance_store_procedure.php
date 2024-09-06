<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpAliasMaintenanceStoreProcedure extends Migration
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
CREATE PROCEDURE sp_alias_maintenance
    @id INT = 0,
    @alias NVARCHAR(125) NULL,
    @punto_venta_id INT = 0,
    @plataforma_id INT = 0,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ultimo_id INT = 0;

    --CONSULTA OPCION 1 Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
        SELECT T0.*, 
        T1.alias AS 'punto_venta', 
        T2.plataforma AS 'plataforma' 
		FROM [dbo].[tbl_alias] T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_punto_venta] T1 WITH(NOLOCK) ON T0.punto_venta_id = T1.id AND T1.[deleted_at] IS NULL
		INNER JOIN [dbo].[tbl_plataforma] T2 WITH(NOLOCK) ON T0.plataforma_id = T2.id AND T2.[deleted_at] IS NULL
		WHERE T0.[deleted_at] IS NULL;
    END

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
		IF(EXISTS(SELECT * FROM [dbo].[tbl_alias] WHERE [punto_venta_id] = @punto_venta_id AND [plataforma_id] = @plataforma_id))
		BEGIN
			SELECT @ultimo_id = [id] FROM [dbo].[tbl_alias] WHERE [punto_venta_id] = @punto_venta_id AND [plataforma_id] = @plataforma_id;

			EXECUTE sp_alias_maintenance
            @id = @ultimo_id,
            @alias = @alias,
            @punto_venta_id = @punto_venta_id,
            @plataforma_id = @plataforma_id,
            @usuario = @usuario,
			@opcion = 3
		END
        ELSE
        BEGIN
            INSERT INTO [dbo].[tbl_alias] ([alias], [punto_venta_id], [plataforma_id], [created_at], [created_by])
            VALUES (@alias, @punto_venta_id, @plataforma_id, GETDATE(), @usuario);

            SET @ultimo_id = IDENT_CURRENT(N'[dbo].[tbl_alias]');

			EXECUTE sp_alias_maintenance
            @id = @ultimo_id,
            @alias = NULL,
            @punto_venta_id = NULL,
            @plataforma_id = NULL,
            @usuario = NULL,
			@opcion = 5
        END
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_alias]
        SET 
            [alias] = @alias,
            [punto_venta_id] = @punto_venta_id,
            [plataforma_id] = @plataforma_id,
            [updated_by] = @usuario,
            [updated_at] = GETDATE(),
            [deleted_by] = NULL,
            [deleted_at] = NULL
        WHERE [id] = @id;

        EXECUTE sp_alias_maintenance
        @id = @id,
        @alias = NULL,
        @punto_venta_id = NULL,
        @plataforma_id = NULL,
        @usuario = NULL,
        @opcion = 5
    END

    --CONSULTA OPCION 4 Eliminamos el registro en la tabla.
    IF @opcion = 4 
    BEGIN
        UPDATE [dbo].[tbl_alias] SET [deleted_by] = @usuario, [deleted_at] = GETDATE() WHERE [id] = @id;
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
        SELECT T0.*, 
        T1.alias AS 'punto_venta', 
        T2.plataforma AS 'plataforma' 
		FROM [dbo].[tbl_alias] T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_punto_venta] T1 WITH(NOLOCK) ON T0.punto_venta_id = T1.id AND T1.[deleted_at] IS NULL
		INNER JOIN [dbo].[tbl_plataforma] T2 WITH(NOLOCK) ON T0.plataforma_id = T2.id AND T2.[deleted_at] IS NULL
		WHERE T0.[deleted_at] IS NULL AND T0.[id] = @id;
    END

    --CONSULTA OPCION 6 Seleccionamos id y nombre para llenar lista desplegable.
    IF @opcion = 6 
    BEGIN
		DECLARE @TempAlias AS TABLE 
		(id INT, punto_venta VARCHAR(125))

		DECLARE @FinalAlias AS TABLE 
		(id INT, punto_venta VARCHAR(125), plataforma VARCHAR(125), punto_venta_id INT, plataforma_id INT)

		INSERT INTO @TempAlias (id, punto_venta)
        SELECT [id], [alias] FROM [dbo].[tbl_punto_venta] WITH(NOLOCK) WHERE [deleted_at] IS NULL ORDER BY [alias];

		DECLARE @punto_venta VARCHAR(125), @plataforma VARCHAR(125), @count INT = (SELECT COUNT(*) FROM @TempAlias);

		WHILE @count > 0
		BEGIN
			SELECT TOP 1 @id = 0, @punto_venta = [punto_venta], @plataforma = '', @punto_venta_id = [id] FROM @TempAlias;

			SELECT @id = ISNULL([id], 0), @plataforma = ISNULL([alias], '') 
			FROM [dbo].[tbl_alias] WITH(NOLOCK) 
			WHERE [plataforma_id] = @plataforma_id AND [punto_venta_id] = @punto_venta_id AND [deleted_at] IS NULL;

			INSERT INTO @FinalAlias (id, punto_venta, plataforma, punto_venta_id, plataforma_id) 
			VALUES (@id, @punto_venta, @plataforma, @punto_venta_id, @plataforma_id);

			DELETE TOP (1) FROM @TempAlias;
            SELECT @count = COUNT(*) FROM @TempAlias;
		END

		SELECT * FROM @FinalAlias ORDER BY [punto_venta] ASC;
    END

    --CONSULTA OPCION 7 Seleccionamos por punto de venta y alias.
    IF @opcion = 7 
    BEGIN
        SELECT T0.*, 
        T1.alias AS 'punto_venta', 
        T2.plataforma AS 'plataforma' 
		FROM [dbo].[tbl_alias] T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_punto_venta] T1 WITH(NOLOCK) ON T0.punto_venta_id = T1.id AND T1.[deleted_at] IS NULL
		INNER JOIN [dbo].[tbl_plataforma] T2 WITH(NOLOCK) ON T0.plataforma_id = T2.id AND T2.[deleted_at] IS NULL
		WHERE T1.[id] = @punto_venta_id AND T2.[id] = @plataforma_id;
    END

    --CONSULTA OPCION 8 Seleccionamos por plataforma.
    IF @opcion = 8 
    BEGIN
        SELECT T0.*, 
        T1.alias AS 'punto_venta', 
        T2.plataforma AS 'plataforma' 
		FROM [dbo].[tbl_alias] T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_punto_venta] T1 WITH(NOLOCK) ON T0.punto_venta_id = T1.id AND T1.[deleted_at] IS NULL
		INNER JOIN [dbo].[tbl_plataforma] T2 WITH(NOLOCK) ON T0.plataforma_id = T2.id AND T2.[deleted_at] IS NULL
		WHERE T2.[id] = @plataforma_id AND T0.[deleted_at] IS NULL;
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_alias_maintenance;");
    }
}
