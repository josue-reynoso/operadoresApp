<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class Helper
{

    public static function applClasses()
    {
        // default data array
        $DefaultData = [
          'mainLayoutType' => 'vertical',
          'theme' => 'light',
          'sidebarCollapsed' => false,
          'navbarColor' => '',
          'horizontalMenuType' => 'floating',
          'horizontalMenuType' => '',
          'verticalMenuNavbarType' => 'floating',
          'verticalMenuNavbarType' => '',
          'footerType' => 'static', //footer
          'layoutWidth' => 'full',
          'showMenu' => true,
          'showMenu' => false,
          'bodyClass' => '',
          'bodyStyle' => '',
          'pageClass' => '',
          'pageHeader' => true,
          'contentLayout' => 'default',
          'blankPage' => false,
          'defaultLanguage'=>'es',
          'direction' => env('MIX_CONTENT_DIRECTION', 'ltr'),
        ];

        // if any key missing of array from custom.php file it will be merge and set a default value from dataDefault array and store in data variable
        $data = array_merge($DefaultData, config('custom.custom'));

        // All options available in the template
        $allOptions = [
            'mainLayoutType' => array('vertical', 'horizontal'),
            'theme' => array('light' => 'light', 'dark' => 'dark-layout', 'bordered' => 'bordered-layout', 'semi-dark' => 'semi-dark-layout'),
            'sidebarCollapsed' => array(true, false),
            'showMenu' => array(true, false),
            'layoutWidth' => array('full', 'boxed'),
            'navbarColor' => array('bg-primary', 'bg-info', 'bg-warning', 'bg-success', 'bg-danger', 'bg-dark'),
            'horizontalMenuType' => array('floating' => 'navbar-floating', 'static' => 'navbar-static', 'sticky' => 'navbar-sticky'),
            'horizontalMenuClass' => array('static' => '', 'sticky' => 'fixed-top', 'floating' => 'floating-nav'),
            'verticalMenuNavbarType' => array('floating' => 'navbar-floating', 'static' => 'navbar-static', 'sticky' => 'navbar-sticky', 'hidden' => 'navbar-hidden'),
            'navbarClass' => array('floating' => 'floating-nav', 'static' => 'navbar-static-top', 'sticky' => 'fixed-top', 'hidden' => 'd-none'),
            'footerType' => array('static' => 'footer-static', 'sticky' => 'footer-fixed', 'hidden' => 'footer-hidden'),
            'pageHeader' => array(true, false),
            'contentLayout' => array('default', 'content-left-sidebar', 'content-right-sidebar', 'content-detached-left-sidebar', 'content-detached-right-sidebar'),
            'blankPage' => array(false, true),
            'sidebarPositionClass' => array('content-left-sidebar' => 'sidebar-left', 'content-right-sidebar' => 'sidebar-right', 'content-detached-left-sidebar' => 'sidebar-detached sidebar-left', 'content-detached-right-sidebar' => 'sidebar-detached sidebar-right', 'default' => 'default-sidebar-position'),
            'contentsidebarClass' => array('content-left-sidebar' => 'content-right', 'content-right-sidebar' => 'content-left', 'content-detached-left-sidebar' => 'content-detached content-right', 'content-detached-right-sidebar' => 'content-detached content-left', 'default' => 'default-sidebar'),
            'defaultLanguage'=>array('en'=>'en','fr'=>'fr','de'=>'de','pt'=>'pt','es'=>'es'),
            'direction' => array('ltr', 'rtl'),
        ];

        //if mainLayoutType value empty or not match with default options in custom.php config file then set a default value
        foreach ($allOptions as $key => $value) {
            if (array_key_exists($key, $DefaultData)) {
                if (gettype($DefaultData[$key]) === gettype($data[$key])) {
                    // data key should be string
                    if (is_string($data[$key])) {
                        // data key should not be empty
                        if (isset($data[$key]) && $data[$key] !== null) {
                            // data key should not be exist inside allOptions array's sub array
                            if (!array_key_exists($data[$key], $value)) {
                                // ensure that passed value should be match with any of allOptions array value
                                $result = array_search($data[$key], $value, 'strict');
                                if (empty($result) && $result !== 0) {
                                    $data[$key] = $DefaultData[$key];
                                }
                            }
                        } else {
                            // if data key not set or
                            $data[$key] = $DefaultData[$key];
                        }
                    }
                } else {
                    $data[$key] = $DefaultData[$key];
                }
            }
        }

        //layout classes
        $layoutClasses = [
            'theme' => $data['theme'],
            'layoutTheme' => $allOptions['theme'][$data['theme']],
            'sidebarCollapsed' => $data['sidebarCollapsed'],
            'showMenu' => $data['showMenu'],
            'layoutWidth' => $data['layoutWidth'],
            'verticalMenuNavbarType' => $allOptions['verticalMenuNavbarType'][$data['verticalMenuNavbarType']],
            'navbarClass' => $allOptions['navbarClass'][$data['verticalMenuNavbarType']],
            'navbarColor' => $data['navbarColor'],
            'horizontalMenuType' => $allOptions['horizontalMenuType'][$data['horizontalMenuType']],
            'horizontalMenuClass' => $allOptions['horizontalMenuClass'][$data['horizontalMenuType']],
            'footerType' => $allOptions['footerType'][$data['footerType']],
            'sidebarClass' => 'menu-expanded',
            'bodyClass' => $data['bodyClass'],
            'bodyStyle' => $data['bodyStyle'],
            'pageClass' => $data['pageClass'],
            'pageHeader' => $data['pageHeader'],
            'blankPage' => $data['blankPage'],
            'blankPageClass' => '',
            'contentLayout' => $data['contentLayout'],
            'sidebarPositionClass' => $allOptions['sidebarPositionClass'][$data['contentLayout']],
            'contentsidebarClass' => $allOptions['contentsidebarClass'][$data['contentLayout']],
            'mainLayoutType' => $data['mainLayoutType'],
            'defaultLanguage'=>$allOptions['defaultLanguage'][$data['defaultLanguage']],
            'direction' => $data['direction'],
        ];
        // set default language if session hasn't locale value the set default language
        if(!session()->has('locale')){
            app()->setLocale($layoutClasses['defaultLanguage']);
        }

        // sidebar Collapsed
        if ($layoutClasses['sidebarCollapsed'] == 'true') {
            $layoutClasses['sidebarClass'] = "menu-collapsed";
        }

        // blank page class
        if ($layoutClasses['blankPage'] == 'true') {
            $layoutClasses['blankPageClass'] = "blank-page";
        }

        return $layoutClasses;
    }

    public static function updatePageConfig($pageConfigs)
    {
        $demo = 'custom';
        if (isset($pageConfigs)) {
            if (count($pageConfigs) > 0) {
                foreach ($pageConfigs as $config => $val) {
                    Config::set('custom.' . $demo . '.' . $config, $val);
                }
            }
        }
    }

    public static function caps($cadena) {
        return ucfirst( mb_strtolower($cadena) );
    }

    public static function getBreadcrumbsJSON($idFuncion, $ExtraCrumb = '') {
        foreach (session('menu') as $arreglo) {
            foreach ($arreglo as $menu) {          
                //echo $menu[0];                
                foreach ($menu['submenu'] as $key => $submenu) {                    
                    if($key == $idFuncion){
                        if($ExtraCrumb === ''){
                            return [
                                'pageConfigs' => ['showMenu' => true],
                                'breadcrumbs' => [[
                                    'name' => __('messages.inicio'),
                                    'link' => '/inicio'
                                ], [
                                    'name' => self::caps($menu[0]),
                                    'link' => '#'
                                ], [
                                    'name' => $submenu[0],
                                    'link' => $submenu[1]
                                ]]
                            ];
                        }else{
                            return [
                                'pageConfigs' => ['showMenu' => true],
                                'breadcrumbs' => [[
                                    'name' => __('messages.inicio'),
                                    'link' => '/inicio'
                                ], [
                                    'name' => self::caps($menu[0]),
                                    'link' => '#'
                                ], [
                                    'name' => $submenu[0],
                                    'link' => $submenu[1]
                                ], [
                                    'name' => $ExtraCrumb,
                                    'link' => $submenu[1]
                                ]]
                            ];
                        }
                
                    }
                }
            }
        }
    }

    public static function replaceWildCards(
        $frase, 
        $param1 =null, 
        $param2 =null, 
        $param3 =null, 
        $param4 =null, 
        $param5 =null, 
        $param6 =null, 
        $param7 =null, 
        $param8 =null, 
        $param9 =null, 
        $param10 =null
    ) {
        if($param1!=null){
            $frase = str_replace('{1}',$param1, $frase);
        }
        if($param2!=null){
            $frase = str_replace('{2}',$param2, $frase);
        }
        if($param3!=null){
            $frase = str_replace('{3}',$param3, $frase);
        }
        if($param4!=null){
            $frase = str_replace('{4}',$param4, $frase);
        }
        if($param5!=null){
            $frase = str_replace('{5}',$param5, $frase);
        }
        if($param6!=null){
            $frase = str_replace('{6}',$param6, $frase);
        }
        if($param7!=null){
            $frase = str_replace('{7}',$param7, $frase);
        }
        if($param8!=null){
            $frase = str_replace('{8}',$param8, $frase);
        }
        if($param9!=null){
            $frase = str_replace('{9}',$param9, $frase);
        }
        if($param10!=null){
            $frase = str_replace('{10}',$param10, $frase);
        }
        return $frase;
    }

    public static function mysqlDate($fecha) {
        // Not exists
        if(!$fecha) {
            return $fecha;
        }
        // Empty date
        if(Str::of($fecha)->trim()->isEmpty()) {
            return $fecha;
        }
        // Format fecha
        $newDate = $fecha;
        if (Str::contains($newDate, [' '])) {
            $newDate = explode(' ', $newDate)[0];
        }
        if (Str::contains($newDate, ['/'])) {
            $parts = explode('/', $newDate);
            $newDate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
        } else {
            $newDate = date('Y-m-d', strtotime($newDate));
        }
        return $newDate;
    }

    public static function mysqlDateTime($fecha) {
        // Not exists
        if(!$fecha) {
            return $fecha;
        }
        // Empty date
        if(Str::of($fecha)->trim()->isEmpty()) {
            return $fecha;
        }
        
        return date('Y-m-d H:i:s', strtotime($fecha));
    }

    public static function slashDate($fecha) {
        // Not exists
        if(!$fecha) {
            return $fecha;
        }
        // Empty date
        if(Str::of($fecha)->trim()->isEmpty()) {
            return $fecha;
        }
        // Format fecha
        $newDate = $fecha;
        if (Str::contains($newDate, [' '])) {
            $newDate = explode(' ', $newDate)[0];
        }
        if (Str::contains($newDate, ['-'])) {
            $parts = explode('-', $newDate);
            $newDate = $parts[2] . '/' . $parts[1] . '/' . $parts[0];
        } else {
            $newDate = date('d/m/Y,', strtotime($newDate));
        }
        return $newDate;
    }

    public static function slashDateTime($fecha) {
        // Not exists
        if(!$fecha) {
            return $fecha;
        }
        // Empty date
        if(Str::of($fecha)->trim()->isEmpty()) {
            return $fecha;
        }

        return date('d/m/Y H:i:s', strtotime($fecha));
    }

    public static function tiempo_transcurrido($date1, $date2){
        $interval = date_diff($date1, $date2);

        $min=$interval->format('%i');
        $sec=$interval->format('%s');
        $hour=$interval->format('%h');
        $mon=$interval->format('%m');
        $day=$interval->format('%d');
        $year=$interval->format('%y');
        if($interval->format('%i%h%d%m%y')=="00000") {
            //echo $interval->format('%i%h%d%m%y')."<br>";
            return $sec." ".__('messages.segundos');
        } else if($interval->format('%h%d%m%y')=="0000"){
            return $min." ".__('messages.minutos');
        } else if($interval->format('%d%m%y')=="000"){
            return $hour." ".__('messages.horas');
        } else if($interval->format('%m%y')=="00"){
            return $day." ".__('messages.dias');
        } else if($interval->format('%y')=="0"){
            return $mon." ".__('messages.meses');
        } else{
            return $year." ".__('messages.años');
        }    

    }

    public static function parseMonth($month){
        $resultado ="";
        switch ($month) {
            case '1':
                $resultado =__('messages.enero');
                break;
            case '2':
                $resultado =__('messages.febrero');
                break;
            case '3':
                $resultado =__('messages.marzo');
                break;            
            case '4':
                $resultado =__('messages.abril');
                break;
            case '5':
                $resultado =__('messages.mayo');
                break;
            case '6':
                $resultado =__('messages.junio');
                break;
            case '7':
                $resultado =__('messages.julio');
                break;
            case '8':
                $resultado =__('messages.agosto');
                break;
            case '9':
                $resultado =__('messages.septiembre');
                break;
            case '10':
                $resultado =__('messages.octubre');
                break;
            case '11':
                $resultado =__('messages.noviembre');
                break;
            case '12':
                $resultado =__('messages.diciembre');
                break;
            
            default:
                $resultado =="";
                break;
        }

        return $resultado;
    }

    public static function parseTipoFr($val){
        $result='';
        switch($val){
            
            case 1:
                $result= __('messages.con_rut');
                break;
            case 2:
                $result= __('messages.con_rut_lit_e');
                break;
            case 3:
                $result= __('messages.sin_rut');
                break;
            default:

            break;
        }

        return $result;

    }

    public static function parsePagTip($val){
        $result="";

        switch($val){
            
            case 'V':
                $result= __('messages.vendedores');
                break;
            case 'F':
                $result= __('messages.franquicias');
                break;
            case 'S':
                $result= __('messages.sueldos');
                break;
            case 'O':
                $result= __('messages.otros');
                break;
            default:
                
            break;
            
        }
        return $result;

    }
    public static function parsePagLinDoc($val){
        $result="";

        switch($val){
            
            case 1:
                $result= __('messages.ci_pasaporte');
                break;
            case 3:
                $result= __('messages.otro');
                break;
            case 9:
                $result= __('messages.rut');
                break;
            default:
                
            break;
            
        }
        return $result;

    }

    public static function parsePagLinEst($val){
        $result='';
        switch($val){
            
            case 'P':
                $result= __('messages.pago');
                break;
            case 'A':
                $result= __('messages.anulado');
                break;
            case 'G':
                $result= __('messages.generado');
                break;
            case 'N':
                $result= __('messages.notificado');
                break;
            default:

            break;
        }

        return $result;

    }

    public static function parsePagEst($val){
        $result='';
        switch($val){
            
            case 'A':
                $result= __('messages.anulado');
                break;
            case 'G':
                $result= __('messages.generado');
                break;
            case 'T':
                $result= __('messages.total');
                break;
            case 'P':
                $result= __('messages.parcial');
                break;
            default:

            break;
        }

        return $result;

    }
    
    

}

// End of file