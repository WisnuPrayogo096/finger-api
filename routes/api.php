<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);

    Route::get('employee-profile', function (Request $request) {
        // Auth::guard('api')->user() akan mengembalikan instance model Employee
        $employee = $request->user();
        return response()->json([
            'message' => 'Employee profile accessed!',
            'data' => $employee->only(['no_reg', 'nama_pegawai', 'tgl_lahir', 'email']) // Contoh data yang ingin ditampilkan
        ]);
    });

    // Contoh rute lain yang dilindungi
    Route::get('protected-resource', function () {
        return response()->json(['message' => 'This is a protected resource for employees!']);
    });
});

// Anda bisa menghapus rute default ini jika tidak digunakan
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });