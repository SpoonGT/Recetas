<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpUsuarioCrudStoreProcedure extends Migration
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
CREATE PROCEDURE sp_usuario_crud
    @id INT = 0,
    @nombre_completo NVARCHAR(150) NULL,
    @nameuser NVARCHAR(25) NULL,
    @contrasenia NVARCHAR(250) NULL,
    @email NVARCHAR(100) NULL,
    @rol_id INT = 0,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ultimo_id INT = 0;

    --CONSULTA OPCION 1  Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
        SELECT T0.*, T1.nombre AS 'rol' 
		FROM [dbo].[tbl_usuario] T0
		INNER JOIN [dbo].[tbl_rol] T1 ON T0.rol_id = T1.id
		WHERE T0.[deleted_at] IS NULL;
    END

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
		IF(EXISTS(SELECT * FROM [dbo].[tbl_usuario] WHERE [usuario] = @nameuser))
		BEGIN
			SELECT @ultimo_id = [id] FROM [dbo].[tbl_usuario] WHERE [usuario] = @nameuser;

			EXECUTE sp_usuario_crud
            @id = @ultimo_id,
            @nombre_completo = null,
            @nameuser = null,
            @contrasenia = null,
            @email = null,
            @rol_id = null,
            @usuario = NULL,
			@opcion = 3
		END
        ELSE
        BEGIN
            INSERT INTO [dbo].[tbl_usuario] ([nombre_completo], [usuario], [contrasenia], [email], [rol_id], [created_at], [created_by])
            VALUES (@nombre_completo, @nameuser, @contrasenia, @email, @rol_id, GETDATE(), @usuario);

            SET @ultimo_id = IDENT_CURRENT(N'[dbo].[tbl_usuario]');

            EXECUTE sp_usuario_crud
            @id = @ultimo_id,
            @nombre_completo = null,
            @nameuser = null,
            @contrasenia = null,
            @email = null,
            @rol_id = null,
            @usuario = NULL,
            @opcion = 5
        END
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_usuario]
        SET 
            [nombre_completo] = @nombre_completo,  
            [usuario] = @nameuser,
			[contrasenia] = @contrasenia,
            [email] = @email,
            [rol_id] = @rol_id,
            [updated_by] = @usuario,
            [updated_at] = GETDATE(),
            [deleted_by] = NULL,
            [deleted_at] = NULL
        WHERE [id] = @id;

        EXECUTE sp_usuario_crud
        @id = @id,
        @nombre_completo = null,
        @nameuser = null,
        @contrasenia = null,
        @email = null,
        @rol_id = null,
        @usuario = NULL,
        @opcion = 5
    END

    --CONSULTA OPCION 4 Eliminamos el registro en la tabla.
    IF @opcion = 4 
    BEGIN
        UPDATE [dbo].[tbl_usuario] SET [deleted_by] = @usuario, [deleted_at] = GETDATE() WHERE [id] = @id;
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
        SELECT T0.*, T1.nombre AS 'rol' 
		FROM [dbo].[tbl_usuario] T0
		INNER JOIN [dbo].[tbl_rol] T1 ON T0.rol_id = T1.id
		WHERE T0.[deleted_at] IS NULL AND T0.[id] = @id;
    END

    --CONSULTA OPCION 6 Seleccionamos id y nombre para llenar lista desplegable.
    IF @opcion = 6 
    BEGIN
        SELECT [id], [nombre_completo] FROM [dbo].[tbl_usuario] WHERE [deleted_at] IS NULL;
    END

    --CONSULTA OPCION 7 Seleccionamos registro.
    IF @opcion = 7 
    BEGIN
        SELECT T0.*, T1.nombre AS 'rol' 
		FROM [dbo].[tbl_usuario] T0
		INNER JOIN [dbo].[tbl_rol] T1 ON T0.rol_id = T1.id
		WHERE T0.[deleted_at] IS NULL AND T0.[usuario] = @nameuser AND T0.[contrasenia] = @contrasenia;
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_usuario_crud;");
    }
}
