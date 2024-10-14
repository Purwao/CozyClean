<?php

namespace App\Services;


use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Service;
use App\Models\Type;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\Request;

class OrderService
{


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

        return (string) "{$year}/{$month}/{$day}/{$formattedOrderCount}";
    }

    public function getAllOrders()
    {
        try {
            // Mengambil semua data dari tabel Order
            $orders = Order::all();
            // Memeriksa apakah data kosong
            if ($orders->isEmpty()) {
                return response()->json([
                    'message' => 'There is no data available'
                ], 404); // Mengembalikan status kode 404 (Not Found)
            } else {
                return response()->json([
                    'message' => 'data is available',
                    'data' => $orders
                ]);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    public function showAnOrder($id)
    {
        try {
            //mencari data berdasarkan id
            $order = Order::findOrFail($id);
            return response()->json($order);
        } catch (ModelNotFoundException $e) {
            //jika id tidak ditemukan, akan mengembalikan pesan order not found
            return response()->json([
                'message' => 'The order is not found',
            ]);
        }
    }

    public function placeOrder($validator)
    {
        try {
            //cek apakah foreign key ditemukan
            $users_id = $validator['users_id'];
            $types_id = $validator['types_id'];
            $services_id = $validator['services_id'];


            if (!User::find($users_id) || !Type::find($types_id) || !Service::find($services_id)) {
                return response()->json(['error' => 'One or more foreign keys does not exist.'], 400);
            }

            //mengambil baris Services
            $service = Service::findOrFail($validator['services_id']);

            //membuat baris baru di dalam tabel orders
            $order =  Order::create([
                'users_id' => $validator['users_id'],
                'order_date' => now(),
                'status' => 0,
                'total' => $service->price * $validator['weight'],
                'paid' => null,
                'change' => null,
            ]);

            //membuat baris baru di dalam tabel order_details
            $orderDetail = OrderDetail::create([
                'orders_id' => $order->id,
                'services_id' => $validator['services_id'],
                'order_code' => (string) $this->generateOrderCode(),
                'types_id' => $validator['types_id'],
                'weight' => $validator['weight'],
            ]);

            //mengembalikan response sesuai apa yang sudah di buat tadi
            return response()->json([
                "message" => "Data inserted successfully",
                "data" => [
                    "order" => $order,
                    "orderDetail" => $orderDetail,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
    public function changeStatusToPaid($validatedData, $id)
    {
        try {
            //menaruh value ke dalam var
            $paid = $validatedData['paid'];

            //mencari berdasarkan id
            $order = Order::findOrFail($id);

            //cek apakah "paid" kurang dari total
            $change = $paid - $order->total;
            if ($change < 0) {
                return response()->json(['message' => 'Balance is not enough'], 422);
            }

            //update status, change, dan paid di tabel orders
            $order->update([
                'paid' => $paid,
                'change' => $change,
                'status' => 1,
            ]);

            //mengembalikan response berhasil
            return response()->json([
                'message' => 'Status has been changed successfully',
                'order' => $order
            ]);

            //catch error
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the order'], 500);
        }
    }
    public function destroyOrder($id)
    {
        try {
            //mencari berdasarkan id
            $order = Order::findOrFail($id);
            //delete order
            $order->delete();
            return response()->json(['message' => 'Order deleted'], 200);
        } catch (ModelNotFoundException $e) {
            // Jika order tidak ditemukan, pesan 'Order not found' dengan status kode 404
            return response()->json(['message' => 'Order not found'], 404);
        } catch (Exception $e) {
            // Jika terjadi kesalahan, ya gitu
            return response()->json(['message' => 'An error occurred while deleting the order'], 500);
        }
    }
}
