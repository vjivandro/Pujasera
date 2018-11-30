@extends('layouts.content')

@section('page-title', 'Edit Hidangan')

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
                            <h3 class="box-title">Form Edit Hidangan</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form id="form-create" role="form" action="{{ route('admin.hidangan.update', $food->id) }}"
                              method="POST" form-type="upload">
                            @method('PUT')
                            <div class="box-body">

                                <div class="form-group">
                                    <label for="nama">Nama Hidangan</label>
                                    <input type="text" class="form-control" value="{{ $food->nama }}"
                                           id="nama" name="nama" placeholder="Masukan Nama Hidangan">
                                </div>

                                <div class="form-group">
                                    <label for="harga">Harga</label>
                                    <div class="input-group ">
                                        <span class="input-group-addon">Rp.</span>
                                        <input data-type="rupiah" type="text" class="form-control"
                                               value="{{ toRp($food->harga, true) }}"
                                               id="harga" name="harga" placeholder="Masukan Harga">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="stock">Stock</label>
                                    <select name="stock" id="stock" class="form-control select2"
                                            style="width: 100%" data-placeholder="Pilih Stock">
                                        <option></option>
                                        @foreach($ddlStock as $val => $title)
                                            <option @if($val == $food->stock )
                                                selected="selected" @endif
                                                    value="{{ $val }}">{{ $title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="stan">Stan</label>
                                    <select name="id_stan" id="stan" class="form-control select2"
                                            style="width: 100%" data-placeholder="Pilih Stan">
                                        <option></option>
                                        @foreach($ddlStan as $val => $title)
                                            <option @if($val == $food->id_stan )
                                                    selected="selected" @endif
                                                    value="{{ $val }}">{{ $title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <select name="id_kategori" id="kategori" class="form-control select2"
                                            style="width: 100%" data-placeholder="Pilih Kategori">
                                        <option></option>
                                        @foreach($ddlKategori as $val => $title)
                                            <option @if($val == $food->id_kategori )
                                                    selected="selected" @endif
                                                    value="{{ $val }}">{{ $title }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="attachment-block clearfix">
                                    <img class="attachment-img" src="{{ asset('storage/foto/hidangan/'.$food->foto) }}"
                                         alt="Foto Hidangan">

                                    <div class="attachment-pushed">
                                        <div class="form-group">
                                            <label for="foto">Pilih Ubah File Foto Hidangan</label>
                                            <input name="foto" type="file" id="foto">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-block btn-success">
                                    <i class="fa fa-save"></i> Simpan Perubahan
                                </button>
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

