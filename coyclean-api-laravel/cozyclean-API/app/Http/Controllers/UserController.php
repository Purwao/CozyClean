<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //ambil semua data di tabel user
        $data= User::all();
        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //validasi
        $request->validate([
            'name' => 'required',
            'address'=>'required',
            'telephone' => 'required',
            'role'=>'required|numeric',
            'email'=>'required',
            'password'=>'required',
        ]);

        //create password yang mau di hash
        $hashedPassword=Hash::make($request['password']);

        //create datanya
        $data=User::create([
            'name' => $request['name'],
            'address'=> $request['address'],
            'telephone' => $request['telephone'],
            'role'=>$request['role'],
            'email'=>$request['email'],
            'password'=>$hashedPassword,
        ]);

        return response()->json(['message'=>'Data has been inserted','data'=>$data]);
        
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
        $data= User::findOrFail($id);
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        //validasi
        $request->validate([
            'name' => 'nullable',
            'address'=>'nullable',
            'telephone' => 'nullable',
            'role'=>'nullable|numeric',
            'email'=>'nullable',
            'password'=>'nullable',
        ]);

        //create password yang mau di hash
        $hashedPassword=Hash::make($request['password']);

        //cari User pake idnya
        $data=User::findOrFail($id);


        //mengupdate dengan data yang ada
        if ($request->filled('name')) {
            $data->name = $request->input('name');
        }
        if ($request->filled('address')) {
            $data->address = $request->input('address');
        }
        if ($request->filled('telephone')) {
            $data->telephone = $request->input('telephone');
        }
        if ($request->filled('role')) {
            $data->role = $request->input('role');
        }
        if ($request->filled('email')) {
            $data->email = $request->input('email');
        }
        if ($request->filled('password')) {
            $data->password = $hashedPassword;
        }

        //save datanya
        $data->save();

        return response()->json($data, 200);
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
        //cari menggunakan id, lalu di delete
        $data=User::findOrFail($id);
        $data->delete();

        return response()->json(['message'=>'data has been deleted successfully'], 200);
    }
}
