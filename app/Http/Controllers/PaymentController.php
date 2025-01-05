<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Retrieve the order details using the order_id passed as a parameter
        $order = Order::findOrFail($request->order_id);

        return view('payment.quickteller', [
            'order_id' => $order->id,
            'total_price' => $order->total_price,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

        public function processPayment(Request $request)
    {
        // Get the order details
        $order = Order::find($request->order_id);
        $cartTotal = $order->total_price;

        // Define Quickteller API endpoint
        $apiUrl = 'https://qa.interswitchng.com/quicktellerservice/api/v5/transactions/TransferFunds';

        // Set up payload for payment request
        $payload = [
            'amount' => $cartTotal,
            'currency' => 'KES',
            'order_id' => $order->id,
            'callback_url' => route('payment.callback'),
        ];

        // Make the request to Quickteller API
        $response = Http::post($apiUrl, $payload);

        if ($response->successful()) {
            // Process successful response
            return redirect($response->json()['payment_url']);
        } else {
            // Handle failure (log error, notify user)
            return redirect()->route('cart.index')->with('error', 'Payment initiation failed.');
        }
    }

    public function paymentCallback(Request $request)
    {
        // Verify the payment status from Quickteller
        if ($request->payment_status == 'success') {
            // Update order status in DB
            $order = Order::find($request->order_id);
            $order->status = 'paid';
            $order->save();
    
            // Save payment information
            Payment::create([
                'order_id' => $order->id,
                'transaction_reference' => $request->transaction_reference,
                'amount' => $order->total_price,
                'status' => 'successful',
            ]);
    
            return redirect()->route('orders.index')->with('success', 'Payment successful!');
        } else {
            return redirect()->route('orders.index')->with('error', 'Payment failed.');
        }
    }
}
