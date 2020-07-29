<form action="{{route('employees.import')}}" method="post" enctype="multipart/form-data" style="display: none">
    @csrf
    <input type="file" name="csvFile" id="js-import-file" accept="text/csv">
</form>
<div class="table-control--top">
    <div class="table-control__inner">
        <div class="dropdown dropdown-employees">
            <button class="btn btn-primary btn-square dropdown-toggle" type="button" id="employees-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Employees </button>
            <ul class="list-unstyled dropdown-menu" aria-labelledby="employees-dropdown">
                <li><a class="dropdown-item" href="{{route('employees.create')}}">Add Employee</a></li>
                <li><a class="dropdown-item js-import-employees" data-href="{{route('employees.import')}}" href="#">Import Employees</a></li>
                <li><a class="dropdown-item js-archive-employees" data-href="{{route('employees.bulkArchive')}}" href="#">Archive Employees</a></li>
                <li>
                    <a class="dropdown-item" href="{{route('employees.viewArchive')}}">Past Employees</a>
                </li>
            </ul>
        </div>
        <div class="dropdown pr-4">
            <button class="btn btn-primary btn-square dropdown-toggle" type="button" id="employees-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Courses </button>
            <ul class="list-unstyled dropdown-menu" aria-labelledby="employees-dropdown">
                <li><a class="dropdown-item btn-assign-show-popup" href="#"  data-toggle="modal" data-target="#assign-course-no-users-selected">Assign Course</a></li>
                <li><a class="dropdown-item btn-markCompleted-show-popup" href="#"  data-toggle="modal" data-target="#markCompleted-course-no-users-selected">Mark External Courses</a></li>
            </ul>
        </div>
        <a class="btn btn-primary btn-square" id="employees-dropdown" href="{{ route('reports.organization') }}">Reporting</a>
    </div>
</div>
<div class="table-responsive">
    <form action="" method="post" id="js-employees-form">
        @csrf
        <table class="table manager-table data-table" id="all-staff-table">
            <thead>
            <tr>
                <th ></th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Mobile</th>
                <th scope="col">Username</th>
                <th scope="col">Completed</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($employees as $employee)
                <tr>
                    <th scope="row">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="user_id[]" class="js-employee-id custom-control-input" id="employee-{{$employee->id}}" value="{{$employee->id}}"
                            @if($employee->isManager()) disabled @endif>
                            <label class="custom-control-label" for="employee-{{$employee->id}}"></label>
                        </div>
                    </th>
                    <td><a href="{{route('employees.edit', $employee)}}" >{{$employee->first_name}}</a></td>
                    <td>{{$employee->last_name}}</td>
                    <td>{{$employee->phone}}</td>
                    <td>{{$employee->username}}</td>
                    <td>{{$employee->completedCoursesHistory->count()}} of {{$employee->coursesHistory->count()}}</td>
                    <td>
                        <a href="{{route('employees.edit', $employee)}}" class="btn btn-blue btn-square">Edit</a>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </form>
</div>
