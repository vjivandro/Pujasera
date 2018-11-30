@extends('layouts.content')

@section('page-title', 'Kelola Data Hidangan')

@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <div class="col-md-4">
                <form class="form-horizontal form-filter" action="{{ route('admin.hidangan.index') }}"
                      method="get" role="form">

                    <div class="form-group">
                        <label for="stan" class="col-sm-2 control-label">Stan</label>
                        <div class="col-sm-10">
                            <select name="id_stan" id="stan" class="form-control select2 filter" style="width: 100%">
                                @foreach($ddlStan as $val => $title)
                                    <option @if(isset($filter['id_stan']) && $val == $filter['id_stan'])
                                            selected="selected" @endif
                                            value="{{ $val }}">{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="kategori" class="col-sm-2 control-label">Kategori</label>
                        <div class="col-sm-10">
                            <select name="id_kategori" id="kategori" class="form-control select2 filter" style="width: 100%">
                                @foreach($ddlKategori as $val => $title)
                                    <option @if(isset($filter['id_kategori']) && $val == $filter['id_kategori'])
                                            selected="selected" @endif
                                            value="{{ $val }}">{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="input-group">
                        <input type="text" value="{{ isset($filter['search']) ? $filter['search'] : "" }}"
                               name="search" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
                    <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
                    </div>
                </form>
            </div>

            <a href="{{ route('admin.hidangan.create') }}" type="button"
                   class="btn btn-primary btn-social pull-right">
                    <i class="fa fa-plus"></i> Tambah Hidangan</a>
        </div>

        <div class="box-body table-responsive no-padding">

            <div class="box box-widget widget-user-2">
                @foreach($foods as $food)
                <div class="box-header with-border col-md-6">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>
                                <div class="box-tools" >

                                    <a href="{{ route('admin.hidangan.edit', $food->id) }}"
                                       class="btn btn-box-tool"
                                            data-toggle="tooltip" data-original-title="Edit Hidangan">
                                        <i class="fa fa-edit"></i></a>

                                    <a class="btn btn-box-tool btn-delete"
                                            href="{{ route('admin.hidangan.destroy', $food->id) }}"
                                            data-toggle="tooltip" data-original-title="Hapus Hidangan">
                                        <i class="fa fa-trash"></i></a>
                                </div>
                            </th>
                            <th width="250">Detail</th>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-block">
                                    <div class="widget-user-image">
                                        <img class="img-circle"
                                             src="{{ asset('storage/foto/hidangan/'.$food->foto) }}"
                                             alt="Foto Hidangan">
                                    </div>
                                    <h3 class="widget-user-username">{{ $food->nama }}</h3>
                                    <h5 class="widget-user-desc">{{ toRp($food->harga) }}</h5>

                                </div>
                            </td>
                            <td>
                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item">
                                        <b>Stock</b> <span class="pull-right">{{ $food->stock_info }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Stan</b> <span class="pull-right">{{ $food->stan->nama }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Kategori</b> <span class="pull-right">{{ $food->kategori->nama }}</span>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                    @if($loop->iteration%2 == 0)
                        <div class="clear clearfix"></div>
                        @endif

                @endforeach
            </div>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
                {{ $foods->links() }}
        </div>
        <!-- /.box-footer-->
    </div>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@section('script')
    <script>
        setMenuActive('hidangan');
        $('.filter').change(function (event) {
            $('.form-filter').submit();
        });

        $('.btn-delete').confirmDelete();
    </script>
@endsection
