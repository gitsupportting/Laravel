@extends('layouts.internal')
@section('title', 'Edit organization')
@section('content')
    <div class="content-wrapper container add-ministrator" data-simplebar>
        <section class="section-manager">
            <div class="section-manager__content ">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active">
                        <div class="form-card">
                            <div class="form-header">
                                <h2>@yield('title')</h2>
                            </div>
                            <div class="form-body">
                                <form class="form form-admin" action="{{route('organizations.update', $entity)}}"
                                      method="post">
                                    @csrf
                                    {{method_field('PUT')}}
                                    @include('admin.organizations._form')
                                </form>
                                <form action="{{route('organizations.destroy', $entity)}}" id="entity-delete-form"
                                      method="post">
                                    @csrf
                                    {{method_field('DELETE')}}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
