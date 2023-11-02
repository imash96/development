<?php

namespace App\Http\Controllers;

use App\Models\Addresses;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\ItemCost;
use App\Models\OrderCost;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Package;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class feedController extends Controller
{
    public function index(Request $request)
    {
        $requestData = $request->json()->all();

        $responseData = ($requestData['type'] == 'amazon') ?
            $this->amazonFeed($requestData) : ($requestData['type'] == 'ebay' ?
                $this->ebayFeed($requestData) : $this->selfFeed($requestData));
        return response()->json($responseData);
    }

    public function amazonFeedProcessing($order, $sellerName)
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

        // Log::info(print_r($order, true));

        $order_items = $order['order_items'];
        unset($order['order_items']);

        $packages = $order['packages'];
        unset($order['packages']);

        $ord = Order::updateOrCreate(
            ['amazonOrderId' => $order['amazonOrderId']],
            array_merge($order, [
                'customerId' => $customer['customerId'],
                'addressId' => $addresses['addressId'],
                'accountName' => $sellerName
            ])
        );

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

            $order_item_obj = OrderItem::updateOrCreate(
                ['OrderItemId' => $order_item['OrderItemId']],
                array_merge($order_item, [
                    'orderId' => $ord['orderId'],
                    'productId' => $product['productId']
                ])
            );
        }

        foreach ($packages as $package) {
            $pack = Package::updateOrCreate(
                ['packageId' => $package['packageId'], 'orderId' => $ord['orderId']],
                $package
            );
        }
    }

    public function amazonFeed($requestData)
    {
        $orders = $requestData['orders'];
        $sellerName = $requestData['sellerName'];
        foreach ($orders as $order) {
            $this->amazonFeedProcessing($order, $sellerName);
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
