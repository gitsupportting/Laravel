<div class="modal modal-theme fade" id="assign-course" tabindex="-1" role="dialog" aria-labelledby="assign-courseLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assign-courseLabel">Assign a Course</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Click the arrow below to select a course and then click Assign.</label>
                    <select class="custom-select form-control js-course-id" id="inputGroupSelect01">
                        @foreach($courses as $course)
                        <option value="{{$course->id}}">{{$course->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-square" data-dismiss="modal">Close</button>
                <button data-url="{{route('employees.bulkAssign')}}" type="button" class="btn btn-primary btn-square" id="js-employees-assign-course">Assign</button>
            </div>
        </div>
    </div>
</div>
@component('components.popups.noEmployeesSelected')
    @slot('title', 'Assign a Course')
    @slot('class', 'assign')
@endcomponent
