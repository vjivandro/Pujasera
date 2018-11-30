<?php

namespace App\Http\Controllers;

use App\Hidangan;
use App\Kategori;
use App\Stan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HidanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $foods = Hidangan::query();

       if ($request->has('id_stan'))
       {
           $id_stan = $request->get('id_stan');
           if($id_stan != 0)
               $foods = $foods->where('id_stan', $id_stan);
       }
       if ($request->has('id_kategori')){
           $id_kategori = $request->get('id_kategori');
           if($id_kategori != 0)
               $foods = $foods->where('id_kategori', $id_kategori);
       }
        if($request->has('search')){
            $search = $request->get('search');
            $key = '%' . $search . '%';
            $foods = $foods->where(function($q) use ($key){
                $q->where('nama','like',$key)
                    ->orWhere('harga','like',$key);
            });
        }

        $foods = $foods->orderBy('id','desc')->paginate(12);
        $foods->appends($request->query());

        $ddlKategori = Kategori::getDdl(true);
        $ddlStan = Stan::getDdl(true);
        $filter = $request->all();
        return view('hidangan.index', compact('foods', 'filter','ddlKategori','ddlStan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ddlKategori = Kategori::getDdl();
        $ddlStan = Stan::getDdl();
        $ddlStock = Hidangan::getDdlStock();
        return view('hidangan.create', compact('ddlStan', 'ddlKategori','ddlStock'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge(['harga' => str_replace('.','',$request->input('harga'))]);
        $request->validate([
            'nama' => 'required|string|max:30',
            'stock' => 'required|numeric|in:1,2',
            'harga' => 'required|numeric|digits_between:1,9',
            'id_stan' => 'required|numeric|exists:stan,id',
            'id_kategori' => 'required|numeric|exists:kategori,id',
            'foto' => 'required|image|mimes:jpeg,jpg,png'
        ]);
        $data = $request->all();

        DB::beginTransaction();
        try {
            $data['foto'] = uniqid(date('YmdHis')) . '.' . $request->foto->getClientOriginalExtension();
            Hidangan::create($data);
            reziseImage($request->foto, 'hidangan', $data['foto']);

            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Hidangkan berhasil dibuat',
                'redirect' => route('admin.hidangan.index')
            ]);
        }catch (QueryException $exception){
            DB::rollBack();

            return response()->json([
                'status' => 2,
                'message' => $exception
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $food = Hidangan::find($id);
        $ddlKategori = Kategori::getDdl();
        $ddlStan = Stan::getDdl();
        $ddlStock = Hidangan::getDdlStock();
        return view('hidangan.edit', compact('ddlStan', 'ddlKategori', 'food','ddlStock'));
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
        $request->merge(['harga' => str_replace('.','',$request->input('harga'))]);
        $request->validate([
            'nama' => 'required|string|max:30',
            'stock' => 'required|numeric|in:1,2',
            'harga' => 'required|numeric|digits_between:1,9',
            'id_stan' => 'required|numeric|exists:stan,id',
            'id_kategori' => 'required|numeric|exists:kategori,id',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png'
        ]);
        $data = $request->all();

        DB::beginTransaction();
        try {
            $hidangan = Hidangan::findOrFail($id);
            if ($request->hasFile('foto')) {
                Storage::delete('public/foto/hidangan/'. $hidangan->foto);
                $data['foto'] = uniqid(date('YmdHis')) . '.' . $request->foto->getClientOriginalExtension();

                reziseImage($request->foto, 'hidangan', $data['foto']);
            }else{
                $data['foto'] = $hidangan->foto;
            }

            $hidangan->update($data);

            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Hidangkan berhasil diubah',
                'redirect' => route('admin.hidangan.index')
            ]);
        }catch (QueryException $exception){
            DB::rollBack();

            return response()->json([
                'status' => 2,
                'message' => $exception
            ],500);
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
        $hidangan = Hidangan::findOrFail($id);
        Storage::delete('public/foto/hidangan/'.$hidangan->foto);
        $hidangan->delete();

        return back()->with('success', 'Hidangan Berhasil Dihapus');
    }
}
