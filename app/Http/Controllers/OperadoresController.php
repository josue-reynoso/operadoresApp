<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;

use App\Models\Modulo;
use App\Models\Funcion;
use App\Models\Operadores;
use App\Models\Usuarios;

class OperadoresController extends controller{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }


    public function operadores(Request $request){

        return view('content.operadores');

    }

    public function getRowOperadores(Request $request){
        Log::info($request);
        $array_where = [];
        if($request->input('nombre')){
            array_push($array_where, ['OP_Name', 'like', "%".$request->input('nombre')."%"]);
        }
        if($request->input('estatus') != null){
            //array_push($array_where, ['estado', '=', $request->input('estado')== 1 ? 1 : 2]);
            array_push($array_where, ['OP_Status', '=', $request->input('estatus')]);
        }
        $data = DB::table('operadores')
        ->where($array_where)
        ->select('*')->get();
        // Prepare dataSet for dataTable

        $dataSet = array();
        foreach ($data as $d) {
            /*<th style="width: 5%;">{{ __('messages.id') }}</th>
                            <th style="width: 20%;">{{ __('messages.nombre') }}</th>
                            <th style="width: 20%;">{{ __('messages.apellido') }}</th>
                            <th style="width: 20%;">{{ __('messages.fecha_nacimiento') }}</th>
                            <th style="width: 20%;">{{ __('messages.telefono') }}</th>
                            <th style="width: 15%;">{{ __('messages.direccion') }}</th>
                            <th style="width: 15%;">{{ __('messages.correo') }}</th>
                            <th style="width: 5%;">{{ __('messages.estado') }}</th> */
            $ds = array(
                '',
                $d->OP_ID,
                $d->OP_Name,
                $d->OP_APE.' '.$d->OP_APEM,
                $d->OP_Fch_Nac,
                $d->OP_Cel,
                $d->OP_Address,
                $d->OP_Email,
                $d->OP_Status == 1 ? __('messages.activo'):__('messages.inactivo'),
                $d->OP_ID,
            );

            $dataSet[] = $ds;
        }

        $this->results->dataSet = json_encode($dataSet);

        return [
            "recordsTotal" => count($data),
            "recordsFiltered" => count($dataSet),
            "data" => $dataSet
        ];

    }

    public function actions(Request $request, $action, $id){

        Log::info("entro a edicion");
        Log::info($action);

        $this->results->action = $action;
        if($action == 'detail') {
            $this->results->titulo = __('messages.editar_servidor');
            $this->results->objPorEditar = null;

        }
        if($action == 'delete'){

            $bl = Operadores::where('OP_ID', $id)->first();
            $bl->delete();

            return redirect()->to('operadores');
        }

        $this->results->objPorEditar = DB::table('operadores')
            ->where('OP_ID', $id)->select('*')->first();

        return view("content.nuevo-operador");

    }

    public function new(Request $request){
        $this->results->titulo = __('messages.nuevo_operador');
        $this->results->objPorEditar = new Operadores();
        return view("content.nuevo-operador");

    }

    public function saveOperador(Request $request){

        DB::beginTransaction();
        try{

            $id = $request->input('OP_ID');
            $obj = $id ? Operadores::where('OP_ID', $id)->firstOrFail() : new Operadores();

            $obj->fill($request->all());
            $obj->save();
            DB::commit();
            return redirect()->to('operadores');



        }catch(Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => __('messages.error_save_operador')]);
        }

    }

}
