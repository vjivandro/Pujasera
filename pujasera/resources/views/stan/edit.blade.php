@extends('layouts.content')

@section('page-title', 'Edit Stan')

@section('page-content')
    <div class="row center-block">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">

                    <a href="{{ route('admin.stan.index') }}" type="button" class="btn btn-primary btn-social">
                        <i class="fa fa-caret-left"></i> Kembali</a>

                    {{--         <h3 class="box-title">Title</h3>--}}
                </div>
                <div class="box-body table-responsive">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Form Edit Stan</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->

                        <form id="form-create-stan" role="form" action="{{ route('admin.stan.update', $stan->id) }}"
                              method="POST" form-type="upload">

                            <div class="box-body">

                            @method('PUT')
                            <div class="box-body">
                            <div class="form-group">
                                <label for="nama">Nama Stan</label>
                                <input type="text" value="{{ $stan->nama }}"
                                       class="form-control" id="nama" name="nama" placeholder="Masukan Nama Stan">
                            </div>
                            <div class="form-group">
                                <label for="saldo">Saldo</label>
                                <div class="input-group ">
                                    <span class="input-group-addon">Rp.</span>
                                    <input data-type="rupiah" value="{{ toRp($stan->saldo, true) }}" type="text" class="form-control"
                                           id="saldo" name="saldo" placeholder="Masukan Saldo Pertama Stan">
                                </div>
                            </div>

                            <div class="attachment-block clearfix">
                                <img class="attachment-img" src="{{ asset('storage/foto/stan/'.$stan->foto) }}" alt="Attachment Image">

                                <div class="attachment-pushed">
                                    <div class="form-group">
                                        <label for="foto">Pilih Ubah File Foto Stan</label>
                                        <input name="foto" type="file" id="foto">
                                    </div>
                                </div>
                            </div>
                            </div>


                            <div class="form-group">
                                <label for="username">Username</label>
                                <input value="{{ $stan->user->username }}" type="text" class="form-control" id="username"
                                       name="username" placeholder="Masukan Username Stan">
                            </div>
                    {{--        <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password"
                                       name="password" placeholder="Masukan Password Stan">
                            </div>

                            <div class="form-group">
                                <label for="password-confirm">{{ __('Konfirmasi Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                                       placeholder="Silahkan Konfirmasi Password ">

                            </div>--}}
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-block btn-success">
                                    <i class="fa fa-save"></i> Simpan Perubahan</button>
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
        setMenuActive('stan');
        $('#form-create-stan').ajaxForm();
    </script>
@endsection

