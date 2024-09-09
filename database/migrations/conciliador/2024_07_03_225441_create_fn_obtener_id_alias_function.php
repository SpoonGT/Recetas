<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateFnObtenerIdAliasFunction extends Migration
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
CREATE FUNCTION fn_obtener_id_alias (
	@local NVARCHAR(MAX),
    @plataforma NVARCHAR(MAX) NULL,
    @buscar_en NVARCHAR(MAX)
)
RETURNS INT
AS
BEGIN
	DECLARE @id INT;

    IF @plataforma IS NULL
    BEGIN
        SET @id = (SELECT [id] FROM [dbo].[tbl_punto_venta] WITH(NOLOCK) WHERE [deleted_at] IS NULL AND [alias] = @local);
    END
    ELSE
    BEGIN
        IF @buscar_en = 'ICG'
        BEGIN
            SET @id = (
                SELECT TOP 1 T1.[id] 
                FROM [dbo].[tbl_alias] AS T0 WITH(NOLOCK)
                INNER JOIN [dbo].[tbl_punto_venta] AS T1 WITH(NOLOCK) ON T1.[id] = T0.[punto_venta_id] AND T1.[deleted_at] IS NULL
                INNER JOIN [dbo].[tbl_plataforma] AS T2 WITH(NOLOCK) ON T2.[id] = T0.[plataforma_id] AND T2.[deleted_at] IS NULL
                WHERE T1.[alias] = @local AND T2.[abreviatura] = @plataforma AND T0.[deleted_at] IS NULL
            );
        END

        IF @buscar_en = 'Plataforma'
        BEGIN
            SET @id = (
                SELECT TOP 1 T0.[id] 
                FROM [dbo].[tbl_alias] AS T0 WITH(NOLOCK)
                INNER JOIN [dbo].[tbl_punto_venta] AS T1 WITH(NOLOCK) ON T1.[id] = T0.[punto_venta_id] AND T1.[deleted_at] IS NULL
                INNER JOIN [dbo].[tbl_plataforma] AS T2 WITH(NOLOCK) ON T2.[id] = T0.[plataforma_id] AND T2.[deleted_at] IS NULL
                WHERE T0.[alias] = @local AND T2.[abreviatura] = @plataforma AND T0.[deleted_at] IS NULL
            );
        END
    END

	RETURN ISNULL(@id, 0)
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
        DB::unprepared("DROP FUNCTION IF EXISTS fn_obtener_id_alias;");
    }
}
