<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpImportNetsuitStoreProcedure extends Migration
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
CREATE PROCEDURE sp_import_netsuit
    @id INT = 0,
    @mensaje NVARCHAR(MAX) NULL,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    --CONSULTA OPCION 1 Seleccionar todos los registros de la tabla.
    IF @opcion = 1
    BEGIN
        SELECT * FROM [dbo].[tbl_import_netsuit] WITH(NOLOCK);
    END

    --CONSULTA OPCION 3 Actualizamos el registro en la tabla.
    IF @opcion = 3
    BEGIN
        UPDATE [dbo].[tbl_import_netsuit] SET [procesado] = 1, [mensaje] = @mensaje WHERE [id] = @id;
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
        SELECT * FROM [dbo].[tbl_import_netsuit] WITH(NOLOCK) WHERE [id] = @id;
    END
    
    --CONSULTA OPCION 6 Seleccionamos registros que  no se registraron en la tabla definitiva.
    IF @opcion = 6 
    BEGIN
        SELECT *
        FROM [dbo].[tbl_import_netsuit] AS TEMPORAL WITH(NOLOCK)
        WHERE [procesado] = 0
        AND dbo.fn_obtener_letras_sin_espacio([articulo]) IN ('MP', 'EM', 'AS')
        AND NOT EXISTS (
            SELECT *
            FROM [dbo].[tbl_informacion] AS DEFINITIVA WITH(NOLOCK)
            WHERE TEMPORAL.[articulo] = DEFINITIVA.[netsuit]
        )
    END

    --CONSULTA OPCION 7 Seleccionamos registros que fueron operados y se registraron en la tabla definitiva.
    IF @opcion = 7 
    BEGIN
        SELECT *
        FROM [dbo].[tbl_import_netsuit] AS TEMPORAL WITH(NOLOCK)
        WHERE [procesado] = 0
        AND dbo.fn_obtener_letras_sin_espacio([articulo]) IN ('MP', 'EM', 'AS')
        AND EXISTS (
            SELECT *
            FROM [dbo].[tbl_informacion] AS DEFINITIVA WITH(NOLOCK)
            WHERE TEMPORAL.[articulo] = DEFINITIVA.[netsuit]
        )
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_import_netsuit;");
    }
}
