@csrf
<div class="row">
    <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
        <div class="form-group">
            <label class="form-lable">First Name</label>
            <input type="text" name="first_name" value="{{old('first_name', $employee->first_name)}}" class="form-control" placeholder="First Name" />
        </div>
    </div>
    <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
        <div class="form-group">
            <label class="form-lable">Last Name</label>
            <input type="text" name="last_name" value="{{old('last_name', $employee->last_name)}}" class="form-control" placeholder="Last Name" />
        </div>
    </div>
    <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
        <div class="form-group">
            <label class="form-lable">Email</label>
            <input type="email" name="email" value="{{old('email', $employee->email)}}" class="form-control"/>
        </div>
    </div>
    <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
        <div class="form-group">
            <label class="form-lable">Mobile</label>
            <input type="text" name="phone" value="{{old('phone', $employee->phone)}}" class="form-control"/>
        </div>
    </div>
    <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
        <div class="form-group">
            <label class="form-lable">Employee ID </label>
            <input type="text" name="employee_id" value="{{old('employee_id', $employee->employee_id)}}" class="form-control" >
        </div>
    </div>
    <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
        <div class="form-group">
            <label class="form-lable">On Leave </label>
            <select class="custom-select drop-select form-control" name="on_leave">
                <option value="0" {{ old('on_leave', $employee->on_leave) == 0 ? 'selected' : '' }}>No</option>
                <option value="1" {{ old('on_leave', $employee->on_leave) == 1 ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
    </div>
    <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
        <div class="form-group">
            <label class="form-lable">Access Level  </label>
            <select class="custom-select drop-select form-control" @if($employee->isPrimaryManager()) disabled @endif name="role_id">
                @foreach($roles as $role)
                    <option value="{{$role->id}}" {{$role->id == old('role_id', $employee->role_id) ? 'selected' : ''}}>
                        {{$role->display_name == 'Employee' ? 'Staff' : $role->display_name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
        <div class="form-group">
            <label class="form-lable">Job Role </label>
            <select class="custom-select drop-select form-control" name="job_role">
                <option selected></option>
                @foreach(\App\Models\JobRole::orderBy('name')->get() as $role)
                    <option value="{{$role->id}}" {{$role->id == old('job_role', $employee->job_role) ? 'selected' : ''}}>{{$role->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col col-12 col-md-12 col-lg-12 col-xl-12">
        <div class="form-group">
            <label class="form-lable">Username</label>
            <input type="text" name="username" id="username" readonly value="{{old('username', $employee->username)}}" class="form-control" placeholder="Logon Name" autocomplete="off" />
        </div>
    </div>
    <div class="col col-12 col-md-12 col-lg-12 col-xl-12">
        <div class="form-group">
            <label class="form-lable">Password</label>
            <input name="password" class="form-control" placeholder="Password" @if(!$employee->id) value="{{$password}}" typeof="text" @else type="password" @endif />
        </div>
    </div>
</div>

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                $('#username').removeAttr('readonly');
            }, 500);
        });
    </script>
@endsection
