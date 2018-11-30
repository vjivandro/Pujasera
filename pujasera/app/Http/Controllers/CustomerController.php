<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Deposit;
use App\Refund;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('search')){
            $search = $request->get('search');
            $key = '%' . $search . '%';
            $customers = Customer::where('nama','like',$key)
                ->orWhere('nohp','like',$key)
                ->orWhere('email','like',$key)
                ->orderBy('id','desc')->paginate(8);
        }else{
            $customers = Customer::orderBy('id','desc')->paginate(8);
            $search = "";
        }

        $customers->appends($request->query());
        return view('customer.index', compact('customers', 'search'));
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
        $customer = Customer::find($id);
        return view('customer.show', compact('customer'));
    }

    public function deposit($id)
    {
        $deposits = Deposit::where('id_customer', $id)->paginate(8);
        return view('customer.deposit', compact('deposits'));
    }

    public function refund($id)
    {
        $refund = Refund::where('id_customer', $id)->paginate(8);
        return view('customer.deposit', compact('deposits'));
    }

    public function transaksi()
    {

    }

    public function pemesanan()
    {

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
