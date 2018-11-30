@extends('layouts.content')

@section('page-title', 'Kelola Data Meja')

@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <div class="col-md-4">
                <form class="form-horizontal form-filter" action="{{ route('admin.meja.index') }}"
                      method="get" role="form">

                    <div class="form-group">
                        <label for="status" class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10">
                            <select name="status" id="status" class="form-control select2 filter" style="width: 100%">
                                @foreach($ddlStatus as $val => $title)
                                    <option @if(isset($filter['status']) && $val == $filter['status'])
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

            <a href="{{ route('admin.meja.create') }}"
               class="btn btn-primary btn-social pull-right btn-create">
                <i class="fa fa-plus"></i> Tambah Meja</a>
        </div>

        <div class="box-body table-responsive no-padding">

            <div class="box box-widget widget-user-2">
                @foreach($tables as $table)
                    <div class="col-md-4">
                        <div class="box box-default box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">Meja Nomor {{ $table->nomor }}</h3>

                                <div class="box-tools pull-right">

                                    <a href="{{ route('admin.meja.edit', $table->id) }}"
                                       class="btn btn-box-tool btn-edit"
                                       data-toggle="tooltip" data-original-title="Edit Meja">
                                        <i class="fa fa-edit"></i></a>

                                    <a class="btn btn-box-tool btn-delete"
                                       href="{{ route('admin.meja.destroy', $table->id) }}"
                                       data-toggle="tooltip" data-original-title="Hapus Meja">
                                        <i class="fa fa-trash"></i></a>

                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body" style="">
                                Status : {{ $table->status_info }}
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>

                    @if($loop->iteration%3 == 0)
                        <div class="clear clearfix"></div>
                    @endif

                @endforeach
            </div>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
                {{ $tables->links() }}
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
        setMenuActive('meja');

        $('.filter').change(function (event) {
            $('.form-filter').submit();
        });

        $('.btn-edit, .btn-create').click(function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            $('#modal-default').modal('show');
            $('#modal-default').find('.modal-content').load(url);
        })

        $('.btn-delete').confirmDelete();
    </script>
@endsection
