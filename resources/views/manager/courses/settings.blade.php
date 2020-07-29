@extends('layouts.internal')
@section('title', 'Course settings')
@section('content')
    <div class="content-wrapper container" data-simplebar>
        <section class="section-manager">
            <div class="section-manager__content manager-detail">
                <div class="tab-content" id="managerDetailsContent">
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="manager-details-card">
                            <div class="form-card">
                                <div class="form-header">
                                    <h2>Course Settings</h2>
                                </div>
                                <div class="form-body">
                    <form action="{{route('courses.storeSettings', $course)}}" method="post">
                        {{csrf_field()}}
                                <div>
                                    <div class="row mb-4">
                                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label class="c-s__label">What is the minimum score to pass?</label>
                                        </div>
                                        <div class="col col-12 col-sm-12 col-md-3 col-lg-3">
                                            <div class="course-control__group">
                                                <div class="form-group mb-0">
                                                    <input id="score_percentage" type="number" name="settings[score]" value="{{old('settings.score', $courseSettings->settings['score'])}}" required
                                                           min="1" max="100" class="form-control"/>
                                                </div>
                                                <a href="javascript:;" class="tooltip-icon" data-toggle="tooltip"
                                                   data-placement="right"
                                                   title="A score below this value would appear in the low-scoring report."><i
                                                        class="fas fa-info"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label class="c-s__label">How often should employees retake this course?
                                                (Months)</label>
                                        </div>
                                        <div class="col col-12 col-sm-12 col-md-3 col-lg-3">
                                            <div class="course-control__group">
                                                <div class="form-group mb-0">
                                                    <input type="number" name="settings[retake_month]" min="0" required
                                                           max="48"  value="{{old('settings.retake_month', $courseSettings->settings['retake_month'])}}"
                                                           class="form-control"/>
                                                </div>
                                                <a href="javascript:;" class="tooltip-icon" data-toggle="tooltip"
                                                   data-placement="right"
                                                   title="This setting defines how often an employee should take this course."><i
                                                        class="fas fa-info"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label class="c-s__label">Re-enable the course before it's due
                                                (Months)</label>
                                        </div>
                                        <div class="col col-12 col-sm-12 col-md-3 col-lg-3">
                                            <div class="course-control__group">
                                                <div class="form-group mb-0">
                                                    <input type="number" name="settings[due_re_enable]"
                                                           value="{{old('settings.due_re_enable', $courseSettings->settings['due_re_enable'])}}" min="0" max="8"
                                                           required class="form-control"/>
                                                </div>
                                                <a href="javascript:;" class="tooltip-icon" data-toggle="tooltip"
                                                   data-placement="right"
                                                   title="This setting defines when the course should be re-enabled before it is due."><i
                                                        class="fas fa-info"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6">
                                            <label class="c-s__label">Send an email to the employee when the course is
                                                due?</label>
                                        </div>
                                        <div class="col col-12 col-sm-12 col-md-3 col-lg-3">
                                            <div class="course-control__group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                           name="settings[due_notify_employee]" value="1"
                                                           {{old('settings.due_notify_employee', @$courseSettings->settings['due_notify_employee']) ? 'checked' : ''}}
                                                           id="customCheck1">
                                                    <label class="custom-control-label" for="customCheck1"> </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-control mt-5 mb-0">
                                        <div class="table-control__inner">
                                            <div class="button-left">
                                                <a href="{{route('home', ['anchor' => 'courses'])}}" class="btn btn-primary btn-square">Back</a>
                                            </div>
                                            <div class="button_right">
                                                <a href="{{route('home', ['anchor' => 'courses'])}}" class="btn btn-primary btn-square">Cancel</a>
                                                <button type="submit" class="btn btn-primary btn-square">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
