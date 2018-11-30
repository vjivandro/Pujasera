@extends('layouts.content')

@section('page-title', 'Kelola Data Stan')

@section('page-content')
<div class="box">
           <div class="box-header with-border">

                   <div class="col-md-4">
                       <form action="{{ route('admin.stan.index') }}" method="get">
                       <div class="input-group">
                           <input type="text" value="{{ $search }}" name="search" class="form-control" placeholder="Search...">
                           <span class="input-group-btn">
                    <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
                       </div>
                       </form>
                   </div>

                       <a href="{{ route('admin.stan.create') }}" type="button"
                          class="btn btn-primary btn-social pull-right">
                           <i class="fa fa-plus"></i> Tambah Stan</a>

      {{--         <h3 class="box-title">Title</h3>--}}
           </div>
    <div class="box-body table-responsive no-padding">

        <div class="box box-widget widget-user-2">
            @foreach($stans as $stan)
            <div class="box-header with-border col-md-6">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <th>
                            <div class="box-tools" >
                                <a type="button" class="btn btn-box-tool"
                                   href="{{ route('admin.hidangan.index') }}?id_stan={{ $stan->id }}"
                                        data-toggle="tooltip" data-original-title="Daftar Hidangan">
                                    <i class="fa fa-list"></i></a>

                                <a href="{{ route('admin.stan.edit', $stan->id) }}"
                                   class="btn btn-box-tool"
                                        data-toggle="tooltip" data-original-title="Edit Stan">
                                    <i class="fa fa-edit"></i></a>

                                <a href="{{ route('admin.stan.edit.password', $stan->id) }}"
                                   class="btn btn-box-tool btn-edit-password"
                                        data-toggle="tooltip" data-original-title="Edit Password">
                                    <i class="fa fa-key"></i></a>

                                <a class="btn btn-box-tool btn-delete"
                                        href="{{ route('admin.stan.destroy', $stan->id) }}"
                                        data-toggle="tooltip" data-original-title="Hapus Stan">
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
                                         src="{{ asset('storage/foto/stan/'.$stan->foto) }}"
                                         alt="Foto Stan">
                                </div>
                                <h3 class="widget-user-username">{{ $stan->nama }}</h3>
                                <h5 class="widget-user-desc">{{ $stan->user->username }}</h5>

                            </div>
                        </td>
                        <td>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Saldo</b> <span class="pull-right">{{ toRp($stan->saldo) }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Hidangan</b> <span class="pull-right">{{ $stan->hidangan->count() }}</span>
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
               {{ $stans->links() }}
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
        setMenuActive('stan');

        $('.btn-edit-password').click(function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            $('#modal-default').modal('show');
            $('#modal-default').find('.modal-content').load(url);
        });

        $('.btn-delete').confirmDelete();
    </script>
@endsection
