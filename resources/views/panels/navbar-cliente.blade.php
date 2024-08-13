<nav class="header-navbar floating-nav justify-content-center {{ $configData['navbarColor'] }}" data-nav="brand-center" style="background: #ffffff 0% 0% no-repeat padding-box; box-shadow: 0px 3px 15px #00000029; border-radius: 0px 0px 30px 30px; opacity: 1; margin: 0rem 2rem 0; min-height: 0; padding-top: 1rem; padding-bottom: 10px; width: 98%; z-index: 1050; margin: 0rem 1rem 1rem;">

  
  @if(isset($breadcrumbs))
  <div class="d-flex justify-content-center breadcrumb-wrapper">
    <img id="menu-logotipo" src="{{ asset('storage/logos/logo_small.png') }}" alt="logo" class="d-md-block d-none" style="position: absolute; left: 2%; height: 60%; bottom: 1%; margin-bottom: 0.7%;" />

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">        
        <li class="breadcrumb-item" style="font-weight: bold; font-size: 16px;">          
          <a href="#">
            <span style="color:white;">&nbsp;</span>
          </a>          
        </li>
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

    <ul class="nav navbar-nav d-md-block d-none ulUser">      
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
            <i class="fas fa-sign-out-alt fa-sm fa-fw"></i> {{ __('messages.salir') }}
          </a>
      </div>
      </li>
    </ul>
  </div>
  @endif

</nav>
