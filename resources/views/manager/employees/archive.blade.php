@extends('layouts.internal')
@section('title', 'Past Employees')
@section('content')
    <div class="content-wrapper container" data-simplebar>
        <section class="section-manager">
            <div class="section-manager__content">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active">
                        <div class="form-card">
                            <div class="form-header mb-4">
                                <h2>@yield('title')</h2>
                            </div>
                            <div class="table-control--top">
                                <div class="table-control__inner">
                                    <button class="btn btn-primary btn-square js-bulk-action-employees" data-href="{{route('employees.bulkRestore')}}">Reinstate</button>
                                    <button class="btn btn-warning btn-square js-bulk-action-employees" data-href="{{route('employees.bulkDelete')}}">Permanently Delete</button>
                                </div>
                            </div>
                            <form action="#" method="post" id="js-bulk-action-form">
                                {{csrf_field()}}
                                <div class="table-responsive">
                                    <table class="table manager-table data-table" id="all-staff-table">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th scope="col">First Name</th>
                                            <th scope="col">Last Name</th>
                                            <th scope="col">DOB</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Completed</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($employees as $employee)
                                            <tr>
                                                <th scope="row">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="user_id[]"
                                                               class="js-employee-id custom-control-input"
                                                               id="employee-{{$employee->id}}"
                                                               value="{{$employee->id}}">
                                                        <label class="custom-control-label"
                                                               for="employee-{{$employee->id}}"></label>
                                                    </div>
                                                </th>
                                                <td>
                                                    <a href="{{route('employees.edit', $employee)}}">{{$employee->first_name}}</a>
                                                </td>
                                                <td>{{$employee->last_name}}</td>
                                                <td>{{optional($employee->date_of_birth)->format('d/m/Y')}}</td>
                                                <td>{{$employee->username}}</td>
                                                <td style="text-align: left">{{$employee->completedCourses->count()}}
                                                    of {{$employee->courses->count()}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                            <a href="{{route('home')}}" class="btn btn-primary btn-square">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @component('components.popups.noEmployeesSelected')
        @slot('title', 'Assign a Course')
        @slot('class', 'assign')
    @endcomponent
@endsection
