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
CREATE PROCEDURE [dbo].[sp_information_maintenance]
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
	DECLARE @ErrorMessage NVARCHAR(4000), @ErrorSeverity INT, @ErrorState INT;
	DECLARE @informacion_id INT;
					
	DECLARE @TempMateria AS TABLE 
	(materia_prima_id INT, unidad_id INT, cantidad NVARCHAR(MAX), producto_id INT DEFAULT (0), informacion_id INT DEFAULT (0));
	
	DECLARE @materia_prima_id INT, @cantidad NVARCHAR(MAX), @nomenclatura VARCHAR(20), @count INT;

    --CONSULTA OPCION 1 Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
        SELECT T0.*, T1.[nombre] AS marca, T2.[nombre] AS unidad		
		FROM [dbo].[tbl_informacion] T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_marca] T1 WITH(NOLOCK) ON T0.[marca_id] = T1.[id]
		INNER JOIN [dbo].[tbl_unidad] T2 WITH(NOLOCK) ON T0.[unidad_id] = T2.[id]
    END

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
		BEGIN TRY
			BEGIN TRANSACTION INSERT_INFORMATION

			IF(NOT EXISTS(SELECT * FROM [dbo].[tbl_informacion] WHERE [netsuit] = @netsuit))
			BEGIN
				SELECT @prefijo = dbo.fn_obtener_letras_sin_espacio(@netsuit), @codigo = dbo.fn_obtener_numero(@netsuit);

				INSERT INTO [dbo].[tbl_informacion] ([prefijo], [codigo], [netsuit], [nombre], [descripcion], [marca_id], [unidad_id], [created_at], [created_by])
				VALUES (@prefijo, @codigo, @netsuit, @nombre, @descripcion, @marca_id, @unidad_id, GETDATE(), @usuario);

				SET @informacion_id = IDENT_CURRENT(N'[dbo].[tbl_informacion]');

				IF @proceso = 'tbl_materia_prima'
				BEGIN
					INSERT INTO [dbo].[tbl_materia_prima] ([prefijo], [informacion_id])
					VALUES (@prefijo, @informacion_id);

					SET @ultimo_id = IDENT_CURRENT(N'[dbo].[tbl_materia_prima]');
				
					IF @data IS NOT NULL
					BEGIN
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

				IF @proceso = 'tbl_producto'
				BEGIN
					INSERT INTO [dbo].[tbl_producto] ([prefijo], [informacion_id], [sub_ensmable])
					SELECT 
						@prefijo,
						@informacion_id,
						sub_ensmable
					FROM OPENJSON(@data) WITH (
						sub_ensmable NVARCHAR(50) 'strict $.sub_ensmable'
					)

					SET @ultimo_id = IDENT_CURRENT(N'[dbo].[tbl_producto]');

					INSERT INTO @TempMateria ([cantidad], [materia_prima_id], [unidad_id])
					SELECT 
						cantidad,
						materia_prima_id,
						unidad_id
					FROM OPENJSON(@data) WITH (
						materia_prima NVARCHAR(MAX) '$.materia_prima' AS JSON
					)
					OUTER APPLY OPENJSON(materia_prima) WITH (cantidad NVARCHAR(MAX) 'strict $.cantidad', materia_prima_id NVARCHAR(MAX) 'strict $.materia_prima_id', unidad_id NVARCHAR(MAX) 'strict $.unidad_id');

					SET @count = (SELECT COUNT(*) FROM @TempMateria);

					WHILE @count > 0
					BEGIN
						SELECT TOP 1 @materia_prima_id = [materia_prima_id], @unidad_id = [unidad_id], @cantidad = [cantidad] FROM @TempMateria;
						SELECT @nomenclatura = [nomenclatura] FROM [dbo].[tbl_unidad] WHERE [id] = @unidad_id;

						INSERT INTO [dbo].[tbl_producto_materia_prima] ([cantidad], [nomenclatura], [unidad_id], [producto_id], [informacion_id], [materia_prima_id], [created_at], [created_by])
						VALUES (@cantidad, @nomenclatura, @unidad_id, @ultimo_id, @informacion_id, @materia_prima_id, GETDATE(), @usuario);

						DELETE TOP (1) FROM @TempMateria;
						SELECT @count = COUNT(*) FROM @TempMateria;
					END	
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

			SELECT
				@ErrorMessage = ERROR_MESSAGE(),
				@ErrorSeverity = ERROR_SEVERITY(),
				@ErrorState = ERROR_STATE();

			RAISERROR (@ErrorMessage, -- Message text.
					   @ErrorSeverity, -- Severity.
					   @ErrorState -- State.
					   );
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
        SELECT T0.*, T1.[nombre] AS marca, T2.[nombre] AS unidad		
		FROM [dbo].[tbl_informacion] T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_marca] T1 WITH(NOLOCK) ON T0.[marca_id] = T1.[id]
		INNER JOIN [dbo].[tbl_unidad] T2 WITH(NOLOCK) ON T0.[unidad_id] = T2.[id]
		WHERE T0.[id] = @id;
    END

    --CONSULTA OPCION 6 Seleccionamos id y nombre para llenar lista desplegable.
    IF @opcion = 6 
    BEGIN
        SELECT T0.[id], 
		CONCAT(T0.[netsuit], CONCAT(' | ', CONCAT(T0.[nombre], CONCAT(' (', CONCAT(T2.[nomenclatura], CONCAT(') ', T1.[nombre])))))) AS nombre
		FROM [dbo].[tbl_informacion] T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_marca] T1 WITH(NOLOCK) ON T0.[marca_id] = T1.[id]
		INNER JOIN [dbo].[tbl_unidad] T2 WITH(NOLOCK) ON T0.[unidad_id] = T2.[id]
		WHERE T0.[prefijo] IN (@prefijo);
    END

    --CONSULTA OPCION 7 Seleccionamos por netsuit el registro en la tabla.
    IF @opcion = 7 
    BEGIN
        SELECT T0.*, T1.[nombre] AS marca, T2.[nombre] AS unidad		
		FROM [dbo].[tbl_informacion] T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_marca] T1 WITH(NOLOCK) ON T0.[marca_id] = T1.[id]
		INNER JOIN [dbo].[tbl_unidad] T2 WITH(NOLOCK) ON T0.[unidad_id] = T2.[id]
		WHERE T0.[netsuit] = @netsuit;
    END

    --CONSULTA OPCION 8 Seleccionamos por netsuit el registro en la tabla.
    IF @opcion = 8 
    BEGIN
        SELECT T1.*, T0.unidad_id
		FROM [dbo].[tbl_informacion] AS T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_materia_prima] AS T1 WITH(NOLOCK) ON T0.[id] = T1.[informacion_id]
		WHERE T0.[netsuit] = @netsuit;
    END

    --CONSULTA OPCION 9 Seleccionamos por netsuit el registro en la tabla.
    IF @opcion = 9 
    BEGIN
        SELECT T1.*, T0.unidad_id
		FROM [dbo].[tbl_informacion] AS T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_producto] AS T1 WITH(NOLOCK) ON T0.[id] = T1.[informacion_id]
		WHERE T0.[netsuit] = @netsuit;
    END
	
    --CONSULTA OPCION 10 Guardamos el registro en la tabla.
    IF @opcion = 10 
    BEGIN
		BEGIN TRY
			BEGIN TRAN

			INSERT INTO @TempMateria ([cantidad], [materia_prima_id], [unidad_id], [producto_id], [informacion_id])
			SELECT 
				cantidad,
				materia_prima_id,
				unidad_id,
				producto_id,
				informacion_id
			FROM OPENJSON(@data) WITH (
				materia_prima NVARCHAR(MAX) '$.materia_prima' AS JSON
			)
			OUTER APPLY OPENJSON(materia_prima) WITH (
				cantidad NVARCHAR(MAX) 'strict $.cantidad', 
				materia_prima_id NVARCHAR(MAX) 'strict $.materia_prima_id', 
				unidad_id NVARCHAR(MAX) 'strict $.unidad_id', 
				producto_id NVARCHAR(MAX) 'strict $.producto_id', 
				informacion_id NVARCHAR(MAX) 'strict $.informacion_id'
			);

			SET @count = (SELECT COUNT(*) FROM @TempMateria);

			WHILE @count > 0
			BEGIN
				SELECT TOP 1 @materia_prima_id = [materia_prima_id], @unidad_id = [unidad_id], @cantidad = [cantidad], @ultimo_id = [producto_id], @informacion_id = [informacion_id] FROM @TempMateria;
				SELECT @nomenclatura = [nomenclatura] FROM [dbo].[tbl_unidad] WHERE [id] = @unidad_id;

				INSERT INTO [dbo].[tbl_producto_materia_prima] ([cantidad], [nomenclatura], [unidad_id], [producto_id], [informacion_id], [materia_prima_id], [created_at], [created_by])
				VALUES (@cantidad, @nomenclatura, @unidad_id, @ultimo_id, @informacion_id, @materia_prima_id, GETDATE(), @usuario);

				DELETE TOP (1) FROM @TempMateria;
				SELECT @count = COUNT(*) FROM @TempMateria;
			END	

			COMMIT TRAN

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
			ROLLBACK TRAN

			SELECT
				@ErrorMessage = ERROR_MESSAGE(),
				@ErrorSeverity = ERROR_SEVERITY(),
				@ErrorState = ERROR_STATE();

			RAISERROR (@ErrorMessage, -- Message text.
					   @ErrorSeverity, -- Severity.
					   @ErrorState -- State.
					   );
		END CATCH
	END
	
    --CONSULTA OPCION 11 Guardamos el registro en la tabla.
    IF @opcion = 11 
    BEGIN
		BEGIN TRY
			BEGIN TRAN

			INSERT INTO @TempMateria ([cantidad], [materia_prima_id], [unidad_id], [producto_id], [informacion_id])
			SELECT 
				cantidad,
				materia_prima_id,
				unidad_id,
				producto_id,
				informacion_id
			FROM OPENJSON(@data) WITH (
				materia_prima NVARCHAR(MAX) '$.materia_prima' AS JSON
			)
			OUTER APPLY OPENJSON(materia_prima) WITH (
				cantidad NVARCHAR(MAX) 'strict $.cantidad', 
				materia_prima_id NVARCHAR(MAX) 'strict $.materia_prima_id', 
				unidad_id NVARCHAR(MAX) 'strict $.unidad_id', 
				producto_id NVARCHAR(MAX) 'strict $.producto_id', 
				informacion_id NVARCHAR(MAX) 'strict $.informacion_id'
			);

			SET @count = (SELECT COUNT(*) FROM @TempMateria);

			WHILE @count > 0
			BEGIN
				SELECT TOP 1 @materia_prima_id = [materia_prima_id], @unidad_id = [unidad_id], @cantidad = [cantidad], @ultimo_id = [producto_id], @informacion_id = [informacion_id] FROM @TempMateria;
				SELECT @nomenclatura = [nomenclatura] FROM [dbo].[tbl_unidad] WHERE [id] = @unidad_id;

				INSERT INTO [dbo].[tbl_producto_sub] ([producto_id], [sub_producto_id], [created_at], [created_by])
				VALUES (@ultimo_id, @materia_prima_id, GETDATE(), @usuario);

				DELETE TOP (1) FROM @TempMateria;
				SELECT @count = COUNT(*) FROM @TempMateria;
			END	

			COMMIT TRAN

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
			ROLLBACK TRAN

			SELECT
				@ErrorMessage = ERROR_MESSAGE(),
				@ErrorSeverity = ERROR_SEVERITY(),
				@ErrorState = ERROR_STATE();

			RAISERROR (@ErrorMessage, -- Message text.
					   @ErrorSeverity, -- Severity.
					   @ErrorState -- State.
					   );
		END CATCH
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
