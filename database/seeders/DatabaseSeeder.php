<?php

namespace Database\Seeders;

use App\Imports\ProductoImport;
use Illuminate\Database\Seeder;
use App\Imports\SubEnsambleImport;
use Illuminate\Support\Facades\DB;
use App\Imports\MateriaPrimaImport;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->generar_menu();
        $this->seguridad();
        if (Config::get('database.connections.sqlsrv.database') == "RECETAS") {
            $this->catalogo_receta();
            $this->import_receta();
            $this->materia_prima_receta();
            $this->producto_receta();
        }
    }

    private function generar_menu()
    {
        $rol = DB::select(
            "exec [dbo].[sp_rol_crud] 0, 'Administrador', 'Rol para administrar todas las pantallas del sistema.', 'migration', 2"
        )[0];

        echo "Rol Creado: {$rol->id} - {$rol->nombre}" . PHP_EOL;

        $menu = DB::select(
            "exec [dbo].[sp_menu_crud] 0, 'Inicio', '/Home', 'fa-solid fa-house-laptop', 0, 'migration', 2"
        )[0];

        echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

        $rol_menu = DB::select(
            "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
        )[0];

        echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

        $menu = DB::select(
            "exec [dbo].[sp_menu_crud] 0, 'Seguridad', '/Seguridad', 'fa-solid fa-gears', 0, 'migration', 2"
        )[0];

        echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

        $menu_id = $menu->id;

        $rol_menu = DB::select(
            "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
        )[0];

        echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

        $menu = DB::select(
            "exec [dbo].[sp_menu_crud] 0, 'Rol', '/Seguridad/Rol', 'fa-solid fa-dice-d6', {$menu_id}, 'migration', 2"
        )[0];

        echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

        $rol_menu = DB::select(
            "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
        )[0];

        echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

        $menu = DB::select(
            "exec [dbo].[sp_menu_crud] 0, 'Menu', '/Seguridad/Menu', 'fa-solid fa-list', {$menu_id}, 'migration', 2"
        )[0];

        echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

        $rol_menu = DB::select(
            "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
        )[0];

        echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

        $menu = DB::select(
            "exec [dbo].[sp_menu_crud] 0, 'Usuario', '/Seguridad/Usuario', 'fa-solid fa-user-gear', {$menu_id}, 'migration', 2"
        )[0];

        echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

        $rol_menu = DB::select(
            "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
        )[0];

        echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;
    }

    private function seguridad()
    {
        $configuracion_menu = DB::select(
            "exec [dbo].[sp_rol_menu_config] 1, 0, 'migration', 6  "
        );

        echo "=========================== MENU CONFIGURADO ===========================" . PHP_EOL;

        var_dump($configuracion_menu);

        echo "======================================================================" . PHP_EOL;

        $contrasenia = "aD71mfjmsFmDBnAlc1Hu+fEfJqsZ7+Gp8aSgxVZAT40=";

        $usuario = DB::select(
            "exec [dbo].[sp_usuario_crud] 0, 'Usuario', 'usuario.desa', '{$contrasenia}', 'usuario@gmail.com', 1, 'migration', 2"
        )[0];

        echo "Usuario Creado: {$usuario->id} - {$usuario->nombre_completo}" . PHP_EOL;
    }

    private function catalogo_receta()
    {
        echo "=========================== CATEGORIA ===========================" . PHP_EOL;

        $json = '{"nombre": "Decoracion", "prefijos": "PTL"}';
        $categoria = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_categoria', '{$json}', 'migration', 2"
        )[0];

        echo "Categoría Creada: {$categoria->id} - {$categoria->nombre}" . PHP_EOL;

        $json = '{"nombre": "Decoración", "prefijos": "PTL, PTI"}';
        $categoria = DB::select(
            "exec [dbo].[sp_table_maintenance] $categoria->id, 'tbl_categoria', '{$json}', 'migration', 3"
        )[0];

        echo "Categoría Actualizar: {$categoria->id} - {$categoria->nombre}" . PHP_EOL;

        echo "======================================================================" . PHP_EOL;

        echo "=========================== MARCA ===========================" . PHP_EOL;

        $json = '{"nombre": "Faz"}';
        $marca = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_marca', '{$json}', 'migration', 2"
        )[0];

        echo "Marca Creada: {$marca->id} - {$marca->nombre}" . PHP_EOL;

        $json = '{"nombre": "Fas"}';
        $marca = DB::select(
            "exec [dbo].[sp_table_maintenance] $marca->id, 'tbl_marca', '{$json}', 'migration', 3"
        )[0];

        echo "Marca Actualizar: {$marca->id} - {$marca->nombre}" . PHP_EOL;

        echo "======================================================================" . PHP_EOL;

        echo "=========================== UNIDAD ===========================" . PHP_EOL;

        $json = '{"nomenclatura": "g", "nombre": "Gramos"}';
        $unidad = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_unidad', '{$json}', 'migration', 2"
        )[0];

        echo "Unidad Creada: {$unidad->id} - {$unidad->nombre} | {$unidad->nomenclatura}" . PHP_EOL;

        $json = '{"nomenclatura": "kg", "nombre": "Kilogramos"}';
        $unidad = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_unidad', '{$json}', 'migration', 2"
        )[0];

        echo "Unidad Creada: {$unidad->id} - {$unidad->nombre} | {$unidad->nomenclatura}" . PHP_EOL;

        $json = '{"nomenclatura": "ml", "nombre": "Mililitros"}';
        $unidad = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_unidad', '{$json}', 'migration', 2"
        )[0];

        echo "Unidad Creada: {$unidad->id} - {$unidad->nombre} | {$unidad->nomenclatura}" . PHP_EOL;

        $json = '{"nomenclatura": "ud", "nombre": "Unidades"}';
        $unidad = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_unidad', '{$json}', 'migration', 2"
        )[0];

        echo "Unidad Creada: {$unidad->id} - {$unidad->nombre} | {$unidad->nomenclatura}" . PHP_EOL;

        echo "======================================================================" . PHP_EOL;

        echo "=========================== ALERGENO ===========================" . PHP_EOL;

        for ($i = 0; $i < 20; $i++) {
            $alergia_crear["nombre"] =  "Alergia {$i}";
            $json = json_encode($alergia_crear);
            $alergeno = DB::select(
                "exec [dbo].[sp_table_maintenance] 0, 'tbl_alergeno', '{$json}', 'migration', 2"
            )[0];

            echo "Alergeno Creada: {$alergeno->id} - {$alergeno->nombre}" . PHP_EOL;
        }

        echo "======================================================================" . PHP_EOL;

        echo "=========================== AREA ===========================" . PHP_EOL;

        $json = '{"tipo": "EMPAQUE", "nombre": "UNidad 1"}';
        $area = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_area', '{$json}', 'migration', 2"
        )[0];

        echo "Area Creada: {$area->id} - {$area->nombre} | {$area->tipo}" . PHP_EOL;

        $json = '{"tipo": "PRODUCE", "nombre": "Unidad 1"}';
        $area = DB::select(
            "exec [dbo].[sp_table_maintenance] $area->id, 'tbl_area', '{$json}', 'migration', 3"
        )[0];

        echo "Area Actualizar: {$area->id} - {$area->nombre} | {$area->tipo}" . PHP_EOL;

        echo "======================================================================" . PHP_EOL;
    }

    private function import_receta()
    {
        Excel::import(new MateriaPrimaImport, 'database/seeders/Netsuite.xlsx');
        Excel::import(new ProductoImport, 'database/seeders/Netsuite.xlsx');
        Excel::import(new SubEnsambleImport, 'database/seeders/Netsuite.xlsx');
    }

    private function materia_prima_receta()
    {
        $test_materia = DB::select(
            "exec [dbo].[sp_materia_prima_maintenance] 1, 0, 0, 'migration', 3"
        )[0];

        echo "Actualizar Estado Materia: {$test_materia->activo}" . PHP_EOL;

        $test_materia = count(DB::select(
            "exec [dbo].[sp_materia_prima_maintenance] 1, 0, 0, 'migration', 5"
        ));

        echo "Materia Prima: Se encontraron {$test_materia}" . PHP_EOL;

        $test_materia = count(DB::select(
            "exec [dbo].[sp_materia_prima_maintenance] 0, 0, 0, 'migration', 6"
        ));

        echo "Materia Prima: Se encontraron {$test_materia}" . PHP_EOL;

        $test_materia = DB::select(
            "exec [dbo].[sp_materia_prima_maintenance] 1, 0, 0, 'migration', 7"
        )[0];

        echo "Materia Prima: Se encontraron {$test_materia->netsuit}" . PHP_EOL;

        $test_materia = DB::select(
            "exec [dbo].[sp_materia_prima_maintenance] 1, 0, 1, 'migration', 8"
        )[0];

        echo "Nueva Alergia: {$test_materia->netsuit}" . PHP_EOL;

        $test_materia = DB::select(
            "exec [dbo].[sp_materia_prima_maintenance] 1, 0, 1, 'migration', 9"
        )[0];

        echo "Estado Alergia: {$test_materia->netsuit}" . PHP_EOL;

        $test_materia = DB::select(
            "exec [dbo].[sp_materia_prima_maintenance] 1, 0, 1, 'migration', 10"
        )[0];

        echo "Eliminar Alergia: {$test_materia->netsuit}" . PHP_EOL;
    }

    private function producto_receta()
    {
        $test = DB::select(
            "exec [dbo].[sp_producto_maintenance] 1, 0, 'migration', 3"
        )[0];

        echo "Actualizar Estado Producto: {$test->activo}" . PHP_EOL;

        $test = count(DB::select(
            "exec [dbo].[sp_producto_maintenance] 1, 0, 'migration', 5"
        ));

        echo "Producto: Se encontraron {$test}" . PHP_EOL;

        $test = count(DB::select(
            "exec [dbo].[sp_producto_maintenance] 0, 0, 'migration', 6"
        ));

        echo "Producto: Se encontraron {$test}" . PHP_EOL;

        $test = DB::select(
            "exec [dbo].[sp_producto_maintenance] 1, 0, 'migration', 7"
        )[0];

        echo "Producto: Se encontraron {$test->netsuit}" . PHP_EOL;

        $test = DB::select(
            "exec [dbo].[sp_producto_maintenance] 1, 0, 'migration', 9"
        )[0];

        echo "Estado Producto Materia: {$test->netsuit}" . PHP_EOL;

        $test = DB::select(
            "exec [dbo].[sp_producto_maintenance] 1, 0, 'migration', 10"
        )[0];

        echo "Eliminar Producto Materia: {$test->netsuit}" . PHP_EOL;
    }
}
