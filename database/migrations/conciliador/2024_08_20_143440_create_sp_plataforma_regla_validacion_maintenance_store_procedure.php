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

    DECLARE @ultimo_id INT = 0;

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
        INSERT INTO [dbo].[tbl_plataforma_regla_validacion] ([csv_plataforma_id], [csv_icg_id], [regla_validacion_id], [anio], [mes], [created_at], [created_by])
        VALUES (@csv_plataforma_id, @csv_icg_id, @regla_validacion_id, YEAR(GETDATE()), MONTH(GETDATE()), GETDATE(), @usuario);

        SET @ultimo_id = IDENT_CURRENT(N'[dbo].[tbl_plataforma_regla_validacion]');

        EXECUTE sp_plataforma_regla_validacion_maintenance
        @csv_plataforma_id = @ultimo_id,
        @csv_icg_id = 0,
        @regla_validacion_id = 0,
        @usuario = NULL,
        @opcion = 5
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
        SELECT T1.*, 
		CONCAT(T2.[plataforma], CONCAT('(', CONCAT(T2.[abreviatura], ')'))) AS plataforma,
		T3.[nombre] AS caso
		FROM [dbo].[tbl_plataforma_regla_validacion] T0 WITH(NOLOCK)
        INNER JOIN [dbo].[tbl_regla_validacion] T1 WITH(NOLOCK) ON T1.[id] = T0.[regla_validacion_id] AND T1.[deleted_at] IS NULL
        INNER JOIN [dbo].[tbl_plataforma] T2 WITH(NOLOCK) ON T2.[id] = T1.[plataforma_id] AND T2.[deleted_at] IS NULL
        INNER JOIN [dbo].[tbl_caso] T3 WITH(NOLOCK) ON T3.[id] = T1.[caso_id] AND T3.[deleted_at] IS NULL
        WHERE T0.[id] = @csv_plataforma_id;
    END

    --CONSULTA OPCION 6 Seleccionamos por plataforma.
    IF @opcion = 6 
    BEGIN
        SET @csv_icg_id = (SELECT TOP 1 [csv_icg_id] FROM [dbo].[tbl_plataforma_regla_validacion] WITH(NOLOCK) WHERE [csv_plataforma_id] = @csv_plataforma_id);

        SELECT * FROM [dbo].[tbl_csv_plataforma] WITH(NOLOCK) WHERE [id] = @csv_plataforma_id;   

        SELECT * FROM [dbo].[tbl_csv_icg] WITH(NOLOCK) WHERE [id] = @csv_icg_id;    

        SELECT T1.*, 
		CONCAT(T2.[plataforma], CONCAT('(', CONCAT(T2.[abreviatura], ')'))) AS plataforma,
		T3.[nombre] AS caso
		FROM [dbo].[tbl_plataforma_regla_validacion] T0 WITH(NOLOCK)
        INNER JOIN [dbo].[tbl_regla_validacion] T1 WITH(NOLOCK) ON T1.[id] = T0.[regla_validacion_id] AND T1.[deleted_at] IS NULL
        INNER JOIN [dbo].[tbl_plataforma] T2 WITH(NOLOCK) ON T2.[id] = T1.[plataforma_id] AND T2.[deleted_at] IS NULL
        INNER JOIN [dbo].[tbl_caso] T3 WITH(NOLOCK) ON T3.[id] = T1.[caso_id] AND T3.[deleted_at] IS NULL
        WHERE T0.[csv_plataforma_id] = @csv_plataforma_id AND T0.[csv_icg_id] = @csv_icg_id;
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
