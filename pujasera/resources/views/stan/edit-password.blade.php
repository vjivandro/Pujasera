<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Ubah Password Stan: {{ $stan->nama }}</h4>
</div>
<form id="form-create-stan" role="form" action="{{ route('admin.stan.update.password', $stan->id) }}" method="POST">
    @method('PUT')
<div class="modal-body">

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password"
                   name="password" placeholder="Masukan Password Stan">
        </div>

        <div class="form-group">
            <label for="password-confirm">{{ __('Konfirmasi Password') }}</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                   placeholder="Silahkan Konfirmasi Password ">

        </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Simpan Password</button>
</div>
</form>

<script>

    $('#form-create-stan').ajaxForm();

</script>