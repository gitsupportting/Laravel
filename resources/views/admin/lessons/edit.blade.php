@extends('layouts.internal')
@section('title', 'Edit a lesson')
@section('content')
    <div class="content-wrapper container" data-simplebar>
        <section class="section-manager">
            <div class="section-manager__content add-lesson">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active">
                        <div class="form-card">
                            <div class="form-header">
                                <h2>@yield('title')</h2>
                            </div>
                            <div class="form-body">
                                <form class="form form-admin" action="{{route('lessons.update', $entity)}}"
                                      method="post">
                                    @csrf
                                    {{method_field('PUT')}}
                                    @include('admin.lessons._form')
                                </form>
                                <form action="{{route('lessons.destroy', $entity)}}" id="entity-delete-form"
                                      method="post">
                                    @csrf
                                    {{method_field('DELETE')}}
                                </form>
                                <div style="height: 30px;"></div>
                                <hr>
                                <div style="height: 30px;"></div>
                                <div class="table-control mt-0 mb-4">
                                    <div class="table-control__inner">
                                        <a href="{{route('slides.create', ['lesson_id' => $entity->id])}}"
                                           class="btn btn-primary btn-square">Add Slide</a>
                                    </div>
                                </div>
                                <div class="add-lesson">
                                    @component('components.admin.lessonSlides')
                                        @slot('slides', $entity->slides)
                                    @endcomponent
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
