<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpCsvPlataformaEstadoStoreProcedure extends Migration
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
CREATE PROCEDURE sp_csv_plataforma_estado
    @id INT = 0,
    @nombre NVARCHAR(75) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ultimo_id INT = 0;

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
		IF(EXISTS(SELECT * FROM [dbo].[tbl_csv_plataforma_estado] WITH(NOLOCK) WHERE [nombre] = @nombre))
		BEGIN
			SELECT @ultimo_id = [id] FROM [dbo].[tbl_csv_plataforma_estado] WITH(NOLOCK) WHERE [nombre] = @nombre;

            EXECUTE sp_csv_plataforma_estado
            @id = @ultimo_id,
            @nombre = null,
            @opcion = 5
		END
        ELSE
        BEGIN
            INSERT INTO [dbo].[tbl_csv_plataforma_estado] ([nombre])
            VALUES (@nombre);

            SET @ultimo_id = IDENT_CURRENT(N'[dbo].[tbl_csv_plataforma_estado]');

			EXECUTE sp_csv_plataforma_estado
            @id = @ultimo_id,
            @nombre = null,
			@opcion = 5
        END
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
		SELECT * FROM [dbo].[tbl_csv_plataforma_estado] WITH(NOLOCK) WHERE [id] = @id;
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_csv_plataforma_estado;");
    }
}
