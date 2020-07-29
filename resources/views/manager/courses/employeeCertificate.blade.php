<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--css-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/simplebar.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/iframe/css/slide-styles.css')}}">
</head>
<style type="text/css">
    .certificate-body {

    }
</style>
<body>
<div class="content-wrapper" data-simplebar style="background-color:#F1F1F1;">
    <section class="full-height certificate-card">
        <div class="certificate-card__header">
            <a href="javascript:history.go(-1);" class="btn btn-blue">Return to employee</a>
            <button onclick="printDiv()" class="btn btn-right btn-blue">Print to Certificate</button>
        </div>
        <div id="DivIdToPrint">
            <div class="certificate-body"
                 style="padding: 100px; background-color: #fff; width: 100%; max-width: 680px; margin: auto; margin-top: 20px; position: relative; box-shadow: 4px 4px 13px 0px rgba(0,0,0,0.12);">
                <img src="{{asset('assets/iframe/images/certificate-bg.png')}}"
                     style="position: absolute; width: calc(100% - 40px); left: 20px; top: 20px; z-index: 0; height: calc(100% - 40px); background-size: cover;">
                <table class="certificate-table" style="width: 100%; position: relative; z-index: 2;">
                    <thead>
                    <tr>
                        <th>
                            <h1 style=" color: #053562; font-size: 34px; font-weight: 600; text-align: center; margin-bottom: 0px; letter-spacing: 0.1em;">
                                Certificate of Completion</h1></th>
                    </tr>
                    <tr>
                        <th style=" color: #053562; font-size: 18px; text-align: center;"></th>
                    </tr>
                    <tr>
                        <th style="color: #053562; font-size: 22px; text-align: center; padding-bottom: 30px; font-weight: 500;">This is to certify that</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <h1 style="text-align: center; font-size: 32px; font-weight: 500; color: #053562; padding-bottom: 0px; margin-bottom: 5px;">{{$user->name}}</h1>
                            <hr style="display: block; margin-top: 0.5em; margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset; border-width: 1px; width: 350px;">
                        </td>
                    </tr>
                    <tr>
                        <th style="color: #053562; font-size: 22px; text-align: center; padding-bottom: 10px; font-weight: 500;">has successfully completed</th>
                    </tr>
                    <tr>
                        <th><h1 style="text-align: center; font-size: 32px; font-weight: 500; color: #053562; padding-bottom: 30px; margin-bottom: 5px;">{{$course->name}}</h1></th>
                    </tr>
                    <tr>
                        <td style=" color: #053562; font-size: 18px; padding-bottom: 30px; text-align: center; font-weight: 500;">
                            And achieved {{round($course->pivot->score)}}% on {{\Carbon\Carbon::parse($course->pivot->completed_at)->format('F d Y')}}</td>
                    </tr>
                    <tr>
                        @if($course->lessons->isNotEmpty() && !$course->isExternalHistory())
                            <td style=" color: #053562; font-size: 14px; font-weight: 500; text-align: center;"><p
                                    style="margin-bottom: 0px;">The Course syllabus
                                    included: <br> {{$course->lessons->pluck('name')->implode(', ')}}</p></td>
                        @endif
                    </tr>
                    <tr>
                        <td style=" color: #053562; font-size: 14px; font-weight: 500; padding-top: 30px; text-align: center; width:380px; word-wrap:break-word;">
                            <p style="margin-bottom: 0px;">{{$course->description}}</p></td>
                    </tr>
                    <tr>
                        <td style="padding-top: 50px; padding-bottom: 50px; color: #053562; font-size: 14px; font-weight: 500; text-align: center;">
                            @if($course->isExternalHistory())
                                This course was completed and delivered via a external provider {{ $course->props->leader }}.
                            @else
                                This course was completed and delivered via Correct Care <br> correctcare.co.uk
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <img style="display: block; margin: 0 auto; max-width:120px; width: auto; height: auto;" src ="https://app.correctcare.co.uk/assets/images/certificate-images/CC-logo.svg">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<!--js-->
<script src="{{asset('assets/js/jquery-2.2.4.js')}}"></script>
<script src="{{asset('assets/js/popper.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/js/simplebar.js')}}"></script>
<script>
    function printDiv() {

        var divToPrint = document.getElementById('DivIdToPrint');

        var newWin = window.open('', 'Print-Window');

        newWin.document.open();

        newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');

        newWin.document.close();

        setTimeout(function () {
            newWin.close();
        }, 10);

    }
</script>
</body>
</html>
