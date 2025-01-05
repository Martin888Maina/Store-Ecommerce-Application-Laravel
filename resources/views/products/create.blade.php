<!-- extends the style.blade styling file -->
@extends('layouts.style')

@section('content')
<!-- Bootstrap styling -->
<div class="container mt-5">
    <div class="card shadow-lg p-4 rounded">

    <!-- header -->
        <h2 class="text-center mb-4">Add New Product</h2>
        <!-- form method to submit form data -->
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            <!-- protects against malicious attacks -->
            @csrf

            <!-- Products Name input section -->
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control form-control-lg rounded-3" id="name" name="name" required placeholder="Enter product name">
            </div>

            <!-- Porduct Description input section -->
            <div class="mb-3">
                <label for="description" class="form-label">Product Description</label>
                <textarea class="form-control form-control-lg rounded-3" id="description" name="description" rows="4" required placeholder="Enter product description"></textarea>
            </div>

            <!-- Product Price input section -->
            <div class="mb-3">
                <label for="price" class="form-label">Product Price</label>
                <input type="number" class="form-control form-control-lg rounded-3" id="price" name="price" step="0.01" required placeholder="Enter product price">
            </div>

            <!-- Product Image section -->
            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" class="form-control form-control-lg rounded-3" id="image" name="image" required>
            </div>

            <!-- Button section -->
            <button type="submit" class="btn btn-lg btn-primary w-100 rounded-3">
                <i class="bi bi-plus-circle me-2"></i> Add Product
            </button>
        </form>
    </div>
</div>
@endsection
