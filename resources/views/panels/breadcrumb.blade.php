<div class="content-header row col-12">
  
  @if( session()->get('menu') !== null )        
  @foreach ( session()->get('menu') as $menu )

    <div class="content-header-left mb-0">
      <div class="form-group">
        <div class="dropdown">
          <button class="btn-icon btn btn-primary btn-round btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="{{ $menu[array_keys($menu)[0]][1] }}"></i>
              <span class="d-md-block d-none"><br />{{ $menu[array_keys($menu)[0]][0] }}</span>
          </button>
          <div class="dropdown-menu dropdown-menu-center">
          @foreach ($menu[array_keys($menu)[0]]['submenu'] as $id => $submenu)
            <a class="dropdown-item" href="{{ route($submenu[1]) }}">
              <i class="mr-1" data-feather="arrow-right"></i>
                <span class="align-middle">{{ $submenu[0] }}</span>
            </a>
          @endforeach
          </div>
        </div>
      </div>
    </div>
    &nbsp;
  @endforeach
  @endif
</div>

<div class="content-header row">
  <div class="content-header-left col-md-9 col-12 mb-2">
    <div class="row breadcrumbs-top">
      <div class="col-12">
        <h2 class="content-header-title float-left mb-0">@yield('title') </h2>
      </div>
    </div>
  </div>
</div>
{{-- 
<div class="content-header row">
  <div class="content-header-left col-md-9 col-12 mb-2">
    <div class="row breadcrumbs-top">
      <div class="col-12">
        <h2 class="content-header-title float-left mb-0">@yield('title')</h2>
        <div class="breadcrumb-wrapper">
          @if(@isset($breadcrumbs))
          <ol class="breadcrumb">
              
              @foreach ($breadcrumbs as $breadcrumb)
              <li class="breadcrumb-item">
                  @if(isset($breadcrumb['link']))
                  <a href="{{ $breadcrumb['link'] == 'javascript:void(0)' ? $breadcrumb['link']:url($breadcrumb['link']) }}">
                      @endif
                      {{$breadcrumb['name']}}
                      @if(isset($breadcrumb['link']))
                  </a>
                  @endif
              </li>
              @endforeach
          </ol>
          @endisset
        </div>
      </div>
    </div>
  </div>
</div> --}}