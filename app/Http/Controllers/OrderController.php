<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * index() method checks if user is authenticated and retrieves their order
     */
   
    public function index()
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to view your orders.');
        }

        // Get all orders for the authenticated user
        $orders = Order::where('user_id', Auth::id())->with('items.product')->get();

        // Pass the orders to the view
        return view('orders.index', compact('orders'));
    }

    // Display the checkout page
    public function create()
    {
        // Fetch the cart based on authentication
        if (Auth::check()) {
            // Logged-in user
            $cart = Cart::where('user_id', Auth::id())->with('cartItems.product')->first();
        } else {
            // Guest user
            $sessionId = session()->getId();
            $cart = Cart::where('session_id', $sessionId)->with('cartItems.product')->first();
        }

        // Check if the cart exists and has items
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Pass the cart to the checkout view
        return view('checkout.create', compact('cart'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Retrieve the cart for authenticated users or guest users
        $cart = Cart::where('user_id', Auth::id())->orWhere('session_id', session()->getId())->first();

        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate the total price of the items in the cart
        $totalPrice = $cart->cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // Create the order with the calculated total price
        $order = Order::create([
            'user_id' => Auth::check() ? Auth::id() : null, // Use Auth ID for logged-in users, null for guests
            'total_price' => $totalPrice,
        ]);

        // Transfer cart items to the order
        foreach ($cart->cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
            ]);
        }

        // Clear the cart after checkout
        $cart->cartItems()->delete();
        $cart->delete();

        // Redirect to payment page with the order details
        return redirect()->route('payment.create', ['order_id' => $order->id, 'total_price' => $order->total_price]);
    }
}

