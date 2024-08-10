<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpCsvIcgDuplicadoResueltoStoreProcedure extends Migration
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
CREATE PROCEDURE sp_csv_icg_duplicado_resuelto
    @id INT = 0,
    @usuario NVARCHAR(25) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    --CONSULTA OPCION 1 Seleccionamos todos los registros.
    IF @opcion = 1
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_icg_duplicado] WITH(NOLOCK);
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_csv_icg_duplicado]
        SET 
            [resuelto] = 1,
            [updated_by] = @usuario,
            [updated_at] = GETDATE()
        WHERE [id] = @id;
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
        SELECT * FROM [dbo].[tbl_csv_icg_duplicado] WITH(NOLOCK) WHERE [id] = @id;
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_csv_icg_duplicado_resuelto;");
    }
}
