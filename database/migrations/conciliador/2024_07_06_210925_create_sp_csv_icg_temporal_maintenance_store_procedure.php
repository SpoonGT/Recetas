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
    @mensaje NVARCHAR(MAX) NULL,
    @procesado BIT NULL,
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
        UPDATE [dbo].[tbl_csv_icg_temporal] SET [procesado] = 1, [mensaje] = @mensaje WHERE [id] = @id;
    END

    --CONSULTA OPCION 4 Eliminamos el registro en la tabla.
    IF @opcion = 4 
    BEGIN
        DELETE FROM [dbo].[tbl_csv_icg_temporal] WHERE [id] = @id;
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_icg_temporal] WITH(NOLOCK) WHERE [id] = @id;
    END

    --CONSULTA OPCION 6 Seleccionamos registros repetidos.
    IF @opcion = 6 
    BEGIN
        SELECT 
        (
            SELECT STRING_AGG(TEMP.[id], ' | ')
            FROM [dbo].[tbl_csv_icg_temporal] AS TEMP WITH(NOLOCK) 
            WHERE TEMP.[plataforma] = TABLA.[plataforma]
            AND TEMP.[id_pedido] = TABLA.[id_pedido]
            AND TEMP.[local] = TABLA.[local]
            AND TEMP.[serie_compuesta] = TABLA.[serie_compuesta]
            AND TEMP.[fecha_pedido] = TABLA.[fecha_pedido] 
            AND TEMP.[total_bruto] = TABLA.[total_bruto]
            AND CONVERT(DECIMAL, REPLACE(ISNULL(TEMP.[total_promocion], '0'), ',', '')) = CONVERT(DECIMAL, REPLACE(ISNULL(TABLA.[total_promocion], '0'), ',', ''))
            AND CONVERT(DECIMAL, REPLACE(ISNULL(TEMP.[total_neto], '0'), ',', '')) = CONVERT(DECIMAL, REPLACE(ISNULL(TABLA.[total_neto], '0'), ',', ''))
            AND TEMP.[forma_pago] = TABLA.[forma_pago]
            AND TEMP.[procesado] = 0
        ) AS 'id',
        [plataforma], 
        [id_pedido], 
        [local], 
        [fecha_pedido], 
        [fecha_entrega], 
        [total_bruto], 
        [total_promocion], 
        [total_neto], 
        [serie_compuesta], 
        (
            SELECT STRING_AGG(TEMP.[numero_documento], ' | ')
            FROM [dbo].[tbl_csv_icg_temporal] AS TEMP WITH(NOLOCK) 
            WHERE TEMP.[plataforma] = TABLA.[plataforma]
            AND TEMP.[id_pedido] = TABLA.[id_pedido]
            AND TEMP.[local] = TABLA.[local]
            AND TEMP.[serie_compuesta] = TABLA.[serie_compuesta]
            AND TEMP.[fecha_pedido] = TABLA.[fecha_pedido] 
            AND TEMP.[total_bruto] = TABLA.[total_bruto]
            AND CONVERT(DECIMAL, REPLACE(ISNULL(TEMP.[total_promocion], '0'), ',', '')) = CONVERT(DECIMAL, REPLACE(ISNULL(TABLA.[total_promocion], '0'), ',', ''))
            AND CONVERT(DECIMAL, REPLACE(ISNULL(TEMP.[total_neto], '0'), ',', '')) = CONVERT(DECIMAL, REPLACE(ISNULL(TABLA.[total_neto], '0'), ',', ''))
            AND TEMP.[forma_pago] = TABLA.[forma_pago]
            AND TEMP.[procesado] = 0
        ) AS 'numero_documento',
        (
            SELECT STRING_AGG(TEMP.[numero_orden], ' | ')
            FROM [dbo].[tbl_csv_icg_temporal] AS TEMP WITH(NOLOCK) 
            WHERE TEMP.[plataforma] = TABLA.[plataforma]
            AND TEMP.[id_pedido] = TABLA.[id_pedido]
            AND TEMP.[local] = TABLA.[local]
            AND TEMP.[serie_compuesta] = TABLA.[serie_compuesta]
            AND TEMP.[fecha_pedido] = TABLA.[fecha_pedido] 
            AND TEMP.[total_bruto] = TABLA.[total_bruto]
            AND CONVERT(DECIMAL, REPLACE(ISNULL(TEMP.[total_promocion], '0'), ',', '')) = CONVERT(DECIMAL, REPLACE(ISNULL(TABLA.[total_promocion], '0'), ',', ''))
            AND CONVERT(DECIMAL, REPLACE(ISNULL(TEMP.[total_neto], '0'), ',', '')) = CONVERT(DECIMAL, REPLACE(ISNULL(TABLA.[total_neto], '0'), ',', ''))
            AND TEMP.[forma_pago] = TABLA.[forma_pago]
            AND TEMP.[procesado] = 0
        ) AS 'numero_orden',
        [forma_pago],
        (
            SELECT STRING_AGG(TEMP.[nombre_cliente], ' | ')
            FROM [dbo].[tbl_csv_icg_temporal] AS TEMP WITH(NOLOCK) 
            WHERE TEMP.[plataforma] = TABLA.[plataforma]
            AND TEMP.[id_pedido] = TABLA.[id_pedido]
            AND TEMP.[local] = TABLA.[local]
            AND TEMP.[serie_compuesta] = TABLA.[serie_compuesta]
            AND TEMP.[fecha_pedido] = TABLA.[fecha_pedido] 
            AND TEMP.[total_bruto] = TABLA.[total_bruto]
            AND CONVERT(DECIMAL, REPLACE(ISNULL(TEMP.[total_promocion], '0'), ',', '')) = CONVERT(DECIMAL, REPLACE(ISNULL(TABLA.[total_promocion], '0'), ',', ''))
            AND CONVERT(DECIMAL, REPLACE(ISNULL(TEMP.[total_neto], '0'), ',', '')) = CONVERT(DECIMAL, REPLACE(ISNULL(TABLA.[total_neto], '0'), ',', ''))
            AND TEMP.[forma_pago] = TABLA.[forma_pago]
            AND TEMP.[procesado] = 0
        ) AS 'nombre_cliente',
        (
            SELECT STRING_AGG(TEMP.[cajero], ' | ')
            FROM [dbo].[tbl_csv_icg_temporal] AS TEMP WITH(NOLOCK) 
            WHERE TEMP.[plataforma] = TABLA.[plataforma]
            AND TEMP.[id_pedido] = TABLA.[id_pedido]
            AND TEMP.[local] = TABLA.[local]
            AND TEMP.[serie_compuesta] = TABLA.[serie_compuesta]
            AND TEMP.[fecha_pedido] = TABLA.[fecha_pedido] 
            AND TEMP.[total_bruto] = TABLA.[total_bruto]
            AND CONVERT(DECIMAL, REPLACE(ISNULL(TEMP.[total_promocion], '0'), ',', '')) = CONVERT(DECIMAL, REPLACE(ISNULL(TABLA.[total_promocion], '0'), ',', ''))
            AND CONVERT(DECIMAL, REPLACE(ISNULL(TEMP.[total_neto], '0'), ',', '')) = CONVERT(DECIMAL, REPLACE(ISNULL(TABLA.[total_neto], '0'), ',', ''))
            AND TEMP.[forma_pago] = TABLA.[forma_pago]
            AND TEMP.[procesado] = 0
        ) AS 'cajero',
        (
            SELECT STRING_AGG(TEMP.[estado], ' | ')
            FROM [dbo].[tbl_csv_icg_temporal] AS TEMP WITH(NOLOCK) 
            WHERE TEMP.[plataforma] = TABLA.[plataforma]
            AND TEMP.[id_pedido] = TABLA.[id_pedido]
            AND TEMP.[local] = TABLA.[local]
            AND TEMP.[serie_compuesta] = TABLA.[serie_compuesta]
            AND TEMP.[fecha_pedido] = TABLA.[fecha_pedido] 
            AND TEMP.[total_bruto] = TABLA.[total_bruto]
            AND CONVERT(DECIMAL, REPLACE(ISNULL(TEMP.[total_promocion], '0'), ',', '')) = CONVERT(DECIMAL, REPLACE(ISNULL(TABLA.[total_promocion], '0'), ',', ''))
            AND CONVERT(DECIMAL, REPLACE(ISNULL(TEMP.[total_neto], '0'), ',', '')) = CONVERT(DECIMAL, REPLACE(ISNULL(TABLA.[total_neto], '0'), ',', ''))
            AND TEMP.[forma_pago] = TABLA.[forma_pago]
            AND TEMP.[procesado] = 0
        ) AS 'estado'
        FROM [dbo].[tbl_csv_icg_temporal] AS TABLA WITH(NOLOCK) 
        WHERE [procesado] = 0
        AND [numero_documento] IN (
            SELECT
                [numero_documento]
            FROM [dbo].[tbl_csv_icg_temporal] WITH(NOLOCK) 
            WHERE [procesado] = 0
            GROUP BY [numero_documento]
            HAVING COUNT(*) = 1
        )
        GROUP BY [plataforma], [id_pedido], [local], [serie_compuesta], [fecha_pedido], [fecha_entrega], [total_bruto], [total_promocion], [total_neto], [forma_pago]
        HAVING 
        COUNT(plataforma) > 1 AND
        COUNT(id_pedido) > 1 AND
        COUNT(local) > 1 AND
        COUNT(serie_compuesta) > 1 AND
        COUNT(fecha_pedido) > 1 AND
        COUNT(total_bruto) > 1 AND
        COUNT(total_promocion) > 1 AND
        COUNT(total_neto) > 1 AND
        COUNT(forma_pago) > 1;
    END

    --CONSULTA OPCION 7 Seleccionamos registros que  no se registraron en la tabla definitiva.
    IF @opcion = 7 
    BEGIN
        SELECT *
        FROM [dbo].[tbl_csv_icg_temporal] AS TEMPORAL WITH(NOLOCK)
        WHERE [procesado] = 0
        AND NOT EXISTS (
            SELECT *
            FROM [dbo].[tbl_csv_icg] AS DEFINITIVA WITH(NOLOCK)
            WHERE TEMPORAL.[plataforma] = DEFINITIVA.[plataforma]
            AND TEMPORAL.[id_pedido] = DEFINITIVA.[id_pedido]
            AND TEMPORAL.[local] = DEFINITIVA.[punto_venta]
            AND TEMPORAL.[fecha_pedido] = DEFINITIVA.[fecha_pedido] 
            AND TEMPORAL.[fecha_entrega] = DEFINITIVA.[fecha_entrega] 
            AND TEMPORAL.[total_bruto] = DEFINITIVA.[total_bruto]
            AND CONVERT(DECIMAL, REPLACE(ISNULL(TEMPORAL.[total_promocion], '0'), ',', '')) = DEFINITIVA.[total_promocion]
            AND CONVERT(DECIMAL, REPLACE(ISNULL(TEMPORAL.[total_neto], '0'), ',', '')) = DEFINITIVA.[total_neto]
            AND TEMPORAL.[serie_compuesta] = DEFINITIVA.[serie_compuesta]
            AND TEMPORAL.[numero_documento] = DEFINITIVA.[numero_documento]
            AND TEMPORAL.[numero_orden] = ISNULL(DEFINITIVA.[numero_orden], '')
            AND TEMPORAL.[forma_pago] = ISNULL(DEFINITIVA.[forma_pago], '')
            AND TEMPORAL.[nombre_cliente] = ISNULL(DEFINITIVA.[nombre_cliente], '')
            AND TEMPORAL.[cajero] = ISNULL(DEFINITIVA.[cajero], '')
            AND TEMPORAL.[estado] = ISNULL(DEFINITIVA.[estado], '')
        )
    END

    --CONSULTA OPCION 8 Seleccionamos registros que fueron operados y se registraron en la tabla definitiva.
    IF @opcion = 8 
    BEGIN
        SELECT *
        FROM [dbo].[tbl_csv_icg_temporal] AS TEMPORAL WITH(NOLOCK)
        WHERE [procesado] = 0
        AND EXISTS (
            SELECT *
            FROM [dbo].[tbl_csv_icg] AS DEFINITIVA WITH(NOLOCK)
            WHERE TEMPORAL.[plataforma] = DEFINITIVA.[plataforma]
            AND TEMPORAL.[id_pedido] = DEFINITIVA.[id_pedido]
            AND TEMPORAL.[local] = DEFINITIVA.[punto_venta]
            AND TEMPORAL.[fecha_pedido] = DEFINITIVA.[fecha_pedido] 
            AND TEMPORAL.[fecha_entrega] = DEFINITIVA.[fecha_entrega] 
            AND TEMPORAL.[total_bruto] = DEFINITIVA.[total_bruto]
            AND CONVERT(DECIMAL, REPLACE(ISNULL(TEMPORAL.[total_promocion], '0'), ',', '')) = DEFINITIVA.[total_promocion]
            AND CONVERT(DECIMAL, REPLACE(ISNULL(TEMPORAL.[total_neto], '0'), ',', '')) = DEFINITIVA.[total_neto]
            AND TEMPORAL.[serie_compuesta] = DEFINITIVA.[serie_compuesta]
            AND TEMPORAL.[numero_documento] = DEFINITIVA.[numero_documento]
            AND TEMPORAL.[numero_orden] = ISNULL(DEFINITIVA.[numero_orden], '')
            AND TEMPORAL.[forma_pago] = ISNULL(DEFINITIVA.[forma_pago], '')
            AND TEMPORAL.[nombre_cliente] = ISNULL(DEFINITIVA.[nombre_cliente], '')
            AND TEMPORAL.[cajero] = ISNULL(DEFINITIVA.[cajero], '')
            AND TEMPORAL.[estado] = ISNULL(DEFINITIVA.[estado], '')
        )
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
