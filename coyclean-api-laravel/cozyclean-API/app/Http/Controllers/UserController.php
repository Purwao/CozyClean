<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            //ambil semua data di tabel user
            $data = User::all();
            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error has occured' . $e]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)//ini juga handle register
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = User::findOrFail($id);
            return response()->json($data, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error has occured']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request) {}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            //validasi
            $validatedData = Validator::make($request->all(), [
                'name' => 'nullable',
                'address' => 'nullable',
                'telephone' => 'nullable',
                'role' => 'nullable|numeric',
                'email' => 'nullable|email',
                'password' => 'nullable',
            ]);

            if ($validatedData->fails()) {
                return response()->json(['message' => 'validation errors', 'errors' => $validatedData->errors()]);
            } else {
                //create password yang mau di hash
                $hashedPassword = Hash::make($request['password']);

                //cari User pake idnya
                $data = User::findOrFail($id);


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
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error has occured']);
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
        try {
            //cari menggunakan id, lalu di delete
            $data = User::findOrFail($id);
            $data->delete();

            return response()->json(['message' => 'data has been deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error has occured']);
        }
    }

    public function login(Request $request)
    {
        try {
            //validasi
            $validatedData = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            //cek validasi
            if ($validatedData->fails()) {
                return response()->json(
                    [
                        'message' => 'Validation Errors',
                        'errors' => $validatedData->errors()
                    ],
                    422
                );
            } else {
                $email = $request->input('email');

                //cari data user
                $data = User::where('email', $email)->first();

                if ($data) { 
                    //cek apa passwordnya benar
                    if (Hash::check($request->input('password'),$data->password)) {
                        //password benar
                        return response()->json([
                            'message'=>'login Success',
                            'data' => $data
                        ], 200);
                    } else {
                        //password salah
                        return response()->json([
                            'message' => 'password salah'
                        ], 422);  
                    }
                } else {
                    //user not found
                    return response()->json(['message' => 'User not found'], 404);
                }
            }
            //catch error yang tidak diinginkan ciakh
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error has occured']);
        }
    }
    public function register(Request $request){
        try {
            $validatedData = Validator::make($request->all(), [
                'name' => 'required',
                'address' => 'required',
                'telephone' => 'required|numeric',
                'role' => 'required|numeric',
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validatedData->fails()) {
                return response()->json(['message' => 'validation errors', 'errors' => $validatedData->errors()]);
            } else {
                //create password yang mau di hash
                $hashedPassword = Hash::make($request['password']);

                //create datanya
                $data = User::create([
                    'name' => $request['name'],
                    'address' => $request['address'],
                    'telephone' => $request['telephone'],
                    'role' => $request['role'],
                    'email' => $request['email'],
                    'password' => $hashedPassword,
                ]);

                return response()->json(['message' => 'Data has been created', 'data' => $data]);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'An error has occured' . $e . '']);
        }
    }
}
