<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpPlataformaCrudStoreProcedure extends Migration
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
CREATE PROCEDURE sp_plataforma_crud
    @id INT = 0,
    @abreviatura NVARCHAR(10) NULL,
    @plataforma NVARCHAR(125) NULL,
    @fila INT = 0,
    @redondea BIT NULL,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ultimo_id INT = 0;

    --CONSULTA OPCION 1 Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
        SELECT * FROM [dbo].[tbl_plataforma] WITH(NOLOCK);
    END

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
		IF(EXISTS(SELECT * FROM [dbo].[tbl_plataforma] WHERE [abreviatura] = @abreviatura))
		BEGIN
			SELECT @ultimo_id = [id] FROM [dbo].[tbl_plataforma] WHERE [abreviatura] = @abreviatura;

			EXECUTE sp_plataforma_crud
            @id = @ultimo_id,
            @abreviatura = @abreviatura,
            @plataforma = @plataforma,
            @fila = @fila,
            @redondea = @redondea,
            @usuario = @usuario,
			@opcion = 3
		END
        ELSE
        BEGIN
            INSERT INTO [dbo].[tbl_plataforma] ([abreviatura], [plataforma], [fila], [redondea], [created_at], [created_by])
            VALUES (@abreviatura, @plataforma, @fila, @redondea, GETDATE(), @usuario);

            SET @ultimo_id = IDENT_CURRENT(N'[dbo].[tbl_plataforma]');

			EXECUTE sp_plataforma_crud
            @id = @ultimo_id,
            @abreviatura = null,
            @plataforma = null,
            @fila = 0,
            @redondea = null,
            @usuario = NULL,
			@opcion = 5
        END
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_plataforma]
        SET 
            [abreviatura] = @abreviatura,
            [plataforma] = @plataforma,
            [fila] = @fila,
            [redondea] = @redondea,
            [updated_by] = @usuario,
            [updated_at] = GETDATE(),
            [deleted_by] = NULL,
            [deleted_at] = NULL
        WHERE [id] = @id;

        EXECUTE sp_plataforma_crud
        @id = @id,
        @abreviatura = null,
        @plataforma = null,
        @fila = 0,
        @redondea = null,
        @usuario = NULL,
        @opcion = 5
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
        SELECT * FROM [dbo].[tbl_plataforma] WITH(NOLOCK) WHERE [deleted_at] IS NULL AND [id] = @id;
    END

    --CONSULTA OPCION 6 Seleccionamos id y nombre para llenar lista desplegable.
    IF @opcion = 6 
    BEGIN
        SELECT [id], [abreviatura], [plataforma] FROM [dbo].[tbl_plataforma] WITH(NOLOCK) WHERE [deleted_at] IS NULL;
    END

    --CONSULTA OPCION 7 Seleccionamos por abreviatura.
    IF @opcion = 7 
    BEGIN
        SELECT * FROM [dbo].[tbl_plataforma] WITH(NOLOCK) WHERE [abreviatura] = @abreviatura;
    END

    --CONSULTA OPCION 8 Activar el registro en la tabla.
    IF @opcion = 8 
    BEGIN
        UPDATE [dbo].[tbl_plataforma] SET [deleted_by] = NULL, [deleted_at] = NULL WHERE [id] = @id;
    END

    --CONSULTA OPCION 9 Desactivar el registro en la tabla.
    IF @opcion = 9 
    BEGIN
        UPDATE [dbo].[tbl_plataforma] SET [deleted_by] = @usuario, [deleted_at] = GETDATE() WHERE [id] = @id;
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_plataforma_crud;");
    }
}
