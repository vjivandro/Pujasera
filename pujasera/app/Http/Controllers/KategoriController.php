<?php

namespace App\Http\Controllers;

use App\Kategori;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
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
            $categories = Kategori::where('nama','like',$key)
                ->orderBy('id','desc')->paginate(12);
        }else{
            $categories = Kategori::orderBy('id','desc')->paginate(12);
            $search = "";
        }

        $categories->appends($request->query());
        return view('kategori.index', compact('categories', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:30|unique:kategori',
        ]);

        DB::beginTransaction();
        try {
            Kategori::create($request->all());
            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Kategori berhasil Didaftarkan',
                'redirect' => route('admin.kategori.index')
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
        $category = Kategori::find($id);
        return view('kategori.edit',compact('category'));
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
        $request->validate([
            'nama' => 'required|string|max:30|unique:kategori,nama,'.$id,
        ]);

        DB::beginTransaction();
        try {
            Kategori::find($id)->update($request->all());
            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Kategori berhasil diubah',
                'redirect' => route('admin.kategori.index')
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
        Kategori::find($id)->delete();
        return back()->with('success', 'Kategori Berhasil Dihapus');
    }
}
