@extends('layouts.internal')
@section('title', $policy->name)
@section('content')
    <div class="content-wrapper " data-simplebar>
        <section class="section-manager container">
            <div class="section-manager__content add-course">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="Courses-staff" role="tabpanel" aria-labelledby="Courses-tab">
                        <div class="form-card">
                            <div class="form-header">
                                <h2>{{$policy->name}}</h2>
                            </div>
                            <div class="form-body">
                                <div class="table-responsive">
                                    <table class="table data-table" id="lifting-table">
                                        <thead>
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col" class="text-center">Assigned</th>
                                            <th scope="col" class="text-center">Read and Accepted</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($employees as $employee)
                                            @if($policy->assignees->pluck('id')->contains($employee->id))
                                                <tr>
                                                    <td><a href="{{route('employees.show', $employee)}}">{{$employee->name}}</a></td>
                                                    <td class="text-center">{{$policy->assignees->pluck('id')->contains($employee->id) ? 'Yes' : 'No'}}</td>
                                                    @if ($policy->assignees->where('id', $employee->id)->first()->pivot->read_at)
                                                        <td class="text-center">{{$policy->assignees->where('id', $employee->id)->first()->pivot->read_at->format('d/m/Y')}}</td>
                                                    @else
                                                        <td class="text-center"> - </td>
                                                    @endif
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="table-control">
                                <div class="table-control__inner">
                                    <div class="button-left">
                                        <a href="{{route('home', ['anchor' => 'policies'])}}" class="btn btn-primary btn-square">Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
