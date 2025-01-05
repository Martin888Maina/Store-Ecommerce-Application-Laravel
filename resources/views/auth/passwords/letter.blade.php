<!-- Extends the styling file -->
@extends('layouts.passwords.first')

@section('content')
<!-- bootstrap styling -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- card design -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <!-- header -->
                        <h3 class="text-center mb-4">Forgot Your Password?</h3>
                        <!-- method to submit form data -->
                        <form method="POST" action="{{ route('password.email') }}">
                            <!-- crsf token to protect again cross site request forgery attacks -->
                            @csrf
                            <div class="mb-3">
                                <!-- email input field -->
                                <label for="email" class="form-label">Enter Your Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required>
                            </div>

                            <!-- submission button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">Send Password Reset Link</button>
                            </div>
                        </form>

                        <!-- //feedback message -->
                        @if (session('status'))
                            <div class="alert alert-success mt-3">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

