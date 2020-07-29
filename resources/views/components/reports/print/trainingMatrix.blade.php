<table class="table table-matrix">
    <thead>
    @if($courses->isNotEmpty())
        <tr>
            <th></th>
            @foreach($courses as $course)
                <th>{{$course->name}}</th>
            @endforeach
        </tr>
    @else
        <th>No data to display</th>
    @endif
    </thead>
    <tbody>
    @foreach($employees as $employee)
        <tr>
            <th>
                                    <span class="name"
                                          @if($employee->on_leave)
                                          style="text-decoration: line-through;"
                                          title="On Leave"
                                          @endif
                                    >
                                        {{ $employee->name }}
                                    </span>
                <span class="designation">{{ $employee->jobRole ? $employee->jobRole->name : $employee->role->display_name }}</span>
                @if($employee->on_leave)
                    <span class="d-none">On Leave</span>
                @endif
            </th>
            @foreach($courses as $course)
                @php
                    $employeeCourse = $employee->$employeeCourses->where('id', $course->id)->first();
                    if($employeeCourse) {
                        $courseView = new App\Views\CourseView($employeeCourse, $employee);
                    } else {
                        $courseView = null;
                    }

                @endphp
                <td style="
                @if($courseView)
                    @if($employeeCourse->isDueSoon($employee))
                        background-color: #fad7db;
                    @elseif($employeeCourse->isOverdue($employee))
                        background-color: #ffa1b1;
                    @elseif($courseView->isFinished())
                        background-color: #ceffe6;
                    @else
                        background-color: #f9f6af;
                    @endif
                @endif
                    ">
                    <span  data-toggle="tooltip" data-html="true" title=""
                       data-original-title="
                                           <div class='matrix-tooltip'>
                                                <span>
                                                @if($courseView && $courseView->isFinished())
                           Completed: {{$courseView->getCompletedAt()->format('d/m/Y')}}
                       @endif
                           </span>
                           <span>
@if($courseView)
                           Next Due: {{ $courseView->getDueDate($organization)->format('d/m/Y') }}
                       @endif</span>
                                                <span>
                                                @if($courseView)
                           Status: {{ $courseView->getState($organization) }}
                       @endif</span>
                                                 @if($employee->on_leave)
                           <span>On Leave</span>
@endif
                           </div>">
                                            <span class="date">
                                                @if($courseView && $courseView->isFinished())
                                                    @if($courseView->getCourse()->isExternalHistory())
                                                        *@endif
                                                    {{$courseView->getCompletedAt()->format('d/m/Y')}} <br/>
                                                    @else
                                                    &mdash; <br/>
                                                @endif
                                            </span>
                        <span class="date text-gray">
                                                  @if($courseView) {{ $courseView->getDueDate($organization)->format('d/m/Y') }} @endif
                                            </span>
                    </span>
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
