<div class="modal modal-theme fade" id="assign-employees" tabindex="-1" role="dialog" aria-labelledby="assign-employeeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assign-employeeLabel">Assign Employees</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <p class="text-center loader"><img src="{{asset('assets/images/spinner.gif')}}" alt=""></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-square" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-square" onclick="$('#js-course-assignment-form').submit();">Assign</button>
            </div>
        </div>
    </div>
</div>
