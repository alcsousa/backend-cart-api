<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterUserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     description="Registers a new user",
     *     tags={"user"},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="password", type="string")
     *          )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Form validation errors"
     *     )
     * )
     */
    public function register(RegisterUser $request): JsonResponse
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json([
            'message' => 'User created successfully'
        ], 201);
    }
}
