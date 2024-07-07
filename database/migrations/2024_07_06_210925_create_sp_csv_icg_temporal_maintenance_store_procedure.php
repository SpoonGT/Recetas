<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpCsvIcgTemporalMaintenanceStoreProcedure extends Migration
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
CREATE PROCEDURE sp_csv_icg_temporal_maintenance
    @id INT = 0,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    --CONSULTA OPCION 1 Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
        DECLARE @ultiama_carga DATETIME, @usuario NVARCHAR(50)

        SELECT * FROM [dbo].[tbl_csv_icg_temporal] WITH(NOLOCK);
        SELECT TOP 1 @ultiama_carga = [created_at], @usuario = created_by FROM [dbo].[tbl_csv_icg_temporal] WITH(NOLOCK);

        SELECT 
        (SELECT COUNT(*) FROM [dbo].[tbl_csv_icg_temporal] WITH(NOLOCK) WHERE [procesado] = 0) AS pendientes,
        (SELECT COUNT(*) FROM [dbo].[tbl_csv_icg_temporal] WITH(NOLOCK) WHERE [procesado] = 1) AS procesados,
        @ultiama_carga AS ultiama_carga,
        @usuario AS usuario
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_csv_icg_temporal] SET [procesado] = 1 WHERE [id] = @id;

        EXECUTE sp_csv_icg_temporal_maintenance
        @id = @id,
        @opcion = 5
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_icg_temporal] WITH(NOLOCK) WHERE [id] = @id;
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_csv_icg_temporal_maintenance;");
    }
}
