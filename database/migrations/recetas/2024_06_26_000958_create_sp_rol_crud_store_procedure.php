<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpRolCrudStoreProcedure extends Migration
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
CREATE PROCEDURE sp_rol_crud
    @id INT = 0,
    @nombre NVARCHAR(75) NULL,
    @descripcion NVARCHAR(255) NULL,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ultimo_id INT = 0;

    --CONSULTA OPCION 1  Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
        SELECT * FROM [dbo].[tbl_rol] WHERE [deleted_at] IS NULL;
    END

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
        INSERT INTO [dbo].[tbl_rol] ([nombre], [descripcion], [created_at], [created_by])
        VALUES (@nombre, @descripcion, GETDATE(), @usuario);

        SET @ultimo_id = IDENT_CURRENT(N'[dbo].[tbl_rol]');

        EXECUTE sp_rol_crud
        @id = @ultimo_id,
        @nombre = null,
        @descripcion = null,
        @usuario = NULL,
        @opcion = 5
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_rol]
        SET 
            [nombre] = @nombre,  
            [descripcion] = @descripcion,
            [updated_by] = @usuario,
            [updated_at] = GETDATE()
        WHERE [id] = @id;

        EXECUTE sp_rol_crud 
        @id = @id, 
        @nombre = null, 
        @descripcion = null,
        @usuario = NULL,
        @opcion = 5
    END

    --CONSULTA OPCION 4 Eliminamos el registro en la tabla.
    IF @opcion = 4 
    BEGIN
        UPDATE [dbo].[tbl_rol] SET [deleted_by] = @usuario, [deleted_at] = GETDATE() WHERE [id] = @id;
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
        SELECT * FROM [dbo].[tbl_rol] WHERE [id] = @id;
    END

    --CONSULTA OPCION 6 Seleccionamos id y nombre para llenar lista desplegable.
    IF @opcion = 6 
    BEGIN
        SELECT [id], [nombre] FROM [dbo].[tbl_rol] WHERE [deleted_at] IS NULL;
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_rol_crud;");
    }
}
