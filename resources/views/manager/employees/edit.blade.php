@extends('layouts.internal')
@section('title', 'Edit employee')
@section('content')
    <div class="content-wrapper container" data-simplebar>
        <section class="section-manager">
            <div class="page-title"></div>

            <div class="section-manager__content manager-detail">
                <ul class="nav nav-tabs" id="managerDetails" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="course-history-tab" data-toggle="tab" href="#course-history" role="tab" aria-controls="course-history" aria-selected="false">Course History</a>
                    </li>
                </ul>
                <div class="tab-content" id="managerDetailsContent">
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="manager-details-card">
                            <div class="form-card">
                                <div class="form-header">
                                    <h2>{{$employee->name}}</h2>
                                </div>
                                <div class="form-body">
                                    <form class="form form-admin" method="post" id="employee-form" action="{{route('employees.update', $employee)}}">
                                        @method('PUT')
                                        @include('manager.employees._form')
                                    </form>
                                </div>
                                <div class="table-control man-details">
                                    <div class="table-control__inner">
                                        <div class="button-left">
                                            <a href="/" class="btn btn-primary btn-square">Back</a>
                                        </div>
                                        <div class="button_right">
                                            <a href="{{route('home')}}" class="btn btn-primary btn-square">Cancel</a>
                                            <button onclick="$('#employee-form').submit();" class="btn btn-primary btn-square">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="course-history" role="tabpanel" aria-labelledby="course-history-tab">
                        <div class="table-card">
                            <div class="table-control">
                                <div class="table-control__inner">
                                    <div class="dropdown select-dropdown">
                                        <button class="btn btn-primary btn-square dropdown-toggle dropdown-placeholder" type="button" id="employees-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Show All
                                        </button>
                                        <ul class="list-unstyled dropdown-menu" aria-labelledby="employees-dropdown">
                                            <li><a class="dropdown-item js-display-employee-courses" href="#">Show All</a></li>
                                            <li><a class="dropdown-item js-display-employee-courses" href="#">Uncompleted</a></li>
                                            <li><a class="dropdown-item js-display-employee-courses" href="#">Completed</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table manager-table" id="course-history-table">
                                    <thead>
                                    <tr>
                                        <th scope="col">Course Name</th>
                                        <th scope="col">Completed</th>
                                        <th scope="col">Score</th>
                                        <th scope="col">Completion Date</th>
                                        <th scope="col">Certificate</th>
                                        <th scope="col">External</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($coursesViews as $courseView)
                                    <tr class="course-row" data-completed="{{(int) $courseView->isFinished()}}">
                                        <td>{{$courseView->getName()}}</td>
                                        <td>{{$courseView->isFinished() ? 'Yes' : 'No'}}</td>
                                        @if($courseView->isFinished())
                                            <td>{{$courseView->getScore()}}%</td>
                                            <td>{{$courseView->getCompletedAt()->format('d/m/Y')}}</td>
                                            <td><a href="{{route('courses.showEmployeeCertificate', [$courseView->getCourse(), $employee, 'attempt' => $courseView->getCourse()->pivot->id])}}">View</a></td>
                                        @else
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        @endif
                                        <td>{{ $courseView->getCourse()->isExternal() ? 'Yes' : 'No'}}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-control bottom-con">
                                <div class="table-control__inner">
                                    <div class="button-left">
                                        <a href="{{route('home')}}" class="btn btn-primary btn-square">Back</a>
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
