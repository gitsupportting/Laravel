<input type="hidden" name="lesson_id" value="{{$lesson->id}}">
<div class="form-body">
    <div class="row">
        @foreach(App\Models\Slide::$formFields as $field => $prop)
            <div
                class="col col-12 col-md-12 col-lg-{{empty($prop['width'])?12:$prop['width']}} col-xl-{{empty($prop['width'])?12:$prop['width']}} ">
                <div class="form-group">
                    <label class="form-lable">{{$prop['label']}}</label>
                    @if(isset($prop['type']) && $prop['type'] == 'textarea')
                        <textarea class="form-control"
                                  name="{{$field}}" {{isset($prop['required']) ? 'required': ''}}>{{old($field, $entity->$field)}}</textarea>
                    @elseif(isset($prop['type']) && $prop['type'] == 'radio')
                        <div class="status-radio-btn">
                            @foreach($prop['values'] as $i => $value)
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="slide-{{$value}}" name="{{$field}}"
                                           {{old($field, $entity->$field) == $value ? 'checked' : ''}}
                                           value="{{$value}}" class="custom-control-input slide-type">
                                    <label class="custom-control-label" for="slide-{{$value}}">
                                        @if($value == 'html')
                                            HTML
                                        @elseif($value == 'json')
                                            Question
                                        @elseif($value == 'video')
                                            Video
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @elseif(isset($prop['type']) && $prop['type'] == 'file')
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input id="select-file-{{$field}}" type="file" name="{{$field}}" class="custom-file-input" >
                                <label class="custom-file-label" for="select-file-{{$field}}" aria-describedby="inputGroupFileAddon-{{$field}}">Choose file</label>
                            </div>
                            <div class="input-group-append">
                                <button name="upload_{{$field=='image'?'image':'video'}}" value="1" class="input-group-text" type="submit" id="inputGroupFileAddon-{{$field}}">Upload</button >
                                <a href="javascript:;" id="js-clear-slide-bg-image" class="input-group-text">Clear</a>
                            </div>
                            @if($field == 'image' && !empty($entity->imageThumbUrl))
                                @php
                                    $media = $entity->getFirstMedia();
                                    $url = route('slide.attachment', ['course' => \Illuminate\Support\Str::slug($lesson->course->name), 'slide' => $entity, 'attachment' => $media->file_name]);
                                @endphp
                                @if(request()->has('show_image_attachment'))
                                    <span id="bg_image" style="display: none">{{$url}}</span>
                                @endif
                            @endif
                        </div>
                    @elseif($field == 'bg_color')
                        <input type="{{!empty($prop['type']) ? $prop['type'] : 'text'}}" name="{{$field}}"
                               {{isset($prop['required']) ? 'required': ''}}
                               value="{{old($field, $entity->$field)}}" class="form-control color-input" data-huebee
                               placeholder="{{$prop['label']}}"/>
                    @else
                        <input type="{{!empty($prop['type']) ? $prop['type'] : 'text'}}" name="{{$field}}"
                               {{isset($prop['required']) ? 'required': ''}}
                               value="{{old($field, $entity->$field)}}" class="form-control"
                               placeholder="{{$prop['label']}}"/>
                    @endif
                </div>
            </div>
        @endforeach
            <input type="hidden" name="bg_image" value="{{old('bg_image', $entity->bg_image)}}" id="bg_image_real">
        <div class="col col-12 col-md-12 col-lg-12 col-xl-12 slide-type__content" id="html-box"
            {!! $entity->type != App\Models\Slide::TYPE_HTML ? 'style="display: none;"' : '' !!}>
            <div>
                <div class="form-group mb-0">
                    @php
                        $content = old('content', $entity->content);
                        if(is_array($content)) {
                            $content = '';
                        }
                    @endphp
                    <textarea name="content_html"
                            class="form-control tinymce">{{$content}}</textarea>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-12 col-lg-12 col-xl-12 slide-type__content" id="video-box"
            {!! $entity->type != App\Models\Slide::TYPE_VIDEO ? 'style="display: none;"' : '' !!}>
            <div>
                <div class="form-group mb-0">
                    <label class="form-lable">Video URL</label>
                    <input type="text" name="video_url"
                           value="{{old('video_url', $entity->video_url)}}" class="form-control"
                           placeholder="Video URL"/>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-12 col-lg-12 col-xl-12 slide-type__content" id="question-box"
             {!! $entity->type != App\Models\Slide::TYPE_JSON ? 'style="display: none;"' : '' !!}>
             <div class="slide-type__content_card">
                <div class="form-group align-items-start">
                    <label class="form-lable">Question</label>
                    <input type="text" name="content[question]" class="form-control"
                    value="{{old('content.question', $entity->question)}}"
                    >
                </div>
                @for($i = 0; $i <= 4; $i++)
                <div class="form-group check-input">
                    <label class="form-lable">Answer {{chr(65+$i)}}</label>
                    <input type="text" name="content[answer][]" value="{{old('content.answer.' . $i, $entity->answers[$i])}}" class="form-control">
                    <div class="custom-control grey-radio custom-radio custom-control-inline">
                        <input type="radio" id="answer-{{chr(63+$i)}}" name="content[valid_answer]" class="custom-control-input"
                            value="{{$i}}"
                            {!! old('content.valid_answer', (int) $entity->validAnswer) == $i ? 'checked="checked"' : '' !!}>
                        <label class="custom-control-label " for="answer-{{chr(63+$i)}}"></label>
                    </div>
                </div>
                @endfor
                 <div class="form-group align-items-start">
                     <label class="form-lable">Correct response</label>
                     <input type="text" name="correct_answer_message" class="form-control"
                            value="{{old('correct_answer_message', $entity->correct_answer_message)}}"
                     >
                 </div>
                 <div class="form-group align-items-start">
                     <label class="form-lable">Incorrect response</label>
                     <input type="text" name="incorrect_answer_message" class="form-control"
                            value="{{old('incorrect_answer_message', $entity->incorrect_answer_message)}}"
                     >
                 </div>
            </div>
        </div>
    </div>
</div>
<div class="table-control">
    <div class="table-control__inner">
        <div class="button-left">
            <a href="{{route('lessons.edit', $lesson->id)}}" class="btn btn-primary btn-square">Back</a>
        </div>
        <div class="button_right">
            @if($entity->id)
                @if($slide = $entity->prevSlide())
                <a href="{{route('slides.edit', $slide)}}" class="btn btn-primary btn-square" style="min-width: auto"><</a>
                @endif
                @if($slide = $entity->nextSlide())
                <a href="{{route('slides.edit', $slide)}}" class="btn btn-primary btn-square" style="min-width: auto">></a>
                @endif
                <a href="javascript:;" class="btn btn-primary btn-square" onclick="$('#entity-delete-form').submit();">Delete</a>
                <a href="{{route('slides.clone', $entity)}}" class="btn btn-primary btn-square">Clone</a>
            @endif
            <button type="submit" class="btn btn-primary btn-square">Save</button>
        </div>
    </div>
</div>
<script type="text/javascript">
    function copyToClipboard(el) {
        var copyText = el.closest('.input-group').find('.url-to-copy')[0];
        copyText.select();
        copyText.setSelectionRange(0, 99999); /*For mobile devices*/
        document.execCommand("copy");
    }
</script>
