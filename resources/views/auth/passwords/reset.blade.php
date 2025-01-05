<!-- extends the styling file -->
@extends('layouts.passwords.second')

@section('content')
<!-- bootstrap styling -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- card design -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <!-- header section -->
                    <h3 class="text-center mb-4">Reset Your Password</h3>
                    
                    <!-- form method to submit the form -->
                    <form method="POST" action="{{ route('password.update') }}">
                        <!-- csrf token to protect against malicious attacks -->
                        @csrf
                        <!-- passes the authentication token -->
                        <input type="hidden" name="token" value="{{ $token }}">
                        <!-- passes the email -->
                        <input type="hidden" name="email" value="{{ $email }}">


                        <!-- password input section -->
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- confirm password section -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                            @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Show Password Checkbox -->
                        <div class="form-group form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="showPassword">
                            <label class="form-check-label" for="showPassword">Show Password</label>
                        </div>

                        <!-- button section -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">Reset Password</button>
                        </div>
                    </form>

                    <!-- displays notifications in case of error -->
                    @if ($errors->any())
                        <ul class="alert alert-danger mt-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
