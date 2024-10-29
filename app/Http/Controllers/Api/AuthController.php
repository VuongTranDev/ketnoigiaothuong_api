<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
            'email' => 'required|email|unique:Users,email',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => ' Lỗi xác thực',
                'errors' => $validator->errors(),
                'status' => false
            ], 403);
        }
        $user = new Users();
        $user->email = $request->email;
        $user->password = $request->password;
        $user->status = 1;
        $user->role_id = 2;
        $user->save();

        return response()->json([
            'message' => 'Create user success',
            'data' => $user,
            'status' => true
        ], 201);
    }
    public function login(Request $request){

        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = Users::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Unauthorized',
                'status' => false
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->remember_token = $token;
        $user->save();

        return response()->json([
            'message' => 'Login success',
            'token' => $token,
            'status' => true
        ], 200);
    }

    public function getInfo(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout success',
            'status' => true
        ], 200);
    }
    public function changeStatusUser ($id)
    {
        $currentUser = auth()->user();
        Log::info('curren' . $currentUser->role);
        if ($currentUser->role->name !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized: You do not have permission to change user status.',
                'status' => false
            ], 403);
        }

        $user = Users::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'status' => false
            ], 404);
        }

        $user->status = !$user->status;
        $user->save();

        return response()->json([
            'message' => 'Change status success',
            'data' => $user,
            'status' => true
        ], 200);
    }

}
