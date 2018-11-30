<?php

namespace App\Http\Controllers;

use App\Meja;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tables = Meja::query();

        if ($request->has('status'))
        {
            $status = $request->get('status');
            if($status != 0)
                $tables = $tables->where('status', $status);
        }

        if($request->has('search')){
            $search = $request->get('search');
            $key = '%' . $search . '%';
            $tables= $tables->where('nomor','like',$key);
        }

        $tables = $tables->orderBy('nomor','asc')->paginate(12);
        $tables->appends($request->query());

        $ddlStatus = Meja::getDdlStatus(true);
        $filter = $request->all();
        return view('meja.index', compact('tables', 'filter', 'ddlStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ddlStatus = Meja::getDdlStatus();
        return view('meja.create', compact('ddlStatus'));
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
            'nomor' => 'required|numeric|digits_between:1,4|unique:meja',
            'status' => 'required|numeric|in:1,2,3',
        ]);

        Meja::create($request->all());

        return response()->json([
            'status' => 1,
            'message' => 'Meja berhasil ditambahkan',
            'redirect' => route('admin.meja.index')
        ]);
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
        $table = Meja::findOrFail($id);
        $ddlStatus = Meja::getDdlStatus();
        return view('meja.edit', compact('ddlStatus', 'table'));
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
            'nomor' => 'required|numeric|digits_between:1,4|unique:meja,nomor,'.$id,
            'status' => 'required|numeric|in:1,2,3',
        ]);

        Meja::findOrFail($id)->update($request->all());

        return response()->json([
            'status' => 1,
            'message' => 'Meja berhasil diubah',
            'redirect' => route('admin.meja.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Meja::findOrFail($id)->delete();
        return back()->with('success', 'Meja Berhasil Dihapus');
    }
}
