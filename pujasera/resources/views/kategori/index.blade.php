@extends('layouts.content')

@section('page-title', 'Kelola Data Kategori')

@section('page-content')
    <div class="box">
        <div class="box-header with-border">

            <div class="col-md-4">
                <form action="{{ route('admin.kategori.index') }}" method="get">
                    <div class="input-group">
                        <input type="text" value="{{ $search }}" name="search" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
                    <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
                    </div>
                </form>
            </div>

            <a href="{{ route('admin.kategori.create') }}"
               class="btn btn-primary btn-social pull-right btn-create">
                <i class="fa fa-plus"></i> Tambah Kategori</a>

            {{--         <h3 class="box-title">Title</h3>--}}
        </div>
        <div class="box-body table-responsive no-padding">

            <div class="box box-widget widget-user-2">
                @foreach($categories as $category)
                    <div class="col-md-4">
                        <div class="box box-default box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{ $category->nama }}</h3>

                                <div class="box-tools pull-right">

                                    <a type="button" class="btn btn-box-tool"
                                       href="{{ route('admin.hidangan.index') }}?id_kategori={{ $category->id }}"
                                            data-toggle="tooltip" data-original-title="Daftar Hidangan Pada Kategori Ini">
                                        <i class="fa fa-list"></i></a>

                                    <a href="{{ route('admin.kategori.edit', $category->id) }}"
                                       class="btn btn-box-tool btn-edit"
                                       data-toggle="tooltip" data-original-title="Edit Kategori">
                                        <i class="fa fa-edit"></i></a>

                                    <a class="btn btn-box-tool btn-delete"
                                       href="{{ route('admin.kategori.destroy', $category->id) }}"
                                       data-toggle="tooltip" data-original-title="Hapus Kategori">
                                        <i class="fa fa-trash"></i></a>

                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body" style="">
                                Jumlah Hindangan Di Kategori Ini : {{ $category->hidangan->count() }}
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
            {{ $categories->links() }}
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
        setMenuActive('kategori');

        $('.btn-edit, .btn-create').click(function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            $('#modal-default').modal('show');
            $('#modal-default').find('.modal-content').load(url);
        })

        $('.btn-delete').confirmDelete({
            text : 'Hidangan Yang Mempunyai Kategori Ini Akan Terhapus Juga!!!'
        });
    </script>
@endsection
