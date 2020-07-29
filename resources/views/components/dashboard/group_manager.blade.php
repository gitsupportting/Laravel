@extends('layouts.internal')
@section('content')
{{--    <div class="content-wrapper course-admin" data-simplebar="">--}}
        <section class="section-overview container">
            <div class="card card-chart">
                <div class="card-header">
                    <h4 class="card-title">Group Overview</h4>
                    <div class="dropdown select-dropdown">
                        <button class="btn btn-primary btn-square dropdown-toggle dropdown-placeholder" type="button"
                                id="overview-dropdown" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                            Completed Uncompleted Courses
                        </button>
                        <ul class="list-unstyled dropdown-menu" aria-labelledby="overview-dropdown">
                            <li><a class="dropdown-item"
                                   href="{{ request()->fullUrlWithQuery(['chart' => 'course-internal'] + request()->query()) }}">Completed
                                    Uncompleted Courses</a></li>
                            <li><a class="dropdown-item"
                                   href="{{ request()->fullUrlWithQuery(['chart' => 'course-external'] + request()->query()) }}">External
                                    Completed Uncompleted</a></li>
                            <li><a class="dropdown-item"
                                   href="{{ request()->fullUrlWithQuery(['chart' => 'policy'] + request()->query()) }}">Policies
                                    Read & Accepted</a></li>
                        </ul>
                    </div>
                </div>
                    <div class="card-body">
                        <div class="row chart-row collapse show" id="collapseChart">
                            @foreach($organizations as $organization)
                                <div class="col col-6 col-sm-6 col-md-4">
                                    <div class="chart-box">
                                        <canvas class="dChart canvas-chart" data-value="{{ $chart->get($organization->id)->percent ?? 0 }}"></canvas>
                                        <label class="label">Complete</label>
                                        <h4 class="title">{{ $organization->name }}</h4>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-primary btn-collapse mx-2" id="show_more_chart"><i
                                    class="fas fa-caret-down"></i></button>
                            <button type="button" class="btn btn-primary btn-collapse mx-2" data-toggle="collapse"
                                    id="collapse_link"><i class="fas fa-caret-up"></i></button>
                        </div>
                    </div>
                </div>
            <div class="card card-report">
                <div class="card-header">
                    <h4 class="card-title">Organisation Reports</h4>
                    <div class="card-header__control">
                        <a class="btn btn-primary" href="{{ route('reports.index', ['print' => true, 'organization' => $currentOrganization->getKey(), 'report' => request('report', 'completed_courses')]) }}" target="_blank">Print</a>
                        <a class="btn btn-primary" href="{{ route('reports.index', ['organization' => $currentOrganization->getKey(), 'report' => request('report', 'completed_courses')]) }}" target="_blank">Export</a>
                        <div class="dropdown select-dropdown">
                            <button class="btn btn-primary btn-square dropdown-toggle dropdown-placeholder" type="button" id="courses-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @isset($report_types[request('report', 'completed_courses')])
                                    {{ $report_types[request('report', 'completed_courses')]['title'] }}
                                @endif
                            </button>
                            <ul class="list-unstyled dropdown-menu" aria-labelledby="courses-dropdown">
                                @foreach($report_types as $key => $report)
                                <li>
                                    <a class="dropdown-item"
                                       href="{{ request()->fullUrlWithQuery(['report' => $key] + request()->query()) }}">
                                        {{ $report['title'] }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="dropdown select-dropdown">
                            <button class="btn btn-primary btn-square dropdown-toggle dropdown-placeholder" type="button" id="courses-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ $currentOrganization->name ?? 'No Data' }}
                            </button>
                            <ul class="list-unstyled dropdown-menu" aria-labelledby="courses-dropdown">
                                @foreach($organizations as $organization)
                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ request()->fullUrlWithQuery(['organization' => $organization->getKey()] + request()->query()) }}">
                                            {{ $organization->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" >
                        <table class="table organisation-table report-table " id="organisation-reports-table" role="grid">
                            <thead>
                            <tr>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <th colspan="2">{{ $report->name }}</th>
                                </tr>
                                @foreach($report->items as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->description }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card card-report">
                <div class="card-header">
                    <h4 class="card-title">Send Email Reports</h4>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('reports.store') }}" method="post">
                        @csrf
                        <div class="email-report__filter">
                            <div class="email-report__filter--item">
                                <label for="inputReportCommand"></label>
                                <div class="dropdown select-dropdown" data-toggle="dropdown-select">
                                    <button class="btn btn-primary btn-square dropdown-toggle dropdown-placeholder"
                                            type="button" id="inputReportCommand" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                        Completed Courses
                                    </button>
                                    <input type="hidden" name="command" value="completed_courses"/>
                                    <ul class="list-unstyled dropdown-menu" aria-labelledby="inputReportCommand">
                                        @foreach($report_types as $key => $report)
                                            <li>
                                                <a class="dropdown-item" href="#" data-value="{{$key}}">
                                                    {{ $report['title'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="email-report__filter--item">
                                <label class="label" for="inputReportEmailTo">To</label>
                                <input type="email" name="email_to" class="form-control" placeholder="Email" id="inputReportEmailTo">
                            </div>
                            <div class="email-report__filter--item">
                                <label class="label" for="inputReportWeekDay">On</label>
                                <div class="dropdown select-dropdown" data-toggle="dropdown-select">
                                    <button class="btn btn-primary btn-square dropdown-toggle dropdown-placeholder"
                                            type="button" id="inputReportWeekDay" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                        Monday
                                    </button>
                                    <input type="hidden" name="weekday" value="1"/>
                                    <ul class="list-unstyled dropdown-menu" aria-labelledby="inputReportWeekDay">
                                        <li><a class="dropdown-item" href="#" data-value="1">Monday</a></li>
                                        <li><a class="dropdown-item" href="#" data-value="2">Tuesday</a></li>
                                        <li><a class="dropdown-item" href="#" data-value="3">Wednesday</a></li>
                                        <li><a class="dropdown-item" href="#" data-value="4">Thursday</a></li>
                                        <li><a class="dropdown-item" href="#" data-value="5">Friday</a></li>
                                        <li><a class="dropdown-item" href="#" data-value="6">Saturday</a></li>
                                        <li><a class="dropdown-item" href="#" data-value="7">Sunday</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="email-report__filter--item">
                                <label class="label" for="inputReportTime">At</label>
                                <div class="dropdown select-dropdown" data-toggle="dropdown-select">
                                    <button class="btn btn-primary btn-square dropdown-toggle dropdown-placeholder"
                                            type="button" id="inputReportTime" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                        1:00
                                    </button>
                                    <input type="hidden" name="time" value="1:00"/>
                                    <ul class="list-unstyled dropdown-menu" aria-labelledby="inputReportTime">
                                        @foreach (time_schedule() as $time)
                                            <li><a class="dropdown-item" href="#" data-value="{{ $time }}">{{ $time }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="email-report__filter--item">
                                <button type="submit" class="btn btn-dark-blue btn-block">Add</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table report-table send-email-table data-table" id="send_email_table">
                            <thead>
                            <tr>
                                <th scope="col">Report</th>
                                <th scope="col">To</th>
                                <th scope="col">On</th>
                                <th scope="col">At</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($schedule_reports as $report)
                                <tr>
                                    <td>{{ $report_types[$report->command]['title'] }}</td>
                                    <td>{{ $report->email_to }}</td>
                                    <td>{{ $report->execute_at->format('l') }}</td>
                                    <td>{{ $report->execute_at->format('H:i') }}</td>
                                    <td class="text-right">
                                        <form id="email-report-delete-form{{$report->id}}" action="{{ route('reports.destroy', $report) }}" method="post">
                                            @csrf
                                            @method('delete')
                                        </form>
                                        <a onclick="if(confirm('Are you sure you want to delete this email report?')) $('#email-report-delete-form{{$report->id}}').submit();" href="#" class="link link-primary">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <footer class="main-footer">
            <div class="container">
                <p class="copy-right"><svg xmlns="http://www.w3.org/2000/svg" width="9.86" height="10.068" viewBox="0 0 9.86 10.068"> <path id="Icon_metro-copyright" data-name="Icon metro-copyright" d="M12.152,10.608v.714a.839.839,0,0,1-.234.583,1.554,1.554,0,0,1-.6.4,4.341,4.341,0,0,1-.757.213,4.129,4.129,0,0,1-.754.072,2.936,2.936,0,0,1-2.2-.911,3.131,3.131,0,0,1-.883-2.268,3.049,3.049,0,0,1,.873-2.222A2.922,2.922,0,0,1,9.77,6.295a4.438,4.438,0,0,1,.485.029,4.066,4.066,0,0,1,.6.118,3.022,3.022,0,0,1,.594.223,1.284,1.284,0,0,1,.443.37.854.854,0,0,1,.18.531v.714a.092.092,0,0,1-.1.1h-.757a.092.092,0,0,1-.1-.1V7.822q0-.282-.42-.442A2.453,2.453,0,0,0,9.8,7.219a1.93,1.93,0,0,0-1.467.6,2.171,2.171,0,0,0-.568,1.557,2.337,2.337,0,0,0,.587,1.635,1.937,1.937,0,0,0,1.5.646,2.669,2.669,0,0,0,.886-.157q.449-.157.449-.433v-.459a.1.1,0,0,1,.029-.075.091.091,0,0,1,.067-.029h.764a.1.1,0,0,1,.071.029.1.1,0,0,1,.032.075ZM9.7,5.246a3.923,3.923,0,0,0-1.6.334,4.144,4.144,0,0,0-1.31.895,4.243,4.243,0,0,0-.876,1.337,4.215,4.215,0,0,0,0,3.258A4.243,4.243,0,0,0,6.8,12.407,4.145,4.145,0,0,0,8.1,13.3a3.973,3.973,0,0,0,3.19,0,4.145,4.145,0,0,0,1.31-.895,4.243,4.243,0,0,0,.876-1.337,4.216,4.216,0,0,0,0-3.258A4.243,4.243,0,0,0,12.6,6.475,4.145,4.145,0,0,0,11.3,5.58a3.923,3.923,0,0,0-1.6-.334Zm4.93,4.195A5.043,5.043,0,0,1,12.175,13.8a4.873,4.873,0,0,1-4.949,0A5.043,5.043,0,0,1,4.77,9.441,5.043,5.043,0,0,1,7.225,5.082a4.873,4.873,0,0,1,4.949,0A5.043,5.043,0,0,1,14.63,9.441Z" transform="translate(-4.77 -4.407)" fill="#d3d2d6"></path></svg>Correct Care 2020 All Rights Reserved</p>
            </div>
        </footer>
@endsection
@section('js')

    <script>
        $(document).on('click', '[data-toggle="dropdown-select"] .dropdown-item', function (e) {
            e.preventDefault();
            var title = $(this).html();
            var value = $(this).data('value');
            $(this).parents('.dropdown').find('.dropdown-placeholder').html(title);
            $(this).parents('.dropdown').find('input').val(value);
        });

    </script>
{{--    <script src="{{asset('assets/js/jquery-2.2.4.js')}}"></script>--}}
    <script src="{{asset('assets/js/Chart.min.js')}}"></script>
    <style href="{{asset('assets/css/Chart.min.css')}}"></style>
    <script type="text/javascript">

        $('#organisation-reports-table').DataTable( {
            "bLengthChange" : false,
            "bFilter": false,
            "bInfo":false,
            "order": [],
        });

        var charts = document.getElementsByClassName('dChart');
        Array.prototype.slice.call(charts).forEach(function (chart) {
            var dataValue = chart.getAttribute('data-value');
            var data = {
                labels: [
                    " Completed % ",
                    " Not Completed % "
                ],
                datasets: [
                    {
                        data: [dataValue, 100 - dataValue],
                        backgroundColor: [
                            "#2484BA",
                            "#A6CEE3",
                        ],
                        hoverBackgroundColor: []
                    }]
            };
            var promisedDeliveryChart = new Chart(chart, {
                type: 'doughnut',
                data: data,
                options: {
                    elements: {
                        center: {
                            text: dataValue + '%'
                        },
                        arc: {
                            borderWidth: 0
                        }
                    },
                    cutoutPercentage: 70,
                    segmentShowStroke: false,
                    responsive: false,
                    responsive: true,
                    maintainAspectRatio : false,
                    legend: {
                        display: false
                    }
                }
            });
        });

        Chart.pluginService.register({
            beforeDraw: function (chart) {
                var width = chart.chart.width,
                    height = chart.chart.height,
                    ctx = chart.chart.ctx;
                ctx.restore();
                var fontSize = (height / 114).toFixed(2);
                ctx.font = fontSize + "em sans-serif";
                ctx.textBaseline = "middle";
                ctx.fillStyle = "#1F78B4";
                var text = chart.config.options.elements.center.text,
                    textX = Math.round((width - ctx.measureText(text).width) / 2),
                    textY = height / 2;
                ctx.fillText(text, textX, textY);
                ctx.save();
            }
        });

        $(document).on('click', '#show_more_chart', function () {
            $('#collapseChart').collapse('show');
            if ($('#collapseChart').hasClass('show')) {
                $(this).closest('.card-body').find('.chart-row .col:not(:nth-child(-n+5))').slideDown();
                $('#collapseChart').addClass('show-more');
            }
        });
        $(document).on('click', '#collapse_link', function () {
            if ($('#collapseChart').hasClass('show-more')) {
                $(this).closest('.card-body').find('.chart-row .col:not(:nth-child(-n+5))').slideUp();
            } else {
                $('#collapseChart').collapse('hide');
            }
            $('#collapseChart').removeClass('show-more');
        });
    </script>

    <style>
        body {
            overflow: auto!important;
        }
        .chart-box .label {
            top: 145px;
        }
        .dropdown-placeholder {
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>


@endsection
