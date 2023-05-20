<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FavoriteServices;
use Illuminate\Support\Facades\Bus;
use App\Models\BusinessService;

class FavoriteServicesController extends Controller
{
    public function showAllfavoriteServices(Request $request, $id)
    {
        try {
            $favoriteServices = FavoriteServices::where('user_id', $id)->get('business_service_id');

            $AllfavoriteServices = BusinessService::whereIn('id', $favoriteServices)->get();




            if ($AllfavoriteServices) {
                return response()->json([
                    'message' => 'Successfully show all favoriteServices!',
                    'favoriteServices' => $AllfavoriteServices
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Error',
                    'error' => 'favoriteServices not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function is_exist($user_id, $business_service_id)
    {
        try {
            $favorite = FavoriteServices::where('user_id', $user_id)->where('business_service_id', $business_service_id)->first();
            if ($favorite) {
                return response()->json([
                    'message' => 'Successfully show favorite!',
                    'favorite' => true
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Error',
                    'error' => false
                ], 404);
            }
            //code...
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function addFavorite(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer',
                'business_service_id' => 'required|integer',
                // 'is_favorite' => 'required|boolean',
            ]);
            $favorite = new FavoriteServices();
            $favorite->user_id = $request->user_id;
            $favorite->business_service_id = $request->business_service_id;
            $favorite->is_favorite = 1;
            $favorite->save();
            if ($favorite->save()) {
                return response()->json([
                    'message' => 'Successfully created favorite!',
                    'favorite' => $favorite,
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Error',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function removeFavorite(Request $request, $id)
    {
        try {
            $favorite = FavoriteServices::find($id);
            if ($favorite) {
                $favorite->delete();
                return response()->json([
                    'message' => 'Successfully deleted favorite!',
                    'favorite' => $favorite
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Error',
                    'error' => 'favorite not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
