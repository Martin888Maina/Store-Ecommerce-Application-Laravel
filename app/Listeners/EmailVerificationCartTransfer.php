<?php
namespace App\Listeners;
//imports the UserLoggedIn event
use App\Events\UserLoggedIn;
//imports the Cart Model
use App\Models\Cart;
//imports the CartItem Model file
use App\Models\CartItem;
//imports the logging facade
use Illuminate\Support\Facades\Log;
//imports the database facade
use Illuminate\Support\Facades\DB;

class EmailVerificationCartTransfer
{
    /**
     * Handle cart transfer specifically for email verification flow
     *
     * @param UserLoggedIn $event
     */
    public function handle(UserLoggedIn $event)
    {

        // proceed if this is an email verification login
        if ($event->type !== 'email_verification') {
            return;
        }

        // gets the user attached to the vent object
        $user = $event->user;
        Log::info('Email Verification Cart Transfer Triggered', ['user_id' => $user->id]);

        try {
            // Ensure we have a transaction for cart operations
            DB::transaction(function() use ($user) {
                //get or create a cart for the user
                $cart = $user->cart()->firstOrCreate([]);
                Log::info('User cart retrieved or created', ['user_id' => $user->id, 'cart_id' => $cart->id]);

                //get the cart data for guest user
                $sessionCart = session('cart');
                //if cart data exists then transfer items to the user's cart
                if ($sessionCart) {
                    foreach ($sessionCart as $item) {
                        //check if the product exists in the user's cart
                        $cartItem = $cart->cartItems()
                            ->where('product_id', $item['product_id'])
                            // prevents race conditions
                            ->lockForUpdate()
                            ->first();

                            // if product exists then increase the quantity
                        if ($cartItem) {
                            $cartItem->quantity += $item['quantity'];
                            $cartItem->save();
                        } else {
                            //if the product is not in the cart then create a new cart item
                            $cart->cartItems()->create([
                                'product_id' => $item['product_id'],
                                'quantity' => $item['quantity'],
                            ]);
                        }
                    }
                    
                    //after transferring the session cart then forget it from the session
                    session()->forget('cart');
                    Log::info('Email verification session cart transferred and cleared', ['user_id' => $user->id]);
                }
            });
        } catch (\Exception $e) {
            Log::error('Error in email verification cart transfer', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}