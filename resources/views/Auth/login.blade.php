@extends('layouts/fullLayoutMaster')

@section('title', env('APP_NAME').' - '. 'Inicio de sesión')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-auth.css')) }}">
@endsection

@section('content')
<div class="auth-wrapper auth-v1 px-2">
  <div class="auth-inner py-2">
    <!-- Login v1 -->
    <div class="card mb-0 loginCard">
      <div class="card-title"></div>

      <div class="card-body">
        <img src="{{ asset( 'images/logo/logo.png' ) }}" alt="logo" class="brand-logo" style="display: block; margin-left: auto; margin-right: auto; width: 75%;" />
        <div class="card text-center" style="border: 0px;">
          <div class="card-body">
            <h2><strong>{{ __('messages.inicio_sesion') }}</strong></h2>
          </div>
        </div>

        @if($errors->any())
          @if ( !$errors->has('email') && !$errors->has('password') )
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="alert-body">
              @foreach ($errors->all() as $error)
              {{ $error }}<br />
              @endforeach
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          @endif
        @endif

        <form class="auth-login-form mt-2" method="POST" action="{{ route('login') }}">
          @csrf
          <div class="form-group">
            {{-- <label for="login-email" class="form-label">Correo electrónico</label> --}}
            <input type="text" class="form-control @error('email') is-invalid @enderror loginInputs" id="login-email" name="email" placeholder="Correo electrónico" aria-describedby="login-email" tabindex="1" autofocus value="{{ old('email') }}" maxlength="254" />
            @error('email')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="form-group">
            <div class="d-flex justify-content-between">
            </div>
            <div class="form-group">
              <input type="password" class="form-control @error('password') is-invalid @enderror loginInputs" id="login-password" name="password" tabindex="2" placeholder="Contraseña" aria-describedby="login-password" maxlength="255"/>
              @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>
          @if(env('REMEMBER_ACCOUNT')=='true')
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input" type="checkbox" id="remember_me" name="remember_me" tabindex="3" {{ old('remember_me') ? 'checked' : '' }} />
              <label class="custom-control-label" for="remember_me"> Recordar mi cuenta </label>
            </div>
          </div>
          @endif
          <button type="submit" class="btn btn-primary btn-block loginBtn" tabindex="4"><strong>Ingresar</strong></button>
        </form>

        <!--
        <p class="text-center mt-2">
        @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}" style="text-decoration: underline; color: #111111;">
            <small>Olvide mi contraseña</small>
          </a>
        @endif
        </p>-->

      </div>
    </div>
    <!-- /Login v1 -->
  </div>
</div>
@endsection
