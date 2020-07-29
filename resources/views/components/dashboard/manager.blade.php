<div class="section-manager__content ">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link  {{empty(request('anchor'))?'active':''}}" id="all-staff-tab" data-toggle="tab" href="#all-staff" role="tab" aria-controls="all-staff" aria-selected="true">All Staff</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{request('anchor')=='courses'?'active':''}}" id="all-course-tab" data-toggle="tab" href="#all-course" role="tab" aria-controls="all-course" aria-selected="false">All Courses</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{request('anchor')=='policies'?'active':''}}" id="all-policies" data-toggle="tab" href="#policies" role="tab" aria-controls="job-roles" aria-selected="false">Policies</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{request('anchor')=='job-roles'?'active':''}}" id="all-job-roles" data-toggle="tab" href="#job-roles" role="tab" aria-controls="job-roles" aria-selected="false">Job Roles</a>
        </li>
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link" id="setting-tab" data-toggle="tab" href="#setting" role="tab" aria-controls="setting" aria-selected="false">Settings</a>--}}
{{--        </li>--}}
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show  {{empty(request('anchor'))?'show active':''}}" id="all-staff" role="tabpanel" aria-labelledby="all-staff-tab">
            <div class="table-card">
                @component('components.manager.employees')
                    @slot('employees', $employees)
                @endcomponent
            </div>
        </div>
        <div class="tab-pane fade {{request('anchor')=='courses'?'show active':''}}" id="all-course" role="tabpanel" aria-labelledby="all-course-tab">
            <div class="table-card">
                @component('components.manager.courses')
                    @slot('courses', $courses)
                @endcomponent
            </div>
        </div>
        <div class="tab-pane fade {{request('anchor')=='job-roles'?'show active':''}}" id="job-roles" role="tabpanel" aria-labelledby="job-roles-tab">
            <div class="table-card">
                @component('components.manager.jobRoles')
                    @slot('jobRoles', $jobRoles)
                    @slot('courses', $courses)
                    @slot('policies', $policies)
                @endcomponent
            </div>
        </div>
        <div class="tab-pane fade {{request('anchor')=='policies'?'show active':''}}" id="policies" role="tabpanel" aria-labelledby="policies-tab">
            <div class="table-card">
                @component('components.manager.policies')
                    @slot('policies', $policies)
                @endcomponent
            </div>
        </div>
{{--        <div class="tab-pane fade settings" id="setting" role="tabpanel" aria-labelledby="setting-tab">...</div>--}}

    </div>
</div>
