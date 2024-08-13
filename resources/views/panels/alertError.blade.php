@if(null !== session()->get('errors'))
    @php
    $msg = '';
    foreach ($errors->all() as $error) {
        $msg .= str_replace("'","",$error).' ';
    }
    @endphp
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="alert-body">{{ $msg }} &nbsp;</div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"> &times;</span></button>
    </div>
    @php session()->forget('errors'); @endphp
@endif