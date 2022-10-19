<?php

namespace App\Models;

use App\Contracts\CartOperations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class Cart extends Model implements CartOperations
{
    use HasFactory;

    protected $fillable = ['user_id'];

    protected $with = ['items'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes['uuid'] = Uuid::uuid4()->toString();
        $this->attributes['total_items'] = 0;
        $this->attributes['total_amount'] = 0;
        $this->attributes['checked_out_at'] = null;
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function getCartIdentifier(): string
    {
        return $this->attributes['uuid'];
    }

    public function isCheckedOut(): bool
    {
        return $this->attributes['checked_out_at'] !== null;
    }

    public function addItem(Product $product, int $quantity): bool
    {
        $items = $this->items();
        $filtered = $items->firstWhere('product_id', $product->id);

        if (!$filtered) {
            $new_item = new CartItem();
            $new_item->cart_id = $this->id;
            $new_item->product_id = $product->getId();
            $new_item->quantity = $quantity;
            $new_item->price = $product->getPrice();
            return $new_item->save();
        }

        $filtered->quantity += $quantity;
        $filtered->price = $product->getPrice();
        return $filtered->save();
    }

    public function calculateTotalItems(): int
    {
        return $this->items->pluck('quantity')->sum();
    }

    public function calculateTotalAmount(): float
    {
        $total_amount = 0;

        foreach ($this->items as $item) {
            $total_amount += $item->quantity * $item->price;
        }

        return round($total_amount, 2);
    }

    public function checkout(PaymentDetail $payment): bool
    {
        $this->attributes['payment_detail_id'] = $payment->id;
        $this->attributes['checked_out_at'] = date('Y-m-d H:i:s');
        return $this->update();
    }

    public function scopeUserCartByUuid($query, string $uuid)
    {
        return $query->where([
            ['uuid', '=', $uuid],
            ['user_id', '=', Auth::user()->id],
        ]);
    }
}
