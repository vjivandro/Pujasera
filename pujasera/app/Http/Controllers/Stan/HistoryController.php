<?php

namespace App\Http\Controllers\Stan;

use App\Customer;
use App\Deposit;
use App\Hidangan;
use App\Kategori;
use App\Pemesanan;
use App\Refund;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HistoryController extends Controller
{
    public function index($tipe = 'pesanan', $nbulan = 0, $ntahun = 0)
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

        if ($tipe == 'refund'){
            $history = Refund::where('id_stan', Auth::user()->stan->id)
                ->whereMonth('tanggal', $nbulan)
                ->whereYear('tanggal', $ntahun)
                ->orderBy('tanggal','desc')->get();
        }elseif ($tipe == 'deposit'){
            $history = Deposit::where('id_stan', Auth::user()->stan->id)
                ->whereMonth('tanggal', $nbulan)
                ->whereYear('tanggal', $ntahun)
                ->orderBy('tanggal','desc')->get();
        }else{
            $history = Pemesanan::where('status', 4)
                ->whereMonth('tanggal', $nbulan)
                ->whereYear('tanggal', $ntahun)
                ->whereHas('hidangan', function ($q){
                    $q->where('id_stan', Auth::user()->stan->id);
                })->orderBy('tanggal','desc')->get();
        }
        return response()->json([
            'status' => 1,
            'history' => $history,
            'bulan' => bulan(),
            'tahun' => tahun()
        ]);
    }

    public function depositIndex()
    {
        return response()->json([
            'status' => 1,
            'customer' => Customer::getDdl()
        ]);
    }

    public function depositStore(Request $request)
    {
        $request->validate([
           'customer' => 'required|numeric|exists:customer,id',
            'nohp' => 'required|numeric|digits_between:9,15',
            'nominal' => 'required|numeric|digits_between:3,9',
        ]);

        DB::beginTransaction();

        try{
            $deposit = new Deposit();
            $deposit->id_stan = Auth::user()->stan->id;
            $deposit->id_customer = $request->customer;
            $deposit->nohp = $request->nohp;
            $deposit->nominal = $request->nominal;
            $deposit->save();

            $customer = Customer::find($deposit->id_customer);
            $customer->saldo += $deposit->nominal;
            $customer->save();

            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Deposit berhasil Ditambahkan',
            ]);
        }catch (QueryException $exception){
            DB::rollBack();

            return response()->json([
                'status' => 2,
                'message' => $exception
            ],500);
        }
    }

    public function hidangan($id_kategori = 0)
    {
        if ($id_kategori == 0) $id_kategori = Kategori::first()->id;

        $hidangan = Hidangan::where('id_stan', Auth::user()->stan->id)
            ->where('id_kategori', $id_kategori)->get();
        $ddlKategori = Kategori::getDdl();

        return response()->json([
            'status' => 1,
            'hidangan' => $hidangan,
            'ddlKategori' => $ddlKategori
        ]);
    }

    public function stock($id_hidangan)
    {
        $hidangan = Hidangan::findOrFail($id_hidangan);
        if ($hidangan->stock == 2)
            $hidangan->stock = 1;
        else
            $hidangan->stock = 2;

        $hidangan->save();

        return response()->json([
            'status' => 1,
            'message' => 'Stock hidangan diubah',
        ]);
    }
}
