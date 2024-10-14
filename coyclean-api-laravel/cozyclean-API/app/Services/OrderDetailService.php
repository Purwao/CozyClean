<?php

namespace App\Services;

use App\Models\OrderDetail;

class OrderDetailService{

    public function getAllOrders(){
            return OrderDetail::all();
    }
    
    public function showAnOrder($id){    
            try {
                //mencari data berdasarkan id
                $order = OrderDetail::findOrFail($id);
                return response()->json($order);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                //jika id tidak ditemukan, akan mengembalikan pesan order not found
                return response()->json(['message' => 'Order Details not found']);
            }
        }
    }