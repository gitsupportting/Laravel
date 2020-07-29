<div class="table-control--top">
    <div class="table-control__inner manager">
        <a href="{{route('groups.create')}}" class="btn btn-primary btn-square">Add Group</a>
        <button class="btn btn-primary btn-square" onclick="$('#bulk-delete-groups-form').submit()">Remove Group</button>
    </div>
</div>
<div class="table-responsive">
    <form action="{{route('groups.bulk-delete')}}" method="post" class="bulk-delete-form" id="bulk-delete-groups-form">
        @csrf
        <table class="table">
            <thead>
            <tr>
                <th scope="col" class="border-top-0 border-bottom-0"></th>
                <th scope="col" class="border-top-0 border-bottom-0">Group Name</th>
                <th scope="col" class="border-top-0 border-bottom-0">Group Contact</th>
                <th scope="col" class="border-top-0 border-bottom-0">Organisations</th>
                <th scope="col" class="border-top-0 border-bottom-0"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($groups as $i => $group)
            <tr>
                <td scope="row">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="cb-a{{$i}}" name="ids[]" value="{{$group->id}}">
                        <label class="custom-control-label" for="cb-a{{$i}}"></label>
                    </div>
                </td>
                <td>{{$group->name}}</td>
                <td>
                    @if($group->managers->isNotEmpty())
                        {{$group->managers->first()->email}}
                    @endif
                </td>
                <td>{{$group->organizations_count}}</td>
                <td><a href="{{route('groups.edit', $group)}}" class="btn btn-blue btn-square">Edit</a></td>
            </tr>
            @endforeach
        </table>
    </form>
</div>


