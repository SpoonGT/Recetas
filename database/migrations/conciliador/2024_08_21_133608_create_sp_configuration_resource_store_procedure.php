<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpConfigurationResourceStoreProcedure extends Migration
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
CREATE PROCEDURE sp_configuration_resource
AS
BEGIN
    SET NOCOUNT ON;

    SELECT CONCAT('ICG - ',UPPER(T0.[COLUMN_NAME])) AS nombre,
    CONCAT('T1.', CONCAT('[', CONCAT(T0.[COLUMN_NAME], ']'))) AS valor
    FROM INFORMATION_SCHEMA.COLUMNS AS T0
    WHERE T0.[TABLE_SCHEMA] = 'dbo'
    AND T0.[TABLE_NAME] = 'tbl_csv_icg'
    AND T0.[COLUMN_NAME] NOT IN ('id', 'plataforma_id', 'plataforma', 'punto_venta', 'fecha_entrega', 'total_promocion', 'serie_compuesta', 'numero_documento', 'forma_pago', 'nombre_cliente', 'cajero', 'estado', 'created_at', 'created_by', 'procesado', 'no_id')
    ORDER BY T0.[ORDINAL_POSITION];

    SELECT CONCAT('PLATAFORMA - ',UPPER(T0.[COLUMN_NAME])) AS nombre, 
    CONCAT('T0.', CONCAT('[', CONCAT(T0.[COLUMN_NAME], ']'))) AS valor
    FROM INFORMATION_SCHEMA.COLUMNS AS T0
    WHERE T0.[TABLE_SCHEMA] = 'dbo'
    AND T0.[TABLE_NAME] = 'tbl_csv_plataforma'
    AND T0.[COLUMN_NAME] NOT IN ('id', 'plataforma_id', 'plataforma', 'punto_venta', 'alias_id', 'estado', 'estado_id', 'created_at', 'created_by', 'procesado', 'informacion')
    ORDER BY T0.[ORDINAL_POSITION];

    SELECT CONCAT(T0.id, CONCAT('. ', UPPER(T2.plataforma), CONCAT(' - ', CONCAT(UPPER(T1.nombre), ' (estado)')))) AS nombre, 
    CONCAT(T2.id,CONCAT('|',T0.id)) AS valor
    FROM [dbo].[tbl_plataforma_estado] T0 WITH(NOLOCK)
    INNER JOIN [dbo].[tbl_estado] T1 WITH(NOLOCK) ON T0.estado_id = T1.id
    INNER JOIN [dbo].[tbl_plataforma] T2 WITH(NOLOCK) ON T0.plataforma_id = T2.id AND T2.deleted_at IS NULL;

    SELECT 'IGUAL ( A = B )' AS nombre, '=' AS valor 
    UNION ALL 
    SELECT 'DISTINTO ( A != B )' AS nombre, '!=' AS valor 
    UNION ALL 
    SELECT 'MAYOR A ( A > B )' AS nombre, '>' AS valor 
    UNION ALL 
    SELECT 'MENOR A ( A < B )' AS nombre, '<' AS valor 
    UNION ALL 
    SELECT 'MAYOR O IGUAL A ( A >= B )' AS nombre, '>=' AS valor 
    UNION ALL 
    SELECT 'MENOR O IGUAL A ( A <= B )' AS nombre, '<=' AS valor 
    UNION ALL 
    SELECT 'ES NULO ( A IS NULL B )' AS nombre, 'IS NULL' AS valor 
    UNION ALL 
    SELECT 'NO ES NULO ( A IS NOT NULL B )' AS nombre, 'IS NOT NULL' AS valor 
    UNION ALL 
    SELECT 'COINCIDENCIA ( A LIKE %B% )' AS nombre, 'LIKE' AS valor;

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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_configuration_resource;");
    }
}
