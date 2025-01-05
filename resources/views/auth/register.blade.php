<!-- extends the record styling file code -->
@extends('layouts.record')

@section('content')
<!-- bootstrap styling -->
<div class="register-container">
    <div class="register-card">
        <!-- header -->
        <h2 class="text-center mb-4">Register</h2>

        <!-- error notifications -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- form section -->
         <!-- method to submit form data to the database  -->
        <form action="{{ route('register.submit') }}" method="POST">
            <!-- csrf token to protect against malicious attacks -->
            @csrf

            <!--Full Name Input section  -->
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required value="{{ old('name') }}">
            </div>

            <!-- Email Address Input section -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
            </div>

            <!-- Password section  -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <!--Confirm Password Section  -->
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>

            <!--  Show Password checkbox -->
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="showPassword">
                <label class="form-check-label" for="showPassword">Show Password</label>
            </div>

            <!-- register button -->
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>

        <!-- navigation links to the login page -->
        <p class="mt-3 text-center">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
    </div>
</div>
@endsection
