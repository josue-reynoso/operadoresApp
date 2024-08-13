@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

<!DOCTYPE html>
{{-- {!! Helper::applClasses() !!} --}}
@php $configData = Helper::applClasses(); @endphp

<html lang="@if(session()->has('locale')){{session()->get('locale')}}@else{{ $configData['defaultLanguage'] }}@endif" data-textdirection="{{ env('MIX_CONTENT_DIRECTION') === 'rtl' ? 'rtl' : 'ltr' }}" class="{{ ($configData['theme'] === 'light') ? '' : $configData['layoutTheme'] }}">
<head>
  <meta charset=" utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>@yield('title')</title>
  @if(config("app.system_type") == "CM")
  <link rel="icon" type="image/png" href="{{asset('images/logo/favico.png')}}">
  @elseif(config("app.system_type") == "MB")
  <link rel="icon" type="image/png" href="{{asset('images/logo/favicoMB.png')}}">
  @endif

  {{-- Include core + vendor Styles --}}
  @include('panels/styles')
</head>

@isset($configData["mainLayoutType"])
@extends((( $configData["mainLayoutType"] === 'horizontal') ? 'layouts.horizontalDetachedLayoutMaster' :
'layouts.verticalDetachedLayoutMaster' ))
@endisset
