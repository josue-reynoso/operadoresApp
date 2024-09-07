<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;
use Exception;

use App\Models\Modulo;
use App\Models\Funcion;
use App\Models\Operadores;
use App\Models\Usuarios;
USE App\Models\Rutas;
USE App\Models\Comentarios;

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
            $ds = array(
                '',
                $d->OP_ID,
                $d->OP_Name.' '.$d->OP_APE.' '.$d->OP_APEM,
                $d->OP_Cel,
                $d->OP_Address,
                $d->OP_Status == 1 ? __('messages.activo'):__('messages.inactivo'),
                "<a id='btn_detalles' a href='detalle-operadores/detail/".$d->OP_ID."' class='btn btn-primary'>".__('messages.detalles')."</a>",
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
            $this->results->titulo = __('messages.editar_operador');
            $this->results->objPorEditar = null;

        }
        if($action == 'delete'){

            $bl = Operadores::where('OP_ID', $id)->first();
            $bl->delete();

            return redirect()->to('operadores');
        }

        $this->results->objPorEditar = DB::table('operadores')
            ->where('OP_ID', $id)->select('*')->first();
        $this->results->rutas = DB::table('rutas')->select('*')->get();
        
        $data = DB::table('comentarios')
        ->where('COM_OP_ID', $id)
        ->select('*')->orderBy('COM_ID', 'DESC')->get(); 

        /*$dataSet = array();
        foreach ($data as $d) {
            $ds = array(
                '',
                $d->COM_DES,
                $d->COM_DATE,
                $d->COM_ID,
            );

            $dataSet[] = $ds;
        }*/
        $this->results->comentarios = $data;


        return view("content.nuevo-operador");

    }

    public function new(Request $request){
        $this->results->titulo = __('messages.nuevo_operador');
        $this->results->objPorEditar = new Operadores();
        $this->results->rutas =  DB::table('rutas')->select('*')->get();
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

            if($request->COM_DES !=""){
                $com = new Comentarios();
                $com->COM_OP_ID=$obj->OP_ID;
                $com->COM_DES=$request->COM_DES;
                $com->COM_DATE= Carbon::now()->toDateTimeString();
            
                $com->save();
                DB::commit();
            }
            
            

            return redirect()->to('operadores');



        }catch(Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => __('messages.error_save_operador')]);
        }

    }

}
