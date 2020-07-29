<div class="form-body">
    <div class="row">
        @foreach(App\Models\User::$formFields as $field => $prop)
        <div class="col col-12 col-md-12 col-lg-{{empty($prop['width'])?12:$prop['width']}} col-xl-{{empty($prop['width'])?12:$prop['width']}} ">
            <div class="form-group">
                <label class="form-lable">{{$prop['label']}}</label>
                <input type="{{!empty($prop['type']) ? $prop['type'] : 'text'}}" name="{{$field}}"
                       {{isset($prop['required']) ? 'required': ''}}
                value="{{old($field, $entity->$field)}}" class="form-control" placeholder="{{$prop['label']}}"/>
            </div>
        </div>
        @endforeach
        <div class="col col-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label class="form-lable">Editor Only</label>
                <select name="is_editor" id="" class="custom-select drop-select form-control">
                    <option value="0">No</option>
                    <option value="1" {{$entity->is_editor ? 'selected' : ''}}>Yes</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="table-control">
    <div class="table-control__inner">
        <div class="button-left">
            <a href="/" class="btn btn-primary btn-square">Back</a>
        </div>
        <div class="button_right">
            @if($entity->id && $entity->id != Auth::id())
            <a href="javascript:;" class="btn btn-primary btn-square" onclick="$('#entity-delete-form').submit();">Delete</a>
            @endif
            <a href="/" class="btn btn-primary btn-square">Cancel</a>
            <button type="submit" class="btn btn-primary btn-square">Save</button>
        </div>
    </div>
</div>
