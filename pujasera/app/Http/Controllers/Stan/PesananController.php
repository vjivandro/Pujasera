<?php

namespace App\Http\Controllers\Stan;

use App\Pemesanan;
use App\Stan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PesananController extends Controller
{
    public function index($nbulan = 0, $ntahun = 0)
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

        $pesanan = Pemesanan::where('status', '<>', 1)
            ->whereMonth('tanggal', $nbulan)
            ->whereYear('tanggal', $ntahun)
            ->whereHas('hidangan', function ($q){
                $q->where('id_stan', Auth::user()->stan->id);
            })->get()
            ->each->append(['nomor_meja', 'nama_customer', 'hidangan_detail'])
            ->makeHidden(['transaksi','hidangan','id_transaksi']);

        return response()->json([
            'status' => 1,
            'pesanan' => $pesanan,
            'bulan' => bulan(),
            'tahun' => tahun()
        ]);
    }

    public function current()
    {
        $pesanan = Pemesanan::where('status', '<>', 1)
            ->whereDate('tanggal', hariIni())
            ->whereHas('hidangan', function ($q){
                $q->where('id_stan', Auth::user()->stan->id);
            })->orderBy('status', 'asc')->orderBy('tanggal', 'asc')->get()
            ->each->append(['nomor_meja', 'nama_customer', 'hidangan_detail'])
            ->makeHidden(['transaksi','hidangan','id_transaksi']);

        return response()->json([
            'status' => 1,
            'pesanan' => $pesanan
        ]);
    }

    public function konfirmasi($id_pesanan)
    {
        $pesanan = Pemesanan::findOrFail($id_pesanan);

        DB::beginTransaction();

        try{

            if($pesanan->status == 2)
                $pesanan->status = 3;
            elseif ($pesanan->status == 3){
                $stan = Stan::find(Auth::user()->stan->id);
                $stan->saldo += $pesanan->total;
                $stan->save();

                $pesanan->status = 4;
            }

            else
                return response()->json([
                    'status' => 2,
                    'message' => 'Pesanan ini tidak dapat dikonfirmasi'
                ],412);

            $pesanan->tanggal = sekarang();
            $pesanan->save();

            $transaksi = $pesanan->transaksi;
            $allPemesanan = $transaksi->pemesanan()->whereIn('status', [2,3])->get();
            if($allPemesanan->isEmpty())
                $transaksi->status = 4;
            else
                $transaksi->status = 3;

            $transaksi->tanggal = sekarang();
            $transaksi->save();

            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Transaksi telah dikonfirmasi, status pesanan '.$pesanan->status_info
            ]);

        }catch (QueryException $exception){
            DB::rollBack();

            return response()->json([
                'status' => 2,
                'message' => $exception
            ],500);
        }
    }
}
