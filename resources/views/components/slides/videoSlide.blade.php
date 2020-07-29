<div class="card-body p-0 video-card">
    <div class="video-card__inner">
        <!-- <div class="embed-responsive embed-responsive-16by9">
            <iframe id="player" class="embed-responsive-item video-fluid z-depth-1 videoIframe js-videoIframe" src="https://www.youtube.com/embed/87jS8BoelQk" frameborder="0" allowTransparency="true" allowfullscreen style="display: none;"></iframe>
        </div>
        <div class="video-poster" style="background-image: url('assets/images/slide-video-banner.jpg');">
            <button type="button" id="play_button" class="btn-icon btn-icon-play waves-effect waves-light"><i class="fas fa-play"></i></button>
        </div> -->
        <video class="video-fluid z-depth-1 slide-video" playsinline="" poster="assets/images/slide-video-banner.png"> //removed loop
            <source src="{{$slide->video_url}}">
        </video>
        <button type="button" class="play_button btn-icon btn-icon-play waves-effect waves-light"><i class="fas fa-play"></i></button>
    </div>
</div>
