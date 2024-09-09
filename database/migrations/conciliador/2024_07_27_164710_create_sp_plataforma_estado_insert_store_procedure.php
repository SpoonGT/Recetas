<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateSpPlataformaEstadoInsertStoreProcedure extends Migration
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
CREATE PROCEDURE sp_plataforma_estado_insert
    @id INT = 0,
    @plataforma_id INT = 0,
    @estado_id INT = 0,
    @opcion INT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ultimo_id INT = 0;

    --CONSULTA OPCION 2 Guardamos el registro en la tabla.
    IF @opcion = 2 
    BEGIN
		IF(EXISTS(SELECT * FROM [dbo].[tbl_plataforma_estado] WITH(NOLOCK) WHERE [plataforma_id] = @plataforma_id AND [estado_id] = @estado_id))
		BEGIN
			SELECT @ultimo_id = [id] FROM [dbo].[tbl_plataforma_estado] WITH(NOLOCK) WHERE [plataforma_id] = @plataforma_id AND [estado_id] = @estado_id;

            EXECUTE sp_plataforma_estado_insert
            @id = @ultimo_id,
            @plataforma_id = 0,
            @estado_id = 0,
            @opcion = 5
		END
        ELSE
        BEGIN
            INSERT INTO [dbo].[tbl_plataforma_estado] ([plataforma_id], [estado_id])
            VALUES (@plataforma_id, @estado_id);

            SET @ultimo_id = IDENT_CURRENT(N'[dbo].[tbl_plataforma_estado]');

			EXECUTE sp_plataforma_estado_insert
            @id = @ultimo_id,
            @plataforma_id = 0,
            @estado_id = 0,
			@opcion = 5
        END
    END

    --CONSULTA OPCION 5 Seleccionamos por id el registro en la tabla.
    IF @opcion = 5 
    BEGIN
        SELECT T0.*, 
        T1.nombre AS 'estado' 
		FROM [dbo].[tbl_plataforma_estado] T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_estado] T1 WITH(NOLOCK) ON T0.estado_id = T1.id
		WHERE T0.[id] = @id;
    END

    --CONSULTA OPCION 6 Seleccionamos por id el registro de la plataforma en la tabla.
    IF @opcion = 6 
    BEGIN
        SELECT T0.*, 
        T1.nombre AS 'estado' 
		FROM [dbo].[tbl_plataforma_estado] T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_estado] T1 WITH(NOLOCK) ON T0.estado_id = T1.id
		WHERE T0.[plataforma_id] = @plataforma_id;
    END

    --CONSULTA OPCION 7 Seleccionamos todos los estados registrados para todas las plataformas
    IF @opcion = 7 
    BEGIN
        SELECT T0.*, 
        T1.nombre AS 'estado' 
		FROM [dbo].[tbl_plataforma_estado] T0 WITH(NOLOCK)
		INNER JOIN [dbo].[tbl_estado] T1 WITH(NOLOCK) ON T0.estado_id = T1.id
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
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_plataforma_estado_insert;");
    }
}
