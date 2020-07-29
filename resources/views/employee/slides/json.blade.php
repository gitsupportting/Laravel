<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--css-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/iframe/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/iframe/css/simplebar.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/iframe/css/slide-styles.css')}}">
</head>
<body>
<div class="content-wrapper" data-simplebar>
    <section class="full-height white-card question-card">
        <div class="course-content scrollbar-inner">
            <div class="card-body h-100-vh">
                <h3>Question:</h3>
                <h3>{{$slide->question}}</h3>
                <form action="{{route('slide.storeAnswer', $slide)}}" method="post" id="js-slide-store-answer">
                    {{csrf_field()}}
                    @foreach($slide->answers as $i => $answer)
                        @if(!empty($answer))
                            <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio-{{$slide->id}}-{{$i}}" name="answer" value="{{$answer}}"
                                       class="custom-control-input" {{isset($answers[$slide->id]) && $answers[$slide->id] == $answer ? 'checked' : ''}}>
                                <label class="custom-control-label"
                                       for="customRadio-{{$slide->id}}-{{$i}}">{{$answer}}</label>
                            </div>
                        @endif
                    @endforeach
                </form>
            </div>
        </div>
    </section>
</div>
<!--js-->
<script src="{{asset('assets/iframe/js/jquery-2.2.4.js')}}"></script>
<script src="{{asset('assets/iframe/js/popper.min.js')}}"></script>
<script src="{{asset('assets/iframe/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/iframe/js/simplebar.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.custom-control-input').on('click', function() {
            $('#js-slide-store-answer').submit();
        });

        $('#js-slide-store-answer').on('submit', function() {
            $.ajax({
                dataType: 'json',
                type: 'post',
                data: $(this).serialize(),
                url: $(this).attr('action')
            });
            return false;
        })
    });
</script>
</body>
</html>
