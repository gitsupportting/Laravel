@extends('layouts.internal')
@section('title', $lesson->course->name)
@section('navbarPrepend')
    <h2 class="navbar-title">{{$lesson->course->name}}</h2>
@endsection
@section('headerClass')
@section('content')
    <div class="content-wrapper slide-wrapper">
        <div class="container">
            <div class="slide-card slide-carousel">
                @foreach($lesson->slides as $i => $slide)
                <div class="slide-card__item {{$i>0?'':''}}">
                    <div class="slide-content" data-type="{{$slide->type}}" data-simplebar="init">
                        @component('components.slides.'.$slide->type.'Slide')
                            @slot('slide', $slide)
                        @endcomponent
                    </div>
                </div>
                @endforeach
            </div>
            <div class="slide_nav">
                <div class="slide_nav__inner">
                    <button class="prev-slide slide-btn btn-primary"><i class="fas fa-arrow-left"></i></button>

                    <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                        <div class="slider__label"><span class="slide-count-tooltip" data-toggle="tooltip" data-placement="top" title="Progress 1 of {{$lesson->slides->count()}}"></span></div>
                    </div>
                    <button data-next-url="{{$nextPageUrl}}" class="next-slide slide-btn btn-slide-blue">Next&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
        </div>
        <footer class="main-footer">
            <div class="container">
                <p class="copy-right">
                    <svg xmlns="http://www.w3.org/2000/svg" width="9.86" height="10.068" viewBox="0 0 9.86 10.068">
                        <path id="Icon_metro-copyright" data-name="Icon metro-copyright"
                              d="M12.152,10.608v.714a.839.839,0,0,1-.234.583,1.554,1.554,0,0,1-.6.4,4.341,4.341,0,0,1-.757.213,4.129,4.129,0,0,1-.754.072,2.936,2.936,0,0,1-2.2-.911,3.131,3.131,0,0,1-.883-2.268,3.049,3.049,0,0,1,.873-2.222A2.922,2.922,0,0,1,9.77,6.295a4.438,4.438,0,0,1,.485.029,4.066,4.066,0,0,1,.6.118,3.022,3.022,0,0,1,.594.223,1.284,1.284,0,0,1,.443.37.854.854,0,0,1,.18.531v.714a.092.092,0,0,1-.1.1h-.757a.092.092,0,0,1-.1-.1V7.822q0-.282-.42-.442A2.453,2.453,0,0,0,9.8,7.219a1.93,1.93,0,0,0-1.467.6,2.171,2.171,0,0,0-.568,1.557,2.337,2.337,0,0,0,.587,1.635,1.937,1.937,0,0,0,1.5.646,2.669,2.669,0,0,0,.886-.157q.449-.157.449-.433v-.459a.1.1,0,0,1,.029-.075.091.091,0,0,1,.067-.029h.764a.1.1,0,0,1,.071.029.1.1,0,0,1,.032.075ZM9.7,5.246a3.923,3.923,0,0,0-1.6.334,4.144,4.144,0,0,0-1.31.895,4.243,4.243,0,0,0-.876,1.337,4.215,4.215,0,0,0,0,3.258A4.243,4.243,0,0,0,6.8,12.407,4.145,4.145,0,0,0,8.1,13.3a3.973,3.973,0,0,0,3.19,0,4.145,4.145,0,0,0,1.31-.895,4.243,4.243,0,0,0,.876-1.337,4.216,4.216,0,0,0,0-3.258A4.243,4.243,0,0,0,12.6,6.475,4.145,4.145,0,0,0,11.3,5.58a3.923,3.923,0,0,0-1.6-.334Zm4.93,4.195A5.043,5.043,0,0,1,12.175,13.8a4.873,4.873,0,0,1-4.949,0A5.043,5.043,0,0,1,4.77,9.441,5.043,5.043,0,0,1,7.225,5.082a4.873,4.873,0,0,1,4.949,0A5.043,5.043,0,0,1,14.63,9.441Z"
                              transform="translate(-4.77 -4.407)" fill="#d3d2d6"/>
                    </svg>
                    Correct Care 2020 All Rights Reserved
                </p>
            </div>
        </footer>
    </div>
    <form action="{{route('lesson.saveResults', $lesson)}}" method="post" id="js-store-lesson-results">
        {{csrf_field()}}
    </form>
@endsection
