<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpMateriaPrimaMaintenanceStoreProcedure extends Migration
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
CREATE PROCEDURE sp_materia_prima_maintenance
    @id INT = 0,
    @activo BIT NULL,
    @alergeno_id INT = 0,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ultimo_id INT = 0;
	DECLARE @RecuperarId AS TABLE (Id INT);

    --CONSULTA OPCION 1  Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
		SELECT *
		FROM [dbo].[tbl_materia_prima] AS T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_informacion] AS T1 WITH(NOLOCK) ON T0.[informacion_id] = T1.[id]
		INNER JOIN [dbo].[tbl_marca] AS T2 WITH(NOLOCK) ON T1.[marca_id] = T2.[id]
		INNER JOIN [dbo].[tbl_unidad] AS T3 WITH(NOLOCK) ON T1.[unidad_id] = T3.[id]
		WHERE T0.[deleted_at] IS NULL
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_materia_prima]
        SET 
            [activo] = @activo
        WHERE [id] = @id;

        EXECUTE sp_materia_prima_maintenance
        @id = @id,
        @activo = null,
		@alergeno_id = 0,
        @usuario = NULL,
        @opcion = 5
    END

    --CONSULTA OPCION 4 Eliminamos el registro en la tabla.
    IF @opcion = 4 
    BEGIN
        UPDATE [dbo].[tbl_materia_prima] SET [deleted_by] = @usuario, [deleted_at] = GETDATE() WHERE [id] = @id;
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
		SELECT *
		FROM [dbo].[tbl_materia_prima] AS T0 WITH(NOLOCK)
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
		FROM [dbo].[tbl_materia_prima] AS T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_informacion] AS T1 WITH(NOLOCK) ON T0.[informacion_id] = T1.[id]
		INNER JOIN [dbo].[tbl_marca] AS T2 WITH(NOLOCK) ON T1.[marca_id] = T2.[id]
		INNER JOIN [dbo].[tbl_unidad] AS T3 WITH(NOLOCK) ON T1.[unidad_id] = T3.[id]
		WHERE T0.[deleted_at] IS NULL AND T1.[prefijo] IN ('MP', 'EM', 'AS');
    END
	
    --CONSULTA OPCION 7 Seleccionar todos los registros de la tabla.
    IF @opcion = 7
    BEGIN
		SELECT *
		FROM [dbo].[tbl_materia_prima] AS T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_informacion] AS T1 WITH(NOLOCK) ON T0.[informacion_id] = T1.[id]
		INNER JOIN [dbo].[tbl_marca] AS T2 WITH(NOLOCK) ON T1.[marca_id] = T2.[id]
		INNER JOIN [dbo].[tbl_unidad] AS T3 WITH(NOLOCK) ON T1.[unidad_id] = T3.[id]
		WHERE T0.[deleted_at] IS NULL AND T0.[id] = @id;

		SELECT *
		FROM [dbo].[tbl_materia_prima_alergeno] AS T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_alergeno] AS T1 WITH(NOLOCK) ON T0.alergeno_id = T1.[id]
		WHERE T0.[deleted_at] IS NULL AND T0.[materia_prima_id] = @id;
    END

    --CONSULTA OPCION 8 Guardamos el registro en la tabla.
    IF @opcion = 8 
    BEGIN
		IF(NOT EXISTS(SELECT * FROM [dbo].[tbl_materia_prima_alergeno] WITH(NOLOCK) WHERE [materia_prima_id] = @id AND [alergeno_id] = @alergeno_id))
		BEGIN
            INSERT INTO [dbo].[tbl_materia_prima_alergeno] ([materia_prima_id], [informacion_id], [alergeno_id], [created_at], [created_by])
            VALUES (@id, (SELECT [informacion_id] FROM [dbo].[tbl_materia_prima] WHERE [id] = @id), @alergeno_id, GETDATE(), @usuario);
		END

        EXECUTE sp_materia_prima_maintenance
        @id = @id,
        @activo = null,
		@alergeno_id = 0,
        @usuario = NULL,
        @opcion = 7
    END

    --CONSULTA OPCION 9 Actualizamos el registro en la tabla.
    IF @opcion = 9
    BEGIN
        UPDATE [dbo].[tbl_materia_prima_alergeno]
        SET 
            [activo] = @activo,
			[updated_by] = @usuario, 
			[updated_at] = GETDATE()
		OUTPUT INSERTED.[materia_prima_id] INTO @RecuperarId
        WHERE [id] = @id;

		SET @ultimo_id = (SELECT TOP 1 Id FROM @RecuperarId);

        EXECUTE sp_materia_prima_maintenance
        @id = @ultimo_id,
        @activo = null,
		@alergeno_id = 0,
        @usuario = NULL,
        @opcion = 7
    END

    --CONSULTA OPCION 10 Eliminamos el registro en la tabla.
    IF @opcion = 10 
    BEGIN
        UPDATE [dbo].[tbl_materia_prima_alergeno] SET [deleted_by] = @usuario, [deleted_at] = GETDATE() 
		OUTPUT INSERTED.[materia_prima_id] INTO @RecuperarId
        WHERE [id] = @id;

		SET @ultimo_id = (SELECT TOP 1 Id FROM @RecuperarId);

        EXECUTE sp_materia_prima_maintenance
        @id = @ultimo_id,
        @activo = null,
		@alergeno_id = 0,
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_materia_prima_maintenance;");
    }
}
