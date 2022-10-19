<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddItemToCart;
use App\Http\Resources\CartCollection;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CartController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/carts",
     *     description="Registers a new cart",
     *     tags={"carts"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response="201",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="success",
     *                  value={"uuid": "71cca617-e92e-46ae-9394-74582ec7d00a"},
     *                  summary="Provides id of the created cart",
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
     * )
     */
    public function createCart(Request $request): JsonResponse
    {
        $cart = new Cart(['user_id' => $request->user()->id]);
        $cart->save();

        return response()->json(['uuid' => $cart->getCartIdentifier()], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/carts/{uuid}",
     *     description="Add item to cart",
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
     *              @OA\Property(property="sku", type="string"),
     *              @OA\Property(property="quantity", type="integer"),
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="success",
     *                  value={"uuid": "71cca617-e92e-46ae-9394-74582ec7d00a"},
     *                  summary="Provides id of the created cart",
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
     *         description="Form validation errors"
     *     )
     * )
     */
    public function addItem(string $uuid, AddItemToCart $request): JsonResponse
    {
        $service = new CartService();
        $service->addItemToCart($uuid, $request->validated());

        return response()->json([
            'message' => 'Item added successfully'
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/carts/{uuid}",
     *     description="Get items of cart",
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
     *     @OA\Response(
     *         response="200",
     *         description="Returns a list of cart items",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="success",
     *                  value={"data": {{
     *                      "items": {{
     *                          "name": "Product X",
     *                          "sku": "PDTOMUTXPPZ",
     *                          "description": "Some description here.",
     *                          "price": 100,
     *                          "quantity": 1,
     *                      }},
     *                      "total_items": 1,
     *                      "total_amount": 100,
     *                  }}},
     *                  summary="List of cart items"
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
     * )
     */
    public function viewCart(string $uuid, CartService $service): AnonymousResourceCollection
    {
        return CartCollection::collection($service->viewCart($uuid));
    }
}
