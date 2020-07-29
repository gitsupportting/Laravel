@extends('layouts.internal')
@section('title', 'Add Policy')
@section('content')
    <div class="content-wrapper container" data-simplebar>
        <section class="section-manager">
            <div class="section-manager__content add-policy">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active">
                        <div class="form-card">
                            <div class="form-header">
                                <h2>@yield('title')</h2>
                            </div>
                            <div class="form-body">
                                <form class="form form-admin" action="{{route('policies.store')}}" method="post"
                                      enctype="multipart/form-data">
                                    @csrf
                                    @include('manager.policies._form')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
