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

    $fecha_min = Estadistica::min('ingreso');
    $fecha_max = Estadistica::max('ingreso');

    $tabla_top_nuevo = Estadistica::with('anio', 'interesado', 'tramite', 'estado')->orderBy('ingreso', 'DESC')->limit(10)->get();
    $tabla_top_viejo = Estadistica::with('anio', 'interesado', 'tramite', 'estado')->orderBy('ingreso', 'ASC')->limit(10)->get();

    $categoria_grafica1 = Anio::orderBy('valor')->pluck('valor');
    $data_grafica1 = DB::table('estadistica')->join('anio', 'anio.id', 'anio_id')->select(DB::raw("COUNT(*) AS total"))->groupBy('valor')->orderBy('valor')->pluck('total');

    $tramites = Tramite::orderBy('nombre')->get();
    $data_grafica2 = array();

    foreach ($tramites as $item) {
        $data["name"] = $item->nombre;

        $cantidad = DB::table('estadistica')->where('tramite_id', $item->id)->count();
        $porcentaje =  $cantidad * 100 / $completo;
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
        'expedientes'
    ));
});
