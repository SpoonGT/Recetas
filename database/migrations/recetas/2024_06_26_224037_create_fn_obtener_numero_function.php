<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CreateFnObtenerNumeroFunction extends Migration
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
CREATE FUNCTION fn_obtener_numero (
	@cadena VARCHAR(MAX)
)
RETURNS INT
AS
BEGIN
	DECLARE @intAlpha INT
	SET @intAlpha = PATINDEX('%[^0-9]%', @cadena)

	BEGIN
		WHILE @intAlpha > 0
		BEGIN
		SET @cadena = STUFF(@cadena, @intAlpha, 1, '' )
		SET @intAlpha = PATINDEX('%[^0-9]%', @cadena )
		END
	END

	RETURN CONVERT(INT, ISNULL(@cadena, '0'))
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
        DB::unprepared("DROP FUNCTION IF EXISTS fn_obtener_numero;");
    }
}
