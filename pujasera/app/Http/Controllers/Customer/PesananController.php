<?php

namespace App\Http\Controllers\Customer;

use App\Hidangan;
use App\Kategori;
use App\Meja;
use App\Pemesanan;
use App\Transaksi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function dashboard($id_kategori = 0, $search = "")
    {
        if ($id_kategori == 0) $id_kategori = Kategori::first()->id;

        $key = '%' . $search . '%';
        $foods = Hidangan::where('id_kategori', $id_kategori)
            ->where('nama','like',$key)
            ->where('stock', 2)
            ->orderBy('id','desc')->get();

        $ddlKategori = Kategori::getDdl();

        $cart = Pemesanan::where('status', 1)
            ->whereHas('transaksi', function ($q){
                $q->where('id_customer', Auth::user()->customer->id);
            })->count();

        return response()->json([ compact('foods', 'ddlKategori', 'cart', 'search', 'id_kategori') ]);
    }

    public function pesan(Request $request)
    {
        $request->validate([
           'jumlah' => 'required|numeric|digits_between:1,4',
           'id_hidangan' => 'required|numeric|exists:hidangan,id'
        ]);

        $jumlah = $request->post('jumlah');
        $id_hidangan = $request->post('id_hidangan');
        $id_customer = Auth::user()->customer->id;

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::where('status', 1)
                ->where('id_customer', $id_customer)->first();

            if (empty($transaksi)) {
                $transaksi = new Transaksi();
                $transaksi->id_customer = $id_customer;
                $transaksi->save();
            }

            $harga_hidangan = Hidangan::findOrFail($id_hidangan)->harga;

            $pesanan = $transaksi->pemesanan()
                ->where('id_hidangan', $id_hidangan)
                ->where('status', 1)->first();

            if (empty($pesanan)) {
                $transaksi->pemesanan()->create([
                    'jumlah' => $jumlah,
                    'total' => $jumlah * $harga_hidangan,
                    'id_hidangan' => $id_hidangan
                ]);
            } else {
                $pesanan->jumlah += $jumlah;
                $pesanan->total = $pesanan->jumlah * $harga_hidangan;
                $pesanan->save();
            }

            $transaksi->total = $transaksi->pemesanan()->sum('total');
            $transaksi->save();

            $cart = Pemesanan::where('status', 1)
                ->whereHas('transaksi', function ($q){
                    $q->where('id_customer', Auth::user()->customer->id);
                })->count();

            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Pesanan ditambahkan dalam cart',
                'cart' => $cart
            ]);

        }catch (QueryException $exception){
            DB::rollBack();

            return response()->json([
                'status' => 2,
                'message' => $exception
            ],500);
        }

    }

    public function cart()
    {
        $transaksi = Transaksi::where('id_customer', Auth::user()->customer->id)
            ->where('status', 1)->first();

        $data = [
            'ddlMeja' => Meja::getDdl(),
            'saldo' => Auth::user()->customer->saldo
            ];

        if(!$transaksi || $transaksi->pemesanan->count() <= 0)
            return response()->json( [
                'status' => 2,
                'message' => 'Tidak Ada Pesanan Dalam Cart, Silahkan Pesan Hidangan',
                'transaksi' => $transaksi,
                'cart' => [],
            ] + $data,412);

        return response()->json([
            'status' => 1,
            'transaksi' => $transaksi->makeHidden(['pemesanan', 'meja']),
            'cart' => $transaksi->pemesanan,
        ] + $data);

    }

    public function cartDelete($id_pesanan)
    {
        $transaksi = Transaksi::where('id_customer', Auth::user()->customer->id)
            ->where('status', 1)->firstOrFail();

        $pesan = $transaksi->pemesanan()->where('id', $id_pesanan);

        if (!$pesan->first())
            return response()->json([
                'status' => 2,
                'message' => 'Pesanan tidak tersedia di cart',
            ],412);

        $pesan->delete();

        return response()->json([
            'status' => 1,
            'message' => 'Cart berhasil dihapus',
        ]);

    }

    public function cartAmount(Request $request)
    {
        $transaksi = Transaksi::where('id_customer', Auth::user()->customer->id)
            ->where('status', 1)->firstOrFail();

        $request->validate([
            'jumlah' => 'required|numeric|digits_between:1,4',
            'id_pemesanan' => 'required|numeric|exists:pemesanan,id,id_transaksi,'.$transaksi->id
        ],[
            'id_pemesanan.exists' => 'Pesanan tidak tersedia di cart',
        ]);

        $pesan = $transaksi->pemesanan()->where('id', $request->id_pemesanan)->first();
        $pesan->jumlah += $request->jumlah;
        $pesan->total = $pesan->jumlah * $pesan->hidangan->harga;
        $pesan->save();

        return response()->json([
            'status' => 1,
            'message' => 'Jumlah Pesanan Diubah',
        ]);
    }

    public function beli($id_meja)
    {
        $transaksi = Transaksi::where('id_customer', Auth::user()->customer->id)
            ->where('status', 1)->first();

        if(!$transaksi || $transaksi->pemesanan->count() <= 0)
            return response()->json([
                'status' => 2,
                'message' => 'Tidak Ada Pesanan Dalam Cart, Silahkan Pesan Terlebih Dahulu'
            ],412); //412 Server tidak memenuhi salah satu prasyarat bahwa pemohon memakai permintaan tersebut
        //422 Permintaan tersebut adalah "well-formed" tetapi tidak dapat diikuti karena kesalahan semantik.

        if ($transaksi->customer->saldo < $transaksi->total)
            return response()->json([
                'status' => 2,
                'message' => 'Saldo anda tidak cukup, silahkan tambah saldo anda'
            ],412);

        $transaksiPrevious = Transaksi::whereNotIn('status',[1,5])
            ->where('id_meja', $id_meja)
            ->orderBy('tanggal','desc')->first();

        if( !$transaksiPrevious || ($transaksiPrevious->id_customer != Auth::user()->customer->id) ){
            $meja = Meja::where('id', $id_meja)->where('status', 1)->first();
            if(!$meja)
                return response()->json([
                    'status' => 2,
                    'message' => 'Mohon Maaf, meja ini sedang terpakai'
                ],412);
        }else
            $meja = Meja::where('id', $id_meja)->first();

        DB::beginTransaction();
        try{
            $cust = $transaksi->customer;
            $cust->saldo -= $transaksi->total;
            $cust->save();

            $meja->status = 2;
            $meja->save();

            $transaksi->status = 2;
            $transaksi->id_meja = $meja->id;
            $transaksi->tanggal = sekarang();
            $transaksi->save();

            $transaksi->pemesanan()->update([
                'status' => 2,
                'tanggal' => sekarang()
                ]);

            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Terimakasih, pesanan anda akan kami proses'
            ]);
        }catch (QueryException $exception){

            DB::rollBack();
            return response()->json([
                'status' => 2,
                'message' => $exception
            ],500);
        }
    }

    public function beliAlt(Request $request)
    {
        $transaksi = Transaksi::where('id_customer', Auth::user()->customer->id)
            ->where('status', 1)->first();

        if(!$transaksi || $transaksi->pemesanan->count() <= 0)
            return response()->json([
                'status' => 2,
                'message' => 'Tidak Ada Pesanan Dalam Cart, Silahkan Pesan Terlebih Dahulu'
            ],412);

        $transaksiPrevious = Transaksi::whereNotIn('status',[1,5])
            ->where('id_meja', $request->meja)
            ->orderBy('tanggal','desc')->first();

        if( !$transaksiPrevious || ($transaksiPrevious->id_customer != Auth::user()->customer->id) )
            $exist_meja = 'exists:meja,id,status,1';
        else
            $exist_meja = 'exists:meja,id';

        $request->validate([
            'meja' => 'required|numeric|'.$exist_meja,
            'pesanan' =>  "required|array",
            'pesanan.*' => 'required|numeric|exists:pemesanan,id,id_transaksi,'.$transaksi->id,
        /*    'jumlah' =>  "required|array",
            'jumlah.*' => 'required|numeric|digits_between:1,4',*/
        ],[
            'meja.exists' => 'Mohon Maaf, meja ini sedang terpakai',
            'pesanan.*.exists' => 'Pesanan tidak ada pada cart',
            'pesanan.required' => 'Tidak ada pesanan yang dipilih'
        ]);

        DB::beginTransaction();
        try{
            $pesanan = $transaksi->pemesanan()->whereIn('id', $request->pesanan);
            $pembayaran = $pesanan->sum('total');
            $cust = $transaksi->customer;
            if($cust->saldo < $pembayaran)
                return response()->json([
                    'status' => 2,
                    'message' => 'Saldo anda tidak cukup, silahkan tambah saldo anda'
                ],412);

            $cust->saldo -= $pembayaran;
            $cust->save();

            $meja = Meja::find($request->meja);
            $meja->status = 2;
            $meja->save();

            $newTransaksi = new Transaksi();
            $newTransaksi->id_meja = $meja->id;
            $newTransaksi->id_customer = $cust->id;
            $newTransaksi->status = 2;
            $newTransaksi->total = $pembayaran;
            $newTransaksi->save();

            $pesanan->update([
                'status' => 2,
                'id_transaksi' => $newTransaksi->id,
                'tanggal' => sekarang()
                ]);

            $transaksi->total = $transaksi->pemesanan()->sum('total');
            $transaksi->save();

            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Terimakasih, pesanan anda akan kami proses'
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
