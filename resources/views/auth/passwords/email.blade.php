@extends('layouts.app')

@section('content')
    <div class="card card-login card-horizontal">
        <div class="card-header">
            <h2 class="card-title">Forgot Your Password?</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                @if (session('status'))
                    <div class="alert alert-success" role="alert" style="margin: 0 0 20px;">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="form-group">
                    <label class="form-label">Enter your Email address below</label>
                    <input id="email" type="email"
                           class="form-control @error('email') is-invalid @enderror" name="email"
                           value="{{ old('email') }}" required autocomplete="email" autofocus
                           placeholder="Email">

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="text-center">
                    <a href="javascript:;" onclick="$(this).closest('form').submit()" class="btn btn-primary">Send
                        Email</a>
                </div>
            </form>
            <div class="form-action">
                <span class="note"><a href="{{ route('login') }}"><u>It’s OK I’ve remembered</u></a></span>
            </div>
        </div>
    </div>
@endsection
