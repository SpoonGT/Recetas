<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpImportacionNetsuitMaintenanceStoreProcedure extends Migration
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
CREATE PROCEDURE sp_importacion_netsuit_maintenance
    @id INT = 0,
    @importacion_netsuit_id INT = 0,
    @estado NVARCHAR(25) NULL,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

	DECLARE @ultimo_id INT = 0;

    --CONSULTA OPCION 1 Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
		SELECT * FROM [dbo].[tbl_importacion_netsuit] WITH(NOLOCK);
    END

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
		INSERT INTO [dbo].[tbl_importacion_netsuit] ([estado], [created_at], [created_by])
		VALUES ('PROCESANDO', GETDATE(), @usuario);

		SET @ultimo_id = IDENT_CURRENT(N'[dbo].[tbl_importacion_netsuit]');

		EXECUTE sp_importacion_netsuit_maintenance
		@id = @ultimo_id,
		@importacion_netsuit_id = 0,
		@estado = null,
		@usuario = null,
		@opcion = 5
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_importacion_netsuit_data]
        SET 
            [estado] = @estado
        WHERE [id] = @importacion_netsuit_id;

		IF(NOT EXISTS(SELECT * FROM [dbo].[tbl_importacion_netsuit_data] WHERE [importacion_netsuit_id] = @id AND [estado] = 'PROCESANDO'))
		BEGIN
			UPDATE [dbo].[tbl_importacion_netsuit] SET [estado] = 'VALIDADO' WHERE [id] = @id;
		END
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
		SELECT * 
		FROM [dbo].[tbl_importacion_netsuit] WITH(NOLOCK)
		WHERE [id] = @id;
    END

    --CONSULTA OPCION 6 Seleccionamos por id el registro en la tabla.
    IF @opcion = 6
    BEGIN
		SELECT * 
		FROM [dbo].[tbl_importacion_netsuit_data] WITH(NOLOCK)
		WHERE [importacion_netsuit_id] = @id;
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
		DB::unprepared("DROP PROCEDURE IF EXISTS sp_importacion_netsuit_maintenance;");
	}
}
