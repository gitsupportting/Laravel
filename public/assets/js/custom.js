$(document).ready(function () {
    $('#chooseFile').bind('change', function () {
        var filename = $("#chooseFile").val();
        if (/^\s*$/.test(filename)) {
            $(".file-upload").removeClass('active');
            $("#noFile").text("No file chosen...");
        }
        else {
            $(".file-upload").addClass('active');
            $("#noFile").text(filename.replace("C:\\fakepath\\", ""));
        }
    });

    $('[data-toggle="tooltip"]').tooltip()

    // function setInputFilter(textbox, inputFilter) {
    // 	["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
    // 		textbox.addEventListener(event, function() {
    // 			if (inputFilter(this.value)) {
    // 				this.oldValue = this.value;
    // 				this.oldSelectionStart = this.selectionStart;
    // 				this.oldSelectionEnd = this.selectionEnd;
    // 			} else if (this.hasOwnProperty("oldValue")) {
    // 				this.value = this.oldValue;
    // 				this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
    // 			} else {
    // 				this.value = "";
    // 			}
    // 		});
    // 	});
    // }
    // setInputFilter(document.getElementById("score_percentage"), function(value) {
    // 	return /^\d*$/.test(value) && (value === "" || parseInt(value) <= 500); });

    /*
    $('.iframe-carousel').on('initialized.owl.carousel changed.owl.carousel', function(e) {
        if (!e.namespace)  {
            return;
        }
        var carousel = e.relatedTarget;
        $('.slide-count').text(carousel.relative(carousel.current()) + 1 + ' / ' + carousel.items().length);
    }).owlCarousel({
        loop:false,
        margin:0,
         nav: true,
        dots: false,
        animate: true,
        animateOut: 'fake',
        animateIn: 'fake',
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        }
    });
    var outIndex = '';
    var owl = $('.iframe-carousel');
        effect = 'fxPressAway',
          outIndex,
          isDragged = false;
    owl.owlCarousel();
    // Go to the next item
    $('.iframe-next').click(function() {
        owl.trigger('next.owl.carousel');
    })
    // Go to the previous item
    $('.iframe-prev').click(function() {
        // With optional speed parameter
        // Parameters has to be in square bracket '[]'
        owl.trigger('prev.owl.carousel', [300]);
    })
    owl.on('change.owl.carousel', function(event) {
        outIndex = event.item.index;
    });

    owl.on('changed.owl.carousel', function(event) {
        var inIndex = event.item.index,
        dir = outIndex <= inIndex ? 'Prev' : 'Next';

        var animation = {
            moveIn: {
                item: $('.owl-item', owl).eq(inIndex),
                effect: effect + 'In' + dir
            },
            moveOut: {
                item: $('.owl-item', owl).eq(outIndex),
                effect: effect + 'Out' + dir
            },
            run: function (type) {
                var animationEndEvent = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
                animationObj = this[type],
                inOut = type == 'moveIn' ? 'in' : 'out',
                animationClass = 'animated owl-animated-' + inOut + ' ' + animationObj.effect,
                $nav = owl.find('.owl-prev, .owl-next, .owl-dot, .owl-stage');

                $nav.css('pointerEvents', 'none');

                animationObj.item.stop().addClass(animationClass).one(animationEndEvent, function () {
                // remove class at the end of the animations
                animationObj.item.removeClass(animationClass);
                    $nav.css('pointerEvents', 'auto');
                });
            }
        };

        if (!isDragged){
            animation.run('moveOut');
            animation.run('moveIn');
        }
    });
    */

    $("input[name=slide-radio]:radio").click(function () {
        if ($('input[name=slide-radio]:checked').val() == "html") {
            $('#question-box').hide();
            $('#html-box').show();

        } else if ($('input[name=slide-radio]:checked').val() == "question") {
            $('#question-box').show();
            $('#html-box').hide();

        }
    });

    $(document).on('click', '.jobrole-tab__list .nav-link' , function () {
        var jobrole_title = $(this).html();
        $(this).parents('.dropdown').find('.jobrole-dropdown__link').html(jobrole_title);
    })

    $(".iframe-next").click(function(e) { // Added a '.'
        $(".iframe-carousel").slick("slickNext");
    });

    $(".iframe-prev").click(function(e) { // Added a '.'
        $(".iframe-carousel").slick("slickPrev");
    });

    /*var $status = $('.slide-count');
    var $slickElement = $('.iframe-carousel');

    $slickElement.on('init reInit afterChange', function(event, slick, currentSlide, nextSlide){
        //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
        var i = (currentSlide ? currentSlide : 0) + 1;
        $status.text(i + ' / ' + slick.slideCount);
    });*/
    // slick instead of owl end


    $('input[name="employees-retake"],#course-before-month').on('keydown keyup keypress paste',function(e) {
        if(e.which >= 49 && e.which <= 90){
            e.preventDefault();
            e.stopPropagation();
        }
    });

    $(document).on('input propertychange keydown keyup keypress','input[name="score"]',function(e){
        var currentValue = String.fromCharCode(e.which);
        var finalValue = $(this).val() + currentValue;
        if(finalValue > 100){
            e.preventDefault();
        }
    });

    $(document).on('input propertychange','input[name="employees-retake"]',function(event){
        $no_arr = ['0','1','3','6','12','24','36','48'];
        $old_value = $(this).attr('old-value');
        $index_no = $.inArray($old_value, $no_arr);
        $val = $no_arr[$index_no +1];
        if($no_arr[$no_arr.length-1] == $old_value && $(this).val() > $old_value){
            $(this).val($old_value);
            return;
        }
        if($(this).val() < $old_value){
            $val = $no_arr[$index_no-1];
        }
        $(this).attr('old-value',$val);
        $(this).val($val);
        $('#course-before-month').val('');
    });

    $(document).on('input propertychange','input[name="re-enable-due"]',function(event){
        $val = $(this).val();
        if($(this).val() > parseInt($('#course-month').val())){
            $val = $(this).val()-1;
        }
        $(this).val($val);
    });


    $('input[type="file"]').change(function(e){
        var fileName = e.target.files[0].name;
        $(this).next('.custom-file-label').html(fileName);
    });

    // slick instead of owl
    /*$('.iframe-carousel').slick({
        dots: false,
        arrows: false,
        infinite: false,
        speed: 1300,
        slidesToShow: 1,
        slidesToScroll: 1,
        accessibility: true,
        cssEase: 'ease-in-out'
    });*/


    //start : new ketan
    // slick instead of owl
    var $slider = $('.slide-carousel');
    var $progressBar = $('.progress');
    var $progressBarLabel = $( '.slider__label' );

    $slider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
        var calc = ( (nextSlide) / (slick.slideCount-1) ) * 100;

        $progressBar
            .css('background-size', calc + '% 100%')
            .attr('aria-valuenow', calc );

        $progressBarLabel
            .css('width', calc + '%');
    });

    $slider.slick({
        dots: false,
        arrows: false,
        infinite: false,
        speed: 700,
        slidesToShow: 1,
        slidesToScroll: 1,
        accessibility: true,
        // adaptiveHeight: true,
        cssEase: 'ease-in-out'
    });

    $(".slide-next").click(function(e) { // Added a '.'
        $(".slide-carousel").slick("slickNext");
    });

    $(".slide-prev").click(function(e) { // Added a '.'
        $(".slide-carousel").slick("slickPrev");
    });

    // $("body").keydown(function(e){
    //     // left arrow
    //     if ((e.keyCode || e.which) == 37)
    //     {
    //         $(".slide-carousel").slick("slickPrev");
    //     }
    //     // right arrow
    //     if ((e.keyCode || e.which) == 39)
    //     {
    //         $(".slide-carousel").slick("slickNext");
    //     }
    // });

    var $status = $('.slide-count');
    var $statusTooltip = $('.slide-count-tooltip');
    var $slickElement = $('.slide-carousel');
    var getHeight  = $('.slick-list').height();
    $('.slick-current').css({'height': getHeight});
    $slickElement.on('init reInit afterChange', function(event, slick, currentSlide, nextSlide){
        //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
        var getHeight  = $('.slick-list').height();
        $('.slick-current').css({'height': getHeight});
        var i = (currentSlide ? currentSlide : 0) + 1;
        $status.attr("title",i + ' / ' + slick.slideCount);
        $statusTooltip.attr("data-original-title",'Progress, slide '+ i + ' of ' + slick.slideCount);
    });
    // slick instead of owl end
    var playButton = $('.play_button');
    // Event listener for the play/pause button
    playButton.click(function() {
        var video = $(this).prev()[0];
        if (video.paused == true) {
            video.play();
            $(this).parent('.video-card__inner').removeClass('video-pause').addClass('video-playing');
            $(this).children('i').addClass('fa-pause');
            $(this).children('i').removeClass('fa-play');
        } else {
            video.pause();

            // Update the button text to 'Play'
            $(this).parent('.video-card__inner').removeClass('video-playing').addClass('video-pause');
            $(this).children('i').addClass('fa-play');
            $(this).children('i').removeClass('fa-pause');
        }
    });
    $('.slide-video').each(function() {
        var playVideo = $(this)[0];
        playVideo.addEventListener('ended',function(){
            $(this).children('i').addClass('fa-play');
            $(this).children('i').removeClass('fa-pause');
        });
    });
    $(document).on('click', '.select-dropdown .dropdown-menu li a', function () {
        $('.dropdown-item').removeClass('active');
        var setText = $(this).text();
        $(this).closest('.select-dropdown').find('.dropdown-placeholder').text(setText);
        $(this).addClass('active');
    });
});
