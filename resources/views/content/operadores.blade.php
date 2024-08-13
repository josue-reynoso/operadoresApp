@extends('adminlte::page')

@section('content')


<style>
    .no-sort::after { display: none!important; }

    .no-sort { pointer-events: none!important; cursor: default!important; }

    .justify-content-end {
        padding: 10px !important;
    }
</style>



{{-- Filtros  --}} {{-- route('getRowServidores') --}}
<form action="{{ route('encuentra-operadores') }}" method="post" id="buscarForm">
    <div class="row">
        <div class="col-12">
            <div class="card first-card">
                <div class="card-body">

                        <div class="row align-items-center" >
                            <div class="col-lg-9 col-md-9 col-sm-12">
                                <div class="row">

                                    <div class="col-lg-3 col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label for="nombre">{{__('messages.nombre')}}:</label>
                                            <input type="text" class="form-control " id="nombre" name="nombre" value="" placeholder="" maxlength="50" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label for="estatus">Estatus:</label>
                                            <select class="form-control @error('estatus') is-invalid @enderror" id="estatus" name="estatus">
                                                <option value="">{{ __('messages.todos') }}</option>
                                                <option value="1">{{ __('messages.activo') }}</option>
                                                <option value="0">{{ __('messages.inactivo') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12"></div>
                                </div>
                                <div class="row">

                                    <div class="col-lg-3 col-md-3 col-sm-12">
                                        <br/>
                                        <br/>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12"></div>
                                    <div class="col-lg-3 col-md-3 col-sm-12"></div>
                                    <div class="col-lg-3 col-md-3 col-sm-12"></div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <div class="row">
                                    <div class="col-12">
                                        {!! __('messages._bAlt') !!}
                                    </div>
                                    <div class="col-12">
                                        <a href="{{ route('nuevo-operador') }}"  class="btn btn-secondary" style="width: 144px ; height: 40px;">{!! __('messages._n') !!}</a>

                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</form>
<hr>



<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="resultsDataTable" actionsUrl="{{ route('detalle-operadores') }}" class="table">
                    <thead>
                        <tr>
                            <th class="dt-no-export no-sort" style="width: 0%;"></th>
                            <th style="width: 3%;">{{ __('messages.id') }}</th>
                            <th style="width: 20%;">{{ __('messages.nombre') }}</th>
                            <th style="width: 20%;">{{ __('messages.apellido') }}</th>
                            <th style="width: 20%;">{{ __('messages.fecha_nacimiento') }}</th>
                            <th style="width: 10%;">{{ __('messages.telefono') }}</th>
                            <th style="width: 15%;">{{ __('messages.direccion') }}</th>
                            <th style="width: 15%;">{{ __('messages.correo') }}</th>
                            <th style="width: 5%;">{{ __('messages.estado') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@include('panels/dataTableDetalles')

@stop

@section('js')
<script>
    window.onload = function() {

    };
</script>

@stop


