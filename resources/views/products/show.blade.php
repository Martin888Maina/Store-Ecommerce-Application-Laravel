<!-- extends the display.blade styling file -->
@extends('layouts.display')

@section('content')
<!-- bootstrap styling -->
    <div class="container py-5">
        <div class="row">
            <!-- Product Image and Details Section -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0">
                    <!-- Image section -->
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="border-radius: 8px;">
                </div>
            </div>

            <!-- Product Information Section -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 p-4">
                    <!-- product name -->
                    <h1 class="display-4 text-dark mb-3">{{ $product->name }}</h1>
                    <!-- product description -->
                    <p class="text-muted mb-3">{{ $product->description }}</p>
                    <!-- product price -->
                    <p class="text-success h3 mb-4"><strong>${{ $product->price }}</strong></p>
                    
                    <!-- Add to Cart Button -->
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        <!-- csrf token to protect user against malicious attacks -->
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg w-100" style="border-radius: 30px; font-weight: bold;">
                            Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        </div>  
    </div>
@endsection
