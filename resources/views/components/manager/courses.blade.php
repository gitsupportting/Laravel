<div class="table-control--top">
    <div class="table-control__inner">
        <a class="btn btn-primary btn-square button-external-course" href="{{route('courses.createExternal')}}">Add External Course</a>
    </div>
</div>
<div class="table-responsive">
    <table class="table manager-table data-table" id="all-staff-table">
        <thead>
        <tr>
            <th scope="col">Course Name</th>
            <th scope="col" class="text-center">Enrolled Employees</th>
            <th scope="col" class="text-center">Completed</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($courses as $course)
            <tr>
                <td>{{$course->name}} @if($course->isExternal()) (External) @endif</td>
                <td class="text-center">{{$course->assignees->count()}}</td>
                <td class="text-center">{{$course->graduates->count()}}</td>
                <td>
                    @if($course->isExternal())
                        <a href="{{route('courses.editExternal', $course)}}" class="btn btn-blue btn-square">Edit</a>
                    @else
                        <a href="{{route('courses.settings', $course)}}" class="btn btn-blue btn-square">Edit</a>
                    @endif
                    <button data-toggle="modal" data-url="{{route('courses.assign', $course)}}"
                            data-target="#assign-employees" class="btn btn-blue btn-square js-course-assign-employees">
                        Assign
                    </button>
                    <a href="{{route('course.managerView', $course)}}" class="btn btn-blue btn-square">Take</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
