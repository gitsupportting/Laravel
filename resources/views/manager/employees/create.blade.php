@extends('layouts.internal')
@section('title', 'Add employee')
@section('content')
    <div class="content-wrapper container" data-simplebar>
        <section class="section-manager">
            <div class="section-manager__content manager-detail">
                <div class="tab-content" id="managerDetailsContent">
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="manager-details-card">
                            <div class="form-card">
                                <div class="form-header">
                                    <h2>Add Employee</h2>
                                </div>
                                <div class="form-body">
                                    <form class="form form-admin js-add-employee-form" method="post" id="employee-form"
                                          action="{{route('employees.store')}}">
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
                                            <button onclick="$('#employee-form').submit();"
                                                    class="btn btn-primary btn-square">Save
                                            </button>
                                        </div>
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
