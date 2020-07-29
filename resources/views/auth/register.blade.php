@extends('layouts.app')

@section('content')
    <style>
        .hideme {
            position: absolute;
            top: -10000px;
        }
    </style>
    <div class="card card-login card-horizontal">
        <div class="card-header">
            <h2 class="card-title">Welcome, let's get started.</h2>
            <ul class="list-unstyled started-list">
                <li class="active">Sign up here to start using Correct Care in minutes. It's easy!</li>
                <li>Step 1 Sign up and activate your account</li>
                <li>Step 2 Add employees and assign courses</li>
                <li>Step 3 That's it employees can start training</li>
            </ul>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="checkbox" name="contact_me_by_fax_only" value="1" style="display:none !important" tabindex="-1" autocomplete="off">
                <div class="row">
                    <div class="col col-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <input id="name" type="text" class="form-control @error('first_name') is-invalid @enderror"
                                   name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name"
                                   autofocus placeholder="First Name">

                            @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col col-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <input id="name" type="text" class="form-control @error('last_name') is-invalid @enderror"
                                   name="last_name" value="{{ old('last_name') }}" required placeholder="Last Name">

                            @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                   name="phone" value="{{ old('phone') }}" required placeholder="Phone Number">

                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <input id="organization" type="text"
                                   class="form-control @error('organization') is-invalid @enderror" name="organization"
                                   value="{{ old('organization') }}" required placeholder="Organization Name">

                            @error('organization')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <input id="email" placeholder="Email Address" type="email"
                                   class="form-control @error('email') is-invalid @enderror" name="email"
                                   value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col col-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <input id="password" placeholder="Password" type="password"
                                   class="form-control @error('password') is-invalid @enderror" name="password" required
                                   autocomplete="new-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col col-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <input id="password-confirm" placeholder="Confirm Password" type="password"
                                   class="form-control" name="password_confirmation" required
                                   autocomplete="new-password">
                        </div>
                    </div>
                    <div class="col col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customCheck1" name="accept_terms" value="1">
                            <label class="custom-control-label @if($errors->has('accept_terms')) is-invalid @endif" for="customCheck1"><a href="https://correctcare.co.uk/terms-conditions/" target="_blank">I accept Terms and Conditions</a></label>
                        </div>
                    </div>
                </div>
                <div class="form-group float-left">
                    <div class="g-recaptcha" id="feedback-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY')  }}"></div>
                </div>
                @error('g-recaptcha-response')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <button type="submit" class="btn btn-primary w-auto">Sign up</button>
            </form>
            <span class="note">Already have an account? <a href="{{ route('login') }}"> Click here. </a></span>
        </div>
    </div>
    <style>
        label.is-invalid {
            color: red !important;
        }
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito">
@endsection
@section('js')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
