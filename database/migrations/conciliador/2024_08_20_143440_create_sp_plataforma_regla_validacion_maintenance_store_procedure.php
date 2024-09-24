<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpPlataformaReglaValidacionMaintenanceStoreProcedure extends Migration
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
CREATE PROCEDURE sp_plataforma_regla_validacion_maintenance
    @csv_plataforma_id INT = 0,
    @csv_icg_id INT = 0,
    @regla_validacion_id INT = 0,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
        INSERT INTO [dbo].[tbl_plataforma_regla_validacion] ([csv_plataforma_id], [csv_icg_id], [regla_validacion_id], [anio], [mes], [created_at], [created_by])
        VALUES (@csv_plataforma_id, @csv_icg_id, @regla_validacion_id, YEAR(GETDATE()), MONTH(GETDATE()), GETDATE(), @usuario);

        UPDATE [dbo].[tbl_csv_icg] SET [procesado] = 1 WHERE [id] = @csv_icg_id;
        UPDATE [dbo].[tbl_csv_plataforma] SET [procesado] = 1, [informacion] = 'REGLA VALIDACION' WHERE [id] = @csv_plataforma_id;
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_plataforma_regla_validacion]
        SET 
            [resuelto] = 1,
            [updated_by] = @usuario,
            [updated_at] = GETDATE()
        WHERE [id] = @csv_plataforma_id;
    END

    --CONSULTA OPCION 6 Seleccionamos por plataforma.
    IF @opcion = 6 
    BEGIN
        SELECT 
            T0.[id] AS id,
            T2.[id] AS 'codigo', 
            T2.[nombre] AS 'caso',
            T4.id AS csv_plataforma_id, T4.plataforma_id, T4.plataforma, T4.id_pedido, T4.punto_venta_id, T4.punto_venta, T4.alias_id, T4.fecha, T4.total, T4.estado, T4.plataforma_estado_id, T4.estado_id, T4.informacion,
            T3.id AS csv_icg_id, T3.fecha_pedido, T3.fecha_entrega, T3.total_bruto, T3.total_promocion, T3.total_neto, T3.serie_compuesta, T3.numero_documento, T3.numero_orden, T3.forma_pago, T3.nombre_cliente, T3.cajero
        FROM [dbo].[tbl_plataforma_regla_validacion] AS T0
        INNER JOIN [dbo].[tbl_regla_validacion] AS T1 ON T1.[id] = T0.[regla_validacion_id]
        INNER JOIN [dbo].[tbl_caso] AS T2 ON T2.[id] = T1.[caso_id]
        INNER JOIN [dbo].[tbl_csv_icg] AS T3 ON T3.[id] = T0.[csv_icg_id]
        INNER JOIN [dbo].[tbl_csv_plataforma] AS T4 ON T4.[id] = T0.[csv_plataforma_id]
        WHERE [resuelto] = 0;
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_plataforma_regla_validacion_maintenance;");
    }
}
