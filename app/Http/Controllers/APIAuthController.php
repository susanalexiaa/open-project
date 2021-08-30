<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class APIAuthController extends Controller
{
    public function requestToken(Request $request): string
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Unauthenticated.'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // после создания токет не получить! он не хранится в открытом коде
//        if ($user->tokens->isNotEmpty()) {
//            $user->tokens()->delete();
//            return response()->json([
//                'username' => $user->name,
//                'result' => $user->tokens->first()->token,
//                'token_status' => "current"
//            ], JsonResponse::HTTP_OK);
//        }

        return response()->json([
            'username' => $user->name,
            'results' => $user->createToken($request->device_name)->plainTextToken,
            'token_status' => "new"
        ], JsonResponse::HTTP_OK);
    }
}
