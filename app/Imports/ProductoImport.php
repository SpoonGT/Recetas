<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductoImport implements ToModel
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

            $existe_materia = DB::select(
                "exec [dbo].[sp_information_maintenance] 0, '{$row[6]}', null, null, 0, 0, null, null, null, 'migration', 8"
            );

            if (count($existe_materia) > 0) {
                $es_valdo = substr($row[0], 0, 2);

                if ($es_valdo == "SE") {

                    $data["ensamble"] = $row[0];
                    $data["materia"] = $row[6];
                    $data["sub_ensmable"] = $es_valdo == "SE";
                    $data["materia_prima"] = array();
                    $materia_prima["cantidad"] = $row[10];
                    $materia_prima["materia_prima_id"] = $existe_materia[0]->id;
                    $materia_prima["unidad_id"] = $existe_materia[0]->unidad_id;

                    $existe = DB::select(
                        "exec [dbo].[sp_information_maintenance] 0, '{$row[0]}', null, null, 0, 0, null, null, null, 'migration', 9"
                    );

                    if (count($existe) == 0) {
                        array_push($data["materia_prima"], $materia_prima);
                        $json = json_encode($data);
                        echo "JSON PADRE: {$json}" . PHP_EOL;

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
                            "exec [dbo].[sp_information_maintenance] 0, '{$row[0]}', '{$row[3]}', '{$row[7]}', 1, {$unidad_id}, null, 'tbl_producto', '{$json}', 'migration', 2"
                        )[0];

                        echo "Información Creada: {$informacion->id} - {$informacion->netsuit} | {$informacion->nombre}" . PHP_EOL;
                    } else {
                        $materia_prima["producto_id"] = $existe[0]->id;
                        $materia_prima["informacion_id"] = $existe[0]->informacion_id;
                        array_push($data["materia_prima"], $materia_prima);
                        $json = json_encode($data);
                        echo "JSON HIJO: {$json}" . PHP_EOL;

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
                            "exec [dbo].[sp_information_maintenance] 0, '{$row[0]}', '{$row[7]}', '{$row[3]}', 1, {$unidad_id}, null, null, '{$json}', 'migration', 10"
                        )[0];

                        echo "Información Creada: {$informacion->id} - {$informacion->netsuit} | {$informacion->nombre}" . PHP_EOL;
                    }
                }
            }
        } catch (\Throwable $th) {
            print $th->getMessage() . PHP_EOL;
        }
    }
}
