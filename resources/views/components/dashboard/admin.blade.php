<div class="section-manager__content ">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{empty(request('anchor'))?'active':''}}" id="Courses-tab" data-toggle="tab" href="#Courses-staff" role="tab" aria-controls="Courses" aria-selected="true">Courses</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{request('anchor')=='organizations'?'active':''}}" id="all-Organizations-tab" data-toggle="tab" href="#all-Organizations" role="tab" aria-controls="all-Organizations" aria-selected="false">Organizations</a>
        </li>
        @if(!auth()->user()->is_editor)
        <li class="nav-item">
            <a class="nav-link {{request('anchor')=='settings'?'active':''}}" id="setting-tab" data-toggle="tab" href="#setting" role="tab" aria-controls="setting" aria-selected="false">Administrators</a>
        </li>
        @endif
        <li class="nav-item">
            <a class="nav-link {{request('anchor')=='groups'?'active':''}}" id="group-tab" data-toggle="tab" href="#group" role="tab" aria-controls="group" aria-selected="false">Groups</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade {{empty(request('anchor'))?'show active':''}}" id="Courses-staff" role="tabpanel" aria-labelledby="Courses-tab">
            <div class="table-card">
                @component('components.admin.courses')
                    @slot('courses', $courses)
                @endcomponent
            </div>
        </div>
        <div class="tab-pane fade {{request('anchor')=='organizations'?'show active':''}}" id="all-Organizations" role="tabpanel" aria-labelledby="all-Organizations-tab">
            <div class="table-card">
                @component('components.admin.organizations')
                    @slot('organizations', $organizations)
                @endcomponent
            </div>
        </div>
        @if(!auth()->user()->is_editor)
        <div class="tab-pane fade {{request('anchor')=='settings'?'show active':''}}" id="setting" role="tabpanel" aria-labelledby="setting-tab">
            <div class="table-card">
                @component('components.admin.administrators')
                    @slot('admins', $admins)
                @endcomponent
            </div>
        </div>
        @endif
        <div class="tab-pane fade {{request('anchor')=='groups'?'show active':''}}" id="group" role="tabpanel" aria-labelledby="group-tab">
            <div class="table-card">
                @component('components.admin.groups')
                    @slot('groups', $groups)
                @endcomponent
            </div>
        </div>
    </div>
</div>
