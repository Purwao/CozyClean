<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            //ambil semua data yang ada
            $data = Type::all();
            return response()->json($data);
        } catch (Exception $e) {
            //catch error
            return response()->json(['message' => 'An error has occured']);
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
    public function store(Request $request)
    {
        try {
            //validate request
            $validatedData = Validator::make($request->all(), [
                'image' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'types_name' => 'required',
            ]);

            if ($validatedData->fails()) {
                //pesan errors
                return response()->json(['message' => 'validation errors', 'errors' => $validatedData->errors()]);
            } else {
                //naruh file + penamaan file 
                $file = $request->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('image', $fileName, 'public');

                //deklarasi types_name
                $types_name = $request['types_name'];

                //Create Type
                $data = Type::create(['types_name' => $types_name, 'image' => $filePath]);

                return response()->json($data);
            }
        } catch (Exception $e) {
            //catch errors
            return response()->json(['message' => 'An error has occured']);
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
        try {
            //cari data berdasarkan id
            $data = Type::findOrFail($id);
            return response()->json($data);
        } catch (ModelNotFoundException $e) {
            //catch error (model)
            return response()->json(['message' => 'Order not found'], 404);
        } catch (Exception $e) {
            //catch error  (general)
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
            $validatedData = Validator::make($request->all(), ([
                'image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'types_name' => 'required',
            ]));

            //cek gagal atau tidak
            if ($validatedData->fails()) {
                return response()->json(['message' => 'errors', 'error' => $validatedData->errors()]);
            } else {
                $data = Type::findOrFail($id);
                //Cek ada image baru atau tidak
                if ($request->hasFile('image')) {
                    //Kalau ada, di delete dulu
                    if ($data->image && Storage::disk('public')->exists($data->image)) {
                        Storage::disk('public')->delete($data->image);
                    }
                    //proses create path image baru
                    $file = $request->file('image');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('image', $fileName, 'public');
                    //create data dan image baru
                    $data->update([
                        'types_name' => $request['types_name'],
                        'image' => $filePath
                    ]);
                } else {
                    //kalau tidak ada, ya cuma update types_name
                    $data->update([
                        'types_name' => $request['types_name']
                    ]);
                }
                return response()->json($data);
            }
        } catch (ModelNotFoundException $e) {
            //jika id tidak ditemukan, akan mengembalikan pesan service not found
            return response()->json([
                'message' => 'The type is not found',
            ]);
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
            //cari berdasarkan id
            $data = Type::findOrFail($id);
            //delete image yang berkaitan di laravel
            if ($data->image && Storage::disk('public')->exists($data->image)) {
                Storage::disk('public')->delete($data->image);
            }
            //delete & pesan berhasil
            $data->delete();
            return response()->json(['message' => 'deleted Successfully', 'data' => $data]);
        } catch (ModelNotFoundException $e) {
            //catch error(model)
            return response()->json(['message' => 'Type Not Found']);
        } catch (Exception $e) {
            //catch error (general)
            return response()->json(['message' => 'An error has occured']);
        }
    }
}
