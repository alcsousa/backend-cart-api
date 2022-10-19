<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\PaymentDetail;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function cartCheckout(string $uuid, array $payload): void
    {
        $cart = Cart::userCartByUuid($uuid)->firstOrFail();

        if ($cart->isCheckedOut()) {
            abort(422, 'This cart is closed');
        }

        DB::beginTransaction();

        try {
            $payment = PaymentDetail::create([
                'subtotal' => $cart->calculateTotalAmount(),
                'discount' => 0,
                'total' => $cart->calculateTotalAmount(),
                'last_4_digits' => substr($payload['card_number'], -4),
            ]);

            $cart->checkout($payment);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500, 'Something is not working as expected');
        }
    }
}
