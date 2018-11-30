@extends('layouts.content')

@section('page-title', 'Kelola Data Transaksi')

@section('page-content')
    <div class="box">
        <div class="box-header with-border">
            <div class="col-md-4">
                <form class="form-horizontal form-filter" action="{{ route('admin.transaksi.index') }}"
                      method="get" role="form">
                    <div class="form-group">
                        <label for="status" class="col-sm-2 control-label">Pilih Tanggal</label>
                        <div class="col-sm-10">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input data-date-format="dd-mm-yyyy" data-date-end-date="{{ date('d-m-Y') }}"
                                       @if( !isset($filter['date']) )
                                        value="{{ date('d-m-Y') }}"
                                       @elseif($filter['date'])
                                       value="{{ date('d-m-Y', strtotime($filter['date'])) }}"
                                       @endif
                                       type="text" name="date" class="form-control pull-right filter datepicker">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status" class="col-sm-2 control-label">Pilih Status</label>
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

                </form>
            </div>

        </div>

        <div class="box-body table-responsive no-padding">

            <div class="box box-widget widget-user-2">
                @foreach($transactions as $transaction)
                    <div class="col-md-6">
                        <div class="box box-{{ $transaction->status_color}} box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">Transaksi {{ $transaction->tanggal->diffForHumans() }}
                                    <sup>({{ $transaction->tanggal->format( 'd F Y H:i:s') }})</sup>
                                </h3>

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
                                <h4>Customer : <b>{{ $transaction->customer->nama }}</b> </h4>
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
                                        <b>Pesanan</b>
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
                        <!-- /.box -->
                    </div>

                    @if($loop->iteration%2 == 0)
                        <div class="clear clearfix"></div>
                    @endif

                @endforeach
            </div>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
                {{ $transactions->links() }}
        </div>
        <!-- /.box-footer-->
    </div>

@endsection

@section('script')
    <script>
        $('.filter').on('change', function (event) {
            $('.form-filter').submit();
        });

        $('.btn-cancel').confirmDelete({
            method : 'PUT',
            text_confirm : 'Ya, Batalkan',
            title : 'Apa Anda Yakin Membatalkan Transaksi Ini',
            text : 'Pesanan yang belum selesai akan dibatalkan serta nominal pembayaran akan dikembalikan ke Pemesan!!'
        });
    </script>
@endsection
