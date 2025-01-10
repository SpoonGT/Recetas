<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpTokenMaintenanceStoreProcedure extends Migration
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
CREATE PROCEDURE sp_token_maintenance
    @token NVARCHAR(250) NULL,
    @token_refresh NVARCHAR(250) NULL,
    @usuario_id INT = 0,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ultimo_id INT = 0;

    --CONSULTA OPCION 1 Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
        SELECT * FROM [dbo].[tbl_token] WHERE [deleted_at] IS NULL;
    END

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
        UPDATE [dbo].[tbl_token] SET [deleted_by] = @usuario, [deleted_at] = GETDATE() WHERE [usuario_id] = @usuario_id;

        INSERT INTO [dbo].[tbl_token] ([token], [usuario_id], [expira])
        VALUES (@token, @usuario_id, (SELECT DATEADD(minute, 65, GETDATE())));

        EXECUTE sp_token_maintenance
        @token = @token,
        @token_refresh = null,
        @usuario_id = @usuario_id,
        @usuario = null,
        @opcion = 5
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        IF (NOT EXISTS(SELECT * FROM [dbo].[tbl_token] WHERE [deleted_at] IS NULL AND [usuario_id] = @usuario_id AND [token] = @token_refresh))
        BEGIN
            RAISERROR ('El token ya no se encuentra activo, por lo tanto no se puede generar uno nuevo.', 16, 1);
        END
        ELSE
        BEGIN
            EXECUTE sp_token_maintenance
            @token = @token_refresh,
            @token_refresh = null,
            @usuario_id = @usuario_id,
            @usuario = null,
            @opcion = 4

            INSERT INTO [dbo].[tbl_token] ([token], [token_refresh], [usuario_id], [expira])
            VALUES (@token, @token_refresh, @usuario_id, (SELECT DATEADD(minute, 65, GETDATE())));

            EXECUTE sp_token_maintenance
            @token = @token,
            @token_refresh = null,
            @usuario_id = @usuario_id,
            @usuario = null,
            @opcion = 5
        END
    END

    --CONSULTA OPCION 4 Eliminamos el registro en la tabla.
    IF @opcion = 4 
    BEGIN
        UPDATE [dbo].[tbl_token] SET [deleted_by] = @usuario, [deleted_at] = GETDATE() WHERE [usuario_id] = @usuario_id AND [token] = @token;
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
        SELECT TOP 1 * FROM [dbo].[tbl_token] WHERE [deleted_at] IS NULL AND [usuario_id] = @usuario_id AND [token] = @token;
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_token_maintenance;");
    }
}
