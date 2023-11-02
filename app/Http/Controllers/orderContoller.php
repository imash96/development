<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class orderContoller extends Controller
{
    public function get_orders_by_ac(Request $request, $account_name)
    {
        $responseData = Order::where('accountName', $account_name);
        return response()->json($responseData);
    }

    public function get_all_orders(Request $request)
    {
        $responseData = Order::with(['customer', 'addresses', 'orderCost', 'packages', 'orderItem.itemCost', 'orderItem.product'])->get();
        return response()->json($responseData);
    }
}
