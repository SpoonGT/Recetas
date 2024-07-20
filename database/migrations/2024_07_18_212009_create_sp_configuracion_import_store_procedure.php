<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpConfiguracionImportStoreProcedure extends Migration
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
CREATE PROCEDURE sp_configuracion_import
    @id INT = 0,
    @icg NVARCHAR(250) NULL,
    @encabezado NVARCHAR(250) NULL,
    @posicion INT = 0,
    @plataforma_id INT = 0,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ultimo_id INT = 0;

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
		IF(EXISTS(SELECT * FROM [dbo].[tbl_configuracion_import] WHERE [plataforma_id] = @plataforma_id AND [icg] = @icg AND [encabezado] = @encabezado))
		BEGIN
			SELECT @ultimo_id = [id] FROM [dbo].[tbl_configuracion_import] WHERE [plataforma_id] = @plataforma_id AND [icg] = @icg AND [encabezado] = @encabezado;

			EXECUTE sp_configuracion_import
            @id = @ultimo_id,
            @icg = @icg,
            @encabezado = @encabezado,
            @posicion = @posicion,
            @plataforma_id = @plataforma_id,
            @usuario = @usuario,
			@opcion = 3
		END
        ELSE
        BEGIN
            INSERT INTO [dbo].[tbl_configuracion_import] ([icg], [encabezado], [posicion], [plataforma_id], [created_at], [created_by])
            VALUES (@icg, @encabezado, @posicion, @plataforma_id, GETDATE(), @usuario);

            SET @ultimo_id = IDENT_CURRENT(N'[dbo].[tbl_configuracion_import]');

			EXECUTE sp_configuracion_import
            @id = @ultimo_id,
            @icg = null,
            @encabezado = null,
            @posicion = 0,
            @plataforma_id = 0,
            @usuario = NULL,
			@opcion = 6
        END
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_configuracion_import]
        SET 
            [encabezado] = @encabezado,  
            [posicion] = @posicion,  
            [plataforma_id] = @plataforma_id,
            [updated_by] = @usuario,
            [updated_at] = GETDATE()
        WHERE [id] = @id;

        EXECUTE sp_configuracion_import
        @id = @id,
        @icg = null,
        @encabezado = null,
        @posicion = 0,
        @plataforma_id = 0,
        @usuario = NULL,
        @opcion = 6
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
		DECLARE @TempConfiguracion AS TABLE 
		(icg VARCHAR(250))

		DECLARE @FinalConfiguracion AS TABLE 
		(id INT, icg VARCHAR(250), encabezado VARCHAR(250), posicion INT, plataforma_id INT)

		INSERT INTO @TempConfiguracion (icg)
		SELECT T0.[COLUMN_NAME] AS icg
		FROM INFORMATION_SCHEMA.COLUMNS AS T0
		WHERE T0.[TABLE_SCHEMA] = 'dbo'
		AND T0.[TABLE_NAME] = 'tbl_csv_plataforma_temporal'
		AND T0.[COLUMN_NAME] NOT IN ('id', 'plataforma_id', 'plataforma', 'created_at', 'created_by', 'procesado', 'mensaje')
		ORDER BY T0.[ORDINAL_POSITION];

		DECLARE @count INT = (SELECT COUNT(*) FROM @TempConfiguracion);

		WHILE @count > 0
		BEGIN
			SELECT TOP 1 @icg = icg FROM @TempConfiguracion;

			SELECT @id = ISNULL([id], 0), @encabezado = ISNULL([encabezado], ''), @posicion = ISNULL([posicion], 0)
			FROM [dbo].[tbl_configuracion_import] WITH(NOLOCK) 
			WHERE [plataforma_id] = @plataforma_id AND [icg] = @icg;

			INSERT INTO @FinalConfiguracion (id, icg, encabezado, posicion, plataforma_id) 
			VALUES (@id, @icg, @encabezado, @posicion, @plataforma_id);

			DELETE TOP (1) FROM @TempConfiguracion;
            SELECT @count = COUNT(*) FROM @TempConfiguracion;
		END

		SELECT * FROM @FinalConfiguracion;
    END

    --CONSULTA OPCION 6 Seleccionamos por id el registro en la tabla.
    IF @opcion = 6 
    BEGIN
		SELECT * FROM [dbo].[tbl_configuracion_import] WITH(NOLOCK) WHERE [id] = @id;
    END

    --CONSULTA OPCION 7 Seleccionamos por id el registro en la tabla.
    IF @opcion = 7 
    BEGIN
		SELECT * FROM [dbo].[tbl_configuracion_import] WITH(NOLOCK) WHERE [plataforma_id] = @plataforma_id;
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_configuracion_import;");
    }
}
