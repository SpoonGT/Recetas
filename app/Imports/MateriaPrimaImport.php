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

            $es_valdo = substr($row[6], 0, 2);

            if ($es_valdo == "MP" || $es_valdo == "EM") {
                $json = '[{"id":1},{"id":2}]';

                $informacion = DB::select(
                    "exec [dbo].[sp_information_maintenance] 0, '{$row[6]}', '{$row[7]}', '{$row[3]}', 1, 1, null, 'tbl_materia_prima', '{$json}', 'migration', 2"
                )[0];

                echo "Información Creada: {$informacion->id} - {$informacion->netsuit} | {$informacion->nombre}" . PHP_EOL;
            }
        } catch (\Throwable $th) {
            print $th->getMessage() . PHP_EOL;
        }
    }
}
