<?php

namespace App\Http\Controllers;

use App\Models\ScrapData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class scrapDataController extends Controller
{
    public function index(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'account_name' => 'required|string|max:6',
            'account_email' => 'required|string|email|max:50',
            'seller_name' => 'required|string|max:50',
            'store_name' => 'required|string|max:30',
            'contact_number' => 'required|string|max:15',
            'status' => 'required|in:Active,Pending,Suspended',
            'lwaClientId' => 'nullable|string|max:70',
            'lwaClientSecret' => 'nullable|string|max:90',
            'awsAccessKeyId' => 'nullable|string|max:30',
            'awsSecretAccessKey' => 'nullable|string|max:50',
            'roleArn' => 'nullable|string|max:50',
            'lwaRefreshToken' => 'nullable|string|max:400',
            'isFirstScrap' => 'boolean',
            'last_scrap' => 'numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $acc = ScrapData::create($requestData);
        return response()->json($acc);
    }

    public function update_last_scrap(Request $request, $account_name)
    {
        $acc = ScrapData::where('account_name', $account_name)->first();
        if (!$acc) {
            return response()->json(['errors' => 'account not found'], 400);
        }
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'last_scrap' => 'required|numeric',
            'isFirstScrap' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $acc->last_scrap = $requestData['last_scrap'];
        $acc->isFirstScrap = $requestData['isFirstScrap'];
        $acc->save();
        return response()->json($acc);
    }

    public function get_last_scrap($account_name)
    {
        $acc = ScrapData::where('account_name', $account_name)->first();
        if (!$acc) {
            return response()->json(['errors' => 'account not found'], 400);
        }
        return response()->json($acc);
    }
}
