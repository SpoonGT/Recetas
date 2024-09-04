<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpGetAlergiasStoreProcedure extends Migration
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
                CREATE PROCEDURE sp_get_alergias
                    @id INT = 0,
                    @opcion INT
                AS
                BEGIN
                    SET NOCOUNT ON;

                    --CONSULTA OPCION 1 Seleccionar todas las alergias de un producto tomando en cuenta los sub productos.
                    IF @opcion = 1
                    BEGIN
                        WITH TablaRecursivaSubProducto AS (
                            SELECT T0.[producto_id], T0.[sub_producto_id]
                            FROM [dbo].[tbl_producto_sub] T0 WITH(NOLOCK)
                            INNER JOIN [dbo].[tbl_producto] T1 WITH(NOLOCK) ON T1.[id] = T0.[producto_id] AND T1.[deleted_at] IS NULL
                            WHERE T0.[producto_id] = @id
                            UNION ALL
                            SELECT T0.[producto_id], T0.[sub_producto_id]
                            FROM [dbo].[tbl_producto_sub] T0 WITH(NOLOCK)
                            INNER JOIN [dbo].[tbl_producto] T1 WITH(NOLOCK) ON T1.[id] = T0.[producto_id] AND T1.[deleted_at] IS NULL
                            INNER JOIN TablaRecursivaSubProducto T2 ON T2.[sub_producto_id] = T0.[producto_id]
                        ),
                        TablaUnionProductoSubProducto AS (
                            SELECT id FROM (SELECT 
                                T0.[producto_id] AS id
                            FROM 
                                TablaRecursivaSubProducto T0
                            UNION ALL
                            SELECT 
                                T0.[sub_producto_id] AS id
                            FROM 
                                TablaRecursivaSubProducto T0) T1
                            GROUP BY T1.[id]
                        ),
                        TablaAllAlergias AS (
                            SELECT 
                                T3.[id], T3.[nombre]
                            FROM 
                                TablaUnionProductoSubProducto T0
                            INNER JOIN [dbo].[tbl_producto_materia_prima] T1 WITH(NOLOCK) ON T1.[producto_id] = T0.[id] AND T1.[deleted_at] IS NULL
                            INNER JOIN [dbo].[tbl_materia_prima_alergeno] T2 WITH(NOLOCK) ON T2.[materia_prima_id] = T1.[materia_prima_id] AND T2.[deleted_at] IS NULL
                            INNER JOIN [dbo].[tbl_alergeno] T3 WITH(NOLOCK) ON T3.[id] = T2.[alergeno_id] AND T3.[deleted_at] IS NULL
                            GROUP BY T3.[id], T3.[nombre]
                        )

                        SELECT STRING_AGG(id, ',') AS ids, STRING_AGG(nombre, ' ,') AS alergias FROM TablaAllAlergias;
                    END

                    --CONSULTA OPCION 2 Seleccionar todas las alergias de una materia prima.
                    IF @opcion = 2
                    BEGIN
                        WITH TablaAllAlergias AS (
                            SELECT T2.[id], T2.[nombre]
                            FROM [dbo].[tbl_producto_materia_prima] AS T0 WITH(NOLOCK) 
                            INNER JOIN [dbo].[tbl_materia_prima_alergeno] AS T1 WITH(NOLOCK) ON T1.[materia_prima_id] = T0.[materia_prima_id] AND T1.[deleted_at] IS NULL 
                            INNER JOIN [dbo].[tbl_alergeno] AS T2 WITH(NOLOCK) ON T1.[alergeno_id] = T2.[id] AND T2.[deleted_at] IS NULL 
                            WHERE T0.[deleted_at] IS NULL AND T0.[materia_prima_id] = @id
                            GROUP BY T2.[id], T2.[nombre]
                        )

                        SELECT STRING_AGG(id, ',') AS ids, STRING_AGG(nombre, ' ,') AS alergias
                        FROM TablaAllAlergias;
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_alergias;");
    }
}
