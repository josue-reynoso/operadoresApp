@extends('adminlte::page')

@section('content')

@php $obj = $results->objPorEditar; @endphp
@php $rutas = $results->rutas; @endphp
@php 
$comen;
if($obj->OP_ID>0){
    $comen= $results->comentarios;
}  
@endphp



<style>
    .btn-primary.disabled, .btn-primary:disabled {
        color: #fff;
        background-color: gray;
        border-color: black;
    }
</style>

<!--Nuevo Registro-->
@if($obj !== null)
<form action="{{ route('save-operador') }}" id="newObj" method="post" enctype="multipart/form-data">
    @csrf
    @if($obj !== null)
    <input type="hidden" value="{{ $obj->OP_ID }}" id="OP_ID" name="OP_ID" />
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card first-card">
            @if(null !== session()->get('errors'))
                @php
                $msg = '';
                @endphp
                @foreach ($errors->all() as $error)
                @php
                if(!str_contains($error,'campo')){
                $msg .= str_replace("'","",$error).' ';
                }

                @endphp
                @endforeach
                @if($msg!='')
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="alert-body">{{$msg}} &nbsp;</div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"> &times;</span></button>
                </div>
                @endif
                @php session()->forget('errors'); @endphp
                @endif
                <div class="card-header">
                    <h4 class="card-title" style="font-weight: bold">{{$results->titulo}}</h4>
                    <div style="text-align: right !important">

                        <a href="{{ route('operadores') }}" class="btn btn-outline-secondary">{!! __('messages._r') !!}</a>
                        <button class="dt-button create-new btn btn-primary" type="submit" id="btn-save">{!! __('messages._g') !!}</button>

                    </div>
                </div>
                <hr/>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="OP_Name">{!!__('messages.nombre')!!}:</label>
                                <input type="text" class="form-control @error('OP_Name') is-invalid @enderror" autocomplete="false" id="OP_Name" name="OP_Name" value="{{ old('OP_Name') ?: ($obj!==null? $obj->OP_Name : '') }}" placeholder="" maxlength="55"  required/>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{!!__('messages.campo_requerido')!!}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="OP_APE">{!!__('messages.apellidop')!!}:</label>
                                <input type="text" class="form-control @error('OP_APE') is-invalid @enderror" autocomplete="false" id="OP_APE" name="OP_APE" value="{{ old('OP_APE') ?: ($obj!==null? $obj->OP_APE : '') }}" placeholder="" maxlength="55" required/>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{!!__('messages.campo_requerido_o_formato_invalido')!!}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="OP_APEM">{!!__('messages.apellidom')!!}:</label>
                                <input type="text" class="form-control @error('OP_APEM') is-invalid @enderror" autocomplete="false" id="OP_APEM" name="OP_APEM" value="{{ old('OP_APEM') ?: ($obj!==null? $obj->OP_APEM : '') }}" placeholder="" maxlength="55" required/>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{!!__('messages.campo_requerido_o_formato_invalido')!!}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="OP_Fch_Nac">{!!__('messages.fecha_nacimiento')!!}:</label>
                                <input type="date" class="form-control @error('OP_Fch_Nac') is-invalid @enderror" id="OP_Fch_Nac" name="OP_Fch_Nac" @if($obj->OP_Fch_Nac) value="{{ date_format(date_create($obj->OP_Fch_Nac),'Y-m-d') }}" @else value="{{ old('OP_Fch_Nac') }}" @endif required onblur="validateRequired(this)" />
                                <span class="invalid-feedback" role="alert">
                                    <strong>{!!__('messages.campo_requerido')!!}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="OP_Address">{!!__('messages.direccion')!!}:</label>
                                <input type="text" class="form-control @error('OP_Address') is-invalid @enderror" autocomplete="false" id="OP_Address" name="OP_Address" value="{{ old('OP_Address') ?: ($obj!==null? $obj->OP_Address : '') }}" placeholder="" maxlength="55" required/>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{!!__('messages.campo_requerido_o_formato_invalido')!!}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="OP_Cel">{!!__('messages.telefono')!!}:</label>
                                <input type="text" class="form-control @error('OP_Cel') is-invalid @enderror" autocomplete="false" id="OP_Cel" name="OP_Cel" value="{{ old('OP_Cel') ?: ($obj!==null? $obj->OP_Cel : '') }}" placeholder="" maxlength="55" required/>
                                <span class="invalid-feedback" role="alert">
                                    <strong>{!!__('messages.campo_requerido_o_formato_invalido')!!}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="OP_Email">{!!__('messages.correo')!!}:</label>
                                <input type="text" class="form-control @error('OP_Email') is-invalid @enderror" autocomplete="false" id="OP_Email" name="OP_Email" value="{{ old('OP_Email') ?: ($obj!==null? $obj->OP_Email : '') }}" placeholder="" maxlength="55" />
                                <span class="invalid-feedback" role="alert">
                                    <strong>{!!__('messages.campo_requerido_o_formato_invalido')!!}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="OP_ID_R">{!!__('messages.ruta')!!}:</label>
                                <select class="form-control @error('OP_ID_R') is-invalid @enderror"  id="OP_ID_R" name="OP_ID_R" value="{{ old('OP_ID_R') ?: ($obj!==null? $obj->OP_ID_R : '') }}" required onChange="validateForm();">
                                    <option value="">{!!__('messages.seleccione_opcion')!!}</option>
                                    @foreach ($rutas as $r)
                                        <option value="{{$r->R_ID}}" {{ $obj->OP_ID_R == $r->R_ID ? 'selected' : '' }}>{{$r->R_Color}} {{$r->R_Numero}}{{$r->R_Letra}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="OP_Status">{!!__('messages.estado')!!}:</label>
                                <select class="form-control @error('OP_Status') is-invalid @enderror" id="OP_Status" name="OP_Status">
                                    <option value="1" {{ $obj->OP_Status == '1' ? 'selected' : '' }}>{!!__('messages.activo')!!}</option>
                                    <option value="0" {{ $obj->OP_Status == '0' ? 'selected' : '' }}>{!!__('messages.inactivo')!!}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="COM_DES">{!!__('messages.comentario_nuevo')!!}:</label>
                                <textarea cols="4" type="text" class="form-control @error('COM_DES') is-invalid @enderror" 
                                autocomplete="false" id="COM_DES" name="COM_DES" value="" placeholder="" maxlength="255"></textarea>
                                
                            </div>
                        </div>

                        <div class="col-sm-3">
                           
                        </div>
                        <div class="col-sm-3">
                           
                        </div>
                        <div class="col-sm-3">
                            <span style="text-align: left !important; color:red; font-weight: bold; font-size: 10px; position: absolute;">En caso de no tener un dato poner un *</span>
                    
                        </div>


                        

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endif

@if($obj->OP_ID > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="resultsDataTable"  class="table">
                    <thead>
                        <tr>
                            <th class="dt-no-export no-sort" style="width: 0%;"></th>
                            <th style="width: 70%;">{{ __('messages.comentarios') }}</th>
                            <th style="width:30%">{{__('messages.fecha_emision')}} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comen as $c)
                        <tr>
                            <th></th>
                            <th style="font-weight:normal">{{$c->COM_DES}}</th>
                            <th style="font-weight:normal">{{date('d-m-Y H:i:s', strtotime($c->created_at))}}</th>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif



@stop

@section('js')
<script>
    window.onload = function() {

    validateForm();
    $('input:text').each(function() {
        $(this).keyup(function() {
            validateForm()
        })
    });




};

function validateForm() {
    let form = document.getElementById("newObj")
    console.log('validando submit')

    form.classList.add('was-validated');
    if (form.checkValidity() === false) {
        $('#btn-save').prop('disabled', true);
        $('#btn-save').css('pointer-events', 'none');
        return;
    } else {
        $('#btn-save').prop('disabled', false);
        $('#btn-save').css('pointer-events', 'auto');
    }

}

function validateRequired(e) {
    //console.log(e.id);
    //console.log($('#' + e.id)[0].value);
    if ($('#' + e.id)[0].value == '') {
      e.classList.add("is-invalid");
    } else {
      e.classList.remove("is-invalid");
    }
  }
</script>

@stop


