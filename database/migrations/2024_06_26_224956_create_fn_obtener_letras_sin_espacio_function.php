<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateFnObtenerLetrasSinEspacioFunction extends Migration
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
CREATE FUNCTION fn_obtener_letras_sin_espacio (
	@cadena VARCHAR(MAX)
)
RETURNS VARCHAR(MAX)
AS
BEGIN
	DECLARE @intAlpha INT
	SET @intAlpha = PATINDEX('%[^a-zA-Z]%', @cadena)

	BEGIN
		WHILE @intAlpha > 0
		BEGIN
		SET @cadena = STUFF(@cadena, @intAlpha, 1, '' )
		SET @intAlpha = PATINDEX('%[^a-zA-Z ]%', @cadena )
		END
	END

	RETURN ISNULL(@cadena, '')
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
        DB::unprepared("DROP FUNCTION IF EXISTS fn_obtener_letras_sin_espacio;");
    }
}
