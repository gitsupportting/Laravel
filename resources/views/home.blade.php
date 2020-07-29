@extends('layouts.internal')

@section('title', 'Dashboard')

@section('content')
<div class="content-wrapper {{!Auth::user()->isAdmin()?:' administrator course-admin'}}" data-simplebar>
    <section class="section-manager container ketan">
        @if (session('flash_notification'))
           <!-- <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>-->
			@foreach (session('flash_notification', collect())->toArray() as $message)
				@if ($message['overlay'])
					@include('flash::modal', [
						'modalClass' => 'flash-modal',
						'title'      => $message['title'],
						'body'       => $message['message']
					])
				@else
					<div class="alert
								alert-{{ $message['level'] }}
					{{ $message['important'] ? 'alert-important' : '' }}"
						 role="alert"
					>
						<button type="button"
								class="close"
								data-dismiss="alert"
								aria-hidden="true"
						>&times;</button>

						{!! $message['message'] !!}
					</div>
				@endif
			@endforeach

			{{ session()->forget('flash_notification') }}
        @endif
        @component('components.dashboard.'.Auth::user()->role->name)
        @endcomponent
    </section>
</div>

    @component('components.popups.employeesAssignCourse')
    @endcomponent
    @component('components.popups.employeesCompleteCourse')
    @endcomponent
    @component('components.popups.courseAssignEmployees')
    @endcomponent
@endsection
