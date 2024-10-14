<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            //ambil semua data
            $data = Service::all();
            if ($data->isEmpty()) {
                return response()->json([
                    'message' => 'There is no data available'
                ], 404); // Mengembalikan status kode 404 (Not Found)
            } else {
                return response()->json([
                    'message' => 'data is available',
                    'data' => $data
                ]);
            }
        } catch (Exception $e) {
            //catch error
            return response()->json(['message' => 'error']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            //validasi
            $validatedData = Validator::make($request->all(), ([
                'service' => 'required',
                'price' => 'required|numeric',
                'description' => 'required'
            ]));

            //cek gagal atau tidak
            if ($validatedData->fails()) {
                // pesan error
                return response()->json([
                    'message' => 'Validation Error',
                    'errors' => $validatedData->errors()
                ], 422); // 422 Unprocessable Entity status code
            } else {
                //create
                $data = Service::create([
                    'service' => $request['service'],
                    'price' => $request['price'],
                    'description' => $request['description'],
                ]);
                return response()->json($data);
            }
        } catch (Exception $e) {
            // Jika terjadi kesalahan, ya gitu
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = Service::findOrFail($id);
            return response()->json($data);
        } catch (ModelNotFoundException $e) {
            //jika id tidak ditemukan, akan mengembalikan pesan service not found
            return response()->json([
                'message' => 'The services is not found',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            //validasi
            $validatedData = Validator::make($request->all(), ([
                'service' => 'required',
                'price' => 'required|numeric',
                'description' => 'required'
            ]));

            if ($validatedData->fails()) {
                // pesan error
                return response()->json([
                    'message' => 'Validation Error',
                    'errors' => $validatedData->errors()
                ], 422); // 422 Unprocessable Entity status code
            } else {
                //update dengan data baru
                $data = Service::findOrFail($id);
                $data->update([
                    'service' => $request['service'],
                    'price' => $request['price'],
                    'description' => $request['description'],
                ]);

                return response()->json($data);
            }
        } catch (ModelNotFoundException $e) {
            //jika id tidak ditemukan, akan mengembalikan pesan service not found
            return response()->json([
                'message' => 'The services is not found',
            ]);
        } catch (Exception $e) {
            // Jika terjadi kesalahan, ya gitu
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = Service::findOrFail($id);
            $data->delete();

            return response()->json(['message' => 'data has been deleted', 'data' => $data]);
        } catch (ModelNotFoundException $e) {
            //jika id tidak ditemukan, akan mengembalikan pesan service not found
            return response()->json([
                'message' => 'The services is not found',
            ]);
        } catch (Exception $e) {
            // Jika terjadi kesalahan, ya gitu
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
}
