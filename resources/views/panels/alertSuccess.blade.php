@if(null !== session()->get('successMsg'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="alert-body">{{ session()->get('successMsg') }} &nbsp;</div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"> &times;</span>
        </button>
    </div>
    @php session()->forget('successMsg'); @endphp
@endif