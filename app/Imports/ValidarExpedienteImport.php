<?php

namespace App\Imports;

use App\Models\Estadistica\Anio;
use Illuminate\Support\Collection;
use App\Models\Estadistica\Estadistica;
use Maatwebsite\Excel\Concerns\ToModel;

class ValidarExpedienteImport implements ToModel
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

            $expediente = explode("-", $row[0]);
            $anio = Anio::where('valor', $expediente[1])->first();

            Estadistica::where('anio_id', $anio->id)
            ->where('correlativo', $expediente[0])
            ->update(['resuelto' => true]);

            print $row[0] . PHP_EOL;
        } catch (\Throwable $th) {
            print $th->getMessage() . PHP_EOL;
        }
    }
}
