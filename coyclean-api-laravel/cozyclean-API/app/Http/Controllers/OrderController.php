<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     //construct orderService agar bisa dipanggil(sepertinya)
    protected $orderService;

    public function __construct(OrderService $orderService ) {
        $this->orderService = $orderService;
    }


    public function index()
    {
        //mengambil semua data
       $orders= $this->orderService->getAllOrders();
       return response()->json($orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   
        //validasi data sebelum di pass ke method di orderService
        $validatedData= $request->validate([
            'users_id' => 'required',
            'types_id'=>'required',
            'services_id'=>'required',
            'weight'=>'required|numeric',
        ]);
    
        //check validasi data di laravel.log
        // Log::info('The Validated Data Are:',$validatedData);

        //menjalankan fungsi tambah order di dalam orderService
        $createOrder=$this->orderService->placeOrder($validatedData);
        return response()->json($createOrder);
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
        //mengambil id lalu di pass ke fungsi cari berdasarkan id di orderService
        $orders=$this->orderService->showAnOrder($id);
        return response()->json($orders);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //validasi data
        $validatedData = $request->validate([
            'paid' => 'required|numeric',
        ]);
    
        //menjalankan fungsi ubah status di orderService
        $orderStatusChange = $this->orderService->changeStatusToPaid($validatedData, $id);
        return response()->json($orderStatusChange);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //mengambil id lalu menjalankan fungsi hapus di orderService
        $this->orderService->destroyOrder($id);
        return response()->json(['message'=>'Data has been deleted']);
    }
}
