<div class="form-body">
    <div class="row">
        @foreach(App\Models\Lesson::$formFields as $field => $prop)
        <div class="col col-12 col-md-12 col-lg-{{empty($prop['width'])?12:$prop['width']}} col-xl-{{empty($prop['width'])?12:$prop['width']}} ">
            <div class="form-group">
                <label class="form-lable">{{$prop['label']}}</label>
                @if(isset($prop['type']) && $prop['type'] == 'textarea')
                    <textarea class="form-control" name="{{$field}}" {{isset($prop['required']) ? 'required': ''}}>{{old($field, $entity->$field)}}</textarea>
                @elseif(isset($prop['type']) && $prop['type'] == 'radio')
                    <div class="status-radio-btn">
                    @foreach($prop['values'] as $i => $value)
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="live-radio-{{$i}}" name="{{$field}}" {{old($field, $entity->$field) == $value ? 'checked' : ''}}
                            value="{{$value}}" class="custom-control-input">
                            <label class="custom-control-label" for="live-radio-{{$i}}">{{ucfirst($value)}}</label>
                        </div>
                    @endforeach
                    </div>
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
            <a href="{{route('courses.edit', $courseId)}}" class="btn btn-primary btn-square">Back</a>
        </div>
        <div class="button_right">
            @if($entity->id)
            <a href="javascript:;" class="btn btn-primary btn-square" onclick="$('#entity-delete-form').submit();">Delete</a>
            @endif
            <a href="{{route('courses.edit', $courseId)}}" class="btn btn-primary btn-square">Cancel</a>
            <button type="submit" class="btn btn-primary btn-square">Save</button>
        </div>
    </div>
</div>
