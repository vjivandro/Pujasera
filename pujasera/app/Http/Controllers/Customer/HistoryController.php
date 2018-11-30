<?php

namespace App\Http\Controllers\Customer;

use App\Deposit;
use App\Pemesanan;
use App\Refund;
use App\Transaksi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HistoryController extends Controller
{
    public function transaksi($nbulan = 0, $ntahun = 0)
    {
        $val = Validator::make(compact('nbulan','ntahun'), [
            'nbulan' => 'required|numeric|in:'. implode(',', array_keys(bulan()) ),
            'ntahun' => 'required|numeric|in:'. implode(',', array_keys(tahun()) )
        ]);

        if($val->fails()){
            $errors = $val->errors();
            if($errors->first('nbulan')) $nbulan = date('m');
            if($errors->first('ntahun')) $ntahun = date('Y');
        }

        $transaksi = Transaksi::where('id_customer', Auth::user()->customer->id)
            ->where('status','<>', 1)
            ->whereMonth('tanggal', $nbulan)
            ->whereYear('tanggal', $ntahun)
            ->get();
        return response()->json([
            'status' => 1,
            'transaksi' => $transaksi->makeHidden(['id_meja', 'meja'])->each->append('pesanan_amount'),
            'bulan' => bulan(),
            'tahun' => tahun()
        ]);
    }

    public function transaksiCurrent()
    {
        $transaksi = Transaksi::where('id_customer', Auth::user()->customer->id)
            ->where('status','<>', 1)
            ->whereDate('tanggal', hariIni())
            ->get();

        return response()->json([
            'status' => 1,
            'transaksi' => $transaksi->makeHidden(['id_meja', 'meja'])->each->append('pesanan_amount')
        ]);
    }

    public function dompet($nbulan = 0, $ntahun = 0)
    {
        $val = Validator::make(compact('nbulan','ntahun'), [
            'nbulan' => 'required|numeric|in:'. implode(',', array_keys(bulan()) ),
            'ntahun' => 'required|numeric|in:'. implode(',', array_keys(tahun()) )
        ]);

        if($val->fails()){
            $errors = $val->errors();
            if($errors->first('nbulan')) $nbulan = date('m');
            if($errors->first('ntahun')) $ntahun = date('Y');
        }

        $refund = Refund::selectRaw("nominal, tanggal, 'refund' as dari, 'plus' as tipe")
            ->whereMonth('tanggal', $nbulan)
            ->whereYear('tanggal', $ntahun)
            ->where('id_customer', Auth::user()->customer->id);

        $deposit = Deposit::selectRaw("nominal, tanggal, 'deposit' as dari, 'plus' as tipe")
            ->whereMonth('tanggal', $nbulan)
            ->whereYear('tanggal', $ntahun)
            ->where('id_customer', Auth::user()->customer->id);

        $transaksi = Transaksi::selectRaw("total as nominal, tanggal, 'transaksi' as dari, 'min' as tipe")
            ->where('id_customer', Auth::user()->customer->id)
            ->whereMonth('tanggal', $nbulan)
            ->whereYear('tanggal', $ntahun)
            ->whereNotIn('status', [1,5]);

        $history = $refund->union($deposit)->union($transaksi)
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json([
            'status' => 1,
            'history' => $history,
            'saldo' => Auth::user()->customer->saldo,
            'bulan' => bulan(),
            'tahun' => tahun()
        ]);


    }
    
    public function refund(Request $request)
    {
        $request->validate([
            'alasan' => 'required|string|',
            'nohp' => 'required|numeric|digits_between:9,15',
            'id_pemesanan' => [
                'required','numeric',
                Rule::exists('pemesanan','id')->where(function ($query) {
                    $query->whereHas('transaksi', function ($q){
                            $q->where('id_customer', Auth::user()->customer->id)
                                ->where('status', 1);
                        })->where(function ($q){
                            $q->where('status',2)->orWhere('status',3);
                    });
                }),
            ]
        ],[
            'id_pemesanan.exists' => 'Pesanan ini tidak dapat direfund'
        ]);

        $pesan = Pemesanan::find($request->id_pemesanan);
        
        $refund = new Refund();
        $refund->id_stan = $pesan->hidangan->stan->id;
        $refund->id_customer = Auth::user()->customer->id;
        $refund->nohp = $request->nohp;
        $refund->nominal = $pesan->total;
        $refund->alasan = $request->alasan;
        $refund->save();

        return response()->json([
            'status' => 1,
            'message' => 'Refund Berhasil'
        ]);
    }
}

/*            $refund = DB::table('refund')->selectRaw("nominal, tanggal, 'refund' as dari, 'plus' as tipe")
                ->where('id_customer', Auth::user()->customer->id);

        $deposit = DB::table('deposit')->selectRaw("nominal, tanggal, 'deposit' as dari, 'plus' as tipe")
            ->where('id_customer', Auth::user()->customer->id);

        $transaksi = DB::table('transaksi')->selectRaw("total as nominal, tanggal, 'transaksi' as dari, 'min' as tipe")
            ->where('id_customer', Auth::user()->customer->id)
            ->whereNotIn('status', [1,5]);
        return $query = $refund->union($deposit)->union($transaksi)->orderBy('tanggal', 'desc')->get();
        $querySql = $query->toSql();
        return DB::table( DB::raw( "( $querySql ) AS history") )->mergeBindings($query)
            ->orderBy('tanggal', 'desc')->get();

        $history = $refund->union($deposit)->union($transaksi)->orderBy('tanggal', 'desc')
            ->get()
            ->makeHidden(['status_info', 'nomor_meja', 'total_rupiah', 'selisih_tanggal']);*/
