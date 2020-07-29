<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="form-body">
    <div class="row">
        @foreach(App\Models\Policy::$formFields as $field => $prop)
        <div class="col col-12 col-md-12 col-lg-{{empty($prop['width'])?12:$prop['width']}} col-xl-{{empty($prop['width'])?12:$prop['width']}} ">
            <div class="form-group">
                <label class="form-lable">{{$prop['label']}}</label>
                @if(isset($prop['type']) && $prop['type'] == 'textarea')
                    <textarea class="form-control trumbowyg" name="{{$field}}" {{isset($prop['required']) ? 'required': ''}}>{{old($field, $entity->$field)}}</textarea>
                @elseif(isset($prop['type']) && $prop['type'] == 'radio')
                    <div class="status-radio-btn">
                    @foreach($prop['values'] as $key => $value)
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="live-radio-{{$key}}" name="{{$field}}" {{old($field, $entity->$field) == $key ? 'checked' : ''}}
                            value="{{$key}}" class="custom-control-input">
                            <label class="custom-control-label" for="live-radio-{{$key}}">{{ucfirst($value)}}</label>
                        </div>
                    @endforeach
                    </div>
                @elseif(isset($prop['type']) && $prop['type'] == 'number')
                <input type="number" name="{{$field}}"
                       {{isset($prop['required']) ? 'required': ''}}
                value="{{old($field, $entity->$field)}}" class="form-control" placeholder="{{$prop['label']}}"/>
                @elseif(isset($prop['type']) && $prop['type'] == 'date')
                    <input type="text" name="{{$field}}" value="{{old($field, optional($entity->$field)->format('d/m/Y'))}}" class="form-control datepicker-input" required>
                @else
                <input type="{{!empty($prop['type']) ? $prop['type'] : 'text'}}" name="{{$field}}"
                       {{isset($prop['required']) ? 'required': ''}}
                value="{{old($field, $entity->$field)}}" class="form-control" placeholder="{{$prop['label']}}"/>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
<div class="table-control">
    <div class="table-control__inner">
        <div class="button-left">
            <a href="{{route('home', ['anchor' => 'policies'])}}" class="btn btn-primary btn-square">Back</a>
        </div>
        <div class="button_right">
            @if($entity->id)
            <a href="javascript:;" class="btn btn-primary btn-square" onclick="if(confirm('Are you sure you want to delete this policy?')) $('#entity-delete-form').submit();">Delete</a>
            @endif
            <a href="/?anchor=policies" class="btn btn-primary btn-square">Cancel</a>
            <button type="submit" class="btn btn-primary btn-square">Save</button>
        </div>
    </div>
</div>
@section('js')
    <script src="{{asset('assets/trumbowyg/plugins/cleanpaste/trumbowyg.cleanpaste.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.trumbowyg').trumbowyg({
            btns: [
            ['formatting'],
            ['strong', 'em', 'del'],
            ['fontsize'],
            ['superscript', 'subscript'],
            ['link'],
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ['unorderedList', 'orderedList'],
            ['horizontalRule'],
            ['historyUndo', 'historyRedo']
            ]
            });
            $('.datepicker-input').datepicker({
                dateFormat: 'dd/mm/yy'
            });
        });
    </script>
@append
