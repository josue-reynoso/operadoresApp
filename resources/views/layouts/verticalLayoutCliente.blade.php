@auth

@if( !empty(session()->get('menu')) )

<body class="vertical-layout vertical-menu-modern {{ $configData['showMenu'] === true ? '2-columns' : '1-column' }}
{{ $configData['blankPageClass'] }} {{ $configData['bodyClass'] }}
{{ $configData['verticalMenuNavbarType'] }}
{{ $configData['sidebarClass'] }} {{ $configData['footerType'] }}" data-menu="vertical-menu-modern" data-col="{{ $configData['showMenu'] === true ? '2-columns' : '1-column' }}" data-layout="{{ ($configData['theme'] === 'light') ? '' : $configData['layoutTheme'] }}" style="{{ $configData['bodyStyle'] }}" data-framework="laravel" data-asset-path="{{ asset('/')}}">
<script>

  window.addEventListener( "pageshow", function ( event ) {
      reloadPage();
  });

  function reloadPage() {
      console.log('reload');
      var historyTraversal = event.persisted || 
                              ( typeof window.performance != "undefined" && 
                                  window.performance.navigation.type === 2 );
      if ( historyTraversal ) {
          // Handle page restore.
          window.location.reload();
      }
  }
</script>
  {{-- Include Navbar --}}
  @include('panels.navbar-cliente')

  {{-- Include Sidebar 
  @if((isset($configData['showMenu']) && $configData['showMenu'] === true))
  @include('panels.sidebar-cliente')
  @endif --}}

  <!-- BEGIN: Content-->
  <div {{--class="app-content content {{ $configData['pageClass'] }}"--}} style="padding-top: 35px; position: relative;">
    <!-- BEGIN: Header-->
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>


    @if(($configData['contentLayout']!=='default') && isset($configData['contentLayout']))
    <div class="content-area-wrapper {{ $configData['layoutWidth'] === 'boxed' ? 'container p-0' : '' }}">

      <div class="{{ $configData['sidebarPositionClass'] }}">
        <div class="sidebar">
          {{-- Include Sidebar Content --}}
          @yield('content-sidebar')
        </div>
      </div>
      <div class="{{ $configData['contentsidebarClass'] }}">
        <div class="content-wrapper">

          <div class="content-body">
            {{-- Include Page Content --}}
            @yield('content')
          </div>
        </div>
      </div>
    </div>
    @else
    <div class="content-wrapper {{ $configData['layoutWidth'] === 'boxed' ? 'container p-0' : '' }}">
      {{-- Include Breadcrumb --}}
      {{-- @if($configData['pageHeader'] === true && isset($configData['pageHeader']))
      @include('panels.breadcrumb')
      @endif --}}

      <div class="content-body">
        {{-- Include Page Content --}}
        
        @yield('content')
      </div>
    </div>
    @endif

  </div>
  <!-- End: Content-->  

  {{-- include footer --}}
  {{-- @include('panels/footer') --}}

  {{-- include default scripts --}}
  @include('panels/scripts')

  <script type="text/javascript">
    $(window).on('load', function() {
      if (feather) {
        feather.replace({
          width: 14,
          height: 14
        });
      }
    })
  </script>
</body>

@else

<script>
  window.location = "{{ route('logout') }}";
</script>

@endif

@endauth

{{--
@if(count($errors))
@php

$msg = 'Debe corregir los siguientes errores: <p><ol>';
  @endphp
  @foreach ($errors->all() as $error)
  @php
  $msg .= '<li>'.str_replace("'","",$error).'</li>';
  @endphp
  @endforeach
  @php
  $msg .= '</ol></p>';

@endphp
<script type="text/javascript">
  $(function() {
    toastr['error']('{!! $msg !!}', '¡Error!', {
      closeButton: true,
      tapToDismiss: true,
      showMethod: 'slideDown',
      hideMethod: 'fadeOut',
      timeOut: 3500,
      rtl: false
    });
  })
</script>
@endif
--}}

<!-- @if (Session::has('success'))
<script>
  window.onload = function() {
    console.log("{{Session::get('success')}}");
    toastr['success']('{{Session::get(' success ')}}', '¡Correcto!\n{{Session::get(' success ')}}', {
        closeButton: true,
        tapToDismiss: true,
        showMethod: 'slideDown',
        hideMethod: 'fadeOut',
        timeOut: 2000,
        rtl: false
      });
  }
</script>
@endif -->

<script>
  (function($) {
    $.fn.inputFilter = function(inputFilter) {
      return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
        if (inputFilter(this.value)) {
          this.oldValue = this.value;
          this.oldSelectionStart = this.selectionStart;
          this.oldSelectionEnd = this.selectionEnd;
        } else if (this.hasOwnProperty("oldValue")) {
          this.value = this.oldValue;
          this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        } else {
          this.value = "";
        }
      });
    };
  }(jQuery));


  $("#US_TEL").inputFilter(function(value) {
    return /^-?\d*$/.test(value);
  });

  $(function() {
    $("a").not('#lnkLogOut').click(function() {
      window.onbeforeunload = null;
    });
    $(".btn").click(function() {
      window.onbeforeunload = null;
    });
  });

  function validateField(e, f) {
    f.forEach(i => i(e));
  }

  function validateRequired(e) {
    //console.log(e.id);
    //console.log($('#' + e.id)[0].value);
    if ($('#' + e.id)[0].value == '') {
      e.classList.add("is-invalid");
    } else {
      e.classList.remove("is-invalid");
    }
  }


  function validateMail(e) {
    console.log('Validando formato correo de ' + $('#' + e.id)[0].value)
    var valid = $('#' + e.id)[0].value.match(
      /\S+@\S+\.\S+/
    );
    if (!valid) {
      e.classList.add("is-invalid");
    } else {
      e.classList.remove("is-invalid");
    }
  }

  function validateMailNoRequired(e) {
    console.log('Validando formato correo de ' + $('#' + e.id)[0].value)
    var valid = $('#' + e.id)[0].value.match(
      /\S+@\S+\.\S+/
    );
    if (!valid && $('#' + e.id)[0].value != '') {
      e.classList.add("is-invalid");
    } else {
      e.classList.remove("is-invalid");
    }
  }

  function setOnlyNumsTo(e) {
    
    e.inputFilter(function(value) {
      return /^-?\d*$/.test(value);
    });
  }

  function setOnlyNumsDecimalTo(e, limit, decimal) {
    e.inputFilter(function(value) {

      if (value.toString().length > limit-decimal) {
        var maxc = limit-decimal;
        var pattern = '/^-?\\d{0,'+maxc+'}[.]\\d{0,'+decimal+'}$/'
        console.log(pattern)
        var r = new RegExp(pattern)
        return pattern.test(value);
      } else {
        var maxc = limit-decimal;
        var pattern = '/^-?\\d{0,'+maxc+'}[.]?\\d{0,'+decimal+'}$/'
        console.log(pattern)
        var r = new RegExp(pattern)
        return pattern.test(value);

      }

    });
  }

  function setMaxLengthTo(e, l) {
    console.log(e)
    e.attr('maxLength',l);
  }

  function setMaxLengthTexrArea() {
    $("textarea[maxlength]").bind('input propertychange', function() {
      var maxLength = $(this).attr('maxlength');
      if ($(this).val().length > maxLength) {
        $(this).val($(this).val().substring(0, maxLength));
      }
    });
  }
</script>


</html>