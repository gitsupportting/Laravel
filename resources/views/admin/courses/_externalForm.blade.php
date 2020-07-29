<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="form-body">
    <div class="row">
        <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label class="form-lable">Course Name</label>
                <input type="text" name="name" value="{{old('name', $course->name)}}" class="form-control" required>
            </div>
        </div>
        <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label class="form-lable">Type</label>
                <select class="custom-select drop-select form-control" name="type">
                    <option selected="">Select Type</option>
                    @foreach(\App\Models\CourseAttribute::TYPES as $type)
                    <option value="{{$type}}" {{$type == old('type', $course->props->type) ? 'selected' : ''}}>{{$type}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label class="form-lable">Course Leader</label>
                <input type="text" name="leader" value="{{old('leader', $course->props->leader)}}" class="form-control" >
            </div>
        </div>
        <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label class="form-lable">Date</label>
                <input type="text" name="start_date" value="{{old('start_date', optional($course->props->start_date)->format('d/m/Y H:i'))}}" class="form-control datepicker-input" required>
            </div>
        </div>

        <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label class="form-lable">Contact Phone</label>
                <input id="contact_number" type="text" value="{{old('phone', $course->props->phone)}}" name="phone" class="form-control">
            </div>
        </div>

        <div class="col col-12 col-md-12 col-lg-6 col-xl-6">
            <div class="form-group">
                <label class="form-lable">Duration (Hours)</label>
                <input id="duration" type="number" name="duration" value="{{old('duration', $course->props->duration)}}"  min="0.5" max="100.5" class="form-control" step="0.5">
            </div>
        </div>
        <div class="col col-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label class="form-lable">Notes</label>
                <textarea  class="form-control" name="description" style="height: 220px;">{{old('description', $course->description)}}</textarea>
            </div>
        </div>
    </div>
</div>
@component('components.form.footer')
    @slot('entity', $course)
    @slot('anchor', 'courses')
    @slot('deleteMessage', 'Are you sure you want to delete this external course?')
@endcomponent

@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            (function($) {
                $.fn.inputFilter = function(inputFilter) {
                    return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
                        if (inputFilter(this.value)) {
                            this.oldValue = this.value;
                            this.oldSelectionStart = this.selectionStart;
                            this.oldSelectionEnd = this.selectionEnd;
                        } else if (this.hasOwnProperty("oldValue")) {
                            this.value = this.oldValue;
                            this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                        } else {
                            this.value = "";
                        }
                    });
                };
            }(jQuery));
            $("#contact_number").inputFilter(function(value) {
                return /^\d*$/.test(value); });

            $('.datepicker-input').datetimepicker({
                format: 'DD/MM/YYYY HH:mm'
            });
            $(document).on('input propertychange keydown keyup keypress click change','#duration',function(e){
                var format = String.fromCharCode(e.which);
                var finalValue = $(this).val()+currentValue;
                if(finalValue > 100){
                    console.log(finalValue);
                    e.preventDefault();
                }
            });

        })
    </script>
@append
