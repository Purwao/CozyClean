<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data= Type::all();
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //validate request
        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,pdf|max:2048', 
            'types_name'=>'required',
        ]);

        //naruh file + penamaan file 
        $file=$request->file('image');
        $fileName=time() . '_' . $file->getClientOriginalName();
        $filePath=$file->storeAs('image',$fileName,'public');

        //deklarasi types_name
        $types_name= $request['types_name'];

        //Create Type
        $data=Type::create(['types_name'=>$types_name,'image'=>$filePath]);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data=Type::findOrFail($id);
        return response()->json($data);
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
        'image' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048', 
        'types_name' => 'required',
    ]);

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
        $data=Type::findOrFail($id);

        //delete image yang berkaitan di laravel
        if ($data->image && Storage::disk('public')->exists($data->image)) {
            Storage::disk('public')->delete($data->image);
        }

        $data->delete();

        return response()->json($data);
    }
}
