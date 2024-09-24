<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateReporteValidacionView extends Migration
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
CREATE VIEW [REPORTE_VALIDACION_VIEW]
AS
SELECT 
	T2.[id] AS 'Codigo', 
	T2.[nombre] AS 'Caso', 
	T3.[punto_venta] AS 'Local', 
	T3.[numero_documento] AS 'ID_Factura', 
	T3.[fecha_entrega] AS 'Fecha', 
	DATENAME(MONTH,T3.[fecha_entrega]) AS 'Mes', 
	DATENAME(WEEK,T3.[fecha_entrega]) AS 'Semana', 
	DATENAME(WEEKDAY,T3.[fecha_entrega]) AS 'Dia', 
	T1.[caso_id] AS 'Caso_Id', 
	T3.[punto_venta_id] AS 'Punto_Venta_Id', 
	T3.[plataforma_id] AS 'Plataforma_Id'
FROM [dbo].[tbl_plataforma_regla_validacion] AS T0
INNER JOIN [dbo].[tbl_regla_validacion] AS T1 ON T1.[id] = T0.[regla_validacion_id]
INNER JOIN [dbo].[tbl_caso] AS T2 ON T2.[id] = T1.[caso_id]
INNER JOIN [dbo].[tbl_csv_icg] AS T3 ON T3.[id] = T0.[csv_icg_id]
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
        DB::unprepared("DROP VIEW IF EXISTS REPORTE_VALIDACION_VIEW;");
    }
}
