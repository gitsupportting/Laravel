@extends('layouts.internal')
@section('title', 'Add slide')
@section('content')
    <div class="content-wrapper container" data-simplebar>
        <section class="section-manager">
            <div class="section-manager__content add-slide">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active">
                        <div class="form-card">
							<div class="form-header">
								<h2>@yield('title')</h2>
							</div>
							<div class="form-body">
								<form class="form form-admin" action="{{route('slides.store')}}" method="post"
									  enctype="multipart/form-data">
									@csrf
									<input type="hidden" name="course_id" value="{{$lesson->course->id}}">
									@include('admin.slides._form')
								</form>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
