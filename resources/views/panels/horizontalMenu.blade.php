@php
$configData = Helper::applClasses();
@endphp
{{-- Horizontal Menu --}}
<div class="horizontal-menu-wrapper">
  <div class="header-navbar navbar-expand-sm navbar navbar-horizontal {{$configData['horizontalMenuClass']}} {{($configData['theme'] === 'dark') ? 'navbar-dark' : 'navbar-light' }} navbar-shadow menu-border" role="navigation" data-menu="menu-wrapper" data-menu-type="floating-nav">
    <div class="navbar-header">
      <ul class="nav navbar-nav flex-row">
        <li class="nav-item mr-auto">
          <a class="navbar-brand" href="{{url('/')}}">
            <img src="{{asset('images/logo/logo.jpg')}}" alt="png" height="50"/>
          </a>
        </li>
        <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i></a></li>
      </ul>
    </div>
    <div class="shadow-bottom"></div>
    <!-- Horizontal menu content-->
    <div class="navbar-container main-menu-content" data-menu="menu-container">
      <ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation">
      {{-- Foreach menu item starts --}}
        @if(isset($menuData[1]))
        @foreach($menuData[1]->menu as $menu)
        @php
        $custom_classes = "";
        if(isset($menu->classlist)) {
        $custom_classes = $menu->classlist;
        }
        @endphp
        <li class="nav-item @if(isset($menu->submenu)){{'dropdown'}}@endif {{ $custom_classes }} {{ Route::currentRouteName() === $menu->slug ? 'active' : '' }}"
         @if(isset($menu->submenu)){{'data-menu=dropdown'}}@endif>
          <a href="{{isset($menu->url)? url($menu->url):'javascript:void(0)'}}" class="nav-link d-flex align-items-center @if(isset($menu->submenu)){{'dropdown-toggle'}}@endif" target="{{isset($menu->newTab) ? '_blank':'_self'}}"  @if(isset($menu->submenu)){{'data-toggle=dropdown'}}@endif>
            <i data-feather="{{ $menu->icon }}"></i>
            <span>{{ __('locale.'.$menu->name) }}</span>
          </a>
          @if(isset($menu->submenu))
          @include('panels/horizontalSubmenu', ['menu' => $menu->submenu])
          @endif
        </li>
        @endforeach
        @endif
        {{-- Foreach menu item ends --}}
      </ul>
    </div>
  </div>
</div>
