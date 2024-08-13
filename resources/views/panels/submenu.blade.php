{{-- For submenu --}}
<ul class="menu-content">
  {{-- @dd($menu) --}}
  @if(isset($menu))
  @foreach($menu as $submenu)
  <li class="{{ isset($submenu["slug"]) && $submenu["slug"] === Route::currentRouteName() ? 'active' : '' }}">
      <a href="{{isset($submenu[1]) ? url($submenu[1]):'javascript:void(0)'}}" target="{{isset($submenu->newTab) && $submenu->newTab === true  ? '_blank':'_self'}}" ><!--class="d-flex align-items-center" style="background: #CCCCCC 0% 0% no-repeat padding-box;">-->
        @if(isset($submenu[2]))
      {{-- <i  data-feather="{{$submenu->icon}}"></i> --}}
        <i  class="{{$submenu[2]}}"></i>
        @endif 
        <span class="menu-item" style="font-weight: bold;">{{ Helper::caps( __($submenu[0]) ) }}</span>
      </a>
      @if (isset($submenu->submenu))
      @include('panels/submenu', ['menu' => $submenu->submenu])
      @endif
  </li>
  @endforeach
  @endif
</ul>
