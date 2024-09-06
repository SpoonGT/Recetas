<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpCsvIcgConciliarStoreProcedure extends Migration
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
CREATE PROCEDURE sp_csv_icg_conciliar
    @id INT = 0,
    @plataforma_id INT = 0,
    @punto_venta_id INT = 0,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    --CONSULTA OPCION 1 Seleccionamos todos los registros que no han sido procesados.
    IF @opcion = 1
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_icg] WITH(NOLOCK) WHERE [procesado] = 0;
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_csv_icg] SET [procesado] = 1 WHERE [id] = @id;
    END

    --CONSULTA OPCION 4 Actualizamos el registro en la tabla.
    IF @opcion = 4
    BEGIN
        UPDATE [dbo].[tbl_csv_icg] SET [procesado] = 0, [no_id] = 1 WHERE [no_id] = 0 AND [id_pedido] LIKE '%NO ID%' AND [plataforma_id] = @plataforma_id;
        
        UPDATE [dbo].[tbl_csv_plataforma_temporal] SET [procesado] = 1, [mensaje] = 'Fuera de la conciliación por identificador inválido' WHERE [procesado] = 0 AND [id_pedido] LIKE '%NO ID%' AND [plataforma_id] = @plataforma_id;
    END

    --CONSULTA OPCION 5 Seleccionamos todos los registros que ya han sido procesados.
    IF @opcion = 5 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_icg] WITH(NOLOCK) WHERE [procesado] = 1;
    END

    --CONSULTA OPCION 6 Seleccionamos todos los registros de la plataforma.
    IF @opcion = 6 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_icg] WITH(NOLOCK) WHERE [plataforma_id] = @plataforma_id;
    END

    --CONSULTA OPCION 7 Seleccionamos todos los registros del punto de venta.
    IF @opcion = 7 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_icg] WITH(NOLOCK) WHERE [punto_venta_id] = @punto_venta_id;
    END

    --CONSULTA OPCION 8 Seleccionamos todos los registros de la plataforma y punto de venta.
    IF @opcion = 8 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_icg] WITH(NOLOCK) WHERE [plataforma_id] = @plataforma_id AND [punto_venta_id] = @punto_venta_id;
    END

    --CONSULTA OPCION 9 Seleccionamos todos los no id.
    IF @opcion = 9 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_icg] WITH(NOLOCK) WHERE [no_id] = 1 AND [procesado] = 0;
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_csv_icg_conciliar;");
    }
}
