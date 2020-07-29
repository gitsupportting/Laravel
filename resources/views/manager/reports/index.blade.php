@extends('layouts.internal')
@section('title', 'Reporting')
@section('content')
    <div class="content-wrapper container" data-simplebar>
        <section class="section-manager">
            <div class="section-manager__content add-policy">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active">
                        <div class="form-card">
                            <div class="form-header">
                                <h2>@yield('title')</h2>
                            </div>

                            <div class="matrix-table__content">
                                <div class="table-control--top">
                                    <div class="table-control__inner manager pb-0">
                                        <div class="dropdown select-dropdown">
                                            <button class="btn btn-primary btn-square dropdown-toggle dropdown-placeholder" type="button" id="overview-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Training Matrix </button>
                                            <ul class="list-unstyled dropdown-menu" aria-labelledby="overview-dropdown">
                                                <li><a class="dropdown-item" href="{{ route('reports.organization', ['report' => 'trainingMatrix']) }}">Training Matrix</a></li>
                                                <li><a class="dropdown-item" href="{{ route('reports.organization', ['report' => 'overdue']) }}">Overdue</a></li>
                                                <li><a class="dropdown-item" href="{{ route('reports.organization', ['report' => 'due']) }}">Due soon</a></li>
                                                <li><a class="dropdown-item" href="{{ route('reports.organization', ['report' => 'nonCompliant']) }}">Low-scoring</a></li>
                                                <li><a class="dropdown-item" href="{{ route('reports.organization', ['report' => 'policy']) }}">Policies</a></li>
                                                <li><a class="dropdown-item" href="{{ route('reports.organization', ['report' => 'incomplete']) }}">Incomplete</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive" data-simplebar="">
                                    {!! $report !!}
                                </div>
                                <div class="table-data-explain"></div>
                                <div class="table-control">
                                    <div class="table-control__inner">
                                        <div class="button-left">
                                            <a class="btn btn-primary btn-square" href="{{ route('home') }}">Back</a>
                                        </div>
                                        <div class="button_right">
                                            <a class="btn btn-primary btn-square" target="_blank"
                                               href="{{ route('reports.organization', ['report' => request('report', 'trainingMatrix'), 'download' => true]) }}">Export</a>
                                            <a class="btn btn-primary btn-square" target="_blank"
                                               href="{{ route('reports.organization', ['report' => request('report', 'trainingMatrix'), 'print' => true]) }}">Print</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection
