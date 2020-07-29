<div class="card-body question-card" data-value="{{$slide->validAnswer}}">
    <div class="question-card__inner">
        <h3>Question</h3>
        <h3>{{$slide->question}}</h3>
        <div class="question-radiolist">
            <form action="{{route('slide.storeAnswer', $slide)}}" method="post" class="js-slide-store-answer">
                <button type="submit" style="height: 1px; width: 1px; visibility: hidden;"
                        class="save-slide-answer"></button>
                {{csrf_field()}}
                @foreach($slide->answers as $i => $answer)
                    @if(!empty($answer))
                        <div class="custom-control custom-radio" data-index="{{$i}}">
                            <input type="radio" id="customRadio-{{$slide->id}}-{{$i}}" name="answer"
                                   required="required"
                                   value="{{$answer}}"
                                   class="custom-control-input customRadio" {{isset($answers[$slide->
					id]) && $answers[$slide->id] == $answer ? 'checked' : ''}}>
                            <label class="custom-control-label"
                                   for="customRadio-{{$slide->id}}-{{$i}}">{{$answer}}</label>
                        </div>
                    @endif
                @endforeach
            </form>
        </div>
        <h3 class="correct-response hidden">
            {{$slide->correct_answer_message}}
        </h3>
        <h3 class="incorrect-response hidden">
            {{$slide->incorrect_answer_message}}
        </h3>
    </div>
</div>
