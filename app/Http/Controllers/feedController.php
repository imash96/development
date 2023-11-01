<?php

namespace App\Http\Controllers;

use App\Models\Addresses;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\ItemCost;
use App\Models\OrderCost;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\Packages;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class feedController extends Controller
{
    public function index(Request $request)
    {
        $requestData = $request->json()->all();

        $responseData = ($requestData['type'] == 'amazon') ?
            $this->amazonFeed($requestData['orders']) : ($requestData['type'] == 'ebay' ?
                $this->ebayFeed($requestData['orders']) : $this->selfFeed($requestData['orders']));
        return response()->json($responseData);
    }

    public function amazonFeedProcessing($order)
    {
        $cust = $order['customer'];
        $cust['badges'] = json_encode($cust['badges']);
        unset($order['customer']);
        $customer = Customer::updateOrCreate(
            ['buyerProxyEmail' => $cust['buyerProxyEmail']],
            $cust
        );

        $add = $order['addresses'];
        unset($order['addresses']);
        $addresses = Addresses::firstOrCreate(
            ['addressId' => $add['addressId']],
            array_merge($add, ['customerId' => $customer['customerId']])
        );

        $ord_cost = $order['order_cost'];
        unset($order['order_cost']);
        $order_cost = OrderCost::updateOrCreate(
            ['amazonOrderId' => $order['amazonOrderId']],
            $ord_cost
        );

        $order_items = $order['order_items'];
        unset($order['order_items']);
        foreach ($order_items as $order_item) {
            $itm_cost = $order_item['item_cost'];
            unset($order_item['item_cost']);
            $item_cost = ItemCost::updateOrCreate(
                ['OrderItemId' => $order_item['OrderItemId']],
                $itm_cost
            );

            $prod = $order_item['product'];
            unset($order_item['product']);
            $product = Product::firstOrCreate(
                ['ASIN' => $prod['ASIN']],
                $prod
            );

            $order_item_obj = OrderItems::updateOrCreate(
                ['OrderItemId' => $order_item['OrderItemId']],
                array_merge($order_item, [
                    'amazonOrderId' => $order['amazonOrderId'],
                    'productId' => $product['productId']
                ])
            );
        }

        $packages = $order['packages'];
        unset($order['packages']);
        foreach ($packages as $package) {
            Log::info(print_r($package, true));
            $pack = Packages::updateOrCreate(
                ['packageId' => $package['packageId'], 'amazonOrderId' => $order['amazonOrderId']],
                $package
            );
        }

        $ord = Orders::updateOrCreate(
            ['amazonOrderId' => $order['amazonOrderId']],
            array_merge($order, [
                'customerId' => $customer['customerId'],
                'addressId' => $addresses['addressId']
            ])
        );
    }

    public function amazonFeed($orders)
    {
        foreach ($orders as $order) {
            $this->amazonFeedProcessing($order);
        }
        return response()->json(['status' => 'success']);
    }

    // public function amazonFeedwa($orders)
    // {
    //     $temp_orders = [];
    //     foreach ($orders as $order) {
    //         $customer = $order['customer'];
    //         unset($order['customer']);
    //         $addresses = $order['addresses'];
    //         unset($order['addresses']);
    //         $order_cost = $order['order_cost'];
    //         unset($order['order_cost']);
    //         $packages = $order['packages'];
    //         unset($order['packages']);
    //         $order_items = $order['order_items'];
    //         unset($order['order_items']);
    //         $order_items_arr = [];
    //         foreach ($order_items as $order_item) {
    //             $item_cost = $order_item['item_cost'];
    //             unset($order_item['item_cost']);
    //             $product = $order_item['product'];
    //             unset($order_item['product']);
    //             $order_item_obj = OrderItems::updateOrCreate(

    //             )
    //         }
    //     }
    //     return response()->json(['status' => 'success']);
    // }

    public function ebayFeed($data)
    {
    }

    public function selfFeed($data)
    {
    }
}
