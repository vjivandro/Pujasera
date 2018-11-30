<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Ubah Kategori: {{ $category->nama }}</h4>
</div>
<form id="form-create" role="form" action="{{ route('admin.kategori.update', $category->id) }}" method="POST">
    @method('PUT')
    <div class="modal-body">

        <div class="form-group">
            <label for="nama">Nama Kategori</label>
            <input type="text" class="form-control" value="{{ $category->nama }}"
                   id="nama" name="nama" placeholder="Masukan Perubahan Nama Kategori">
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan Kategori</button>
    </div>
</form>

<script>

    $('#form-create').ajaxForm();

</script>