<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    //construct orderService agar bisa dipanggil(sepertinya)
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }


    public function index()
    {
        //mengambil semua data
        $orders = $this->orderService->getAllOrders();
        return response()->json($orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
       //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'users_id' => 'required',
            'types_id' => 'required',
            'services_id' => 'required',
            'weight' => 'required|numeric',
        ]);

        // Periksa jika validasi gagal
        if ($validator->fails()) {
            // pesan error
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity status code
        } else {
            // lari ke OrderService
            $createOrder = $this->orderService->placeOrder($validator->validated());
            return response()->json($createOrder);
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
        //mengambil id lalu di pass ke fungsi cari berdasarkan id di orderService
        $orders = $this->orderService->showAnOrder($id);
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
        $validatedData = Validator::make($request->all(), ['paid' => 'required|numeric']);

        //cek gagal atau tidak
        if ($validatedData->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validatedData->errors()
            ], 422);
        } else {
            //menjalankan fungsi ubah status di orderService
            $orderStatusChange = $this->orderService->changeStatusToPaid($validatedData->validated(), $id);
            return response()->json($orderStatusChange);
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
        //mengambil id lalu menjalankan fungsi hapus di orderService
        $orders = $this->orderService->destroyOrder($id);
        return response()->json(['message' => $orders]);
    }

    private function validasi() {}
}
