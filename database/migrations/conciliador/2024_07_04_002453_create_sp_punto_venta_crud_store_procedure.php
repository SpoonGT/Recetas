<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpPuntoVentaCrudStoreProcedure extends Migration
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
CREATE PROCEDURE sp_punto_venta_crud
    @id INT = 0,
    @codigo NVARCHAR(25) NULL,
    @local NVARCHAR(125) NULL,
    @alias NVARCHAR(125) NULL,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ultimo_id INT = 0;

    --CONSULTA OPCION 1 Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
        SELECT * FROM [dbo].[tbl_punto_venta] WITH(NOLOCK) WHERE [deleted_at] IS NULL;
    END

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
		IF(EXISTS(SELECT * FROM [dbo].[tbl_punto_venta] WHERE [codigo] = @codigo))
		BEGIN
			SELECT @ultimo_id = [id] FROM [dbo].[tbl_punto_venta] WHERE [codigo] = @codigo;

			EXECUTE sp_punto_venta_crud
            @id = @ultimo_id,
            @codigo = @codigo,
            @local = @local,
            @alias = @alias,
            @usuario = @usuario,
			@opcion = 3
		END
        ELSE
        BEGIN
            INSERT INTO [dbo].[tbl_punto_venta] ([codigo], [local], [alias], [created_at], [created_by])
            VALUES (@codigo, @local, @alias, GETDATE(), @usuario);

            SET @ultimo_id = IDENT_CURRENT(N'[dbo].[tbl_punto_venta]');

			EXECUTE sp_punto_venta_crud
            @id = @ultimo_id,
            @codigo = null,
            @local = null,
            @alias = null,
            @usuario = NULL,
			@opcion = 5
        END
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_punto_venta]
        SET 
            [codigo] = @codigo,  
            [local] = @local,  
            [alias] = @alias,
            [updated_by] = @usuario,
            [updated_at] = GETDATE(),
            [deleted_by] = NULL,
            [deleted_at] = NULL
        WHERE [id] = @id;

        EXECUTE sp_punto_venta_crud
        @id = @id,
        @codigo = null,
        @local = null,
        @alias = null,
        @usuario = NULL,
        @opcion = 5
    END

    --CONSULTA OPCION 4 Eliminamos el registro en la tabla.
    IF @opcion = 4 
    BEGIN
        UPDATE [dbo].[tbl_punto_venta] SET [deleted_by] = @usuario, [deleted_at] = GETDATE() WHERE [id] = @id;
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
        SELECT * FROM [dbo].[tbl_punto_venta] WITH(NOLOCK) WHERE [deleted_at] IS NULL AND [id] = @id;
    END

    --CONSULTA OPCION 6 Seleccionamos lista desplegable.
    IF @opcion = 6 
    BEGIN
        SELECT 0 AS id, [alias] AS 'punto_venta', '' AS 'plataforma', [id] AS 'punto_venta_id', 0 AS plataforma_id
        FROM [dbo].[tbl_punto_venta] WITH(NOLOCK) WHERE [deleted_at] IS NULL;
    END

    --CONSULTA OPCION 7 Seleccionamos por alias.
    IF @opcion = 7 
    BEGIN
        SELECT * FROM [dbo].[tbl_punto_venta] WITH(NOLOCK) WHERE [alias] = @alias;
    END

    --CONSULTA OPCION 8 Seleccionamos por local.
    IF @opcion = 8 
    BEGIN
        SELECT * FROM [dbo].[tbl_punto_venta] WITH(NOLOCK) WHERE [local] = @local;
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_punto_venta_crud;");
    }
}
