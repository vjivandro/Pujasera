<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Tambah Kategori</h4>
</div>
<form id="form-create" role="form" action="{{ route('admin.kategori.store') }}" method="POST">
    <div class="modal-body">

        <div class="form-group">
            <label for="nama">Nama Kategori</label>
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukan Nama Kategori">
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Simpan Kategori</button>
    </div>
</form>

<script>

    $('#form-create').ajaxForm();

</script>