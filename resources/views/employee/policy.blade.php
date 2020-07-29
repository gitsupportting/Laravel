@extends('layouts.internal')
@section('title', $policy->name)
@section('content')
<div class="content-wrapper course-admin" data-simplebar>
    <section class="section-policy">
        <div class="container">
            <div class="card card-policy">
                <div class="card-body">
                    <div class="card-header">
                        <h6>{{$policy->name}}</h6>
                        <p>Created by {{$policy->name_on_policy}}, Version {{$policy->version}}, Last Updated {{$policy->date->format('d/m/Y')}}</p>
                    </div>
                    <h2 class="policy-title">{{$policy->name}}</h2>
                    {!! $policy->description !!}
                </div>
            </div>
            <div class="policy-btn-left">
                <a href="{{route('home', ['anchor' => 'policies'])}}" class="btn btn-primary btn-square">Back</a>
                <a href="{{route('policy.read', $policy)}}" class="btn btn-primary ml-4">Mark as read and understood</a>
            </div>
        </div>
    </section>
</div>
@endsection
