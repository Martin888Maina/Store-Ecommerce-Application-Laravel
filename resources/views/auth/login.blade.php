<!-- extends the access styling file -->
@extends('layouts.access')

@section('content')
<!-- bootstrap -->
<div class="login-container">
    <div class="login-card">
        <!-- header -->
        <h2 class="text-center mb-4">Login</h2>

        <!-- display notification for errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- form method for submitting form data -->
        <form action="{{ route('login.submit') }}" method="POST">
            <!-- csrf token for protecting again cross site request forgery attacks -->
            @csrf
            <!-- email address input -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
            </div>

            <!-- password input section -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <!-- checkbox input section to toggle passwords -->
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="showPassword">
                <label class="form-check-label" for="showPassword">Show Password</label>
            </div>

            <!-- login button section -->
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>

        <!-- register form navigation link -->
        <p class="mt-3 text-center">Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
        <!-- forgot password navigation link -->
        <p class="text-center"><a href="{{ route('password.request') }}">Forgot your password?</a></p>
    </div>
</div>
@endsection
