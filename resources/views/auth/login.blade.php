@extends('layouts.app')

@section('content')
    <div class="card card-login card-horizontal">
        <div class="card-header">
            <h2 class="card-title">Welcome Back to Correct Care</h2>
            <ul class="list-unstyled started-list">
                <li class="active">Sign in to continue to your account.</li>
            </ul>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email Address / Username</label>
                    <input id="email" type="text" placeholder="Username"
                           class="form-control @error('email') is-invalid @enderror" name="email"
                           value="" required autocomplete="email" autofocus>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input id="password" placeholder="Password" type="password"
                           class="form-control @error('password') is-invalid @enderror" name="password" required
                           autocomplete="current-password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="text-center">
                    <a onclick="$(this).closest('form').submit()" href="javascript:;" class="btn btn-primary">Sign In</a>
                </div>
                <div class="form-action">
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    <span class="note"><a href="https://correctcare.co.uk"><img src="https://app.correctcare.co.uk/assets/images/CC-logo-grey.png" alt="Correct Care Website"></a></span>
                    <!--   <span class="note">Don't have an account? 
                        <a href="{{ route('register') }}" class="btn-link btn-link--primary">Sign Up</a>
                    </span> -->
                </div>
            </form>
        </div>
    </div>
@endsection
