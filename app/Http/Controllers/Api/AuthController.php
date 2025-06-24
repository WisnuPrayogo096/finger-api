<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tgl_lahir' => 'required|date_format:Y-m-d',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $tgl_lahir = $request->tgl_lahir;
        $password = $request->password;

        $employee = Employee::whereDate('tgl_lahir', $tgl_lahir)
            ->where('status_aktif', 1)
            ->first();

        if (!$employee) {
            return response()->json(['error' => 'Unauthorized: Invalid credentials'], 401);
        }

        if ($employee->getAuthPassword() !== $password) {
            return response()->json(['error' => 'Unauthorized: Invalid password'], 401);
        }

        $token = JWTAuth::fromUser($employee);

        return $this->respondWithToken($token, $employee);
    }

    public function me()
    {
        return response()->json(Auth::guard('api')->user());
    }

    public function logout()
    {
        try {
            auth('api')->logout();

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());

            return response()->json([
                'code' => 500,
                'status' => 'error',
                'message' => 'Logout failed'
            ], 500);
        }
    }

    public function refresh()
    {
        $token = JWTAuth::parseToken()->refresh();
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token, $employee = null)
    {
        if (!$employee) {
            $employee = Auth::guard('api')->user();
        }

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'nip_pegawai' => $employee?->nip_pegawai,
                'gelar_depan' => $employee?->gelar_depan,
                'full_name' => $employee?->nama_pegawai,
                'gelar_belakang' => $employee?->gelar_belakang,
                'idf' => $employee?->idf,
            ],
            'token' => $token,
        ], 200);
    }
}
