@php
$configData = Helper::applClasses();
@endphp
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true" style="background: #ffffff 0% 0% no-repeat padding-box; box-shadow: 0px 0px 10px #00000042; border-radius: 0px 20px 20px 0px; opacity: 1; padding-top: 65px; padding-bottom: 0.1%;">
  
  <div class="main-menu-content" style="background-color: #ffffff;overflow:auto;">
    @if( session()->get('menu') !== null )
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" style="background-color: #ffffff;">

      @foreach ( session()->get('menu') as $menu )

        @if(isset($menu->navheader))
        <li class="nav-item {{ $custom_classes }}" style="margin-top: 5px; margin-inline-end: 0px;">
          <span class="menu-title">{{ $menu[array_keys($menu)[0]][0] }}</span>
          <i data-feather="more-horizontal"></i>
        </li>
        @else
        {{-- Add Custom Class with nav-item --}}
        @php
        $custom_classes = "";
        if(isset($menu->classlist)) {
          $custom_classes = $menu->classlist;
        }

        @endphp

          {{-- 10 Es el modulo de notificaciones --}}
          @if( key($menu) != '10' )
            <li class="nav-item {{ $custom_classes }}" style="margin-top: 5px;">
              <a href="{{isset($menu->url)? url($menu->url):'javascript:void(0)'}}"  target="{{isset($menu->newTab) ? '_blank':'_self'}}"><!--class="d-flex align-items-center"-->
                <i class="{{ $menu[array_keys($menu)[0]][1] }}"></i>
                <span class="menu-title" style="font-weight: bold;">{{ __($menu[array_keys($menu)[0]][0]) }}</span>
              </a>
              @if(isset($menu[array_keys($menu)[0]]["submenu"]))
              @include('panels/submenu', ['menu' => $menu[array_keys($menu)[0]]["submenu"]])
              @endif
            </li>
          @endif

        @endif

      @endforeach
      {{-- Foreach menu item ends --}}

    @endif

    {{-- User SubMenu --}}
    <li class="nav-item {{ $custom_classes }}" style="margin-top: 5px; display:none">
      <a href="javascript:void(0)" ><!--class="d-flex align-items-center">-->
        <i class="fas fa-user"></i>
        @php
        $uid = Auth::user()->id;
        $name1 = \App\Models\Usuario::where(['US_ID' => $uid])->pluck('US_NOM')->first();
        $name2 = \App\Models\Usuario::where(['US_ID' => $uid])->pluck('US_APE')->first();
        $fullname = strtoupper($name1) . ' ' . strtoupper($name2);
        @endphp
        <span class="menu-title" style="font-weight: bold;">{{ $fullname }}</span>
      </a>
      <ul class="menu-content">
        <li class="{{ isset($submenu["slug"]) && $submenu["slug"] === Route::currentRouteName() ? 'active' : '' }}">
          <a href="{{ route("logout") }}"><!-- class="d-flex align-items-center" style="background: #CCCCCC 0% 0% no-repeat padding-box;">-->
            <span class="menu-item" style="font-weight: bold;">Salir</span>
          </a>
        </li>
      </ul>
    </li>
    {{-- End User SubMenu --}}

  </div>
</div>
<!-- END: Main Menu-->
