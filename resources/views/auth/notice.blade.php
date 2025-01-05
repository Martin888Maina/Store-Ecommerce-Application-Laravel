<!-- Extends the styling file -->
@extends('layouts.elegant')

@section('content')
<!-- bootstrap styling -->
    <div class="verification-container">
        <!-- status message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- container -->
        <div class="icon-container">
            <svg class="email-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>

        <!-- header -->
        <h1 class="title">Verify Your Email Address</h1>
        
        <!-- message -->
        <p class="message">
            Thanks for registering! Before getting started, please verify your email address by clicking on the link we just emailed to you. 
            If you didn't receive the email, click the link below and we'll send you another.
        </p>

        <!-- statis message -->
        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success">
                A new verification link has been sent to your email address.
            </div>
        @endif

        <!-- method to send data to the database (post) -->
        <form method="POST" action="{{ route('verification.send') }}">
            <!-- protects the user from cross site forgery attacks -->
            @csrf
            <button type="submit" class="resend-link">
                Resend Verification Email
            </button>
        </form>
    </div>
@endsection
