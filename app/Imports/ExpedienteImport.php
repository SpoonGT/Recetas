<?php

namespace App\Imports;

use Exception;
use App\Models\Estadistica\Anio;
use App\Models\Estadistica\Estado;
use Illuminate\Support\Facades\DB;
use App\Models\Estadistica\Tramite;
use App\Models\Estadistica\Bitacora;
use App\Models\Estadistica\Interesado;
use App\Models\Estadistica\Estadistica;
use Maatwebsite\Excel\Concerns\ToModel;

class ExpedienteImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            if ($row[0] == "NO" || is_null($row[0]) || empty($row[0])) {
                return null;
            }

            DB::beginTransaction();

            if (is_null($row[1]) || empty($row[1])) throw new Exception("El año está vacío", 1);

            $anio = Anio::firstOrNew(['valor' => $row[1]]);
            $anio->save();

            if (is_null($row[3]) || empty($row[3])) throw new Exception("El nombre del interesado está vacío", 1);

            $interesado = Interesado::firstOrNew(['nombre_completo' => $row[3]]);
            $interesado->save();

            if (is_null($row[4]) || empty($row[4])) throw new Exception("El nombre del trámite está vacío", 1);

            $tramite = Tramite::firstOrNew(['nombre' => $row[4]]);
            $tramite->save();

            if (is_null($row[6]) || empty($row[6])) throw new Exception("El nombre del estado está vacío", 1);

            $estado = Estado::firstOrNew(['nombre' => $row[6]]);
            $estado->save();

            $correlativo = str_replace(['S/N', '(5T)', ' ', ' Y 456-2010', '(2T)', '-', '2016', '(3T)', '|', '2T)'], ['', '', '', '', '', '', '', '', '', ''], $row[2]);

            Estadistica::create(
                [
                    'anio_id' => $anio->id,
                    'correlativo' => $correlativo == '.' ? 0 : intval($correlativo),
                    'interesado_id' => $interesado->id,
                    'tramite_id' => $tramite->id,
                    'ingreso' => is_numeric($row[5]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[5])->format('Y-m-d') : date('Y-m-d', strtotime($row[5])),
                    'estado_id' => $estado->id
                ]
            );

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            Bitacora::create(
                [
                    "error" => $th->getMessage(),
                    "json"  => json_encode($row)
                ]
            );

            print $th->getMessage() . PHP_EOL;
        }
    }
}
