<?php

use App\Models\Estadistica\Anio;
use Illuminate\Support\Facades\DB;
use App\Models\Estadistica\Bitacora;
use Illuminate\Support\Facades\Route;
use App\Models\Estadistica\Estadistica;
use App\Models\Estadistica\Estado;
use App\Models\Estadistica\Interesado;
use App\Models\Estadistica\Tramite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    $completo = Estadistica::count();
    $incompleto = Bitacora::count();

    $fecha_min = Estadistica::whereYear('ingreso', '>', 1995)->min('ingreso');
    $fecha_max = Estadistica::max('ingreso');

    $tabla_top_nuevo = Estadistica::with('anio', 'interesado', 'tramite', 'estado')->orderBy('ingreso', 'DESC')->limit(10)->get();
    $tabla_top_viejo = Estadistica::with('anio', 'interesado', 'tramite', 'estado')->whereYear('ingreso', '>', 1995)->orderBy('ingreso', 'ASC')->limit(10)->get();

    $categoria_grafica1 = Anio::orderBy('valor')->pluck('valor');
    $data_grafica1 = DB::table('estadistica')->join('anio', 'anio.id', 'anio_id')->select(DB::raw("COUNT(*) AS total"))->groupBy('valor')->orderBy('valor')->pluck('total');

    $tramites = Tramite::orderBy('nombre')->get();
    $data_grafica2 = array();

    foreach ($tramites as $item) {
        $data["name"] = $item->nombre;

        $cantidad = DB::table('estadistica')->where('tramite_id', $item->id)->count();
        $porcentaje =  $cantidad;
        $data["y"] = $porcentaje;

        array_push($data_grafica2, $data);
    }

    $anios = Anio::orderByDesc('valor')->limit(10)->get();

    $categoria_grafica3 = Anio::orderByDesc('valor')->limit(10)->pluck('valor');
    $estados = Estado::orderByDesc('nombre')->get();
    $data_grafica3 = array();
    $data = null;

    foreach ($estados as $item) {
        if (DB::table('estadistica')->where('estado_id', $item->id)->count() > 25) {
            $data["name"] = $item->nombre;
            $cantidad = array();
            foreach ($anios as $anio) {
                $encontrados = DB::table('estadistica')->where('estado_id', $item->id)->where('anio_id', $anio->id)->count();
                array_push($cantidad, $encontrados);
            }
            $data["data"] = $cantidad;
            array_push($data_grafica3, $data);
        }
    }

    $interesados = DB::table('estadistica')->select(DB::raw("COUNT(interesado_id) AS total"), DB::raw("interesado_id"))->groupBy('interesado_id')->orderBy('total')->having('total', '>', 1)->pluck('interesado_id');
    $data_grafica4 = DB::table('estadistica')->select(DB::raw("COUNT(interesado_id) AS total"))->groupBy('interesado_id')->orderBy('total')->having('total', '>', 1)->pluck('total');
    $categoria_grafica4 = Interesado::whereIn('id', $interesados)->pluck('nombre_completo');

    $cantidad_anios = Anio::get();
    $cantidad_interesados = Interesado::get();
    $cantidad_tramites = Tramite::get();
    $cantidad_estados = Estado::get();

    $expedientes = Estadistica::with('anio', 'interesado', 'tramite', 'estado')->get();

    $date = date("Y-m-d");
    $categoria_grafica_agrupado = [
        "14/01/1996 - 14/01/2000",
        "14/01/2000 - 14/01/2004",
        "14/01/2004 - 14/01/2008",
        "14/01/2008 - 14/01/2012",
        "14/01/2012 - 14/01/2016",
        "14/01/2016 - 14/01/2020",
        "14/01/2020 - 14/01/2024",
        "14/01/2024 - {$date}"
    ];
    $categoria_grafica_agrupados = [
        "Período Presidencial 14/01/1996 - 14/01/2000",
        "Período Presidencial 14/01/2000 - 14/01/2004",
        "Período Presidencial 14/01/2004 - 14/01/2008",
        "Período Presidencial 14/01/2008 - 14/01/2012",
        "Período Presidencial 14/01/2012 - 14/01/2016",
        "Período Presidencial 14/01/2016 - 14/01/2020",
        "Período Presidencial 14/01/2020 - 14/01/2024",
        "Período Presidencial 14/01/2024 - {$date}"
    ];
    $agrupado_1 = DB::table('estadistica')->whereBetween('ingreso', ['1996-01-01', '2000-01-14'])->count();
    $agrupado_2 = DB::table('estadistica')->whereBetween('ingreso', ['2000-01-14', '2004-01-14'])->count();
    $agrupado_3 = DB::table('estadistica')->whereBetween('ingreso', ['2004-01-14', '2008-01-14'])->count();
    $agrupado_4 = DB::table('estadistica')->whereBetween('ingreso', ['2008-01-14', '2012-01-14'])->count();
    $agrupado_5 = DB::table('estadistica')->whereBetween('ingreso', ['2012-01-14', '2016-01-14'])->count();
    $agrupado_6 = DB::table('estadistica')->whereBetween('ingreso', ['2016-01-14', '2020-01-14'])->count();
    $agrupado_7 = DB::table('estadistica')->whereBetween('ingreso', ['2020-01-14', '2024-01-14'])->count();
    $agrupado_8 = DB::table('estadistica')->whereBetween('ingreso', ['2024-01-14', $date])->count();
    $data_grafica_agrupado = [$agrupado_1, $agrupado_2, $agrupado_3, $agrupado_4, $agrupado_5, $agrupado_6, $agrupado_7, $agrupado_8];

    $data_grafica5 = array();
    $data = null;

    for ($i = 0; $i < 8; $i++) {
        $data["name"] = $categoria_grafica_agrupados[$i];
        $data["y"] = $data_grafica_agrupado[$i];
        array_push($data_grafica5, $data);
    }

    return view('welcome', compact(
        'completo',
        'incompleto',
        'fecha_min',
        'fecha_max',
        'tabla_top_nuevo',
        'tabla_top_viejo',
        'categoria_grafica1',
        'data_grafica1',
        'data_grafica2',
        'categoria_grafica3',
        'data_grafica3',
        'categoria_grafica4',
        'data_grafica4',
        'cantidad_anios',
        'cantidad_interesados',
        'cantidad_tramites',
        'cantidad_estados',
        'expedientes',
        'categoria_grafica_agrupado',
        'data_grafica5'
    ));
});
