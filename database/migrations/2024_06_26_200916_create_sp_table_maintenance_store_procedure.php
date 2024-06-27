<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpTableMaintenanceStoreProcedure extends Migration
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
CREATE PROCEDURE sp_table_maintenance
    @id INT = 0,
    @table NVARCHAR(150),
    @data NVARCHAR(MAX) NULL,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ultimo_id INT = 0, @nombre NVARCHAR(MAX), @nomenclatura NVARCHAR(MAX), @tipo NVARCHAR(MAX), @prefijos NVARCHAR(MAX), 
	@query NVARCHAR(MAX), @parameters NVARCHAR(MAX);

	IF @data IS NOT NULL
	BEGIN
		SELECT 
			@nombre = nombre,
            @prefijos = prefijos,
			@nomenclatura = nomenclatura,
			@tipo = tipo
		FROM OPENJSON(@data) WITH (
			nombre NVARCHAR(50) '$.nombre',
			prefijos NVARCHAR(50) '$.prefijos',
			nomenclatura NVARCHAR(50) '$.nomenclatura',
			tipo NVARCHAR(50) '$.tipo'
		)
	END

    --CONSULTA OPCION 1 Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
		SET @query = N'SELECT * FROM [dbo].['+@table+N'] WHERE [deleted_at] IS NULL';
		EXECUTE sp_executesql @query;
    END

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
		IF @table = 'tbl_categoria'
		BEGIN
			SET @query = N'INSERT INTO [dbo].['+@table+N'] ([nombre], [prefijos], [created_at], [created_by]) VALUES (@pnombre, @pprefijos, GETDATE(), @pcreated_by);';
			SET @parameters = N'@pnombre NVARCHAR(MAX), @pprefijos NVARCHAR(MAX), @pcreated_by NVARCHAR(MAX)';
			EXECUTE sp_executesql @query, @parameters, @pnombre = @nombre, @pprefijos = @prefijos, @pcreated_by = @usuario;
		END

		IF @table = 'tbl_marca' OR @table = 'tbl_alergeno'
		BEGIN
			SET @query = N'INSERT INTO [dbo].['+@table+N'] ([nombre], [created_at], [created_by]) VALUES (@pnombre, GETDATE(), @pcreated_by);';
			SET @parameters = N'@pnombre NVARCHAR(MAX), @pcreated_by NVARCHAR(MAX)';
			EXECUTE sp_executesql @query, @parameters, @pnombre = @nombre, @pcreated_by = @usuario;
		END

		IF @table = 'tbl_unidad'
		BEGIN
			SET @query = N'INSERT INTO [dbo].['+@table+N'] ([nomenclatura], [nombre], [created_at], [created_by]) VALUES (@pnomenclatura, @pnombre, GETDATE(), @pcreated_by);';
			SET @parameters = N'@pnomenclatura NVARCHAR(MAX), @pnombre NVARCHAR(MAX), @pcreated_by NVARCHAR(MAX)';
			EXECUTE sp_executesql @query, @parameters, @pnomenclatura = @nomenclatura, @pnombre = @nombre, @pcreated_by = @usuario;
		END

		IF @table = 'tbl_area'
		BEGIN
			SET @query = N'INSERT INTO [dbo].['+@table+N'] ([nombre], [tipo], [created_at], [created_by]) VALUES (@pnombre, @ptipo, GETDATE(), @pcreated_by);';
			SET @parameters = N'@pnombre NVARCHAR(MAX), @ptipo NVARCHAR(MAX), @pcreated_by NVARCHAR(MAX)';
			EXECUTE sp_executesql @query, @parameters, @pnombre = @nombre, @ptipo = @tipo, @pcreated_by = @usuario;
		END

        SET @ultimo_id = IDENT_CURRENT(N'[dbo].['+@table+N']');

        EXECUTE sp_table_maintenance
        @id = @ultimo_id,
        @table = @table,
        @data = null,
        @usuario = NULL,
        @opcion = 5
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
		IF @table = 'tbl_categoria'
		BEGIN
			SET @query = N'UPDATE [dbo].['+@table+N'] SET [nombre] = @pnombre, [prefijos] = @pprefijos, [updated_at] = GETDATE(), [updated_by] = @pupdated_by WHERE [id] = @pid;';
			SET @parameters = N'@pid INT, @pnombre NVARCHAR(MAX), @pprefijos NVARCHAR(MAX), @pupdated_by NVARCHAR(MAX)';
			EXECUTE sp_executesql @query, @parameters, @pid = @id, @pnombre = @nombre, @pprefijos = @prefijos, @pupdated_by = @usuario;
		END

		IF @table = 'tbl_marca' OR @table = 'tbl_alergeno'
		BEGIN
			SET @query = N'UPDATE [dbo].['+@table+N'] SET [nombre] = @pnombre, [updated_at] = GETDATE(), [updated_by] = @pupdated_by WHERE [id] = @pid;';
			SET @parameters = N'@pid INT, @pnombre NVARCHAR(MAX), @pupdated_by NVARCHAR(MAX)';
			EXECUTE sp_executesql @query, @parameters, @pid = @id, @pnombre = @nombre, @pupdated_by = @usuario;
		END

		IF @table = 'tbl_unidad'
		BEGIN
			SET @query = N'UPDATE [dbo].['+@table+N'] SET [nomenclatura] = @pnomenclatura, [nombre] = @pnombre, [updated_at] = GETDATE(), [updated_by] = @pupdated_by WHERE [id] = @pid;';
			SET @parameters = N'@pid INT, @pnomenclatura NVARCHAR(MAX), @pnombre NVARCHAR(MAX), @pupdated_by NVARCHAR(MAX)';
			EXECUTE sp_executesql @query, @parameters, @pid = @id, @pnomenclatura = @nomenclatura, @pnombre = @nombre, @pupdated_by = @usuario;
		END

		IF @table = 'tbl_area'
		BEGIN
			SET @query = N'UPDATE [dbo].['+@table+N'] SET [nombre] = @pnombre, [tipo] = @ptipo, [updated_at] = GETDATE(), [updated_by] = @pupdated_by WHERE [id] = @pid;';
			SET @parameters = N'@pid INT, @pnombre NVARCHAR(MAX), @ptipo NVARCHAR(MAX), @pupdated_by NVARCHAR(MAX)';
			EXECUTE sp_executesql @query, @parameters, @pid = @id, @ptipo = @tipo, @pnombre = @nombre, @pupdated_by = @usuario;
		END

        EXECUTE sp_table_maintenance
        @id = @id,
        @table = @table,
        @data = null,
        @usuario = NULL,
        @opcion = 5
    END

    --CONSULTA OPCION 4 Eliminamos el registro en la tabla.
    IF @opcion = 4 
    BEGIN
		SET @query = N'UPDATE [dbo].['+@table+N'] SET [deleted_by] = @pdeleted_by, [deleted_at] = GETDATE() WHERE [id] = @pid;';
		SET @parameters = N'@pid INT, @pdeleted_by NVARCHAR(MAX)';
		EXECUTE sp_executesql @query, @parameters, @pid = @id, @pdeleted_by = @usuario;
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
		SET @query = N'SELECT * FROM [dbo].['+@table+N'] WHERE [deleted_at] IS NULL AND id = @identificador';
		SET @parameters = N'@identificador INT';
		EXECUTE sp_executesql @query, @parameters, @identificador = @id;
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
		DB::unprepared("DROP PROCEDURE IF EXISTS sp_table_maintenance;");
	}
}
