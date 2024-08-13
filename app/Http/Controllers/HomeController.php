<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Models\Modulo;
use App\Models\Funcion;
use App\Models\Usuarios;

class HomeController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Funcion inicial para generar el menu y verificar que el usuario sea activo
     * Ruta: inicio
     * @return View: home
     * @author Alejandro Amaro Flores 2021-06-07
     */
    public function inicio(Request $request)
    {
        Log::info("inicio");
        $pageConfigs = ['showMenu' => true];

        Log::info(auth()->check());
        Log::info("****");
        // Get user
        if (auth()->check()) {
            $user = auth()->user();
            //Log::info(json_encode($user));
            // Check active
            return view('content.home');
        } else {
            // Inactive user, then revoke token and flush session
            $this->kick($request);
        }
    }

    /**
     * Funcion privada que crea el menu y lo agrega a la sesion
     * Ruta: inicio
     * @author Alejandro Amaro Flores 2021-06-07
     */
    /**
     * Funcion privada que crea el menu y lo agrega a la sesion
     * Ruta: inicio
     * @author Alejandro Amaro Flores 2021-06-07
     */
    private function handle($user)
    {
        // Initialize menu
        $modulos = array();
        $funciones = array();
        $this->results->modulos = array();
        $this->results->funciones = array();

        // Initialize jsonModules
        $jsonModules = "{}";
        //Log::info(json_encode($user));
        // Check modules
        if ($user->modulos_usuario) {
            $jsonModules = $user->modulos_usuario;
        }
        $modules = json_decode($jsonModules);

        // Set user modules
        $modulosUsuario = json_decode($jsonModules, true);

        // Check functions
        if ($modules->modulos) {
            foreach ($modules->modulos as $m) {
                $funcion = Funcion::find($m->funcion);

                $modulo = Modulo::find($funcion->id_modulo);

                if (!in_array($funcion->id, $funciones)) {
                    $funciones[] = $funcion->id;
                    $this->results->funciones[] = $funcion;
                }
                if (!in_array($modulo->id, $modulos)) {
                    $modulos[] = $modulo->id;
                    $this->results->modulos[] = $modulo;
                }
            }
        }
        $bread_menu = array();
        // Create menu
        $this->results->menu = array();
        if (count($modulos) > 0 && count($funciones) > 0) {
            for ($i = 0; $i < count($this->results->modulos); $i++) {
                $m = $this->results->modulos[$i];
                $menu = [$m->id => [mb_strtoupper(__('messages.'.$m->modulo)), $m->icono, 'submenu' => array()]];
                array_push($bread_menu, $m->modulo);
                foreach ($this->results->funciones as $f) {
                    if ($f->id_modulo == $m->id) {
                        //Log::debug($modulosUsuario);
                        //se agrega clase de submenu para mostrar icono
                        $menu[$m->id]['submenu'][$f->id] = [__('messages.'.$f->funcion), $f->ruta_funcion, $f->icono, 'permisos' => [$f->alta, $f->baja, $f->consulta, $f->modificacion]];
                    }
                }
                array_push($this->results->menu, $menu);
            }
            //Log::debug($this->results->menu);
            //$this->results->menu = $menu;
        }

        // Check user permisions
        $newMenu = array();
        if ($this->results->menu !== null) {
            $index = 0;
            foreach ($this->results->menu as $menu) {
                foreach ($menu[array_keys($menu)[0]]['submenu'] as $id => &$submenu) {
                    // Replace default permissions
                    $submenu['permisos'] = $modulosUsuario['modulos'][$index]['permisos'];
                    $index++;
                }
                array_push($newMenu, $menu);
            }
        }
        if (count($newMenu) > 0)     $this->results->menu = $newMenu;
        //session(['menu' => json_encode($this->results->menu)]);
        //session()->put('menu' , $this->results->menu);
        //Log::debug('sesion');

        $this->setMenu($menu);
        $this->setMenu($this->results->menu);
    }

}
