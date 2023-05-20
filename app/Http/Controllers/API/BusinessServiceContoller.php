<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessService;
use App\Models\User;

class BusinessServiceContoller extends Controller
{
    public function storeService(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:25',
                // 'user_id' => 'required|integer', // 'user_id' => 'required|integer|exists:users,id
                'description' => 'required| string| max:255',
                'price' => 'required|integer',

            ]);

            $service = new BusinessService();
            $service->name = $request->name;
            $service->user_id = $id;
            $service->description = $request->description;
            $service->price = $request->price;


            $service->save();
            if ($service->save()) {
                return response()->json([
                    'message' => 'Successfully created service!',
                    'service' => $service,
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Error',
                ], 500);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function showService(Request $request, $id)
    {
        try {
            $service = BusinessService::find($id);
            if ($service) {
                return response()->json([
                    'message' => 'Successfully show service!',
                    'service' => $service
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Error',
                    'error' => 'Service not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function showAllServices(Request $request)
    {
        try {
            $services = BusinessService::all();

            if ($services) {
                return response()->json([
                    'message' => 'Successfully show all services!',
                    'services' => $services
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Error',
                    'error' => 'Services not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    //10.1. Select a service from a list
    public function selectServiceFromList()
    {
        //get all services

        //return all services as a list
    }

    //10.2. Then view search results as a list of all companies that provides this service
    //get the distance between your current location and the business service provider 
    //address
}
