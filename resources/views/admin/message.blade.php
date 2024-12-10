@if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible" id="message-alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> THÔNG BÁO!</h4>
        {{ Session::get('error') }}
    </div>
@endif


@if (Session::has('success'))
    <div class="alert alert-success alert-dismissible" id="message-alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-check"></i> THÀNH CÔNG!</h4>
        {{ Session::get('success') }}
    </div>
@endif
