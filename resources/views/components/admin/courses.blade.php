<div class="table-control--top">
    <div class="table-control__inner">
        <a href="{{route('courses.create')}}" class="btn btn-primary btn-square">Add Course</a>
        <button class="btn btn-primary btn-square" onclick="if(confirm('Are you sure you want to delete selected courses?')) $('#bulk-delete-courses-form').submit()">Remove Course</button>
    </div>
</div>
<div class="table-responsive">
    <form action="{{route('courses.bulk-delete')}}" class="bulk-delete-form" method="post" id="bulk-delete-courses-form">
        @csrf
        <table class="table">
            <thead>
            <tr>
                <th scope="col" class="border-top-0 border-bottom-0"></th>
                <th scope="col" class="border-top-0 border-bottom-0">Course Title</th>
                <th scope="col" class="border-top-0 border-bottom-0">Lessons</th>
                <th scope="col" class="border-top-0 border-bottom-0">Slides</th>
                <th scope="col" class="border-top-0 border-bottom-0">Enrolled Students</th>
                <th scope="col" class="border-top-0 border-bottom-0"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($courses as $course)
            <tr>
                <th scope="row">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" value="{{$course->id}}" id="cb-course-{{$course->id}}" name="ids[]">
                        <label class="custom-control-label" for="cb-course-{{$course->id}}"></label>
                    </div>
                </th>
                <td>{{$course->name}}</td>
                <td>{{$course->lessons->count()}}</td>
                <td>{{$course->slidesCount}}</td>
                <td>{{$course->enrolledStudentsCount}}</td>
                <td><a href="{{route('courses.edit', $course)}}" class="btn btn-blue btn-square">Edit</a></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </form>
</div>
