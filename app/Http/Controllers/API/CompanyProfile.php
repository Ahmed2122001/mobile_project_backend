<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BusinessService;
use Geocoder\Geocoder;
use Geocoder\Provider\GeoIP2\GeoIP2;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\DistanceQuery;


class CompanyProfile extends Controller
{
    public  function  getcompanyProfile($servise_id)
    {
        try {
            $service = BusinessService::find($servise_id);
            $user = User::find($service->user_id);
            if ($user) {
                return response()->json([
                    'message' => 'Successfully show user!',
                    'user' => $user
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Error',
                    'error' => 'user not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function getAllBusinessServicesForSpicficCompany($user_id)
    {
        try {
            $services = BusinessService::where('user_id', $user_id)->get();
            if ($services) {
                return response()->json([
                    'message' => 'Successfully show services!',
                    'services' => $services
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Error',
                    'error' => 'services not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    public function getAllCompanies()
    {
        try {
            $users = User::all();
            if ($users) {
                return response()->json([
                    'message' => 'Successfully show all users!',
                    'users' => $users
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Error',
                    'error' => 'Users not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    public function getDistanceBetweenPointsNew($coordinates1, $coordinates2)
    {
        $earthRadius = 6371; // Radius of the Earth in kilometers

        list($lat1, $lon1) = explode(',', $coordinates1);
        list($lat2, $lon2) = explode(',', $coordinates2);

        $lat1Rad = deg2rad((float)trim($lat1));
        $lon1Rad = deg2rad((float)trim($lon1));
        $lat2Rad = deg2rad((float)trim($lat2));
        $lon2Rad = deg2rad((float)trim($lon2));

        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLon = $lon2Rad - $lon1Rad;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1Rad) * cos($lat2Rad) *
            sin($deltaLon / 2) * sin($deltaLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }
    public function getDistance(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $service_id = $request->service_id;

            $user = User::find($user_id);
            $service = BusinessService::find($service_id);
            $company_id = $service->user_id;
            $company = User::find($company_id);


            $user_address = $user->company_location;
            $company_address = $company->company_location;
            $distance = $this->getDistanceBetweenPointsNew($user_address, $company_address);
            if ($distance) {
                return response()->json([
                    'message' => 'Successfully show distance!',
                    'distance' => $distance
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Error',
                    'error' => 'distance not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    // public  function getAllcompaniesForspicficService(Request $request)
    // {
    //     try {
    //         $service_name = $request->service_name;
    //         $service = BusinessService::find($service_name);
    //         $user = User::find($service->user_id);
    //         if ($user) {
    //             return response()->json([
    //                 'message' => 'Successfully show user!',
    //                 'user' => $user
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'message' => 'Error',
    //                 'error' => 'user not found'
    //             ], 404);
    //         }
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'message' => 'Error',
    //             'error' => $th->getMessage()
    //         ], 500);
    //     }
    // }
    public function getAllCompaniesForSpecificService(Request $request)
    {
        try {
            $service_name = $request->service_name;
            $service = BusinessService::where('name', 'LIKE', "%{$service_name}%")->get();
            // dd($service_name);

            if ($service) {
                $user = User::find($service->user_id);

                // $companies = $service->companies;

                return response()->json([
                    'message' => 'Successfully retrieved companies!',
                    'companies' => $user
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Service not found',
                    'error' => 'The requested service was not found'
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
