<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<ol id="sortable" class="row slide" style="list-style-type: none;" data-url="{{route('lessons.sortSlides')}}">
    @foreach($slides as $slide)
        <li class="col col-12 col-md-6 col-lg-3 col-xl-3" id="slide-{{$slide->id}}">
            <div class="slide-box">
                <h6><a href="{{route('slides.edit', $slide)}}">{{$slide->name}}</a></h6>
            </div>
        </li>
    @endforeach
</ol>
