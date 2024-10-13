<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=Service::all();
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
            'service' => 'required', 
            'price'=>'required|numeric',
            'description'=>'required'
         
        ]);

        //create
        $data=Service::create([
            'service' =>$request['service'], 
            'price'=>$request['price'],
            'description'=>$request['description'],
        ]);

        return response()->json($data);

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
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data=Service::findOrFail($id);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //validasi
        $request->validate([
            'service' => 'required', 
            'price'=>'required|numeric',
            'description'=>'required'
        ]);

        //update dengan data baru
        $data=Service::findOrFail($id);
        $data->update([
            'service' =>$request['service'], 
            'price'=>$request['price'],
            'description'=>$request['description'],
        ]);
       
        return response()->json($data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data=Service::findOrFail($id);
        $data->delete();

        return response()->json(['message'=>'data has been deleted', 'data'=>$data]);
    }
}
