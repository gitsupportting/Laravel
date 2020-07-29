$(document).ready(function() {
    function showNextSlide()
    {
        $('.slide-card__item').removeClass('hidden');
        $(".slide-carousel").slick("slickNext");
    }

    function showPrevSlide()
    {
        $(".slide-carousel").slick("slickPrev");
    }

    function slideHasUserAnswer()
    {
        let form = $('.slick-active').find('form');
        let isValid = true;
        form.find('input[type=radio]').map(function() {
            isValid &= this.validity['valid'] ;
        });

        if(!isValid) {
            form[0].reportValidity();
        }

        return isValid;
    }

    function processNextSlide()
    {
        let activeSlide = $('.slick-active').find('.slide-content');
        if(activeSlide.data('type') != 'json') {
            if(!$('.slick-active').next().length) {
                location.href = $('.next-slide').data('nextUrl');
            } else {
                showNextSlide();
            }
        } else if(slideHasUserAnswer()) {
            activeSlide.find('form').submit();
        }
    }

    $('body').on('click', '.custom-radio input', function () {
        if($(this).parent().data('index') == $(this).closest('.question-card').data('value')) {
            $(this).closest('.question-card').find('.correct-response').removeClass('hidden');
            $(this).closest('.question-card').find('.incorrect-response').addClass('hidden');
        } else  {
            $(this).closest('.question-card').find('.correct-response').addClass('hidden');
            $(this).closest('.question-card').find('.incorrect-response').removeClass('hidden');
        }

        $(this).closest('.question-radiolist').find('input.customRadio').not($(this)).attr('disabled', true);
    });

    $('body').on('click', '.next-slide', function() {
        processNextSlide();
        return false;
    });

    $("body").keydown(function(e){
        if($('.slick-active').length) {
            if ((e.keyCode || e.which) == 37) {
                showPrevSlide();
            }

            if ((e.keyCode || e.which) == 39) {
                processNextSlide();
            }
        }
    });

    $('.js-slide-store-answer').on('submit', function() {
        $.ajax({
            dataType: 'json',
            type: 'post',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    if(response.url) {
                        location.href = response.url;
                    } else {
                        showNextSlide();
                    }
                } else  {
                    alert(JSON.stringify(response));
                }
            }
        });

        return false;
    });

    $('body').on('click', '.prev-slide', function() {
        showPrevSlide();
    });

    $('body').on('click', '#finish-slide', function() {
        //todo show certificate
    });

    $('body').on('click', '#previous-slide', function() {
        var id = $('.step-iframe:visible').data('id');
        var prevId = $('.step-iframe:visible').data('id')-1;
        if(prevId >= 1) {
            $('[data-id="'+id+'"]').hide();
            $('[data-id="'+prevId+'"]').show();
        }
        if (prevId < $('.step-iframe').length && prevId >= 1) {
            $('#finish-slide').remove();
        }
    });
});
