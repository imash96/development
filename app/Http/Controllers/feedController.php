<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

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

    public function amazonFeed($data)
    {
        $temp_customer = [];
        foreach ($data as $order) {
            $customer = [
                "buyerName" => $order['buyerName'],
                "buyerPONumber" => $order['buyerPONumber'],
                "buyerVatNumber" => $order['buyerVatNumber'],
                "buyerLegalName" => $order['buyerLegalName'],
                "buyerCompanyName" => $order['buyerCompanyName'],
                "badges" => json_encode($order['badges']),
                "verifiedBusinessBuyer" => $order['verifiedBusinessBuyer'],
                "buyerProxyEmail" => $order['buyerProxyEmail'],
            ];
            $cust = Customer::create($customer);
            $temp_customer = array_merge($temp_customer, $customer);
        }
        return $temp_customer;
    }

    public function ebayFeed($data)
    {
    }

    public function selfFeed($data)
    {
    }
}
