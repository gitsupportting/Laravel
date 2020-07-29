<form action="{{route('courses.storeAssignments', $course)}}" id="js-course-assignment-form" method="post">
    @csrf
    <div class="table-responsive">
        <table class="table manager-table data-table" id="all-staff-table">
            <thead>
            <tr>
                <th ></th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
            </tr>
            </thead>
            <tbody>
            @foreach($employees as $employee)
                <tr>
                    <th scope="row">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="user_id[]" class="custom-control-input" id="assignment-{{$course->id}}-{{$employee->id}}" value="{{$employee->id}}"
                                {{$course->isAssigned($employee) ? 'checked' : ''}}>
                            <label class="custom-control-label" for="assignment-{{$course->id}}-{{$employee->id}}"></label>
                        </div>
                    </th>
                    <td class="text-left">{{$employee->first_name}}</td>
                    <td class="text-left">{{$employee->last_name}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</form>
