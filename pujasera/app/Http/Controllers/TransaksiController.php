<?php

namespace App\Http\Controllers;

use App\Refund;
use App\Transaksi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transactions = Transaksi::query();

        if (!$request->has('date'))
            $request->merge(['date' => date('Y-m-d')]);

            $date = date('Y-m-d', strtotime($request->get('date')));

        $transactions = $transactions->whereDate('tanggal', $date);

        if ($request->has('status'))
        {
            $status = $request->get('status');
            if($status != 0)
                $transactions = $transactions->where('status', $status);
        }

    /*    if($request->has('search')){
            $search = $request->get('search');
            $key = '%' . $search . '%';
            $transactions = $transactions->where(function ($q) use ($key){
                $q->whereHas('customer', function ($qc) use ($key){
                    ....
                });
            });
        }*/

        $transactions = $transactions->orderBy('tanggal','asc')->paginate(12);
        $transactions->appends($request->query());

        $ddlStatus = Transaksi::getDdlStatus(true);
        $filter = $request->all();
        return view('transaksi.index', compact('transactions', 'filter', 'ddlStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction =  Transaksi::findOrFail($id);
        return view('transaksi.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $transaction =  Transaksi::findOrFail($id);
        if ($transaction->status == 2 || $transaction->status == 3){
            DB::beginTransaction();
            try {
                $pesans = $transaction->pemesanan()->where('status', 2)->orWhere('status', 3);

                $pesan_refunds = $pesans->get();

                foreach ($pesan_refunds as $pesan) {
                    $refund = new Refund();
                    $refund->id_stan = $pesan->hidangan->stan->id;
                    $refund->id_customer = $transaction->customer->id;
                    $refund->nohp = $transaction->customer->nohp;
                    $refund->nominal = $pesan->total;
                    $refund->alasan = 'Pembatalan Pesanan Dari Admin';
                    $refund->save();
                }

                $total_refund = $pesans->sum('total');
                $pesans->update(['status' => 5]);

                $cust = $transaction->customer;
                $cust->saldo += $total_refund;
                $cust->save();

                $transaction->pemesanan()->update(['status' => 5]);
                $transaction->update(['status' => 5]);

                DB::commit();

                return back()->with('success',
                    'Transaksi Berhasil Dibatalkan dan Pembayaran Customer Telah Dikembalikan');

            }catch (QueryException $exception){
                DB::rollBack();

                return back()->with('danger', $exception);
            }
        }else{
            return back()->with('warning', 'Transaksi tidak dapat dibatalkan karena status '.$transaction->status_info);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
