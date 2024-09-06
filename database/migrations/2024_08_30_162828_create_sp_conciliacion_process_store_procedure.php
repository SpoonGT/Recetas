<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpConciliacionProcessStoreProcedure extends Migration
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
CREATE PROCEDURE sp_conciliacion_process
    @csv_plataforma_id INT = 0,
    @csv_icg_id INT = 0,
    @comentario NVARCHAR(MAX) NULL,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
        DECLARE @TEMPORAL_CONCILIAR TABLE
        (
            csv_plataforma_id BIGINT, 
            csv_icg_id BIGINT, 
            informacion NVARCHAR(MAX),
			anio INT,
			mes INT,
			created_at DATETIME,
			created_by NVARCHAR(MAX)
        )

		INSERT INTO @TEMPORAL_CONCILIAR ([csv_plataforma_id], [csv_icg_id], [informacion], [anio], [mes], [created_at], [created_by])
        SELECT 
        T0.id AS 'csv_plataforma_id', 
        T1.id AS 'csv_icg_id',
        'CONCILIACION AUTOMATICA' AS informacion,
        YEAR(GETDATE()) AS anio, 
        MONTH(GETDATE()) AS mes, 
        GETDATE() AS created_at,
        @usuario AS created_by
        FROM (SELECT * FROM [dbo].[tbl_csv_plataforma] WITH(NOLOCK) WHERE [plataforma_id] = @csv_plataforma_id AND [procesado] = 0 AND [informacion] = 'REGISTRADO') AS T0, 
        (SELECT * FROM [dbo].[tbl_csv_icg] WITH(NOLOCK) WHERE [plataforma_id] = @csv_plataforma_id AND [procesado] = 0) AS T1
        WHERE T0.[id_pedido] = T1.[id_pedido]
        AND T0.[plataforma_id] = T1.[plataforma_id]
        AND T0.[punto_venta_id] = T1.[punto_venta_id]
        AND T0.[fecha] = T1.[fecha_pedido]
        AND T0.[total] = T1.[total_neto];

        INSERT INTO [dbo].[tbl_conciliacion] ([csv_plataforma_id], [csv_icg_id], [informacion], [anio], [mes], [created_at], [created_by])
		SELECT [csv_plataforma_id], [csv_icg_id], [informacion], [anio], [mes], [created_at], [created_by] FROM @TEMPORAL_CONCILIAR;

		UPDATE T0
		SET T0.[procesado] = 1
		FROM [dbo].[tbl_csv_icg] T0 WITH(NOLOCK)
		INNER JOIN @TEMPORAL_CONCILIAR T1 ON T1.[csv_icg_id] = T0.[id];
		
		UPDATE T0
		SET T0.[procesado] = 1, T0.[informacion] = 'CONCILIACION AUTOMATICA' 
		FROM [dbo].[tbl_csv_plataforma] T0 WITH(NOLOCK)
		INNER JOIN @TEMPORAL_CONCILIAR T1 ON T1.[csv_plataforma_id] = T0.[id];
    END

    --CONSULTA OPCION 3 Guardamos el registro en la tabla.
    IF @opcion = 3 
    BEGIN
        INSERT INTO [dbo].[tbl_conciliacion] ([csv_plataforma_id], [csv_icg_id], [informacion], [anio], [mes], [created_at], [created_by], [comentario])
        VALUES (@csv_plataforma_id, @csv_icg_id, 'CONCILIACION MANUAL', YEAR(GETDATE()), MONTH(GETDATE()), GETDATE(), @usuario, @comentario);
        
        UPDATE [dbo].[tbl_csv_icg] SET [procesado] = 1 WHERE [id] = @csv_icg_id;
        UPDATE [dbo].[tbl_csv_plataforma] SET [procesado] = 1, [informacion] = 'CONCILIACION MANUAL' WHERE [id] = @csv_plataforma_id;
    END

    --CONSULTA OPCION 4 Datos que no pudieron ser procesados pasa al proceso manual.
    IF @opcion = 4 
    BEGIN
		UPDATE [dbo].[tbl_csv_plataforma] 
		SET [procesado] = 1, [informacion] = 'REPROCESAR' 
		WHERE [plataforma_id] = @csv_plataforma_id AND [procesado] = 0 AND [informacion] = 'REGISTRADO';
    END

    --CONSULTA OPCION 6 Seleccionamos las conciliaciones automáticas.
    IF @opcion = 6 
    BEGIN
        SELECT 
        T1.id AS csv_plataforma_id, T1.plataforma_id, T1.plataforma, T1.id_pedido, T1.punto_venta_id, T1.punto_venta, T1.alias_id, T1.fecha, T1.total, T1.estado, T1.plataforma_estado_id, T1.estado_id, T1.informacion,
        T2.id AS csv_icg_id, T2.fecha_pedido, T2.fecha_entrega, T2.total_bruto, T2.total_promocion, T2.total_neto, T2.serie_compuesta, T2.numero_documento, T2.numero_orden, T2.forma_pago, T2.nombre_cliente, T2.cajero,
        T0.comentario AS conciliacion_comentario,
        T0.created_at AS conciliacion_created_at,
        T0.created_by AS conciliacion_created_by,
        T0.anio AS conciliacion_anio,
        T0.mes AS conciliacion_mes
		FROM [dbo].[tbl_conciliacion] T0 WITH(NOLOCK)
        INNER JOIN [dbo].[tbl_csv_plataforma] T1 WITH(NOLOCK) ON T1.[id] = T0.[csv_plataforma_id] AND T1.[procesado] = 1
        INNER JOIN [dbo].[tbl_csv_icg] T2 WITH(NOLOCK) ON T2.[id] = T0.[csv_icg_id] AND T2.[procesado] = 1
        WHERE T0.[informacion] = 'CONCILIACION AUTOMATICA';
    END

    --CONSULTA OPCION 7 Seleccionamos las conciliaciones manuales.
    IF @opcion = 7 
    BEGIN
        SELECT 
        T1.id AS csv_plataforma_id, T1.plataforma_id, T1.plataforma, T1.id_pedido, T1.punto_venta_id, T1.punto_venta, T1.alias_id, T1.fecha, T1.total, T1.estado, T1.plataforma_estado_id, T1.estado_id, T1.informacion,
        T2.id AS csv_icg_id, T2.fecha_pedido, T2.fecha_entrega, T2.total_bruto, T2.total_promocion, T2.total_neto, T2.serie_compuesta, T2.numero_documento, T2.numero_orden, T2.forma_pago, T2.nombre_cliente, T2.cajero,
        T0.comentario AS conciliacion_comentario,
        T0.created_at AS conciliacion_created_at,
        T0.created_by AS conciliacion_created_by,
        T0.anio AS conciliacion_anio,
        T0.mes AS conciliacion_mes
		FROM [dbo].[tbl_conciliacion] T0 WITH(NOLOCK)
        INNER JOIN [dbo].[tbl_csv_plataforma] T1 WITH(NOLOCK) ON T1.[id] = T0.[csv_plataforma_id] AND T1.[procesado] = 1
        INNER JOIN [dbo].[tbl_csv_icg] T2 WITH(NOLOCK) ON T2.[id] = T0.[csv_icg_id] AND T2.[procesado] = 1
        WHERE T0.[informacion] = 'CONCILIACION MANUAL';
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_conciliacion_process;");
    }
}
