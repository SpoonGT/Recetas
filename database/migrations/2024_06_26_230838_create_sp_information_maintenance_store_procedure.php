<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpInformationMaintenanceStoreProcedure extends Migration
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
CREATE PROCEDURE sp_information_maintenance
    @id INT = 0,
    @netsuit NVARCHAR(100) NULL,
    @nombre NVARCHAR(150) NULL,
    @descripcion NVARCHAR(MAX) NULL,
    @marca_id INT = 0,
    @unidad_id INT = 0,
	@prefijo NVARCHAR(50) NULL,
	@proceso NVARCHAR(50) NULL,
	@data NVARCHAR(MAX) NULL,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ultimo_id INT = 0, @codigo INT;

    --CONSULTA OPCION 1 Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
        SELECT T0.*, T1.[nombre] AS marca, T2.[nombre] AS unidad		
		FROM [dbo].[tbl_informacion] T0 
		INNER JOIN [dbo].[tbl_marca] T1 ON T0.[marca_id] = T1.[id]
		INNER JOIN [dbo].[tbl_unidad] T2 ON T0.[unidad_id] = T2.[id]
    END

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
		BEGIN TRY
			BEGIN TRANSACTION INSERT_INFORMATION

			DECLARE @informacion_id INT;

			IF(NOT EXISTS(SELECT * FROM [dbo].[tbl_informacion] WHERE [netsuit] = @netsuit))
			BEGIN
				SELECT @prefijo = dbo.fn_obtener_letras_sin_espacio(@netsuit), @codigo = dbo.fn_obtener_numero(@netsuit);

				INSERT INTO [dbo].[tbl_informacion] ([prefijo], [codigo], [netsuit], [nombre], [descripcion], [marca_id], [unidad_id], [created_at], [created_by])
				VALUES (@prefijo, @codigo, @netsuit, @nombre, @descripcion, @marca_id, @unidad_id, GETDATE(), @usuario);

				SET @informacion_id = IDENT_CURRENT(N'[dbo].[tbl_informacion]');

				IF @proceso = 'tbl_materia_prima'
				BEGIN
					INSERT INTO [dbo].[tbl_materia_prima] ([informacion_id])
					VALUES (@informacion_id);

					SET @ultimo_id = IDENT_CURRENT(N'[dbo].[tbl_materia_prima]');
				
					INSERT INTO [dbo].[tbl_materia_prima_alergeno] ([materia_prima_id], [informacion_id], [alergeno_id], [created_at], [created_by])
					SELECT 
						@ultimo_id,
						@informacion_id,
						alergeno,
						GETDATE(), 
						@usuario
					FROM OPENJSON(@data) WITH (
						alergeno NVARCHAR(50) 'strict $.id'
					)
				END
			END
			ELSE
			BEGIN
				SELECT @informacion_id = [id] FROM [dbo].[tbl_informacion] WHERE [netsuit] = @netsuit;
			END

			COMMIT TRANSACTION INSERT_INFORMATION

			EXECUTE sp_information_maintenance 
			@id = @informacion_id, 
			@netsuit = null,
			@nombre = null,
			@descripcion = null,
			@marca_id = 0,
			@unidad_id = 0,
			@prefijo = null,
			@proceso = null,
			@data = null,
			@usuario = NULL,
			@opcion = 5
		END TRY
		BEGIN CATCH
			ROLLBACK TRANSACTION INSERT_INFORMATION
			RAISERROR(N'Error al guardar la información', 16, 1, 'sp_information_maintenance');
		END CATCH
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_informacion]
        SET 
            [nombre] = @nombre,  
            [descripcion] = @descripcion,
            [marca_id] = @marca_id,
            [unidad_id] = @unidad_id,
            [updated_by] = @usuario,
            [updated_at] = GETDATE()
        WHERE [id] = @id;

        EXECUTE sp_information_maintenance 
        @id = @id, 
        @netsuit = null,
        @nombre = null,
        @descripcion = null,
        @marca_id = 0,
        @unidad_id = 0,
        @prefijo = null,
        @proceso = null,
        @data = null,
        @usuario = NULL,
        @opcion = 5
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
        SELECT * FROM [dbo].[tbl_informacion] WHERE [id] = @id;
    END

    --CONSULTA OPCION 6 Seleccionamos id y nombre para llenar lista desplegable.
    IF @opcion = 6 
    BEGIN
        SELECT T0.[id], 
		CONCAT(T0.[netsuit], CONCAT(' | ', CONCAT(T0.[nombre], CONCAT(' (', CONCAT(T2.[nomenclatura], CONCAT(') ', T1.[nombre])))))) AS nombre
		FROM [dbo].[tbl_informacion] T0 
		INNER JOIN [dbo].[tbl_marca] T1 ON T0.[marca_id] = T1.[id]
		INNER JOIN [dbo].[tbl_unidad] T2 ON T0.[unidad_id] = T2.[id]
		WHERE T0.[prefijo] IN (@prefijo);
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_information_maintenance;");
    }
}
