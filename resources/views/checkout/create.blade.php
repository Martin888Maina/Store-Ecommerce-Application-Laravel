<!-- extends the design styling file -->
@extends('layouts.design')

@section('content')
<!-- bootstrap styling -->
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="container p-4 bg-white rounded shadow-lg text-center" style="width: 70%; max-width: 900px;">
            <!-- header -->
            <h1 class="mb-4">Checkout</h1>
            <!-- http form method to submit form data -->
            <form action="{{ route('checkout.store') }}" method="POST" class="d-flex flex-column" style="height: 100%;">
                <!-- csrf token to protect the against cross site request forgery -->
                @csrf
                <div class="mb-4 flex-grow-1">
                    <!-- header -->
                    <h3 class="mb-3">Your Order(s)</h3>
                    <ul class="list-group">

                    <!-- Loops through the cart items and displays them on the screen -->
                        @foreach ($cart->cartItems as $item)
                            <li class="list-group-item d-flex align-items-center">
                                <!-- image section -->
                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="img-thumbnail" 
                                     style="width: 100px; height: auto; margin-right: 15px;">
                                <div class="text-start">
                                    <!-- product name -->
                                    <h5>{{ $item->product->name }}</h5>
                                    <!-- product description -->
                                    <p>{{ $item->product->description }}</p>
                                    <!-- quantity -->
                                    <p>Quantity: {{ $item->quantity }}</p>
                                    <!-- price -->
                                    <p>Price: ${{ $item->product->price }}</p>
                                    <!-- sub total: which is quantity mulitplied by the price -->
                                    <p>Subtotal: ${{ $item->product->price * $item->quantity }}</p>
                                </div>
                            </li>
                        @endforeach

                    </ul>
                    <!-- display total amount -->
                    <p class="mt-3 fw-bold">Total: ${{ $cart->cartItems->sum(fn($item) => $item->product->price * $item->quantity) }}</p>
                </div>
                <!-- place order button -->
                <button type="submit" class="btn btn-primary w-100 mt-auto">Place Order</button>
            </form>
        </div>
    </div>
@endsection





