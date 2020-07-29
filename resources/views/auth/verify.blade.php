@extends('layouts.app')

@section('content')
    <div class="card card-login card-horizontal">
        <div class="card-header">
            <h2 class="card-title">Get Started with Correct Care</h2>
            <ul class="list-unstyled started-list">
                <li>1 Sign up for an account</li>
                <li class="active">2 Activate your account</li>
                <li>3 Add employees and assign courses</li>
            </ul>
        </div>
        <div class="card-body">
            <span class="note mb-2"><a href="{{ route('login') }}" class="ml-0">Please check your inbox to verify your email</a></span>
            <p class="active-disc">We've sent an email to {{Auth::user()->email}}.</p>
            <p class="active-disc">Follow the instructions to verify your email address.</p>
            <div class="text-center mt-5">
                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-auto">Send the email again</button>
                </form>
            </div>
            <span class="note">Already have an account? <a href="{{ route('re-login') }}"> Click here. </a></span>
        </div>
    </div>
@endsection
