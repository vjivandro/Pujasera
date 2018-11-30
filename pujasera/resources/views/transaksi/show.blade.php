@extends('layouts.content')

@section('page-title', 'Detail Transaksi')

@section('page-content')
    <div class="box">
        <div class="box-header with-border">



            <div class="row">
                <div class="col-md-6">

                    <div class="box box-{{ $transaction->status_color }} box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Transaksi {{ $transaction->tanggal->diffForHumans() }}
                            <sup>({{ $transaction->tanggal->format( 'd F Y H:i:s') }})</sup></h3>
                            <h5></h5>

                            <div class="box-tools pull-right">

                                <a type="button" class="btn btn-box-tool"
                                   href="{{ route('admin.transaksi.show', $transaction->id) }}"
                                   data-toggle="tooltip" data-original-title="Detail Transaksi">
                                    <i class="fa fa-list"></i></a>

                                @if($transaction->status == 2 || $transaction->status == 3)
                                    <a href="{{ route('admin.transaksi.update', $transaction->id) }}"
                                       class="btn btn-box-tool btn-cancel"
                                       data-toggle="tooltip" data-original-title="Batalkan Transaksi">
                                        <i class="fa fa-ban"></i></a>
                                @endif

                            </div>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body" style="">

                            <ul class="list-group list-group-unbordered col-md-6">
                                <li class="list-group-item">
                                    <b class="text-{{ $transaction->status_color }}">Status</b>
                                    <span class="pull-right label label-{{ $transaction->status_color }}"
                                          style="font-size: 15px"> {{ $transaction->status_info }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Total</b> <span class="pull-right">{{ toRp($transaction->total) }}</span>
                                </li>
                            </ul>
                            <ul class="list-group list-group-unbordered col-md-6">
                                <li class="list-group-item">
                                    <b>Jumlah Pesanan</b>
                                    <span class="pull-right">{{ $transaction->pemesanan->count() }}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Meja Nomor</b>
                                    <span class="pull-right">{{ $transaction->nomor_meja }}</span>
                                </li>
                            </ul>
                        </div>
                        <!-- /.box-body -->
                    </div>

                </div>
                <!-- /.col -->
                <div class="col-md-6">
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-{{ $transaction->status_color }} widget-user-2 box-solid">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="box-header">
                            <div class="widget-user-image">
                                <img class="img-circle" src="{{ $transaction->customer->path_foto }}" alt="User Avatar">
                            </div>
                            <h3 class="widget-user-username">{{ $transaction->customer->nama }}
                                ( {{ $transaction->customer->user->username }} )</h3>
                            <h5 class="widget-user-desc">Customer</h5>
                        </div>

                    </div>
                    <!-- /.widget-user -->
                </div>
                <!-- /.col -->
            </div>

        </div>

        <div class="box-body table-responsive no-padding">

            <div class="box box-{{ $transaction->status_color }} widget-user-2 box-soli">
                @foreach($transaction->pemesanan as $pesan)
                    <div class="box-header with-border col-md-6">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <td>
                                    <div class="user-block">
                                        <div class="widget-user-image">
                                            <img class="img-circle"
                                                 src="{{ $pesan->hidangan->path_foto }}"
                                                 alt="Foto Hidangan">
                                        </div>
                                        <h3 class="widget-user-username">{{ $pesan->hidangan->nama }}</h3>
                                        <h5 class="widget-user-desc">{{ toRp($pesan->hidangan->harga) }}</h5>

                                    </div>
                                </td>
                                <td>
                                    <ul class="list-group list-group-unbordered">
                                        <li class="list-group-item">
                                            <b class="text-{{ $pesan->status_color }}">Status</b>
                                            <span class="pull-right label label-{{ $pesan->status_color }}"
                                                  style="font-size: 15px"> {{ $pesan->status_info }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Jumlah</b> <span class="pull-right">{{ $pesan->jumlah}}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Total</b> <span class="pull-right">Rp. {{ $pesan->total_rupiah }}</span>
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
        </div>
        <!-- /.box-footer-->
    </div>

@endsection

@section('script')
<script>
    $('.btn-cancel').confirmDelete({
        method : 'PUT',
        text_confirm : 'Ya, Batalkan',
        title : 'Apa Anda Yakin Membatalkan Transaksi Ini',
        text : 'Pesanan yang belum selesai akan dibatalkan serta nominal pembayaran akan dikembalikan ke Pemesan!!'
    });
</script>
@endsection
