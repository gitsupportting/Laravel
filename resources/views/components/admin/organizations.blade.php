<div class="table-control--top">
    <div class="table-control__inner">
        <a href="{{route('organizations.create')}}" class="btn btn-primary btn-square">Add Organizations</a>
        <button class="btn btn-primary btn-square" onclick="if(confirm('Are you sure you want to delete selected organizations?')) $('#bulk-delete-organizations-form').submit()">Remove Organizations</button>
    </div>
</div>
<div class="table-responsive">
    <form action="{{route('organizations.bulk-delete')}}" method="post" class="bulk-delete-form" id="bulk-delete-organizations-form">
        @csrf
        <table class="table">
            <thead>
            <tr>
                <th scope="col" class="border-top-0 border-bottom-0"></th>
                <th scope="col" class="border-top-0 border-bottom-0">Organization</th>
                <th scope="col" class="border-top-0 border-bottom-0">Name</th>
                <th scope="col" class="border-top-0 border-bottom-0">Email</th>
                <th scope="col" class="border-top-0 border-bottom-0">Employee</th>
                <th scope="col" class="border-top-0 border-bottom-0">Last Logon</th>
                <th scope="col" class="border-top-0 border-bottom-0"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($organizations as $i => $organization)
            <tr>
                <td scope="row">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="cb-o{{$i}}" name="ids[]" value="{{$organization->id}}">
                        <label class="custom-control-label" for="cb-o{{$i}}"></label>
                    </div>
                </td>
                <td>{{$organization->name}}</td>
                <td>{{$organization->manager->first_name}} {{$organization->manager->last_name}}</td>
                <td>{{$organization->manager->email}}</td>
                <td>{{$organization->employees->count()}}</td>
                <td>{{optional($organization->manager->last_login_at)->format('d/m/Y')}}</td>
                <td><a href="{{route('organizations.edit', $organization->id)}}" class="btn btn-blue btn-square">Edit</a></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </form>
</div>
