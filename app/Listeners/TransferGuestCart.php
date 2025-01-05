<?php
namespace App\Listeners;

use App\Events\UserLoggedIn;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Log;

class TransferGuestCart
{
    /**
     * Handle the event when a user logs in.
     * This method transfers the guest's session cart to the user's cart for standard logins.
     *
     * @param UserLoggedIn $event The event instance containing the logged-in user and login type
     */
    public function handle(UserLoggedIn $event)
    {
        // Only handle standard logins, logins that are not from the email verification process
        if ($event->type !== 'standard') {
            Log::info('Skipped cart transfer for non-standard login type', ['user_id' => $event->user->id, 'type' => $event->type]);
            return;
        }

        //find the user attached to the event
        $user = $event->user;
        Log::info('TransferGuestCart Listener Triggered', ['user_id' => $user->id]);

        // Locate or create the user's cart
        try {
            $cart = $user->cart()->firstOrCreate([]);
            Log::info('User cart retrieved or created', ['user_id' => $user->id, 'cart_id' => $cart->id]);
        } catch (\Exception $e) {
            Log::error('Error retrieving or creating user cart', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            return;
        }

        // Retrieve session cart (guest cart)
        $sessionCart = session('cart');
        Log::info('Session Cart Retrieved', ['user_id' => $user->id, 'session_cart' => $sessionCart]);

        // Transfer session cart to user's cart
        if ($sessionCart) {
            foreach ($sessionCart as $item) {
                try {
                    // Check if the item already exists in the user's cart
                    $cartItem = $cart->cartItems()->where('product_id', $item['product_id'])->first();
                    if ($cartItem) {
                        // Update quantity if item exists
                        $cartItem->quantity += $item['quantity'];
                        $cartItem->save();
                        Log::info('Updated existing cart item', [
                            'user_id' => $user->id,
                            'product_id' => $item['product_id'],
                            'new_quantity' => $cartItem->quantity,
                        ]);
                    } else {
                        // Create a new cart item if it doesn't exist
                        $cart->cartItems()->create([
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                        ]);
                        Log::info('Created new cart item', [
                            'user_id' => $user->id,
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Error transferring session cart item', [
                        'user_id' => $user->id,
                        'product_id' => $item['product_id'] ?? null,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
            Log::info('Session Cart Transferred', ['user_id' => $user->id]);
        } else {
            Log::warning('No Session Cart Found for Transfer', ['user_id' => $user->id]);
        }

        // Clear session cart after transfer
        try {
            session()->forget('cart');
            Log::info('Session cart cleared after transfer', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Error clearing session cart', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

