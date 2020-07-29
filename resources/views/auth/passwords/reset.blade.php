@extends('layouts.app')

@section('content')
    <div class="card card-login card-horizontal">
        <div class="card-header">
            <h2 class="card-title">Reset Password</h2>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Enter your Email address below</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ $email ?? old('email') }}" required autocomplete="email"
                           autofocus placeholder="Email">

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                </div>
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input id="password" type="password"
                           class="form-control @error('password') is-invalid @enderror" name="password" required
                           autocomplete="new-password"
                           placeholder="Password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control"
                           name="password_confirmation" required autocomplete="new-password"
                           placeholder="Confirm Password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                </div>
                <div class="text-center">
                    <a href="javascript:;" onclick="$(this).closest('form').submit()" class="btn btn-primary">Reset Password</a>
                </div>
            </form>
            <div class="form-action">
                <span class="note"><a href="{{ route('login') }}"><u>It’s OK I’ve remembered</u></a></span>
            </div>
        </div>
    </div>
@endsection
