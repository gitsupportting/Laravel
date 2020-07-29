<table class="table table-matrix">
    <thead>
    @if($policies->isNotEmpty())
        <tr>
            <th></th>
            @foreach($policies as $policy)
                <th>{{$policy->name}}</th>
            @endforeach
        </tr>
    @else
        <th>No data to display</th>
    @endif
    </thead>
    <tbody>
    @foreach($employees as $employee)
        <tr>
            <th>
                <span class="name"
                      @if($employee->on_leave)
                      style="text-decoration: line-through;"
                      title="On Leave"
                      @endif
                >
                    {{ $employee->name }}
                </span>
                @if($employee->on_leave)
                    <span class="d-none">On Leave</span>
                @endif
                <span class="designation">
                    {{ $employee->jobRole ? $employee->jobRole->name : $employee->role->display_name }}</span>
                </th>
            @foreach($policies as $policy)
                @php
                    $employeePolicy = $employee->policies->keyBy('id')->get($policy->id);
                @endphp
                <td class="
                    @if ($employeePolicy)
                        @if($employeePolicy->pivot->read_at)
                    background-color: #ceffe6;
@else
                    background-color: #fad7db;
@endif
                @endif
                    ">
                    <span data-toggle="tooltip" data-html="true" title=""
                       data-original-title="
                            <div class='matrix-tooltip'>
                           <span>
                           @if($employeePolicy && $employeePolicy->pivot->read_at)
                             Read at: {{$employeePolicy->pivot->read_at->format('d/m/Y')}}
                           @endif
                           @if($employee->on_leave)
                                <span>On Leave</span>
                            @endif
                           </span>
                           </div>">
                        <span class="date">
                             @if($employeePolicy && $employeePolicy->pivot->read_at)
                             {{$employeePolicy->pivot->read_at->format('d/m/Y')}}
                             @else
                             &mdash; <br/>
                            @endif
                        </span>
                    </span>
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
