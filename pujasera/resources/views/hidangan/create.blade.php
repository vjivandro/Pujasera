@extends('layouts.content')

@section('page-title', 'Tambah Hidangan')

@section('page-content')
    <div class="row center-block">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">

                    <a href="{{ route('admin.hidangan.index') }}" type="button" class="btn btn-primary btn-social">
                        <i class="fa fa-caret-left"></i> Kembali</a>

                    {{--         <h3 class="box-title">Title</h3>--}}
                </div>
                <div class="box-body table-responsive">

                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Form Tambah Hidangan</h3>
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <form id="form-create" role="form" action="{{ route('admin.hidangan.store') }}" method="POST" form-type="upload">

                                    <div class="box-body">
                                    <div class="form-group">
                                        <label for="nama">Nama Hidangan</label>
                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukan Nama Hidangan">
                                    </div>

                                    <div class="form-group">
                                        <label for="harga">Harga</label>
                                        <div class="input-group ">
                                            <span class="input-group-addon">Rp.</span>
                                            <input data-type="rupiah" type="text" class="form-control"
                                                   id="harga" name="harga" placeholder="Masukan Harga">
                                        </div>
                                    </div>

                                        <div class="form-group">
                                            <label for="stock">Stock</label>
                                            <select name="stock" id="stock" class="form-control select2"
                                                    style="width: 100%" data-placeholder="Pilih Stock">
                                                <option></option>
                                                @foreach($ddlStock as $val => $title)
                                                    <option value="{{ $val }}">{{ $title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    <div class="form-group">
                                        <label for="stan">Stan</label>
                                        <select name="id_stan" id="stan" class="form-control select2"
                                                style="width: 100%" data-placeholder="Pilih Stan">
                                            <option></option>
                                            @foreach($ddlStan as $val => $title)
                                                <option value="{{ $val }}">{{ $title }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="kategori">Kategori</label>
                                        <select name="id_kategori" id="kategori" class="form-control select2"
                                                style="width: 100%" data-placeholder="Pilih Kategori">
                                            <option></option>
                                            @foreach($ddlKategori as $val => $title)
                                                <option value="{{ $val }}">{{ $title }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="foto">Pilih File Foto Hidangan</label>
                                        <input name="foto" type="file" id="foto" accept=".png, .jpg, .jpeg">
                                    </div>
                                    </div>

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-block btn-success">
                                            <i class="fa fa-save"></i> Simpan</button>
                                    </div>
                                </form>

                    </div>

                </div>

            </div>
        </div>

    </div>

@endsection

@section('script')
<script>
    setMenuActive('hidangan');
    $('#form-create').ajaxForm();
</script>
@endsection

