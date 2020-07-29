<div class="card-body html-card"  @if($slide->bg_image) style="color: #2B354C; background: url({{$slide->bg_image}}) no-repeat 0 0; background-size: cover;" @elseif($slide->bg_color) style="background-size: 100% 100%;  color: #2B354C; background-color: {{$slide->bg_color}}" @endif >
    <div class="html-card__inner">
        {!! $slide->content !!}
    </div>
</div>
