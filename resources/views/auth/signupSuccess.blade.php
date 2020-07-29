@extends('layouts.app')

@section('content')
    <style>
        label.is-invalid {
            color: red !important;
        }
    </style>
    <div class="card card-register">
        <div class="card-body">
            <h4>Thanks for registering at {{config('app.name')}}.</h4>
            <p>You need to confirm your email address. We have sent message with instructions to your email.</p>
        </div>
    </div>
@endsection
