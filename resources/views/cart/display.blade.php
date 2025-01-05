<!-- extends the styling file review.balde file -->
@extends('layouts.review')

@section('content')
<!-- bootstrap styling -->
<div class="container p-4 bg-white rounded shadow-lg" style="width: 70%; max-width: 900px; margin: auto; min-height: 100vh;">

<!-- header -->
    <h1 class="mb-4 text-center">Checkout</h1>

    @if ($cartItems && $cartItems->count() > 0)
        <div class="mb-4">
            <!-- header -->
            <h3 class="mb-3">Review Your Cart</h3>
            <ul class="list-group">
                <!-- checks for products -->
                @foreach ($cartItems as $item)
                    <li class="list-group-item d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <!-- displays the image -->
                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="img-thumbnail" 
                                 style="width: 100px; height: auto; margin-right: 15px;">
                            <div>
                                <!-- display the product name -->
                                <h5 class="mb-1">{{ $item->product->name }}</h5>
                                <!-- displays the product description -->
                                <p class="mb-1 text-muted">{{ $item->product->description }}</p>
                                <!-- displays the quantity -->
                                <p class="mb-0">Quantity: <strong>{{ $item->quantity }}</strong></p>
                            </div>
                        </div>

                        <div class="text-end">
                            <!-- displays the price -->
                            <p class="mb-0">Price: ${{ number_format($item->product->price, 2) }}</p>
                            <!-- indicates the  subtotal-->
                            <p class="fw-bold mb-0">Subtotal: ${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="text-end mt-3">
            <!-- Show the grand total -->
            <h4 class="fw-bold">Grand Total: ${{ number_format($grandTotal, 2) }}</h4>
        </div>

        <div class="d-flex flex-column align-items-center mt-4">
            <div class="mb-3">
                <!-- pagination appears once 5 records are displayed -->
                {{ $cartItems->links('pagination::bootstrap-5') }}
            </div>
            <!-- navigation link -->
            <a href="{{ route('checkout.create') }}" class="btn btn-success btn-lg">Proceed to Place Order</a>
        </div>
    @else
    <!-- notification when cart is emptied -->
        <div class="alert alert-info text-center">
            <h4>Your cart is empty!</h4>
            <p>Go back and add some products to proceed.</p>
        </div>
    @endif
</div>
@endsection
