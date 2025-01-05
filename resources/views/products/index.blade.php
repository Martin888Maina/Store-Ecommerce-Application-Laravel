<!-- extends the display.blade styling file -->
@extends('layouts.display')

@section('content')
<!-- bootstrap styling -->
    <div class="container">
        <!-- header -->
        <h1 class="mb-4">Product Listings</h1>

        <!-- Link to Create Product (Upload Form) -->
        <a href="{{ route('products.create') }}" class="btn btn-primary mb-4">Add New Product</a>
        <div class="row">
            <!-- Loops through the products in the database and display them on the screen -->
            @foreach ($products as $product)
            <!-- Show 4 items per row -->
                <div class="col-md-3 mb-4"> 
                    <div class="card shadow-sm border-0">
                        <!-- Route to display the  products-->
                        <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none">
                            <!-- Image section -->
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            <!-- card body: botstrap styling -->
                            <div class="card-body">
                                <!-- product name -->
                                <h5 class="card-title text-dark">{{ $product->name }}</h5>
                                <!-- product description -->
                                <p class="card-text text-muted">{{ \Str::limit($product->description, 100) }}</p>
                                <!-- product price -->
                                <p class="card-text text-success"><strong>${{ $product->price }}</strong></p>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination section -->
        <!-- Pagination is implemented when 20 product listings are present -->
        <!-- 4 listings displayed across/horizontallly -->
        <!-- 5 listings displayed vertically-->
        @if ($products->total() > 20)
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
