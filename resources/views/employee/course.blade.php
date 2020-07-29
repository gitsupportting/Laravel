@extends('layouts.internal')
@section('title', $course->name)
@section('navbarPrepend')
@endsection
@section('content')
<div class="content-wrapper" data-simplebar>
    <div class="container">
        <div class="staff-list">
            <div class="card single-staff-card">
                <div class="single-staff-card__header">
                    <h1 class="course-name">{{$course->name}}</h1>
                </div>
                <div class="card-body">
                    <h3 class="single-staff__label">Description</h3>
                    <p>{{$course->description}}</p>
                    @if(!empty($course->duration))
                        <h5 class="course-duration mb-3">Course Duration <span>{{$course->duration}}</span></h5>
                    @endif
                    @if(!empty($course->props->start_date))
                        <h5 class="course-duration mb-3">Date and time of course <span>{{$course->props->start_date->format('d/m/Y H:ia')}}</span></h5>
                    @endif
                    @if(!empty($course->subjects))
                    <div class="single-staff-detail">
                        <h3 class="single-staff__label">You will learn</h3>
                        <ul class="list-unstyled single-staff-list">
                            @foreach($lessons as $i => $lessonView)
                            <li><i class="fas fa-check"></i> {{$lessonView->getName()}}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if(!empty($lessons))
                    <div class="lessons-box">
                        <h3 class="single-staff__label">Lessons</h3>
                        <table class="lessons-table table table-borderless table-striped">
                            <tbody>
                            @foreach($lessons as $i => $lessonView)
                            <tr>
                                <td><i class="fas {{$lessonView->getFaIcon()}}"></i> {{$i+1}}. {{$lessonView->getName()}}</td>
                                <td>
                                    @if($lessonView->isFinished())
                                        Completed {{$lessonView->getFinishDate()->format('d/m/Y')}}
                                    @endif
                                    @if($lessonView->canBeStarted())
                                        <a href="{{route('lesson.show', $lessonView->getId())}}" class="btn btn-black btn-block btn-sm">Start</a>
                                    @endif
                                </td>
                                <td style="width: 50%">
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    <div class="table-control mt-5 mb-0">
                        <div>
                            <div class="button-left">
                                @if(auth()->user()->isEmployee())
                                    <a href="/" class="btn btn-primary btn-square">Back</a>
                                @else
                                    <a href="{{route('home', ['anchor' => 'courses'])}}" class="btn btn-primary btn-square">Back</a>
                                @endif
                                @if(!$course->isExternal() && $course->isCompletedBy(auth()->user()))
                                    <a href="{{route('course.retake', $course->id)}}" class="btn btn-black btn-sm" style="margin-left: 30px;">Retake</a>
                                @endif
                                @if($course->isExternal())
                                    <form action="{{ route('course.markCompleteExternal', $course) }}" method="post" class="d-inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-square ml-4">Mark course as complete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
