<div class="form-body">
    <div class="row">
        @foreach(App\Models\Course::$formFields as $field => $prop)
        <div class="col col-12 col-md-12 col-lg-{{empty($prop['width'])?12:$prop['width']}} col-xl-{{empty($prop['width'])?12:$prop['width']}} ">
            <div class="form-group">
                <label class="form-lable">{{$prop['label']}}</label>
                @if(isset($prop['type']) && $prop['type'] == 'textarea')
                    <textarea class="form-control" name="{{$field}}" {{isset($prop['required']) ? 'required': ''}}>{{old($field, $entity->$field)}}</textarea>
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
                @elseif($field == 'bg_color')
                <input type="{{!empty($prop['type']) ? $prop['type'] : 'text'}}" name="{{$field}}"
                       {{isset($prop['required']) ? 'required': ''}}
                value="{{old($field, $entity->$field)}}" class="form-control color-input" data-huebee placeholder="{{$prop['label']}}"/>
                @else
                <input type="{{!empty($prop['type']) ? $prop['type'] : 'text'}}" name="{{$field}}"
                       {{isset($prop['required']) ? 'required': ''}}
                value="{{old($field, $entity->$field)}}" class="form-control" placeholder="{{$prop['label']}}"/>
                @endif
            </div>
        </div>
        @endforeach
        <div class="col col-12 col-md-12 col-lg-12 col-xl-12">
            <div class="form-group">
                <label class="form-lable"> Card Image</label>
                <!-- <div class="file-group">
                    <label class="btn btn-primary btn-square" for="select-file">Select</label>
                    <input id="select-file" type="file" name="image" class="file-control">
                    @if(!empty($entity->imageThumbUrl))
                        <br>
                        <a target="_blank" href="{{$entity->imagePreviewUrl}}">
                            <img src="{{$entity->imageThumbUrl}}" style="height: 40px;" alt="">
                        </a>
                    @endif
                </div> -->
                <div class="input-group mb-3">
                    <div class="custom-file">
                        <input id="select-file" type="file" name="image" class="custom-file-input" >
                        <label class="custom-file-label" for="select-file" aria-describedby="inputGroupFileAddon02">Choose file</label>
                    </div>
                    @if(!empty($entity->imageThumbUrl))
                    <div class="selected-preview">
                        <a target="_blank" href="{{$entity->imagePreviewUrl}}">
                            <img src="{{$entity->imageThumbUrl}}" style="height: 40px;" alt="" class="img-thumbnail">
                        </a>
                    </div>
                    @endif
                </div>
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
            @if($entity->id)
            <a href="javascript:;" class="btn btn-primary btn-square" onclick="if(confirm('Are you sure you want to delete this course?')) $('#entity-delete-form').submit();">Delete</a>
            @endif
            <a href="/" class="btn btn-primary btn-square">Cancel</a>
            <button type="submit" class="btn btn-primary btn-square">Save</button>
        </div>
    </div>
</div>
