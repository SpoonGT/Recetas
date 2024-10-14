<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpProductoMaintenanceStoreProcedure extends Migration
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
CREATE PROCEDURE sp_producto_maintenance
    @id INT = 0,
    @activo BIT NULL,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ultimo_id INT = 0;
	DECLARE @RecuperarId AS TABLE (Id INT)

    --CONSULTA OPCION 1  Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
		SELECT *
		FROM [dbo].[tbl_producto] AS T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_informacion] AS T1 WITH(NOLOCK) ON T0.[informacion_id] = T1.[id]
		INNER JOIN [dbo].[tbl_marca] AS T2 WITH(NOLOCK) ON T1.[marca_id] = T2.[id]
		INNER JOIN [dbo].[tbl_unidad] AS T3 WITH(NOLOCK) ON T1.[unidad_id] = T3.[id]
		WHERE T0.[deleted_at] IS NULL
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_producto]
        SET 
            [activo] = @activo
        WHERE [id] = @id;

        EXECUTE sp_producto_maintenance
        @id = @id,
        @activo = null,
        @usuario = NULL,
        @opcion = 5
    END

    --CONSULTA OPCION 4 Eliminamos el registro en la tabla.
    IF @opcion = 4 
    BEGIN
        UPDATE [dbo].[tbl_producto] SET [deleted_by] = @usuario, [deleted_at] = GETDATE() WHERE [id] = @id;
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
		SELECT *
		FROM [dbo].[tbl_producto] AS T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_informacion] AS T1 WITH(NOLOCK) ON T0.[informacion_id] = T1.[id]
		INNER JOIN [dbo].[tbl_marca] AS T2 WITH(NOLOCK) ON T1.[marca_id] = T2.[id]
		INNER JOIN [dbo].[tbl_unidad] AS T3 WITH(NOLOCK) ON T1.[unidad_id] = T3.[id]
		WHERE T0.[deleted_at] IS NULL AND T0.[id] = @id;
    END

    --CONSULTA OPCION 6 Seleccionamos id y nombre para llenar lista desplegable.
    IF @opcion = 6 
    BEGIN
        SELECT T1.[id], 
		CONCAT(T1.[netsuit], CONCAT(' | ', CONCAT(T1.[nombre], CONCAT(' (', CONCAT(T3.[nomenclatura], CONCAT(') ', T2.[nombre])))))) AS nombre
		FROM [dbo].[tbl_producto] AS T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_informacion] AS T1 WITH(NOLOCK) ON T0.[informacion_id] = T1.[id]
		INNER JOIN [dbo].[tbl_marca] AS T2 WITH(NOLOCK) ON T1.[marca_id] = T2.[id]
		INNER JOIN [dbo].[tbl_unidad] AS T3 WITH(NOLOCK) ON T1.[unidad_id] = T3.[id]
		WHERE T0.[deleted_at] IS NULL AND T1.[prefijo] IN ('SE');
    END
	
    --CONSULTA OPCION 7 Seleccionar todos los registros de la tabla.
    IF @opcion = 7
    BEGIN
		SELECT *
		FROM [dbo].[tbl_producto] AS T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_informacion] AS T1 WITH(NOLOCK) ON T0.[informacion_id] = T1.[id]
		INNER JOIN [dbo].[tbl_marca] AS T2 WITH(NOLOCK) ON T1.[marca_id] = T2.[id]
		INNER JOIN [dbo].[tbl_unidad] AS T3 WITH(NOLOCK) ON T1.[unidad_id] = T3.[id]
		WHERE T0.[deleted_at] IS NULL AND T0.[id] = @id;

		SELECT *
		FROM [dbo].[tbl_producto_materia_prima] AS T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_materia_prima] AS T1 WITH(NOLOCK) ON T0.[materia_prima_id] = T1.[id]
		INNER JOIN [dbo].[tbl_informacion] AS T2 WITH(NOLOCK) ON T1.[informacion_id] = T2.[id]
		INNER JOIN [dbo].[tbl_marca] AS T3 WITH(NOLOCK) ON T2.[marca_id] = T3.[id]
		INNER JOIN [dbo].[tbl_unidad] AS T4 WITH(NOLOCK) ON T2.[unidad_id] = T4.[id]
		WHERE T0.[deleted_at] IS NULL AND T0.[producto_id] = @id;

		SELECT *
		FROM [dbo].[tbl_producto_sub] AS T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_producto] AS T1 WITH(NOLOCK) ON T0.[sub_producto_id] = T1.[id]
		INNER JOIN [dbo].[tbl_informacion] AS T2 WITH(NOLOCK) ON T1.[informacion_id] = T2.[id]
		INNER JOIN [dbo].[tbl_marca] AS T3 WITH(NOLOCK) ON T2.[marca_id] = T3.[id]
		INNER JOIN [dbo].[tbl_unidad] AS T4 WITH(NOLOCK) ON T2.[unidad_id] = T4.[id]
		WHERE T0.[producto_id] = @id;
    END

    --CONSULTA OPCION 9 Actualizamos el registro en la tabla.
    IF @opcion = 9
    BEGIN
        UPDATE [dbo].[tbl_producto_materia_prima]
        SET 
            [activo] = @activo,
			[updated_by] = @usuario, 
			[updated_at] = GETDATE()
		OUTPUT INSERTED.[producto_id] INTO @RecuperarId
        WHERE [id] = @id;

		SET @ultimo_id = (SELECT TOP 1 Id FROM @RecuperarId);

        EXECUTE sp_producto_maintenance
        @id = @ultimo_id,
        @activo = null,
        @usuario = NULL,
        @opcion = 7
    END

    --CONSULTA OPCION 10 Eliminamos el registro en la tabla.
    IF @opcion = 10 
    BEGIN
        UPDATE [dbo].[tbl_producto_materia_prima] SET [deleted_by] = @usuario, [deleted_at] = GETDATE() 
		OUTPUT INSERTED.[producto_id] INTO @RecuperarId
		WHERE [id] = @id;

		SET @ultimo_id = (SELECT TOP 1 Id FROM @RecuperarId);

        EXECUTE sp_producto_maintenance
        @id = @ultimo_id,
        @activo = null,
        @usuario = NULL,
        @opcion = 7
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_producto_maintenance;");
    }
}
