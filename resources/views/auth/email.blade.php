<!-- extends the styling file code -->
@extends('layouts.mail')
<!-- defines the title of the web page -->
@section('title', 'Email Verification')

@section('content')
<!-- cart content -->
<div class="card mx-auto mt-5 p-4">
    <!-- header -->
    <h1 class="text-center">Verify Your Email Address</h1>
    <p>Hello {{ $user->name }},</p>
    <p class="text-center">Please click the button below to verify your email address.</p>
    <div class="text-center">
        <!-- route -->
        <a href="{{ route('verification.verify', ['id' => $user->id, 'hash' => sha1($user->email)]) }}" class="btn btn-primary">Verify Email</a>
    </div>
    <p class="text-center mt-3">If you did not receive the email, click below to resend:</p>
    <!-- http method to submit form data -->
    <form method="POST" action="{{ route('verification.send') }}" class="text-center">
        <!-- crsf token to protect against cross site request forgery -->
        @csrf
        <button type="submit" class="btn btn-primary">Resend Verification Email</button>
    </form>
</div>
@endsection
