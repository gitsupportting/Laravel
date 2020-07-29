<p class="feature-description">Below are the courses that have been assigned to you or your job role, select a course to begin. During the course you will be asked a number of questions related to the subject you are learning, at the end of the course you will see your score showing how well you have done, do not worry if you fail on your first attempt, as you can retake the course as many times as required. If you have any questions regarding the course please speak to your manager.</p>

<div class="row">
    @foreach($courses as $course)
        <div class="col col-12 col-sm-12 col-md-6 col-lg-3">
            <div class="card card-feature-course" style="cursor: pointer; background-color: {{$course->bg_color ?? '#E8EDE3'}};"
            onclick="location.href=$(this).find('.js-course-show').prop('href')">
                <div class="card-body">
                    @if($course->isExternal())
                        <img src="{{asset('assets/images/external-course.jpg')}}" class="img-fluid card-img" alt="">
                    @else
                    <img src="{{$course->imageUrl}}" class="img-fluid card-img" alt="">
                    @endif
                    <h4><a href="{{route('course.show', $course)}}"> {{$course->name}}</a></h4>
                    <p><a href="{{route('course.show', $course)}}"> {{$course->description}}</a></p>
                </div>
                <div class="card-footer card-feature-course__status">
                    @if(!empty($course->time_to_complete))
                        <span class="time">{{$course->time_to_complete}}</span>
                    @endif
                    @if($course->isExternal())
                        <span class="time">{{$course->props->start_date ? $course->props->start_date->format('d/m/Y') : ''}}</span>
                        <a href="javascript:;" class="status js-course-show">External Course</a>
                    @else
                        @if(!empty($course->pivot->completed_at))
                            <a href="{{route('course.resume', $course)}}" class="status">
                                Completed {{Carbon\Carbon::parse($course->pivot->completed_at)->format('d-m-Y')}}
                            </a>
                        @elseif($course->pivot->score > 0)
                            <a href="{{route('course.resume', $course)}}" class="status js-course-show">Resume Course</a>
                        @else
                            <a href="{{route('course.show', $course)}}" class="status js-course-show">
                                @if($course->isDue($user))
                                    Due Soon
                                @elseif($course->isOverdue($user))
                                    Overdue
                                @else
                                    Start Course
                                @endif
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
