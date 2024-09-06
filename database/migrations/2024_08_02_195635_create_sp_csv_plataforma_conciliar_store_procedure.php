<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpCsvPlataformaConciliarStoreProcedure extends Migration
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
CREATE PROCEDURE sp_csv_plataforma_conciliar
    @id INT = 0,
    @plataforma_id INT = 0,
    @punto_venta_id INT = 0,
    @alias_id INT = 0,
    @estado_id INT = 0,
    @informacion NVARCHAR(75) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    --CONSULTA OPCION 1 Seleccionamos todos los registros que no han sido procesados.
    IF @opcion = 1
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_plataforma] WITH(NOLOCK) 
        WHERE [procesado] = 0 
        AND [informacion] IN ('REGISTRADO', 'REPROCESAR');
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_csv_plataforma] SET [procesado] = 1 WHERE [id] = @id;
    END

    --CONSULTA OPCION 4 Actualizamos el estado.
    IF @opcion = 4
    BEGIN
        UPDATE [dbo].[tbl_csv_plataforma] SET [informacion] = @informacion, [procesado] = 1 WHERE [id] = @id;
    END

    --CONSULTA OPCION 5 Seleccionamos todos los registros que ya han sido procesados.
    IF @opcion = 5 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_plataforma] WITH(NOLOCK) WHERE [procesado] = 1;
    END

    --CONSULTA OPCION 6 Seleccionamos todos los registros de la plataforma.
    IF @opcion = 6 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_plataforma] WITH(NOLOCK) WHERE [plataforma_id] = @plataforma_id;
    END

    --CONSULTA OPCION 7 Seleccionamos todos los registros del punto de venta.
    IF @opcion = 7 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_plataforma] WITH(NOLOCK) WHERE [punto_venta_id] = @punto_venta_id;
    END

    --CONSULTA OPCION 8 Seleccionamos todos los registros del alias.
    IF @opcion = 8 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_plataforma] WITH(NOLOCK) WHERE [alias_id] = @alias_id;
    END

    --CONSULTA OPCION 9 Seleccionamos todos los registros de la alias, plataforma y punto de venta.
    IF @opcion = 9 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_plataforma] WITH(NOLOCK) WHERE [plataforma_id] = @plataforma_id AND [punto_venta_id] = @punto_venta_id;
    END

    --CONSULTA OPCION 10 Seleccionamos todos los registros del estado y plataforma.
    IF @opcion = 10 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_plataforma] WITH(NOLOCK) WHERE [plataforma_id] = @plataforma_id AND [estado_id] = @estado_id;
    END

    --CONSULTA OPCION 11 Seleccionamos todos los registros del estado.
    IF @opcion = 11 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_plataforma] WITH(NOLOCK) WHERE [estado_id] = @estado_id;
    END

    --CONSULTA OPCION 12 Seleccionamos todos los registros del estado.
    IF @opcion = 12 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_plataforma] WITH(NOLOCK) WHERE [informacion] = 'REGISTRADO';
    END

    --CONSULTA OPCION 13 Seleccionamos todos los registros del estado.
    IF @opcion = 13 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_plataforma] WITH(NOLOCK) WHERE [informacion] = 'REGLA VALIDACION';
    END

    --CONSULTA OPCION 14 Seleccionamos todos los registros del estado.
    IF @opcion = 14 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_plataforma] WITH(NOLOCK) WHERE [informacion] = 'CONCILIACION AUTOMATICA';
    END

    --CONSULTA OPCION 15 Seleccionamos todos los registros del estado.
    IF @opcion = 15 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_plataforma] WITH(NOLOCK) WHERE [informacion] = 'CONCILIACION MANUAL';
    END

    --CONSULTA OPCION 16 Seleccionamos todos los registros del estado.
    IF @opcion = 16 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_plataforma] WITH(NOLOCK) WHERE [informacion] = 'REPROCESAR';
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_csv_plataforma_conciliar;");
    }
}
