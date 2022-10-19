<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentDetails;
use App\Services\CheckoutService;
use Illuminate\Http\JsonResponse;

class CheckoutController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/carts/{uuid}/checkout",
     *     description="Cart checkout",
     *     tags={"carts"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *          description="Cart universally unique identifier",
     *          in="path",
     *          name="uuid",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="card_number", type="string"),
     *              @OA\Property(property="card_holder", type="string"),
     *              @OA\Property(property="expiration_date", type="string"),
     *              @OA\Property(property="cvv_code", type="integer"),
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="success",
     *                  value={"message": "Checkout finished successfully"},
     *                  summary="Cart is checked out",
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Invalid token",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="error",
     *                  value={"message": "Unauthenticated."},
     *                  summary="Unauthenticated user"
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="error",
     *                  value={"message": "Cart not found"},
     *                  summary="Resource not found"
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Form validation errors",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="error1",
     *                  value={"message": "Error description"},
     *                  summary="Form validation errors"
     *              ),
     *              @OA\Examples(
     *                  example="error2",
     *                  value={"message": "This cart is closed"},
     *                  summary="Cart can not be checked out again"
     *              ),
     *         ),
     *     )
     * )
     */
    public function checkout(string $uuid, StorePaymentDetails $request): JsonResponse
    {
        $service = new CheckoutService();
        $service->cartCheckout($uuid, $request->validated());

        return response()->json([
            'message' => 'Checkout finished successfully'
        ], 200);
    }
}
