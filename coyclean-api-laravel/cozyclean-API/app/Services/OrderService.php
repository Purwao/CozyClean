<?php

namespace App\Services;


use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Service;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Client\Request;

class OrderService
{

    //generate code order
    private function generateOrderCode()
    {
        // Get current date components
        $year = date('y'); // Two-digit year
        $month = date('m'); // Two-digit month
        $day = date('d'); // Two-digit day
    
        // Count existing orders for today
        $orderCount = OrderDetail::whereDate('created_at', now())->count() + 1; // Add 1 to start from 1
    
        // Format the order count to be three digits (e.g., 001, 002)
        $formattedOrderCount = str_pad($orderCount, 3, '0', STR_PAD_LEFT);
    
        // Construct the order code
        // Explicitly cast to string to ensure it's a string type
        return (string) "{$year}/{$month}/{$day}/{$formattedOrderCount}";
    }

    public function getAllOrders()
    {
        //memberikan semua data
        return Order::all();
    }

    public function showAnOrder($id)
    {
        try {
            //mencari data berdasarkan id
            $order = Order::findOrFail($id);
            return response()->json($order);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            //jika id tidak ditemukan, akan mengembalikan pesan order not found
            return response()->json(['message' => 'Order not found']);
        }
    }

    public function placeOrder(array $validatedData)
    {
        //cek apakah foreign key ditemukan
        $users_id = $validatedData['users_id'];
        $types_id = $validatedData['types_id'];
        $services_id = $validatedData['services_id'];
  

        if (!User::find($users_id) || !Type::find($types_id) || !Service::find($services_id)) {
            return response()->json(['error' => 'One or more foreign keys does not exist.'], 400);
        }

        //mengambil tabel Services
        $service = Service::findOrFail($validatedData['services_id']);

        //membuat baris baru di dalam tabel orders
        $order =  Order::create([
            'users_id' => $validatedData['users_id'],
            'order_date' => now(),
            'status' => 0,
            'total' => $service->price * $validatedData['weight'],
            'paid'=> null,
            'change'=> null,
        ]);

        //membuat tabel baru di dalam tabel order_details
        $orderDetail = OrderDetail::create([
            'orders_id' => $order->id,
            'services_id' => $validatedData['services_id'],
            'order_code' => (string) $this->generateOrderCode(), // Ensure it's treated as a string
            'types_id' => $validatedData['types_id'],
            'weight' => $validatedData['weight'],
        ]);

        //mengembalikan response sesuai apa yang sudah di buat tadi
        return response()->json([
            "message" => "Data inserted successfully",
            "data" => [
                "order" => $order,
                "orderDetail" => $orderDetail,
            ],
        ]);
    }

    public function changeStatusToPaid($validatedData, $id)
    {
        //menaruh value ke dalam var
        $paid = $validatedData['paid'];

        //mencari berdasarkan id
        $order = Order::findOrFail($id);

        //cek apakah "paid" kurang dari total
        $change = $paid - $order->total;
        if ($change < 0) {
            return response()->json(['message' => 'Payment unsuccessful'], 400);
        }

        //update status, change, dan paid di tabel orders
        $order->update([
            'paid' => $paid,
            'change' => $change,
            'status' => 1,
        ]);

        //mengembalikan response berhasil
        return response()->json([
            'message' => 'Data has been updated successfully',
            'order' => $order
        ]);
    }

    public function destroyOrder($id)
    {
        //mencari berdasarkan id
        $order = Order::findOrFail($id);
        //delete order
        $order->delete();
    }
}
