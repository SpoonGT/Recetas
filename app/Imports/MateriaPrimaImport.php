<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;

class MateriaPrimaImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            if ($row[0] == "Ensamblaje" || is_null($row[0]) || is_null($row[6]) || empty($row[6])) {
                return null;
            }

            $existe = DB::select(
                "exec [dbo].[sp_information_maintenance] 0, '{$row[6]}', null, null, 0, 0, null, null, null, 'migration', 7"
            );

            if (count($existe) == 0) {
                $es_valdo = substr($row[6], 0, 2);

                if ($es_valdo == "MP" || $es_valdo == "EM") {

                    $rondas = random_int(0, 20);

                    $alergenos = array();
                    for ($i = 0; $i < $rondas; $i++) {
                        $data["id"] = random_int(1, 20);
                        array_push($alergenos, $data);
                    }
                    $json = count($alergenos) > 0 ? json_encode($alergenos) : '[{"id":1}]';

                    $unidades = DB::select(
                        "exec [dbo].[sp_table_maintenance] 0, 'tbl_unidad', null, 'migration', 1"
                    );

                    $unidad_id = 0;
                    foreach ($unidades as $key => $value) {
                        if (mb_strtolower($value->nombre) == mb_strtolower($row[11])) {
                            $unidad_id = $value->id;
                        }
                    }

                    $informacion = DB::select(
                        "exec [dbo].[sp_information_maintenance] 0, '{$row[6]}', '{$row[7]}', '{$row[3]}', 1, {$unidad_id}, null, 'tbl_materia_prima', '{$json}', 'migration', 2"
                    )[0];

                    echo "Información Creada: {$informacion->id} - {$informacion->netsuit} | {$informacion->nombre}" . PHP_EOL;
                }
            }
        } catch (\Throwable $th) {
            print $th->getMessage() . PHP_EOL;
        }
    }
}
