@extends('layouts.internal')
@section('title', 'Edit External Course')
@section('content')
    <div class="content-wrapper container" data-simplebar>
        <section class="section-manager">
            <div class="section-manager__content add-course">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active">
                        <div class="form-card">
                            <div class="form-header">
                                <h2>@yield('title')</h2>
                            </div>
                            <div class="form-body">
                                <form class="form form-admin" action="{{route('courses.updateExternal', $course)}}" method="post"
                                      enctype="multipart/form-data">
                                    @csrf
                                    {{method_field('PUT')}}
                                    @include('admin.courses._externalForm')
                                </form>
                                <form action="{{route('courses.destroyExternal', $course)}}" id="courses-delete-form" method="post">
                                    @csrf
                                    {{method_field('DELETE')}}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection
