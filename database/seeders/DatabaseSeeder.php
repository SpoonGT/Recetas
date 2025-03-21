<?php

namespace Database\Seeders;

use App\Imports\ProductoImport;
use Illuminate\Database\Seeder;
use App\Imports\PuntoVentaImport;
use App\Imports\SubEnsambleImport;
use Illuminate\Support\Facades\DB;
use App\Imports\AsignarAliasImport;
use App\Imports\ExpedienteImport;
use App\Imports\MateriaPrimaImport;
use App\Imports\ValidarExpedienteImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Config;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (Config::get('database.default') == "mysql_estadistica") {
            Excel::import(new ExpedienteImport, 'database/seeders/Expedientes2025.xlsx');
            Excel::import(new ValidarExpedienteImport, 'database/seeders/ExpedienteResuelto.xlsx');
        } else {
            $this->generar_menu();
            $this->seguridad();
            if (Config::get('database.default') == "sqlsrv_recetas_nube" || Config::get('database.default') == "sqlsrv_recetas_desa" || Config::get('database.default') == "sqlsrv_recetas") {
                $this->catalogo_receta();
                $this->import_receta();
                $this->materia_prima_receta();
                //$this->producto_receta();
                echo "Migración de Recetas" . PHP_EOL;
            }

            if (Config::get('database.default') == "sqlsrv_conciliador_desa" || Config::get('database.default') == "sqlsrv_conciliador") {
                $this->catalogo_conciliacion();
                echo "Migración de Conciliación" . PHP_EOL;
            }
        }
    }

    private function generar_menu()
    {
        $rol = DB::select(
            "exec [dbo].[sp_rol_crud] 0, 'Administrador', 'Rol para administrar todas las pantallas del sistema.', 'migration', 2"
        )[0];

        echo "Rol Creado: {$rol->id} - {$rol->nombre}" . PHP_EOL;

        //1
        $menu = DB::select(
            "exec [dbo].[sp_menu_crud] 0, 'Inicio', '/Home', 'fa-solid fa-house-laptop', 0, 'migration', 2"
        )[0];

        echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

        $rol_menu = DB::select(
            "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
        )[0];

        echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

        //2
        $menu = DB::select(
            "exec [dbo].[sp_menu_crud] 0, 'Seguridad', '/Seguridad', 'fa-solid fa-gears', 0, 'migration', 2"
        )[0];

        echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

        $menu_id = $menu->id;

        $rol_menu = DB::select(
            "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
        )[0];

        echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

        //3
        $menu = DB::select(
            "exec [dbo].[sp_menu_crud] 0, 'Rol', '/Seguridad/Rol', 'fa-solid fa-dice-d6', {$menu_id}, 'migration', 2"
        )[0];

        echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

        $rol_menu = DB::select(
            "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
        )[0];

        echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

        //4
        $menu = DB::select(
            "exec [dbo].[sp_menu_crud] 0, 'Menu', '/Seguridad/Menu', 'fa-solid fa-list', {$menu_id}, 'migration', 2"
        )[0];

        echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

        $rol_menu = DB::select(
            "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
        )[0];

        echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

        //5
        $menu = DB::select(
            "exec [dbo].[sp_menu_crud] 0, 'Usuario', '/Seguridad/Usuario', 'fa-solid fa-user-gear', {$menu_id}, 'migration', 2"
        )[0];

        echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

        $rol_menu = DB::select(
            "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
        )[0];

        echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

        if (Config::get('database.default') == "sqlsrv_recetas_nube" || Config::get('database.default') == "sqlsrv_recetas_desa" || Config::get('database.default') == "sqlsrv_recetas") {
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Catálogo', '/Catalogo', 'fa-solid fa-gear', 0, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $menu_id = $menu->id;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Alergeno', '/Catalogo/Alergeno', 'fa-solid fa-shield-virus', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Área', '/Catalogo/Area', 'fa-solid fa-warehouse', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Categoría', '/Catalogo/Categoria', 'fa-solid fa-layer-group', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Marca', '/Catalogo/Marca', 'fa-solid fa-rug', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Unidad', '/Catalogo/Unidad', 'fa-brands fa-unity', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            // Menu transporte
            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Transporte', '/Catalogo/Transporte', 'fa-solid fa-bus', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            // Menu microbiologico
            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Microbiologico', '/Catalogo/Microbiologico', 'fa-solid fa-vial-virus', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            // Menu registroMS
            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Registro MS', '/Catalogo/RegistroMS', 'fa-solid fa-user-nurse', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Materia Prima', '/MateriaPrima', 'fa-solid fa-m', 0, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $menu_id = $menu->id;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Importar', '/MateriaPrima/Importar', 'fa-solid fa-file-import', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Netsuite', '/MateriaPrima/Netsuite', 'fa-solid fa-wheat-awn', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Recetas', '/Recetas', 'fa-solid fa-registered', 0, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $menu_id = $menu->id;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Nueva Receta', '/Recetas/NuevaReceta', 'fa-solid fa-file-circle-plus', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Administración Receta', '/Recetas/AdministracionReceta', 'fa-solid fa-box-archive', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Aprobación Receta', '/Recetas/RevisionReceta', 'fa-solid fa-clipboard-check', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Fichas Técnicas', '/FichasTecnicas', 'fa-solid fa-file-invoice', 0, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $menu_id = $menu->id;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Nueva Ficha Técnica', '/FichasTecnicas/NuevaFichaTecnica', 'fa-solid fa-file-circle-plus', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Administración Ficha Técnica', '/FichasTecnicas/AdministracionFichaTecnica', 'fa-solid fa-boxes-packing', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;
        }

        if (Config::get('database.default') == "sqlsrv_conciliador_desa" || Config::get('database.default') == "sqlsrv_conciliador") {

            //6
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Catálogo', '/Catalogo', 'fa-solid fa-layer-group', 0, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $menu_id = $menu->id;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //7
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Punto de Venta', '/Catalogo/PuntoVenta', 'fa-solid fa-map-location', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //8
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Plataforma', '/Catalogo/Plataforma', 'fa-solid fa-truck-ramp-box', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //9
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Regla de Negocio', '/Catalogo/ReglaNegocio', 'fa-solid fa-pen-ruler', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //10
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Caso', '/Catalogo/Caso', 'fa-solid fa-rectangle-list', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //========================= ICG
            //11
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Administrar ICG', '/AdministrarICG', 'fa-solid fa-circle-info', 0, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $menu_id = $menu->id;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //12
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Importar ICG', '/AdministrarICG/CsvIcgTemporal', 'fa-solid fa-upload', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //13
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Duplicado ICG', '/AdministrarICG/Duplicado', 'fa-solid fa-clone', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //14
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Data Depurada ICG', '/AdministrarICG/Data/Depurada', 'fa-solid fa-file-shield', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //15
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'NO ID ICG', '/AdministrarICG/No/Id', 'fa-solid fa-file-circle-minus', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //========================= Plataforma
            //16
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Administrar Plataforma', '/AdministrarPlataforma', 'fa-solid fa-circle-info', 0, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $menu_id = $menu->id;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //17
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Importar Plataforma', '/AdministrarPlataforma/CsvPlataformaTemporal', 'fa-solid fa-file-csv', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //18
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Data Depurada Plataforma', '/AdministrarPlataforma/Data/Depurada', 'fa-solid fa-file-shield', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //========================= Conciliación
            //19
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Conciliación', '/Conciliacion/ICG/Plataforma', 'fa-solid fa-hard-drive', 0, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $menu_id = $menu->id;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //20
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Conciliación Automática', '/Conciliacion/ICG/Plataforma/Automatica', 'fa-solid fa-person-chalkboard', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //21
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Conciliación Manual', '/Conciliacion/ICG/Plataforma/Manual', 'fa-solid fa-person-through-window', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //22
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Regla Aplicada', '/Conciliacion/Regla/Validacion/Aplicada', 'fa-solid fa-check-double', {$menu_id}, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //========================= Reporte
            //23
            $menu = DB::select(
                "exec [dbo].[sp_menu_crud] 0, 'Reportería', '/Reporte', 'fa-solid fa-boxes-packing', 0, 'migration', 2"
            )[0];

            echo "Menu Creado: {$menu->id} - {$menu->nombre}" . PHP_EOL;

            $menu_id = $menu->id;

            $rol_menu = DB::select(
                "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menu->id}, 'migration', 2"
            )[0];

            echo "Menu asignado al Rol: {$rol_menu->menu_id} - {$rol_menu->rol_id}" . PHP_EOL;

            //==================================================== MENU DISPONIBLE
            /*
                1	Inicio
                2	Seguridad
                3	Rol
                4	Menu
                5	Usuario
                6	Catálogo
                7	Punto de Venta
                8	Plataforma
                9	Regla de Negocio
                10	Caso
                11	Administrar ICG
                12	Importar ICG
                13	Duplicado ICG
                14	Data Depurada ICG
                15	NO ID ICG
                16	Administrar Plataforma
                17	Importar Plataforma
                18	Data Depurada Plataforma
                19	Conciliación
                20	Conciliación Automática
                21	Conciliación Manual
                22	Regla Aplicada
                23	Reportería
            */

            //ROL GESTION
            $rol = DB::select(
                "exec [dbo].[sp_rol_crud] 0, 'Gestor', 'Rol para administrar todas las pantallas y procesos de importación de ICG y plataformas.', 'migration', 2"
            )[0];

            $menus = array(1, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22);
            for ($i = 0; $i < count($menus); $i++) {
                DB::select(
                    "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menus[$i]}, 'migration', 2"
                )[0];
            }

            //ROL USUARIO
            $rol = DB::select(
                "exec [dbo].[sp_rol_crud] 0, 'Usuario', 'Rol para consultar información en reportería disponible en la plataforma.', 'migration', 2"
            )[0];

            $menus = array(1, 23);
            for ($i = 0; $i < count($menus); $i++) {
                DB::select(
                    "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menus[$i]}, 'migration', 2"
                )[0];
            }

            //ROL VALIDACION
            $rol = DB::select(
                "exec [dbo].[sp_rol_crud] 0, 'Validación', 'Rol para configurar el sistema para operar ICG y plataformas.', 'migration', 2"
            )[0];

            $menus = array(1, 6, 7, 8, 9, 10);
            for ($i = 0; $i < count($menus); $i++) {
                DB::select(
                    "exec [dbo].[sp_rol_menu_config] {$rol->id}, {$menus[$i]}, 'migration', 2"
                )[0];
            }
        }
    }

    private function seguridad()
    {
        $configuracion_menu = DB::select(
            "exec [dbo].[sp_rol_menu_config] 1, 0, 'migration', 6  "
        );

        echo "=========================== MENU CONFIGURADO ===========================" . PHP_EOL;

        var_dump($configuracion_menu);

        echo "======================================================================" . PHP_EOL;

        if (Config::get('database.default') == "sqlsrv_recetas_nube" || Config::get('database.default') == "sqlsrv_recetas_desa" || Config::get('database.default') == "sqlsrv_recetas") {
            $contrasenia = "lwYx8+0tB1bK3dAeL8oi8FZs4tBPnV/FsoWBm+hFE9A=";

            $usuario = DB::select(
                "exec [dbo].[sp_usuario_crud] 0, 'Usuario', 'usuario.desa', '{$contrasenia}', 'usuario@gmail.com', 1, 'migration', 2"
            )[0];

            $usuario = DB::select(
                "exec [dbo].[sp_usuario_crud] 0, 'Usuario', 'hcruz', '{$contrasenia}', 'hcruz@fascr.com', 1, 'migration', 2"
            )[0];
        } else {
            $contrasenia = "aD71mfjmsFmDBnAlc1Hu+fEfJqsZ7+Gp8aSgxVZAT40=";

            $usuario = DB::select(
                "exec [dbo].[sp_usuario_crud] 0, 'Usuario', 'usuario.desa', '{$contrasenia}', 'usuario@gmail.com', 1, 'migration', 2"
            )[0];
        }

        echo "Usuario Creado: {$usuario->id} - {$usuario->nombre_completo}" . PHP_EOL;
    }

    private function catalogo_receta()
    {
        DB::table('tbl_marca_comercial')->insert(
            ['nombre' => 'Spoon', 'abreviatura' => "S"]
        );

        DB::table('tbl_marca_comercial')->insert(
            ['nombre' => 'Fas', 'abreviatura' => "F"]
        );

        DB::table('tbl_estado')->insert(
            ['nombre' => 'CREADO', 'color' => "default"]
        );

        DB::table('tbl_estado')->insert(
            ['nombre' => 'ACTUALIZACIÓN', 'color' => "warning"]
        );

        DB::table('tbl_estado')->insert(
            ['nombre' => 'EN REVISIÓN', 'color' => "primary"]
        );

        DB::table('tbl_estado')->insert(
            ['nombre' => 'APROBADA', 'color' => "success"]
        );

        DB::table('tbl_estado')->insert(
            ['nombre' => 'RECHAZADA', 'color' => "danger"]
        );

        DB::table('tbl_estado')->insert(
            ['nombre' => 'SUSTITUIDO POR VERSIÓN', 'color' => "info"]
        );

        echo "=========================== CATEGORIA ===========================" . PHP_EOL;

        $json = '{"nombre": "Decoracion", "prefijos": "PTL"}';
        $categoria = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_categoria', '{$json}', 'migration', 2"
        )[0];

        echo "Categoría Creada: {$categoria->id} - {$categoria->nombre}" . PHP_EOL;

        $json = '{"nombre": "Decoración", "prefijos": "PTL, PTI, MP"}';
        $categoria = DB::select(
            "exec [dbo].[sp_table_maintenance] $categoria->id, 'tbl_categoria', '{$json}', 'migration', 3"
        )[0];

        echo "Categoría Actualizar: {$categoria->id} - {$categoria->nombre}" . PHP_EOL;

        echo "======================================================================" . PHP_EOL;

        echo "=========================== MARCA ===========================" . PHP_EOL;

        $json = '{"nombre": "No Configurada"}';
        $marca = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_marca', '{$json}', 'migration', 2"
        )[0];

        echo "Marca Creada: {$marca->id} - {$marca->nombre}" . PHP_EOL;

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

        $json = '{"abreviatura": "EMPAQUE", "nombre": "UNidad 1"}';
        $area = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_area', '{$json}', 'migration', 2"
        )[0];

        echo "Area Creada: {$area->id} - {$area->nombre} | {$area->abreviatura}" . PHP_EOL;

        $json = '{"abreviatura": "DE", "nombre": "Decoración"}';
        $area = DB::select(
            "exec [dbo].[sp_table_maintenance] $area->id, 'tbl_area', '{$json}', 'migration', 3"
        )[0];

        echo "Area Actualizar: {$area->id} - {$area->nombre} | {$area->abreviatura}" . PHP_EOL;

        echo "======================================================================" . PHP_EOL;

        echo "=========================== MICROBIOLOGICO ===========================" . PHP_EOL;

        $json = '{"nombre": "Faz"}';
        $microbiologico = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_microbiologico', '{$json}', 'migration', 2"
        )[0];

        echo "Microbiologico Creada: {$microbiologico->id} - {$microbiologico->nombre}" . PHP_EOL;

        $json = '{"nombre": "Salmonella"}';
        $microbiologico = DB::select(
            "exec [dbo].[sp_table_maintenance] $microbiologico->id, 'tbl_microbiologico', '{$json}', 'migration', 3"
        )[0];

        echo "Microbiologico Actualizar: {$microbiologico->id} - {$microbiologico->nombre}" . PHP_EOL;

        $json = '{"nombre": "Lysteria Monocytogenes"}';
        $microbiologico = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_microbiologico', '{$json}', 'migration', 2"
        )[0];

        echo "Microbiologico Creada: {$microbiologico->id} - {$microbiologico->nombre}" . PHP_EOL;

        $json = '{"nombre": "E. coli"}';
        $microbiologico = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_microbiologico', '{$json}', 'migration', 2"
        )[0];

        echo "Microbiologico Creada: {$microbiologico->id} - {$microbiologico->nombre}" . PHP_EOL;

        $json = '{"nombre": "Staphylococcus aureus"}';
        $microbiologico = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_microbiologico', '{$json}', 'migration', 2"
        )[0];

        echo "Microbiologico Creada: {$microbiologico->id} - {$microbiologico->nombre}" . PHP_EOL;

        echo "======================================================================" . PHP_EOL;

        echo "=========================== TRANSPORTE ===========================" . PHP_EOL;

        $json = '{"nombre": "Faz"}';
        $transporte = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_transporte', '{$json}', 'migration', 2"
        )[0];

        echo "Transporte Creada: {$transporte->id} - {$transporte->nombre}" . PHP_EOL;

        $json = '{"nombre": "Refrigeración 0°C / 5°C"}';
        $transporte = DB::select(
            "exec [dbo].[sp_table_maintenance] $transporte->id, 'tbl_transporte', '{$json}', 'migration', 3"
        )[0];

        echo "Transporte Actualizar: {$transporte->id} - {$transporte->nombre}" . PHP_EOL;

        $json = '{"nombre": "Congelación -12°/-18°C"}';
        $transporte = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_transporte', '{$json}', 'migration', 2"
        )[0];

        echo "Transporte Creada: {$transporte->id} - {$transporte->nombre}" . PHP_EOL;

        $json = '{"nombre": "Ambiente"}';
        $transporte = DB::select(
            "exec [dbo].[sp_table_maintenance] 0, 'tbl_transporte', '{$json}', 'migration', 2"
        )[0];

        echo "Transporte Creada: {$transporte->id} - {$transporte->nombre}" . PHP_EOL;

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

    private function catalogo_conciliacion()
    {
        Excel::import(new PuntoVentaImport, 'database/seeders/PuntoVenta.xlsx');

        $plataforma = DB::select(
            "exec [dbo].[sp_plataforma_crud] 0, 'DIDI', 'DIDI Pasajero', 0, 1, 'migracion', 2"
        )[0];

        $plataforma = DB::select(
            "exec [dbo].[sp_plataforma_crud] $plataforma->id, 'DIDI', 'DIDI Pasajero', 0, 1, 'migracion', 3"
        )[0];

        echo "Plataforma Creado: {$plataforma->id} - {$plataforma->plataforma}" . PHP_EOL;

        $plataforma = DB::select(
            "exec [dbo].[sp_plataforma_crud] 0, 'PEYA', 'Pedidos Ya', 0, 0, 'migracion', 2"
        )[0];

        $plataforma = DB::select(
            "exec [dbo].[sp_plataforma_crud] $plataforma->id, 'PEYA', 'Pedidos Ya', 0, 0, 'migracion', 3"
        )[0];

        echo "Plataforma Creado: {$plataforma->id} - {$plataforma->plataforma}" . PHP_EOL;

        $plataforma = DB::select(
            "exec [dbo].[sp_plataforma_crud] 0, 'JUSTO', 'Justo Drivers', 0, 1, 'migracion', 2"
        )[0];

        $plataforma = DB::select(
            "exec [dbo].[sp_plataforma_crud] $plataforma->id, 'JUSTO', 'Justo Drivers', 0, 1, 'migracion', 3"
        )[0];

        echo "Plataforma Creado: {$plataforma->id} - {$plataforma->plataforma}" . PHP_EOL;

        $plataforma = DB::select(
            "exec [dbo].[sp_plataforma_crud] 0, 'UBER', 'Uber Eats', 1, 1, 'migracion', 2"
        )[0];

        $plataforma = DB::select(
            "exec [dbo].[sp_plataforma_crud] $plataforma->id, 'UBER', 'Uber Eats', 1, 1, 'migracion', 3"
        )[0];

        echo "Plataforma Creado: {$plataforma->id} - {$plataforma->plataforma}" . PHP_EOL;

        //Excel::import(new AsignarAliasImport, 'database/seeders/AsignarAlias.xlsx');

        DB::select(
            "exec [dbo].[sp_configuracion_import] 0, 'id_pedido', 'Número de pedido', 2, 1, 'migracion', 2"
        )[0];

        DB::select(
            "exec [dbo].[sp_configuracion_import] 0, 'local', 'Nombre de la tienda', 0, 1, 'migracion', 2"
        )[0];

        DB::select(
            "exec [dbo].[sp_configuracion_import] 0, 'fecha', 'Fecha del pedido/reembolso', 3, 1, 'migracion', 2"
        )[0];

        DB::select(
            "exec [dbo].[sp_configuracion_import] 0, 'total', 'Venta de comida (impuestos incluidos)', 7, 1, 'migracion', 2"
        )[0];

        DB::select(
            "exec [dbo].[sp_configuracion_import] 0, 'estado', 'Estado del pedido', 6, 1, 'migracion', 2"
        )[0];

        //UBER

        DB::select(
            "exec [dbo].[sp_configuracion_import] 0, 'id_pedido', 'ID del pedido', 2, 4, 'migracion', 2"
        )[0];

        DB::select(
            "exec [dbo].[sp_configuracion_import] 0, 'local', 'Nombre de la tienda', 0, 4, 'migracion', 2"
        )[0];

        DB::select(
            "exec [dbo].[sp_configuracion_import] 0, 'fecha', 'Fecha del pedido', 4, 4, 'migracion', 2"
        )[0];

        DB::select(
            "exec [dbo].[sp_configuracion_import] 0, 'total', 'Total de las ventas después de los ajustes (impuestos incluidos)', 21, 4, 'migracion', 2"
        )[0];

        DB::select(
            "exec [dbo].[sp_configuracion_import] 0, 'estado', 'Estado del pedido', 8, 4, 'migracion', 2"
        )[0];

        //PEYA

        DB::select(
            "exec [dbo].[sp_configuracion_import] 0, 'id_pedido', 'ID', 0, 3, 'migracion', 2"
        )[0];

        DB::select(
            "exec [dbo].[sp_configuracion_import] 0, 'local', 'Local', 5, 3, 'migracion', 2"
        )[0];

        DB::select(
            "exec [dbo].[sp_configuracion_import] 0, 'fecha', 'Fecha del pedido', 16, 3, 'migracion', 2"
        )[0];

        DB::select(
            "exec [dbo].[sp_configuracion_import] 0, 'total', 'Total con propina', 28, 3, 'migracion', 2"
        )[0];

        DB::select(
            "exec [dbo].[sp_configuracion_import] 0, 'estado', 'Estado', 13, 3, 'migracion', 2"
        )[0];

        //CASOS
        DB::select(
            "exec [dbo].[sp_caso_crud] 0, 'Cancelación paga', 'migracion', 2"
        );

        DB::select(
            "exec [dbo].[sp_caso_crud] 0, 'Cancelación no paga', 'migracion', 2"
        );

        DB::select(
            "exec [dbo].[sp_caso_crud] 0, 'Fecha Incorrecta en la Facturación ICG', 'migracion', 2"
        );

        DB::select(
            "exec [dbo].[sp_caso_crud] 0, 'Refacturacion por cambio de producto/genera diferencia/promocion', 'migracion', 2"
        );

        DB::select(
            "exec [dbo].[sp_caso_crud] 0, 'Código reflejado en otra fecha', 'migracion', 2"
        );

        DB::select(
            "exec [dbo].[sp_caso_crud] 0, 'No esta en el reporte de ICG', 'migracion', 2"
        );

        DB::select(
            "exec [dbo].[sp_caso_crud] 0, 'No esta en el reporte de Otter', 'migracion', 2"
        );

        DB::select(
            "exec [dbo].[sp_caso_crud] 0, 'No esta en el reporte de Uber', 'migracion', 2"
        );

        DB::select(
            "exec [dbo].[sp_caso_crud] 0, 'Fallo otter', 'migracion', 2"
        );

        DB::select(
            "exec [dbo].[sp_caso_crud] 0, 'Error humano', 'migracion', 2"
        );

        DB::select(
            "exec [dbo].[sp_caso_crud] 0, 'Forma de pago incorrecta', 'migracion', 2"
        );

        DB::select(
            "exec [dbo].[sp_caso_crud] 0, 'Anulada en ICG pero activa en UBER', 'migracion', 2"
        );

        DB::select(
            "exec [dbo].[sp_caso_crud] 0, 'Mismo código monto diferente ', 'migracion', 2"
        );

        $query = "SELECT T0.id AS plataforma_id, T0.plataforma AS plataforma_abreviatura, T0.id_pedido AS plataforma_identificador, T0.punto_venta AS plataforma_punto_venta, T0.fecha AS plataforma_fecha, T0.total AS plataforma_total, T0.estado AS plataforma_estado, T1.id AS icg_id, T1.plataforma AS icg_abreviatura, T1.id_pedido AS icg_identificador, T1.punto_venta AS icg_punto_venta, T1.fecha_pedido AS icg_fecha, T1.total_bruto AS icg_total_bruto, T1.total_neto AS icg_total_neto, T1.serie_compuesta AS icg_serie, T1.numero_documento AS icg_documento, T1.numero_orden AS icg_orden FROM [tbl_csv_plataforma] AS T0 WITH(NOLOCK), [tbl_csv_icg] AS T1 WITH(NOLOCK) WHERE T0.[plataforma_id] = 4 AND T1.[plataforma_id] = 4 AND T0.[informacion] = ''REGISTRADO'' AND T0.[procesado] = 0 AND T1.[procesado] = 0 AND T1.[id_pedido] = T0.[id_pedido] AND T0.[plataforma_estado_id] = 2 AND (T1.[total_neto] > ''0'' OR T1.[total_neto] < ''0'') ORDER BY T0.[id_pedido] DESC";
        DB::select(
            "exec [dbo].[sp_regla_validacion_maintenance] 0, 1, 'test', '{$query}', 4, 4, 'migracion', 2"
        );

        $query = "SELECT T0.id AS plataforma_id, T0.plataforma AS plataforma_abreviatura, T0.id_pedido AS plataforma_identificador, T0.punto_venta AS plataforma_punto_venta, T0.fecha AS plataforma_fecha, T0.total AS plataforma_total, T0.estado AS plataforma_estado, T1.id AS icg_id, T1.plataforma AS icg_abreviatura, T1.id_pedido AS icg_identificador, T1.punto_venta AS icg_punto_venta, T1.fecha_pedido AS icg_fecha, T1.total_bruto AS icg_total_bruto, T1.total_neto AS icg_total_neto, T1.serie_compuesta AS icg_serie, T1.numero_documento AS icg_documento, T1.numero_orden AS icg_orden FROM [tbl_csv_plataforma] AS T0 WITH(NOLOCK), [tbl_csv_icg] AS T1 WITH(NOLOCK) WHERE T0.[plataforma_id] = 4 AND T1.[plataforma_id] = 4 AND T0.[informacion] = ''REGISTRADO'' AND T0.[procesado] = 0 AND T1.[procesado] = 0 AND T1.[id_pedido] = T0.[id_pedido] AND T0.[plataforma_estado_id] = 1 AND ''0'' > T1.[total_neto] AND (T0.[total] * ''-1'') = T1.[total_neto] ORDER BY T0.[id_pedido] DESC";
        DB::select(
            "exec [dbo].[sp_regla_validacion_maintenance] 0, 10, 'test', '{$query}', 4, 2, 'migracion', 2"
        );

        $query = "SELECT T0.id AS plataforma_id, T0.plataforma AS plataforma_abreviatura, T0.id_pedido AS plataforma_identificador, T0.punto_venta AS plataforma_punto_venta, T0.fecha AS plataforma_fecha, T0.total AS plataforma_total, T0.estado AS plataforma_estado, T1.id AS icg_id, T1.plataforma AS icg_abreviatura, T1.id_pedido AS icg_identificador, T1.punto_venta AS icg_punto_venta, T1.fecha_pedido AS icg_fecha, T1.total_bruto AS icg_total_bruto, T1.total_neto AS icg_total_neto, T1.serie_compuesta AS icg_serie, T1.numero_documento AS icg_documento, T1.numero_orden AS icg_orden FROM [tbl_csv_plataforma] AS T0 WITH(NOLOCK), [tbl_csv_icg] AS T1 WITH(NOLOCK) WHERE T0.[plataforma_id] = 4 AND T1.[plataforma_id] = 4 AND T0.[informacion] = ''REGISTRADO'' AND T0.[procesado] = 0 AND T1.[procesado] = 0 AND T1.[id_pedido] = T0.[id_pedido] AND T1.[total_neto] = T0.[total] AND T1.[fecha_pedido] != T0.[fecha] AND T0.[plataforma_estado_id] = 1";
        DB::select(
            "exec [dbo].[sp_regla_validacion_maintenance] 0, 20, 'test', '{$query}', 4, 3, 'migracion', 2"
        );
    }
}
