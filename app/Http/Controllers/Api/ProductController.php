<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/products",
     *     description="Get product list",
     *     tags={"products"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *          description="Page number",
     *          in="query",
     *          name="page",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              format="int",
     *              default=1
     *          )
     *     ),
     *     @OA\Parameter(
     *          description="Maximum number of results to return per page",
     *          in="query",
     *          name="limit",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              format="int",
     *              default=5
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns a list of products",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="success",
     *                  value={"data": {{
     *                      "id": 1,
     *                      "name": "Product X",
     *                      "sku": "PDTOMUTXPPZ",
     *                      "description": "Some description here",
     *                      "price": 123.45,
     *                  }}},
     *                  summary="List of products"
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
     *         response="422",
     *         description="Form validation error"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate(['limit' => 'nullable|numeric|gt:0|lte:20']);

        return response()->json(Product::simplePaginate($request->input('limit') ?? 5));
    }
}
