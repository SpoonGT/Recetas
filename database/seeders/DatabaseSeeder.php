<?php

namespace Database\Seeders;

use App\Imports\MateriaPrimaImport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $rol = DB::select(
            "exec [dbo].[sp_rol_crud] 0, 'Administrador', 'Rol para administrar todas las pantallas del sistema.', 'migration', 2"
        )[0];

        echo "Rol Creado: {$rol->id} - {$rol->nombre}" . PHP_EOL;

        $menu = DB::select(
            "exec [dbo].[sp_menu_crud] 0, 'Inicio', '/', 'fa-solid fa-house-laptop', 0, 'migration', 2"
        )[0];

        echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

        $rol_menu = DB::select(
            "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
        )[0];

        echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

        $menu = DB::select(
            "exec [dbo].[sp_menu_crud] 0, 'Inicio 2', '/ini', 'fa-solid fa-house-laptop', {$menu->id}, 'migration', 2"
        )[0];

        echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

        $rol_menu = DB::select(
            "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
        )[0];

        echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

        $configuracion_menu = DB::select(
            "exec [dbo].[sp_rol_menu_config] {$rol->id}, 0, 'migration', 6  "
        );

        echo "=========================== MENU CONFIGURADO ===========================" . PHP_EOL;

        var_dump($configuracion_menu);

        echo "======================================================================" . PHP_EOL;

        $usuario = DB::select(
            "exec [dbo].[sp_usuario_crud] 0, 'David Desarrollador', 'david.desa', 'david@gmail.com', 1, {$rol->id}, 'migration', 2"
        )[0];

        echo "Usuario Creado: {$usuario->id} - {$usuario->nombre_completo}" . PHP_EOL;

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

        $json = '{"nomenclatura": "gg", "nombre": "Gramoz"}';
        $unidad = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_unidad', '{$json}', 'migration', 2"
        )[0];

        echo "Unidad Creada: {$unidad->id} - {$unidad->nombre} | {$unidad->nomenclatura}" . PHP_EOL;

        $json = '{"nomenclatura": "g", "nombre": "Gramos"}';
        $unidad = DB::select(
            "exec [dbo].[sp_table_maintenance] $unidad->id, 'tbl_unidad', '{$json}', 'migration', 3"
        )[0];

        echo "Unidad Actualizar: {$unidad->id} - {$unidad->nombre} | {$unidad->nomenclatura}" . PHP_EOL;

        echo "======================================================================" . PHP_EOL;

        echo "=========================== ALERGENO ===========================" . PHP_EOL;

        $json = '{"nombre": "Triggo"}';
        $alergeno = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_alergeno', '{$json}', 'migration', 2"
        )[0];

        $json = '{"nombre": "Dos"}';
        $alergeno = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_alergeno', '{$json}', 'migration', 2"
        )[0];

        $json = '{"nombre": "Tres"}';
        $alergeno = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_alergeno', '{$json}', 'migration', 2"
        )[0];

        echo "Alergeno Creada: {$alergeno->id} - {$alergeno->nombre}" . PHP_EOL;

        $json = '{"nombre": "Trigo"}';
        $alergeno = DB::select(
            "exec [dbo].[sp_table_maintenance] $alergeno->id, 'tbl_alergeno', '{$json}', 'migration', 3"
        )[0];

        echo "Alergeno Actualizar: {$alergeno->id} - {$alergeno->nombre}" . PHP_EOL;

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

        Excel::import(new MateriaPrimaImport, 'database/seeders/Netsuite.xlsx');
    }
}
