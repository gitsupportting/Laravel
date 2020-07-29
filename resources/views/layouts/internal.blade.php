<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <!-- css -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/font-awesome.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
    <!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/owl.carousel.min.css')}}"> -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/slick.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/simplebar.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/trumbowyg/ui/trumbowyg.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/trumbowyg/plugins/colors/ui/trumbowyg.colors.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/trumbowyg/plugins/table/ui/trumbowyg.table.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/huebee.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap-datetimepicker.css')}}">
    @yield('css')
    <style>
        .table td a.btn-primary {
            color: #fff;
        }
        .hidden {
            display: none !important;
        }
        .data-table {
            display: none;
        }
        .huebee {
            z-index: 10000 !important;
        }
    </style>
</head>
<body>
<header class="main-header @yield('headerClass')">
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="/">
                @if(!Auth::user()->isEmployee() || 'lesson.show' != request()->route()->getAction('as'))
                <img src="{{asset('assets/images/logo.svg')}}">
                @endif
                <span class="header-page__title">
                    @if(Auth::user()->parentOrganization)
                        {{Auth::user()->parentOrganization->name}}
                    @elseif(Auth::user()->organization)
                        {{Auth::user()->organization->name}}
                    @elseif(Auth::user()->groups->isNotEmpty())
                        {{Auth::user()->groups->first()->name}}
                    @else
                        Correct Care
                    @endif
                </span>
            </a>
            @yield('navbarPrepend')
            <div class="navber__right ml-auto">
            {{-- <span class="name">{{Auth::user()->name}}</span> --}}
                <form action="{{route('logout')}}" method="post">
                    @csrf
                    @if(auth()->user()->isEmployee() || auth()->user()->isManager())
                    <div class="dropdown dropdown-header p-0">
                        <button class="btn btn-dark-blue btn-square dropdown-toggle" type="button" id="employees-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{auth()->user()->name}}</button>
                        <ul class="list-unstyled dropdown-menu" aria-labelledby="employees-dropdown">
                            @if(auth()->user()->isEmployee())
                                <li><a class="dropdown-item" href="{{route('home')}}">My Courses</a></li>
                            @elseif(auth()->user()->isManager())
                                <li><a class="dropdown-item" href="{{route('home', ['anchor' => 'courses'])}}">All Courses</a></li>
                            @endif
                            <li>
                                <button type="submit" class="dropdown-item">Sign Out</button>
                            </li>
                        </ul>
                    </div>
                    @else
                    <button type="submit" class="btn btn-dark-blue btn-logout">Sign Out</button>
                    @endif
                </form>
            </div>
        </nav>
    </div>
</header>

<div class="container">
@if($errors ->any())
    <div class="padding p-b-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-danger m-b-0">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
</div>

<div class="container">
    @include('flash.message')
</div>

@yield('content')

<!--js-->
<script src="{{mix('js/all.js')}}"></script>
<script src="{{asset('assets/js/simplebar.js')}}"></script>
<!-- <script src="https://cdn.tiny.cloud/1/hu2gs0nc8rkm2yr463s96bsmmx1xaosp670z62drrctqf32v/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> -->
<script type="text/javascript" src="{{asset('assets/trumbowyg/trumbowyg.js')}}"></script>
<script src="{{asset('assets/trumbowyg/plugins/colors/trumbowyg.colors.min.js')}}"></script>
<script src="{{asset('assets/trumbowyg/plugins/lineheight/trumbowyg.lineheight.min.js')}}"></script>
<script src="{{asset('assets/trumbowyg/plugins/fontsize/trumbowyg.fontsize.min.js')}}"></script>
<script src="{{asset('assets/trumbowyg/plugins/table/trumbowyg.table.min.js')}}"></script>
<script src="{{asset('assets/trumbowyg/plugins/history/trumbowyg.history.min.js')}}"></script>
<script src="{{asset('assets/trumbowyg/plugins/upload/trumbowyg.upload.min.js')}}"></script>
<script src="{{asset('assets/js/huebee.pkgd.min.js')}}"></script>
<script type="text/javascript">
    $('#all-staff-table , #all-course-table, #course-history-table').DataTable({
        dom: 'Brtip',
        "bLengthChange": false,
        "bInfo": false,
        "order": [],
    });
    $(document).ready(function () {
        if ($('textarea.tinymce').length) {
            $('textarea.tinymce').trumbowyg({
                removeformatPasted: true,
                btnsDef: {
                    image: {
                        dropdown: ['insertImage', 'upload'],
                        ico: 'insertImage'
                    }
                },
                btns: [
                    ['viewHTML'],
                    ['formatting'],
                    ['strong', 'em', 'del', 'superscript', 'subscript'],
                    ['fontsize'],
                    ['link'],
                    ['image'],
                    ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                    ['foreColor', 'backColor', 'lineheight'],
                    ['unorderedList', 'orderedList'],
                    ['horizontalRule'],
                    ['table'],
                    ['removeformat'],
                    ['historyUndo', 'historyRedo'],
                ],
                plugins: {
                    fontsize: {
                        sizeList: [
                            '12px', '14px', '16px', '18px', '20px', '22px', '24px', '30px'
                        ]
                    },
                    colors: {
                        foreColorList: [
                            '03363D', '031135', 'C5DBF2', 'DAEACC', 'F5F6F6', 'F2F4F8', 'F8F5F3', 'F5F8FB', 'F0E8DC', 'F2F4F8', 'DDEAEB', 'FBF7ED',
                            '251C14', 'DAEACC', '04575F', 'C5DBF2', 'F5F6F6', 'FFCE3C', 'F2F4F8', '2484BA', 'F8F5F3', '2B354C', 'F9C100', 'FF6623',
                            '1A2E3B', 'FCEFED', 'F2F4F8', '004c3e', '25383C', '413839', '837E7C', 'B6B6B4', 'D1D0CE', 'E60213', '2E5BA8', '00B075',
                            'FFFFFF'
                        ],
                        backColorList: [
                            '99FFCC', 'FF99FF', 'FFFF66', 'fdff32', 'd4ff32'
                        ]
                    },
                    upload: {
                        serverPath: '/imageUpload',
                        fileFieldName: 'image',
                        headers: {
                            'Authorization': 'Client-ID xxxxxxxxxxxx'
                        },
                        urlPropertyName: 'data.link',
                    }
                }
            });
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("input.slide-type:radio").click(function () {
            if ($('input.slide-type:checked').val() == "html") {
                $('#question-box').hide();
                $('#html-box').show();
                $('#video-box').hide();
            } else if ($('input.slide-type:checked').val() == "json") {
                $('#question-box').show();
                $('#html-box').hide();
                $('#video-box').hide();
            } else {
                $('#html-box').hide();
                $('#question-box').hide();
                $('#video-box').show();
            }
        });

        $('#chooseFile').bind('change', function () {
            var filename = $("#chooseFile").val();
            if (/^\s*$/.test(filename)) {
                $(".file-upload").removeClass('active');
                $("#noFile").text("No file chosen...");
            } else {
                $(".file-upload").addClass('active');
                $("#noFile").text(filename.replace("C:\\fakepath\\", ""));
            }
        });

        if($('#sortable').length) {
            $("#sortable").sortable({
                update: function (event, ui) {
                    var data = $(this).sortable('serialize');
                    $.ajax({
                        data: data,
                        type: 'POST',
                        url: $('#sortable').data('url'),
                    });
                }
            });
            $("#sortable").disableSelection();
        }

        var elements = document.getElementsByName("answer");
        for (var i = 0; i < elements.length; i++) {
            elements[i].oninvalid = function(e) {
                e.target.setCustomValidity("");
                if (!e.target.validity.valid) {
                    e.target.setCustomValidity("Choose one of the answers before proceeding.");
                }
            };
            elements[i].oninput = function(e) {
                e.target.setCustomValidity("");
            };
        }


        $('.slide-carousel').on('beforeChange', function(event, slick, currentSlide, nextSlide){
            $video = $('.slide-card__item[data-slick-index="'+nextSlide+'"]').find('video');
            if($video.length == 1){
                $video[0].play();
                $('.play_button').parent('.video-card__inner').removeClass('video-pause').addClass('video-playing');
                $('.play_button').children('i').addClass('fa-pause');
                $('.play_button').children('i').removeClass('fa-play');
            }else{
                $("video").trigger('pause');
                $('.play_button').parent('.video-card__inner').removeClass('video-playing').addClass('video-pause');
                $('.play_button').children('i').addClass('fa-play');
                $('.play_button').children('i').removeClass('fa-pause');
            }
        });

        $('.color-input').change(function() {
            $('.trumbowyg-editor').css('background-color', $(this).val());
        }).change();

        if($('#bg_image').length && $('#bg_image').text().length) {
            $('.trumbowyg-editor').css('background-image', 'url(' + $('#bg_image').text() + ')')
                .css('background-size', 'cover');
            $('#bg_image_real').val($('#bg_image').text());
        }

        $('#js-clear-slide-bg-image').on('click', function() {
            $('.trumbowyg-editor').css('background-image', 'none');
            $('#bg_image_real').val('');
        });

        if($('#bg_image_real').length && $('#bg_image_real').val().length) {
            $('.trumbowyg-editor')
                .css('background-image', 'url(' + $('#bg_image_real').val() + ')')
                .css('background-size', 'cover');
        }

        if($('.color-input').length) {
            var elem = $('.color-input')[0];
            var hueb = new Huebee( elem, {
                // options
                customColors: [ '#E3E9E7', '#F2F2F2', '#EAF2F3', '#FAF9F8', '#F3F2F1', '#FFF8F8', '#F3FCF4', '#FAF9F8', '#EBDDE8', '#FFEEEC', '#E7EDE2', '#E0E8F7', '#F4F4F5', '#DDEAEB', '#FBF6F1', '#E1F3F4', '#ebebd3', '#e4fde1', '#eae0d5', '#fbf9f6', '#f4f4f9', '#edf2f4', '#eaeaea']
            });
            hueb.on( 'change', function( color, hue, sat, lum ) {
                $('.trumbowyg-editor').css('background-color', color);
            })
        }
    });
</script>
@yield('js')
<style>
    .data-table {
        display: table;
    }
</style>
</body>
</html>
