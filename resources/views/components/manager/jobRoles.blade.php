<div class="form-card job-roles-card">
    <div class="form-header">
        <h2>Assign Courses and Policies to Job Roles</h2>
    </div>
    <div class="form-body">
        <p>Select a job title from the menu below then choose the courses you want to assign to this role. Employees with this job role are then automatically assigned these courses.</p>
        <div class="jobrole-tab">
            <div class="dropdown">
                <a href="javascript:;" class="btn btn-primary dropdown-toggle jobrole-dropdown__link"
                   id="jobrole-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{$jobRoles[0]->name}} <span class="jobrole-count" style="text-align: center !important">{{$jobRoles[0]->courses->count()}}</span>
                </a>
                <div class="jobrole-tab__scroll dropdown-menu" data-simplebar="init" aria-labelledby="jobrole-dropdown">
                    <div class="nav nav-pills jobrole-tab__list" id="v-pills-tab" role="tablist" aria-orientation="vertical" >
                        @foreach($jobRoles as $i => $jobRole)
                        <a class="nav-link {{!$i?'active':''}}" id="jobrole-tab-{{$jobRole->id}}" data-toggle="pill" href="#jobrole{{$jobRole->id}}"
                           role="tab" aria-controls="jobrole" aria-selected="true">
                            {{$jobRole->name}} <span class="jobrole-count" style="text-align: center">{{$jobRole->courses->count()}}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="tab-content" id="v-pills-tabContent">
                @foreach($jobRoles as $i => $jobRole)
                <div class="tab-pane fade {{!$i?'show active':''}}" id="jobrole{{$jobRole->id}}" role="tabpanel" aria-labelledby="jobrole-tab">
                    <form action="{{route('job-role.assignCourses', $jobRole)}}" method="post" class="job-role-settings">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="jobrole-title">Courses</h4>
                                <ul class="list-unstyled list-jobroles">
                                    @foreach($courses as $course)
                                        <li>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="jobrole-checkbox-{{$jobRole->id}}-{{$course->id}}"
                                                       {{$jobRole->courses->pluck('id')->contains($course->id) ? 'checked' : ''}}
                                                       name="courses[]" value="{{$course->id}}">
                                                <label class="custom-control-label" for="jobrole-checkbox-{{$jobRole->id}}-{{$course->id}}">{{$course->name}}</label>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h4 class="jobrole-title">Policies</h4>
                                <ul class="list-unstyled list-jobroles">
                                    @foreach($policies as $policy)
                                        <li>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="jobrole-checkbox-policy-{{$jobRole->id}}-{{$policy->id}}"
                                                       {{$jobRole->policies->pluck('id')->contains($policy->id) ? 'checked' : ''}}
                                                       name="policies[]" value="{{$policy->id}}">
                                                <label class="custom-control-label" for="jobrole-checkbox-policy-{{$jobRole->id}}-{{$policy->id}}">{{$policy->name}}</label>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <br>
                        <button class="btn btn-blue btn-square btn-jobrole-save" type="submit">Save</button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
