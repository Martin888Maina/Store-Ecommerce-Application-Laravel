<!-- extends the app.blade styling file -->
@extends('layouts.app')

@section('content')
<!-- header section -->
    <h2>Payment Information</h2>

    <p>Order ID: {{ $order_id }}</p>
    <p>Total Price: KES {{ number_format($total_price, 2) }}</p>

    <!-- form method to submit data -->
    <form action="{{ route('payment.process') }}" method="POST">
        <!-- protects users against malicious attacks -->
        @csrf
        <!-- order_id hidden input section -->
        <input type="hidden" name="order_id" value="{{ $order_id }}">
        <!-- total_price hidden input section -->
        <input type="hidden" name="total_price" value="{{ $total_price }}">

        <!-- Button -->
        <button type="submit" class="btn btn-primary">Proceed to Payment</button>
    </form>
@endsection