<div class="modal modal-theme fade" id="markCompleted-course" tabindex="-1" role="dialog" aria-labelledby="markCompleted-courseLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markCompleted-courseLabel">Mark External Courses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Click the arrow below to select a course and then click Mark as completed.</label>
                    <select class="custom-select form-control js-course-id" id="inputGroupSelect01">
                        @foreach($courses->where('type', \App\Models\Course::TYPE_EXTERNAL) as $course)
                        <option value="{{$course->id}}">{{$course->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-square" data-dismiss="modal">Close</button>
                <button data-url="{{route('employees.bulkMarkCompleted')}}" type="button" class="btn btn-primary btn-square" id="js-employees-markCompleted-course">Mark as completed</button>
            </div>
        </div>
    </div>
</div>
@component('components.popups.noEmployeesSelected')
    @slot('title', 'Mark External Courses')
    @slot('class', 'markCompleted')
@endcomponent
