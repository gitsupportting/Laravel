@extends('layouts.internal')
@section('title', 'Edit a course')
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
                                <form class="form form-admin" action="{{route('courses.update', $entity)}}"
                                      method="post"
                                      enctype="multipart/form-data">
                                    @csrf
                                    {{method_field('PUT')}}
                                    @include('admin.courses._form')
                                </form>
                                <form action="{{route('courses.destroy', $entity)}}" id="entity-delete-form"
                                      method="post">
                                    @csrf
                                    {{method_field('DELETE')}}
                                </form>
                                <hr>
                                <div class="table-control mt-0">
                                    <div class="table-control__inner">
                                        <a href="{{route('lessons.create', ['course_id' => $entity->id])}}"
                                           class="btn btn-primary btn-square">Add Lesson</a>
                                    </div>
                                </div>
                                <form action="">
                                    <div class="table-responsive">
                                        <table class="table course-lessons">
                                            <thead>
                                            <tr>
                                                <th scope="col" class="border-top-0 "></th>
                                                <th scope="col" class="border-top-0 ">Lesson Name</th>
                                                <th scope="col" class="border-top-0 text-center">Slide Count</th>
                                                <th scope="col" class="border-top-0 "></th>
                                            </tr>
                                            </thead>
                                            <tbody data-url="{{route('course.sortLessons')}}">
                                            @foreach($entity->lessons as $lesson)
                                                <tr>
                                                    <th scope="row">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="hidden" name="lesson[]" value="{{$lesson->id}}">
                                                            <input type="checkbox" class="custom-control-input"
                                                                   id="lesson-{{$lesson->id}}">
                                                            <label class="custom-control-label"
                                                                   for="lesson-{{$lesson->id}}"></label>
                                                        </div>
                                                    </th>
                                                    <td>{{$lesson->name}}</td>
                                                    <td class="text-center">{{$lesson->slides->count()}}</td>
                                                    <td><a href="{{route('lessons.edit', $lesson)}}"
                                                           class="btn btn-blue btn-square link">Edit</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.course-lessons tbody').sortable({
                update: function (event, ui) {
                    var data = $('.course-lessons tbody').closest('form').serialize();
                    $.ajax({
                        data: data,
                        type: 'POST',
                        url: $('.course-lessons tbody').data('url'),
                    });
                }
            });
        });
    </script>
@append
