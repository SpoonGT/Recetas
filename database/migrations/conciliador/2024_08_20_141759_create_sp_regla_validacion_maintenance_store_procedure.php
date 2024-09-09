<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpReglaValidacionMaintenanceStoreProcedure extends Migration
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
CREATE PROCEDURE sp_regla_validacion_maintenance
    @id INT = 0,
    @secuencia INT = 0,
    @descripcion NVARCHAR(4000) NULL,
    @query NVARCHAR(4000) NULL,
    @plataforma_id INT = 0,
    @caso_id INT = 0,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ultimo_id INT = 0;

    --CONSULTA OPCION 1  Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
        SELECT T0.*, 
		CONCAT(T1.[plataforma], CONCAT('(', CONCAT(T1.[abreviatura], ')'))) AS plataforma,
		T2.[nombre] AS caso
		FROM [dbo].[tbl_regla_validacion] T0 WITH(NOLOCK)
        INNER JOIN [dbo].[tbl_plataforma] T1 WITH(NOLOCK) ON T1.[id] = T0.[plataforma_id] AND T1.[deleted_at] IS NULL
        INNER JOIN [dbo].[tbl_caso] T2 WITH(NOLOCK) ON T2.[id] = T0.[caso_id] AND T2.[deleted_at] IS NULL;
    END

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
        INSERT INTO [dbo].[tbl_regla_validacion] ([secuencia], [descripcion], [query], [plataforma_id], [caso_id], [created_at], [created_by])
        VALUES (@secuencia, @descripcion, @query, @plataforma_id, @caso_id, GETDATE(), @usuario);

        SET @ultimo_id = IDENT_CURRENT(N'[dbo].[tbl_regla_validacion]');

        EXECUTE sp_regla_validacion_maintenance
        @id = @ultimo_id,
        @secuencia = 0,
        @descripcion = null,
        @query = null,
        @plataforma_id = 0,
        @caso_id = 0,
        @usuario = NULL,
        @opcion = 5
    END

    --CONSULTA OPCION 3 Activar el registro en la tabla.
    IF @opcion = 3 
    BEGIN
        UPDATE [dbo].[tbl_regla_validacion] SET [deleted_by] = NULL, [deleted_at] = NULL WHERE [id] = @id;
    END

    --CONSULTA OPCION 4 Desactivar el registro en la tabla.
    IF @opcion = 4 
    BEGIN
        UPDATE [dbo].[tbl_regla_validacion] SET [deleted_by] = @usuario, [deleted_at] = GETDATE() WHERE [id] = @id;
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
        SELECT T0.*, 
		CONCAT(T1.[plataforma], CONCAT('(', CONCAT(T1.[abreviatura], ')'))) AS plataforma,
		T2.[nombre] AS caso
		FROM [dbo].[tbl_regla_validacion] T0 WITH(NOLOCK)
        INNER JOIN [dbo].[tbl_plataforma] T1 WITH(NOLOCK) ON T1.[id] = T0.[plataforma_id] AND T1.[deleted_at] IS NULL
        INNER JOIN [dbo].[tbl_caso] T2 WITH(NOLOCK) ON T2.[id] = T0.[caso_id] AND T2.[deleted_at] IS NULL
        WHERE T0.[id] = @id;
    END

    --CONSULTA OPCION 6 Seleccionamos las reglas de validación para la plataforma.
    IF @opcion = 6 
    BEGIN
        SELECT T0.*, 
		CONCAT(T1.[plataforma], CONCAT('(', CONCAT(T1.[abreviatura], ')'))) AS plataforma,
		T2.[nombre] AS caso
		FROM [dbo].[tbl_regla_validacion] T0 WITH(NOLOCK)
        INNER JOIN [dbo].[tbl_plataforma] T1 WITH(NOLOCK) ON T1.[id] = T0.[plataforma_id] AND T1.[deleted_at] IS NULL
        INNER JOIN [dbo].[tbl_caso] T2 WITH(NOLOCK) ON T2.[id] = T0.[caso_id] AND T2.[deleted_at] IS NULL
        WHERE T0.[deleted_at] IS NULL AND T0.[plataforma_id] = @plataforma_id
        ORDER BY T0.[secuencia] DESC;
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_regla_validacion_maintenance;");
    }
}
