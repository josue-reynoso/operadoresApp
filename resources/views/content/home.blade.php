@extends('adminlte::page')
@section('title', 'Registro Operadores')

@section('content_header')
    <h1> Bienvenido </h1>
@stop

@section('content')

<p>Registo de operadores rutas CRT.</p>
<br>
<img src="{{ asset( 'images/logo/logo.png' ) }}" alt="logo" class="brand-logo" style="display: block; margin-left: auto; margin-right: auto;" />

@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
