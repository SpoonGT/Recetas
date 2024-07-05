<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;

class PuntoVentaImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            if (is_null($row[0]) || empty($row[0])) {
                return null;
            }

            $this->savePuntoVenta($row[0]);
        } catch (\Throwable $th) {
            print $th->getMessage() . PHP_EOL;
        }
    }

    private function savePuntoVenta($data)
    {
        $existe_punto = DB::select(
            "exec [dbo].[sp_punto_venta_crud] 0, NULL, NULL, '$data', NULL, 7"
        );

        if (count($existe_punto) == 0) {
            $cantidad = DB::select(
                "exec [dbo].[sp_punto_venta_crud] 0, NULL, NULL, NULL, NULL, 1"
            );

            $codigo = str_pad(count($cantidad) + 1, 4, "0", STR_PAD_LEFT);

            $quitar_spoon = explode("-", $data);
            $local = trim($quitar_spoon[1]);

            $insert = DB::select(
                "exec [dbo].[sp_punto_venta_crud] 0, '$codigo', '$local', '$data', 'migracion', 2"
            )[0];

            $punto_venta = DB::select(
                "exec [dbo].[sp_punto_venta_crud] $insert->id, '$codigo', '$local', '$data', 'migracion', 3"
            )[0];

            echo "Punto de Venta Creado: {$punto_venta->id} - {$punto_venta->local}" . PHP_EOL;
        }
    }
}
