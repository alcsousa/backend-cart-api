<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CheckUserCredentials;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class TokenController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/tokens",
     *     description="An registered user can issue a token to make API calls",
     *     tags={"users"},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="password", type="string"),
     *              @OA\Property(property="device_name", type="string")
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *              @OA\Examples(example="success", value={"access_token": "valid-token-here"}, summary="A valid token is issued to the authenticated user"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="The credentials providaded are incorrect or does not exist on the database"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Form validation errors"
     *     )
     * )
     */
    public function issueToken(CheckUserCredentials $request): JsonResponse
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $device_name = $request->input('device_name');

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'The credentials providaded are incorrect or does not exist'
            ], 403);
        }

        return response()->json(['access_token' => $user->createToken($device_name)->plainTextToken]);
    }
}
