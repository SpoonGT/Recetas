<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpCsvPlataformaTemporalMaintenanceStoreProcedure extends Migration
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
CREATE PROCEDURE sp_csv_plataforma_temporal_maintenance
    @id INT = 0,
    @mensaje NVARCHAR(MAX) NULL,
    @procesado BIT NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    --CONSULTA OPCION 1 Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
        DECLARE @ultima_carga DATETIME, @usuario NVARCHAR(50)

        SELECT * FROM [dbo].[tbl_csv_plataforma_temporal] WITH(NOLOCK) WHERE [procesado] = 0;
        SELECT TOP 1 @ultima_carga = [created_at], @usuario = created_by FROM [dbo].[tbl_csv_plataforma_temporal] WITH(NOLOCK);

        SELECT 
        (SELECT COUNT(*) FROM [dbo].[tbl_csv_plataforma_temporal] WITH(NOLOCK) WHERE [procesado] = 0) AS pendientes,
        (SELECT COUNT(*) FROM [dbo].[tbl_csv_plataforma_temporal] WITH(NOLOCK) WHERE [procesado] = 1) AS procesados,
        @ultima_carga AS ultima_carga,
        @usuario AS usuario
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_csv_plataforma_temporal] SET [procesado] = @procesado, [mensaje] = @mensaje WHERE [id] = @id;
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_plataforma_temporal] WITH(NOLOCK) WHERE [id] = @id;
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_csv_plataforma_temporal_maintenance;");
    }
}
