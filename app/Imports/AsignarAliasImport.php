<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;

class AsignarAliasImport implements ToModel
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

            $punto_venta = $this->savePuntoVenta($row[2]);
            $plataforma = DB::select(
                "exec [dbo].[sp_plataforma_crud] 0, '$row[0]', NULL, NULL, 7"
            )[0];

            $existe = DB::select(
                "exec [dbo].[sp_alias_maintenance] 0, '$row[1]', $punto_venta->id, $plataforma->id, NULL, 7"
            );

            if (count($existe) == 0) {
                $insert = DB::select(
                    "exec [dbo].[sp_alias_maintenance] 0, '$row[1]', $punto_venta->id, $plataforma->id, 'migracion', 2"
                )[0];

                $alias = DB::select(
                    "exec [dbo].[sp_alias_maintenance] $insert->id, '$row[1]', $punto_venta->id, $plataforma->id, 'migracion', 3"
                )[0];

                echo "Alias Creado: {$alias->id} - {$alias->punto_venta_id}|{$alias->plataforma_id} {$alias->alias}" . PHP_EOL;
            }
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

            if (count($quitar_spoon) == 1) {
                $local = trim(str_replace("Spoon", "", $data));
            } else {
                $local = trim($quitar_spoon[1]);
            }

            $insert = DB::select(
                "exec [dbo].[sp_punto_venta_crud] 0, '$codigo', '$local', '$data', 'migracion', 2"
            )[0];

            $punto_venta = DB::select(
                "exec [dbo].[sp_punto_venta_crud] $insert->id, '$codigo', '$local', '$data', 'migracion', 3"
            )[0];

            echo "Punto de Venta Creado: {$punto_venta->id} - {$punto_venta->local}" . PHP_EOL;

            return $punto_venta;
        } else {
            return $existe_punto[0];
        }
    }
}
