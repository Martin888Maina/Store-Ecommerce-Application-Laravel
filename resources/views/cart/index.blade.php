<!-- extends the styling file app.blade.php -->
@extends('layouts.app')

@section('content')
<!-- bootstrap styling code -->
    <div class="container">
        <!-- header -->
        <h1>Your Cart</h1>
        <!--checks if caart items exist  -->
        @if ($cartItems && $cartItems->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- loops through each item and displays it on the screen -->
                    @foreach ($cartItems as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <!-- displays the image -->
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="img-thumbnail" 
                                         style="width: 80px; height: auto; margin-right: 10px;">
                                         <!-- displays the product name -->
                                    <span>{{ $item->product->name }}</span>
                                </div>
                            </td>
                            <!-- displays the quantity -->
                            <td>{{ $item->quantity }}</td>
                            <!-- calculates the price multiplied by the quanity -->
                            <td>${{ $item->product->price * $item->quantity }}</td>
                            <td>
                                <!-- removing products from the cart -->
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    <!-- csrf token prevent against cross site request forgery attacks -->
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination and Proceed to Checkout -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <!-- Pagination Links -->
                <div>
                    {{ $cartItems->links('pagination::bootstrap-5') }}
                </div>

                <!-- Proceed to Checkout Button -->
                <div>
                    <a href="{{ route('cart.display') }}" class="btn btn-success">Proceed to Checkout</a>
                </div>
            </div>
            <!-- notification when cart is empty -->
        @else
            <p>Your cart is empty. Add some products!</p>
        @endif
    </div>
@endsection
