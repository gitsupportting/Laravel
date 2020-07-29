<table style="margin: auto; text-align: center; " cellspacing="0">
    <thead>
    <tr>
        <th style="text-align: left; padding: 5px 10px; font-size: 14px; font-weight: bold;">Name</th>
        @foreach($courses as $course)
            <th style="text-align: center; padding: 5px 10px; font-size: 14px; font-weight: bold;" class="rotate">
                <div><span>{{$course->name}}</span></div>
            </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($employees as $employee)
        <tr>
            <td style="text-align: left; padding: 5px 15px; font-size: 14px;">{{$employee->name}}</td>
            @foreach($courses as $course)
                @php
                    $employeeCourse = $employee->$employeeCourses->where('id', $course->id)->first();
                    if($employeeCourse) {
                        $courseView = new App\Views\CourseView($employeeCourse, $employee);
                    } else {
                        $courseView = null;
                    }
                @endphp
                <td style="text-align: center; padding: 5px 15px; font-size: 14px;">
                    @if($courseView && $courseView->isFinished())
                    {{$courseView->getCompletedAt()->format('d/m/Y')}}
                    @elseif($courseView && $courseView->getCourse()->isDue($employee))
                        due
                    @elseif($courseView && $courseView->getCourse()->isOverdue($employee))
                        overdue
                    @else
                    &mdash;
                    @endif
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
