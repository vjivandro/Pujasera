<?php

namespace App\Http\Controllers;

use App\Stan;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StanController extends Controller
{
    public function __construct()
    {
       // $this->middleware('permission:1')->only(['create', 'store', 'createData']);
    }

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
            $stans = Stan::where('nama','like',$key)
                ->orWhereHas('user', function ($q) use ($key){
                    $q->where('username','like',$key);
                })->orderBy('id','desc')->paginate(8);
        }else{
            $stans = Stan::orderBy('id','desc')->paginate(8);
            $search = "";
        }

        $stans->appends($request->query());
        return view('stan.index', compact('stans', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('stan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->merge(['saldo' => str_replace('.','',$request->input('saldo'))]);
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'saldo' => 'required|numeric|digits_between:3,9',
            'foto' => 'required|image|mimes:jpeg,jpg,png',
        ]);
        $data = $request->all();

        DB::beginTransaction();
        try {
            $data['role'] = 2;
            $data['api_token'] = bcrypt(date('Ymd').$data['username']);
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);
            $data['foto'] = uniqid($user->id) . '.' . $request->foto->getClientOriginalExtension();

            $user->stan()->create($data);

            reziseImage($request->foto, 'stan', $data['foto']);
            //$request->foto->storeAs('public/foto/stan', $data['foto']);
            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Stan berhasil Didaftarkan',
                'redirect' => route('admin.stan.index')
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
        $stan = Stan::find($id);
        return view('stan.edit',compact('stan'));
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

        $request->merge(['saldo' => str_replace('.','',$request->input('saldo'))]);
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$id,
            'saldo' => 'required|numeric|digits_between:3,9',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png',
        ]);
        $data = $request->all();

        $stan = Stan::findOrFail($id);
        $user = User::find($stan->id_user);
        DB::beginTransaction();
        try {
            $data['api_token'] = bcrypt(date('Ymd').$data['username']);
            if ($request->hasFile('foto')) {
                Storage::delete('public/foto/stan/'. $stan->foto);
                $data['foto'] = uniqid($user->id) . '.' . $request->foto->getClientOriginalExtension();

                reziseImage($request->foto, 'stan', $data['foto']);
            }else{
                $data['foto'] = $stan->foto;
            }

            $stan->update($data);
            $user->update($data);
            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Stan berhasil Diubah',
                'redirect' => route('admin.stan.index')
            ]);
        }catch (QueryException $exception){
            DB::rollBack();

            return response()->json([
                'status' => 2,
                'message' => $exception
            ],500);
        }
    }

    public function editPassword($id)
    {
        $stan = Stan::findOrFail($id);
        return view('stan.edit-password ',compact('stan'));
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);
        $user = Stan::findOrFail($id)->user()->first();

        DB::beginTransaction();
        try {

            $request->merge(['password' => bcrypt($request->input('password'))]);
            $user->update($request->all());
            DB::commit();

            return response()->json([
                'status' => 1,
                'message' => 'Password Stan berhasil Diubah',
                'redirect' => route('admin.stan.index')
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
        $user = Stan::findOrFail($id)->user()->first();
        Storage::delete('public/foto/stan/'.$user->stan->foto);
        $user->delete();

        return back()->with('success', 'Stan Berhasil Dihapus');
    }
}
