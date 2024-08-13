<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class PermissionHelper
{

    /**
     * Funcion para checar las credenciales, metodo de acceso y los permisos del usuario
     * @return True/False si tiene o no el permiso
     * @author Alejandro Amaro Flores 2021-06-07
     */
    public static function initialChecks($request, $module, $permission)
    {
        $menu = $request->session()->get('menu');
        if (empty($menu)) {
            return false;
        }
        $modulo = null;
        foreach ($menu as $key => $value) {
            foreach ($menu[$key] as $m) {
                foreach($m['submenu'] as $sm) {
                    if($sm[1]==$module){
                        $modulo=$sm['permisos'];
                        break;
                    }
                }
            } 
        }
        if($modulo==null) {
            return false;
        }
        switch($permission) {
            case 'alta': {
                if($modulo[0]==1) {
                    return true;
                }
            }
            case 'baja': {
                if($modulo[1]==1) {
                    return true;
                }
            }
            case 'consulta': {
                if($modulo[2]==1) {
                    return true;
                }
            }
            case 'modificacion': {
                if($modulo[3]==1) {
                    return true;
                }
            }
        }
        return false;
    }
    
}
