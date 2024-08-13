<?php

namespace App\Http\Controllers;

use App\Mail\DemoEmail;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Models\Auditoria;
use App\Models\Notificacion;
use App\Models\Ciudad;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $menu; // Array: Menu for current logged user

    public $results;

    public $unreadNotifications;

    protected function __construct()
    {
        // middleware functions
        $this->middleware(function ($request, $next) {
            $this->results = new \stdClass();
            $this->results->messages = [];

            //$this->unreadNotifications = $this->getUnreadNotifications();

            View::share('menu', $this->menu);
            View::share('results', $this->results);
            //View::share('unreadNotifications', $this->unreadNotifications);

            return $next($request);
        });
    }

    protected function getMenu()
    {
        return $this->menu;
    }

    protected function setMenu($menu)
    {
        $this->menu = $menu;
        session(['menu' => $menu]);
        //\Session::put('menu', $this->menu);
    }

    protected function checkAuth($request)
    {
        if (!auth()->check() || empty(session()->get('menu'))) {
            $this->kick($request);
        }
    }

    protected function kick($request)
    {
        Auth::logout();

        //\Session::flush();
        session()->flush();
        $request->session()->invalidate();
        $request->session()->flush();
        $request->session()->regenerate();

        //$tokenRepository = app(TokenRepository::class);
        //$tokenRepository->revokeAccessToken($request->session()->token());

        return redirect()->route('logout')->withErrors([__('messages.acceso_denegado')]);
    }

    protected function getPermissions($request)
    {
        // Get menu
        $menu = session()->get('menu');

        // Set default permissions
        $permissions = array('alta' => 0, 'baja' => 0, 'consulta' => 0, 'modificacion' => 0);

        // Check menu
        if (!$menu) {
            $this->kick($request);
            return $permissions;
        } else if (empty($menu)) {
            $this->kick($request);
            return $permissions;
        }

        // Get modules
        foreach ($menu as $m) {
            // Get functions
            foreach ($m[array_keys($m)[0]]['submenu'] as $s) {
                // Check permission by path
                if ($request->route()->getName() == $s[1]) {
                    // Set permissions
                    $permissions['alta'] = $s['permisos'][0];
                    $permissions['baja'] = $s['permisos'][1];
                    $permissions['consulta'] = $s['permisos'][2];
                    $permissions['modificacion'] = $s['permisos'][3];
                    break;
                }
            }
        }

        return $permissions;
    }

    /**
     * Funcion que se llamara en cada proceso que se modifique o se genere nuevos registros en la BD
     * @param string $actionType Accion que se realizo [M = Modificacion, A = Alta]
     * @param string|array|null $valueAnt Valor anterior del registro que se modifico, puede ser null si es una alta, en caso de ser un array, la longitud debe ser igual a la cantidad de campos de la tabla
     * @param string|array $valueNew Valor nuevo del registro que se modifico, puede ser un array para establecer los parametros que se estan modificando
     * @param string $modAct Modulo donde se realizo la accion
     * @param int $id_user ID del usuario que realizo la accion
     * @return array: Regresa el estado en el que se procesa el registro de la acción que se efectuo en la BD, [status] puede tener valores [false|true] que indica si se realizo o no, [message] indica en texto que sucedio en el proceso.
     * @author Jorge Luis 2021-11-19
     */
    protected function new_action_system_audit(
        $valueAnt = null,
        $valueNew = [],
        string $modAct = '',
        $id = 0,
        $table = '',
        $action = null,
        $guard = ''
    ) {
        //Log::debug('auditoria');
        //Log::debug($valueNew);
        //Log::debug($valueAnt);
        //Log::debug($id);
        /**
         * Se valida si el nombre de la tabla es diferente a null o vacio, en caso de ser verdadero se retorna un error
         */
        if ($table == '' || $table == null) {
            return [
                "status" => false,
                "message" => "No es posible registrar el registro de la auditoria, ya que no se reconoce el nombre de la tabla"
            ];
        }

        $actionType = $action;
        if (is_array($valueNew)) {
            $success = 0;
            $fecha = date('Y-m-d H:i:s');
            $id_user = auth($guard)->user()->id;

            foreach ($valueNew as $key => $value) {
                $valAnt = $valueAnt[$key] ?? null;
                $valNew = $value;
                $auditoria = new Auditoria();

                //Se asignan los valores al objeto de auditoria
                $auditoria->AD_TabNom = $table;
                $auditoria->AD_IdReg = $id;
                $auditoria->AD_TabFchHor = $fecha;
                $auditoria->AD_TipOpe = $actionType;
                $auditoria->AD_ValAnt = $actionType == 'M' ? $valAnt : null;
                $auditoria->AD_ValAct = $value;
                $auditoria->US_ID     = $id_user;
                $auditoria->AD_ModAct = $modAct;

                $auditoria->save() ? $success++ : null;
            }

            if ($success >= count($valueNew)) {
                return [
                    "status" => true,
                    "message" => "Se registro la auditoria correctamente"
                ];
            } else {
                if ($success == 0)
                    return [
                        "status" => false,
                        "message" => "Ocurrio un error al generar las auditorias"
                    ];
                else
                    return [
                        "status" => false,
                        "message" => "Se registraron " . $success . " de " . (count($valueNew) - 1) . " auditorias"
                    ];
            }
        }
    }

    public function sendCustomEmail($to, $view, $plain, $args, $attachments, $subject)
    {
        $objDemo = new \stdClass();

        //html vars
        //$host = array_key_exists('host',$overrides)? $overrides['host'] : 'http://127.0.0.1:8000';
        //email vars
        $objDemo->sender = 'no-reply@gpsya.com';
        $objDemo->view = $view;
        $objDemo->view_plain = $plain;
        $objDemo->vars = $args;
        $objDemo->attachments = $attachments;
        if ($subject != null) {
            $objDemo->subject = $subject;
        } else {
            $objDemo->subject = '';
        }

        Mail::to($to)->send(new DemoEmail($objDemo));
    }

    /**
     * Funcion para mostrar las notificaciones no leídas del systema acorde a la variable env APP_SYSTEM_TYPE
     * @return Total de notificaciones no leidas
     * @author Alejandro Amaro Flores 2022-03-03
     */
    /*protected function getUnreadNotifications() {
        $appSystemType = config('app.system_type');
        return Notificacion::where('NO_Lei', false)->where('NO_DesNom',$appSystemType)->count();
    }*/

    /**
     * Funcion para auditar los cambios en una tabla
     * @param oldModel: Cambios anteriores en el modelo
     * @param model: Cambios actuales en el modelo
     * @param module: Modulo en donde se hicieron los cambios
     * @param isDelete: Es una baja si o no (default: no)
     * @author Alejandro Amaro Flores 2022-03-16
     */
    protected function auditar($oldModel, $model, $module, $isDelete = false, $transferencia = false) {
        // Changes
        $changes = array();
        $originalValues = array();

        // Get fillable from model
        foreach ($model->getFillable() as $key => $value) {
            if( $model->wasChanged($value) ) {
                $changes[$value] = $model->getAttributeValue($value);
            }
        }
        // Check old value
        if($oldModel) {
            foreach ($changes as $key => $value) {
                $originalValues[$key] = $oldModel->getAttributeValue($key);
            }
        }
        // Format old values
        $oldValues = '';
        foreach ($originalValues as $key => $value) {
            $oldValues .= '<strong>'.$key.': </strong> '.$value.'<br />';
        }
        // Format new values
        $newValues = '';
        foreach ($changes as $key => $value) {
            $newValues .= '<strong>'.$key.': </strong> '.$value.'<br />';
        }
        // Format creation values
        $creationValues = '';
        foreach ($model->attributesToArray() as $key => $value) {
            $creationValues .= '<strong>'.$key.': </strong> '.$value.'<br />';
        }

        try {
            // Save auditoria
            $auditoria = new Auditoria();
            $auditoria->US_ID = $transferencia ? 0 : (Auth::user() ? Auth::user()->id : 0);
            $auditoria->AD_IdReg = $model->getKey();
            $auditoria->AD_ModAct = $module;
            $auditoria->AD_TabNom = $model->getTable();
            $auditoria->AD_TipOpe = $isDelete ? 'B' : ( count($originalValues) > 0 ? 'M' : 'A' );
            $auditoria->AD_TabFchHor = Carbon::now()->toDateTimeString();
            $auditoria->AD_ValAnt = ($isDelete && $oldModel == $model)? (count($changes) > 0 ? $newValues : $creationValues) : (count($originalValues) > 0 ? $oldValues : null);
            $auditoria->AD_ValAct = ($isDelete && $oldModel == $model)? (count($originalValues) > 0 ? $oldValues : null): (count($changes) > 0 ? $newValues : $creationValues);
            $auditoria->save();
        } catch (\Exception $th) {
            Log::info('ERROR AUDITORIA:::: '.$th->getMessage());
        }
    }

}
