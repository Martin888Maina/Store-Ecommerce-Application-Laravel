<?php

namespace App\Http\Controllers;
// imports the Cart Model
use App\Models\Cart;
// importss the CartItem Model
use App\Models\CartItem;
// Imports the Product Model
use App\Models\Product;
// Imports the request class for the interacting with form inputs
use Illuminate\Http\Request;
//Imports the Auth Facade for authentication
use Illuminate\Support\Facades\Auth;
// Imports the Log Facade for logging output in the laravel.log file
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{

    /**
     * Display the products on the landing page.
     */
    public function index()
    {

        // gets the session
        $sessionId = session()->getId();
        //logging output in the laravel.log file
        Log::info('CartController Index: Session ID - ' . $sessionId);

        // checks if user is authenticated
        if (Auth::check()) {
            // logging output in the laravel.log file
            Log::info('User Logged In: User ID - ' . Auth::id());

            // gets the cart associated with the user
            $cart = Cart::where('user_id', Auth::id())->with('cartItems.product')->first();
        } else {
            // logging output in the laravel.log file
            Log::info('Guest User at Index method: Using Session ID - ' . $sessionId);
            // gets the cart associated with the session for guest users
            $cart = Cart::where('session_id', $sessionId)->with('cartItems.product')->first();
        }

        // Checks if cart exists and is not associated wt=ith a user
        if ($cart && !$cart->user_id) {
            // save the cart data in the session
            session(['cart' => $cart->cartItems()->with('product')->get()->toArray()]);
            // logging output in the laravel.log file
            Log::info('Guest Cart data saved to session for session: ' . $sessionId);
        }

        // Get the cart items
        // if no cart item is found return an empty collection
        $cartItems = $cart ? $cart->cartItems()->with('product')->orderBy('created_at', 'desc')->paginate(5) : collect();


        // returns the ;anding page view with the cart items
        return view('cart.index', compact('cartItems'));
    }


    /**
     * Displays the cart data for users.
     */
    public function display()
    {
        // Gets the current session
        $sessionId = session()->getId();
        // Logging output in the laravel.log file
        Log::info('CartController Display: Session ID - ' . $sessionId);

        // checks if the suer is authenticated
        if (Auth::check()) {
            // Logging outtput in the laravel.log file
            Log::info('User Logged In: User ID - ' . Auth::id());
            // associates the cart with the authenticated user
            $cart = Cart::where('user_id', Auth::id())->with('cartItems.product')->first();

            // Paginate cart items
            $cartItems = $cart->cartItems()->with('product')->orderBy('created_at', 'desc')->paginate(5); // Change '5' to the desired number of items per page
        } else {
            //handles the case where the user is not authenticated and is a guest user
            Log::info('Guest User at display method: Using Session ID - ' . $sessionId);
            //associates the cart to the guest user
            $cart = Cart::where('session_id', $sessionId)->with('cartItems.product')->first();
            // paginates the cart data if exists
            $cartItems = $cart ? $cart->cartItems()->with('product')->orderBy('created_at', 'desc')->paginate(5) : collect(); // Paginate
        }

        // calculate the grand total
        $grandTotal = $cart->cartItems()->with('product')->get()->sum(fn($item) => $item->product->price * $item->quantity);

        // navigates the user to the cart.display page
        return view('cart.display', compact('cart', 'cartItems', 'grandTotal'));
    }


    /**
     * Adding products to the cart.
     */

    public function addToCart(Request $request, $productId)
    {
        // get the session
        $sessionId = session()->getId();
        // logging output
        Log::info('AddToCart: Session ID Before Adding Product - ' . $sessionId);

        // find the product byt the id
        $product = Product::findOrFail($productId);
        //logging output
        Log::info('Product Found: Product ID - ' . $productId);

        //checks if the user is authenticated
        if (Auth::check()) {
            //logging output
            Log::info('User Logged In: User ID - ' . Auth::id());
            //find or create cart for authenticated user
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        } else {
            // logging output
            Log::info('Guest User: Using Session ID - ' . $sessionId);
            // find or create cart for guest user
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);

            // get th current session cart for guest users
            $sessionCart = session('cart', []);
            $productIndex = null;

            //checks if product exists in the cart
            foreach ($sessionCart as $index => $item) {
                if ($item['product_id'] == $productId) {
                    $productIndex = $index;
                    // stops the loop
                    break;
                }
            }

            //checks if product is in the cart and updates the quantity
            if ($productIndex !== null) {
                $sessionCart[$productIndex]['quantity'] += 1;
            } else {
                // if product is not in the cart, cart is automatically given a value of 1
                $sessionCart[] = ['product_id' => $productId, 'quantity' => 1];
            }

            // save the cart data in the session
            session(['cart' => $sessionCart]);
            //logging output
            Log::info('Session Cart Updated: ', ['cart' => $sessionCart]);
        }

        // checks if product exists in the cart
        $cartItem = $cart->cartItems()->where('product_id', $productId)->first();
        //if it exists, update quantity by 1
        if ($cartItem) {
            $cartItem->quantity += 1;
            //save the data
            $cartItem->save();
            //logging output
            Log::info('Product Quantity Updated: Product ID - ' . $productId . ', New Quantity - ' . $cartItem->quantity);
        } else {
            //if it does not exist add a quantity of 1
            $cart->cartItems()->create([
                'product_id' => $productId,
                'quantity' => 1,
            ]);

            //logging output
            Log::info('New Product Added to Cart: Product ID - ' . $productId . ', Quantity - 1');
        }

        //logging output
        Log::info('AddToCart: Product Added Successfully for Session ID - ' . $sessionId);

        //redirects the user to the landing page
        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
    }


    /**
     * Removes products from the cart during cart management.
     */
    public function removeFromCart($cartItemId)
    {

        //gets the session
        $sessionId = session()->getId();
        //logging output
        Log::info('RemoveFromCart: Session ID - ' . $sessionId);

        //finds the cart items 
        $cartItem = CartItem::findOrFail($cartItemId);

        //checks if the user has the sessions
        if (!Auth::check()) {
            if ($cartItem->cart->session_id !== $sessionId) {
                abort(403, 'Unauthorized action.');
            }

            // if user contains the session then deletes the cart item
            $cartItem->delete();
            // logging output
            Log::info('Cart Item Removed for Guest User: Session ID - ' . $sessionId);

            // gets the session cart data
            $sessionCart = session('cart', []);
            // loops through the cart
            foreach ($sessionCart as $index => $item) {
                if ($item['product_id'] == $cartItem->product_id) {
                    //removes an item from the cart
                    unset($sessionCart[$index]);
                    //stops the loop
                    break;
                }
            }

            // updates the session with the new cart
            session(['cart' => array_values($sessionCart)]);

            // if the cart is empty clear the cart session
            if (empty($sessionCart)) {
                session()->forget('cart');
                //logging the output
                Log::info('Session Cart Cleared: Session ID - ' . $sessionId);
            }
        } else {
            //for logged in users, cart items should match the user id
            if ($cartItem->cart->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            // delete the cart items
            $cartItem->delete();
            //logging output
            Log::info('Cart Item Removed for Logged-in User: User ID - ' . Auth::id());
        }

        // navigates user to the cart.index page
        return redirect()->route('cart.index')->with('success', 'Product removed from cart.');
    }

}
