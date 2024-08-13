<nav class="header-navbar floating-nav justify-content-center {{ $configData['navbarColor'] }}" data-nav="brand-center" style="background: #ffffff 0% 0% no-repeat padding-box; box-shadow: 0px 3px 15px #00000029; border-radius: 0px 0px 30px 30px; opacity: 1; margin: 0rem 2rem 0; min-height: 0; padding-top: 1rem; padding-bottom: 10px; width: 98%; z-index: 1050; margin: 0rem 1rem 1rem;">

  
  @if(isset($breadcrumbs))
  <div class="d-flex justify-content-center breadcrumb-wrapper">
    <img id="menu-logotipo" src="{{ asset('storage/logos/logo_small.png') }}" alt="logo" class="d-md-block d-none" style="position: absolute; left: 2%; height: 60%; bottom: 1%; margin-bottom: 0.7%;" />

    <a id="menubar" class="nav-link menu-toggle" href="javascript:void(0);"><i onclick="saveStatusToggleMenu()"  class="ficon" data-feather="menu"></i></a>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
        <li class="breadcrumb-item" style="font-weight: bold; font-size: 16px;">
          @if(isset($breadcrumb['link']))
          <a href="{{ $breadcrumb['link'] == 'javascript:void(0)' ? $breadcrumb['link']:url($breadcrumb['link']) }}">
            {{$breadcrumb['name']}}
          </a>
          @endif
        </li>
        @endforeach
      </ol>
    </nav>

    <style>
      .dropMeUser{
        /*
        margin-left:-200%;
        position:relative;
        */
        border-radius: 10px; 
        border: 1px solid #00000029; 
        background: #ffffff 0% 0% no-repeat padding-box;
        width:auto !important;
        right:0% !important;
        left:auto !important;
      }

      .ulUser {
        list-style-type: none;
        position: absolute;
        right: 2.5%;
      }

      .ulUser li {
        display: inline;
        float: left;
        padding-right: 3rem;
      }

      .badge.badge-up.badgeBell {
        position: absolute;
        right: 45%;
      }
    </style>

    <script>
      function toggleMenu(){      
        if (localStorage.getItem("toggleClickMenu") === null) {
          localStorage.setItem('toggleClickMenu', '0');
        }

        const toggleClickMenu = localStorage.getItem('toggleClickMenu');
        if(toggleClickMenu==='1'){
          var element = document.getElementById("menubar");
          element.click();
        }        
      }
      function saveStatusToggleMenu(){        
        if (localStorage.getItem("toggleClickMenu") === null) {          
          localStorage.setItem('toggleClickMenu', '0');
        }

        const toggleClickMenu = localStorage.getItem('toggleClickMenu');        
        if(toggleClickMenu==='0')
          localStorage.setItem('toggleClickMenu', '1');
        else
          localStorage.setItem('toggleClickMenu', '0');
      }
      $(document).ready(function() {
        toggleMenu();
      });
    </script>

    <ul class="nav navbar-nav d-md-block d-none ulUser">
      {{-- Notificaciones --}}
      <li class="nav-item dropdown text-center dropleft">
          <a class="nav-link" id="dropdown-user" href="{{ route("notificaciones") }}">
          {{-- <a class="nav-link dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> --}}
            <i class="fas fa-bell"></i>
            @if($unreadNotifications > 0)
            <span class="badge badge-pill badge-danger badge-up badgeBell">{{ $unreadNotifications }}</span>
            @endif
          </a>
        {{-- 
        <div class="dropdown-menu dropMeUser">
          <a class="dropdown-item text-center" href="{{ route("notificaciones") }}" style="margin-top: 0%;">
            <i class="fas fa-eye fa-sm fa-fw"></i> <small>{{ __('messages.ver_notificaciones') }}</small>
          </a>
        </div>
         --}}
      </li>
      {{-- Usuario --}}
      <li class="nav-item dropdown text-center dropleft">
        <a class="nav-link dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="font-weight-bolder mtxt" style="color: #606060; font-size: 16px"></span> <i class="fas fa-user" style="color: #003c82;"></i>
        </a>
        <div class="dropdown-menu dropMeUser" aria-labelledby="dropdown-user">
          <a class="dropdown-item text-center" href="#" >
            <i class="fas fa-user" style="color: #003c82;"></i><br />
            @php
            $uid = Auth::user()->id;
            $name1 = \App\Models\Usuario::where(['US_ID' => $uid])->pluck('US_NOM')->first();
            $name1 = strtoupper($name1);
            $name2 = \App\Models\Usuario::where(['US_ID' => $uid])->pluck('US_APE')->first();
            $name2 = strtoupper($name2);
            $fullname = $name1 . ' ' . $name2;
            @endphp
            <br/>
            <strong>{{ $name1 }}</strong>
            <br/>
            <strong>{{ $name2 }}</strong>
            <br/>
            <small>{{ Auth::user()->email }}</small>
          </a>
          <a class="dropdown-item text-center" href="{{ route("logout") }}" style="margin-top: 0%;">
            <i class="fas fa-sign-out-alt fa-sm fa-fw"></i> Salir
          </a>
      </div>
      </li>
    </ul>
  </div>
  @endif

</nav>
