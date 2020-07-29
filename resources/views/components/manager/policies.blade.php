<div class="table-control--top">
    <div class="table-control__inner">
        <a class="btn btn-primary btn-square button-external-policy" href="{{route('policies.create')}}">Add Policy</a>
    </div>
</div>
<div class="table-responsive">
    <table class="table manager-table data-table" id="all-staff-table">
        <thead>
        <tr>
            <th scope="col">Policy Name</th>
            <th scope="col" class="text-center">Assigned Employees</th>
            <th scope="col" class="text-center">Marked As Read</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($policies as $policy)
            <tr>
                <td>{{$policy->name}}</td>
                <td class="text-center">{{$policy->assignees->count()}}</td>
                <td class="text-center">{{$policy->graduates->count()}}</td>
                <td>
                    <a href="{{route('policies.stats', $policy)}}" class="btn btn-blue btn-square">Acceptance</a>
                    <a href="{{route('policy.show', $policy)}}" class="btn btn-blue btn-square">View</a>
                    <a href="{{route('policies.edit', $policy)}}" class="btn btn-blue btn-square">Edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
