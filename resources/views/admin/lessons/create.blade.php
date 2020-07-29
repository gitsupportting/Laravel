@extends('layouts.internal')
@section('title', 'Add lesson')
@section('content')
    <div class="content-wrapper container" data-simplebar>
        <section class="section-manager">
            <div class="section-manager__content add-lesson">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active">
                        <div class="form-card" style="padding: 0 60px 60px;">
                            <div class="form-header">
                                <h2>@yield('title')</h2>
                            </div>
                            <div class="form-body">
                                <form class="form form-admin" action="{{route('lessons.store')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{$courseId}}">
                                    @include('admin.lessons._form')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
