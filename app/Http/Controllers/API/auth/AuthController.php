<?php

namespace App\Http\Controllers\API\auth;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['signup', 'login', 'logout']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    // signup
    public function signup(Request $request)
    {
        try {
            $request->validate([
                'company_name' => 'required|string',
                'company_address' => 'required|string',
                'company_industry' => 'required|string',
                'contact_person_name' => 'required|string |max:100,min:4',
                'contact_person_phone' => 'required|string|max:11|min:11',
                'company_location' => 'required|string|max:100|min:4',
                'company_size' => 'required|integer',
                'profile_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8',
            ]);

            $file = $request->file('profile_photo');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move('public/images', $filename);
            $profile_photo = $filename;

            $user = new User([
                'company_name' => $request->company_name,
                'company_address' => $request->company_address,
                'company_industry' => $request->company_industry,
                'contact_person_name' => $request->contact_person_name,
                'contact_person_phone' => $request->contact_person_phone,
                'company_location' => $request->company_location,
                'company_size' => $request->company_size,
                'profile_photo' => $profile_photo,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->save();
            return response()->json([
                'message' => 'Successfully regiration  user!'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    // login
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string|min:8',
            ]);
            $credentials = $request->only('email', 'password');
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            //return $this->createNewToken($token);
            return response()->json([
                'message' => 'Successfully login user!',
                'token' => JWTAuth::attempt($credentials),
                // 'user' => auth()->user()
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    //edit profile
    public function EditProfile(Request $request, $id)
    {
        try {
            $request->validate([
                'contact_person_name' => 'string |max:100,min:4',
                'contact_person_phone' => 'string|max:11|min:11',
                'profile_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'password' => 'string|min:8|confirmed',
            ]);
            $user = User::find($id);
            if ($user) {
                if ($request->hasFile('profile_photo')) {
                    // Get the uploaded image file
                    $uploadedFile = $request->file('profile_photo');

                    // Generate a unique filename for the uploaded image
                    $filename = uniqid() . '.' . $uploadedFile->getClientOriginalExtension();

                    // Store the uploaded image in the public/images directory
                    $path = $uploadedFile->move('public/images', $filename);

                    // Delete the old image file
                    Storage::delete($user->profile_photo);

                    // Update the category image path
                    $user->profile_photo = $path;
                }
                if ($request->contact_person_name) {
                    $user->contact_person_name = $request->contact_person_name;
                }
                if ($request->contact_person_phone) {
                    $user->contact_person_phone = $request->contact_person_phone;
                }
                if ($request->password) {
                    $user->password = bcrypt($request->password);
                }
            }
            $user->save();
            if ($user) {
                return response()->json([
                    'message' => 'Successfully update user!',
                    'user' => $user
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Error',
                    'error' => 'User not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 404);
        }
    }
    // logout
    public function logout()
    {
        try {
            auth()->logout();
            return response()->json(['message' => 'User successfully signed out']);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
