<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password]) === false) {
            return response()->json(
                ['error' => 'The provided credentials are incorrect.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $user = Auth::user();

        $abilities = [
            'server:create',
            'server:read',
            'server:update',
            'server:delete'
        ];

        // Demonstrate abilities for Salesman API
        if ($user['email'] === 'johnwick@example.com') {
            $abilities = ['server:read'];
        }

        $success['token'] = $user->createToken('salesman-app', $abilities)->plainTextToken;
        $success['user'] = $user;

        return response()->json($success, Response::HTTP_OK);
    }
}
