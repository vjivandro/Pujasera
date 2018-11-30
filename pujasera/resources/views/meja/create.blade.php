<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Tambah Meja</h4>
</div>
<form id="form-create" role="form" action="{{ route('admin.meja.store') }}" method="POST">
    <div class="modal-body">

        <div class="form-group">
            <label for="nomor">Nomor Meja</label>
            <input type="number" class="form-control" id="nomor" name="nomor" placeholder="Masukan Nomor Meja">
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control select2"
                    style="width: 100%">
                @foreach($ddlStatus as $val => $title)
                    <option value="{{ $val }}">{{ $title }}</option>
                @endforeach
            </select>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Simpan Meja</button>
    </div>
</form>

<script>

    $('#form-create').ajaxForm();

</script>
