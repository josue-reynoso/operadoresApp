{{-- <link rel="icon" href="{{ asset('images/ico/favicon.ico') }}"> --}}

{{-- <link rel="stylesheet" href="{{ asset(mix('vendors/css/vendors.min.css')) }}" /> --}}


{{-- <link rel="stylesheet" href="{{ asset(mix('vendors/css/ui/prism.min.css')) }}" /> --}}
{{-- Vendor Styles --}}
@yield('vendor-style')
{{-- Theme Styles --}}

<link rel="stylesheet" href="{{ asset(mix('css/core.css')) }}" />
<link rel="stylesheet" href="{{ asset(mix('css/custom_icons_menu.css')) }}" />

{{-- {!! Helper::applClasses() !!} --}}
@php $configData = Helper::applClasses(); @endphp

{{-- Page Styles --}}
@if($configData['mainLayoutType'] === 'horizontal')
<link rel="stylesheet" href="{{ asset(mix('css/base/core/menu/menu-types/horizontal-menu.css')) }}" />
@endif
<link rel="stylesheet" href="{{ asset(mix('css/base/core/menu/menu-types/vertical-menu.css')) }}" />
{{-- <!-- <link rel="stylesheet" href="{{ asset(mix('css/base/core/colors/palette-gradient.css')) }}"> --> --}}

{{-- Page Styles --}}
@yield('page-style')

{{-- Laravel Style --}}
<link rel="stylesheet" href="{{ asset(mix('css/overrides.css')) }}" />


{{-- Custom RTL Styles --}}

@if($configData['direction'] === 'rtl' && isset($configData['direction']))
<link rel="stylesheet" href="{{ asset(mix('css/custom-rtl.css')) }}" />
@endif

{{-- user custom styles --}}
{{-- <link rel="stylesheet" href="{{ asset(mix('css/style.css')) }}" />
<link rel="stylesheet" href="{{ asset(mix('css/style-rtl.css')) }}" /> --}}

{{-- Data tables --}}
{{-- <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}"> --}}

<link rel="preload" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}" as="style"
onload="this.rel='stylesheet'"><noscript><link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap4.min.css')) }}"></noscript>

{{-- <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}"> --}}

<link rel="preload" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}" as="style"
onload="this.rel='stylesheet'"><noscript><link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap4.min.css')) }}"></noscript>

{{-- <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}"> --}}

<link rel="preload" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}" as="style"
onload="this.rel='stylesheet'"><noscript><link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap4.min.css')) }}"></noscript>
{{-- <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap4.min.css')) }}"> --}}
{{-- <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}"> --}}

{{-- ToastR --}}
{{-- <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}"> --}}
