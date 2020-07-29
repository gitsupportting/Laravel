<div class="table-control--top">
    <div class="table-control__inner manager">
        <a href="{{route('users.create')}}" class="btn btn-primary btn-square">Add Global Admin</a>
        <button class="btn btn-primary btn-square" onclick="$('#bulk-delete-admins-form').submit()">Remove Admin</button>
    </div>
</div>
<div class="table-responsive">
    <form action="{{route('users.bulk-delete')}}" method="post" class="bulk-delete-form" id="bulk-delete-admins-form">
        @csrf
        <table class="table">
            <thead>
            <tr>
                <th scope="col" class="border-top-0 border-bottom-0"></th>
                <th scope="col" class="border-top-0 border-bottom-0">First Name</th>
                <th scope="col" class="border-top-0 border-bottom-0">Last Name</th>
                <th scope="col" class="border-top-0 border-bottom-0">Email</th>
                <th scope="col" class="border-top-0 border-bottom-0">Last Logon</th>
                <th scope="col" class="border-top-0 border-bottom-0"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($admins as $i => $admin)
            <tr>
                <td scope="row">
                    @if(Auth::id() != $admin->id)
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="cb-a{{$i}}" name="ids[]" value="{{$admin->id}}">
                        <label class="custom-control-label" for="cb-a{{$i}}"></label>
                    </div>
                    @endif
                </td>
                <td>{{$admin->first_name}}</td>
                <td>{{$admin->last_name}}</td>
                <td>{{$admin->email}}</td>
                <td>{{optional($admin->last_login_at)->format('d/m/Y')}}</td>
                <td><a href="{{route('users.edit', $admin)}}" class="btn btn-blue btn-square">Edit</a></td>
            </tr>
            @endforeach
        </table>
    </form>
</div>
