@extends('layouts.app')
@section('gaya')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">{{ $page_title }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h1>Shuttle</h1>
                                <form method="GET" action="{{ route('admin.dashboard') }}">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="dr_shuttle" name="dr_shuttle"
                                                required />
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <canvas id="chart_shuttle" style="height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h1>Charter</h1>
                                <form method="GET" action="{{ route('admin.dashboard') }}">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="dr_charter" name="dr_charter"
                                                required />
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <canvas id="chart_charter" style="height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        const fromShuttle = @json($from_shuttle) ?? moment().startOf('month');
        const toShuttle = @json($to_shuttle) ?? moment().endOf('month');

        const fromCharter = @json($from_charter) ?? moment().startOf('month');
        const toCharter = @json($to_charter) ?? moment().endOf('month');

        const chartShuttle = document.getElementById('chart_shuttle');
        const dateShuttle = @json($arr_date_shuttle);
        const personShuttle = @json($arr_person_shuttle);

        const chartCharter = document.getElementById('chart_charter');
        const dateCharter = @json($arr_date_charter);
        const personCharter = @json($arr_person_charter);
        const vehicleCharter = @json($arr_vehicle_charter);


        new Chart(chartShuttle, {
            type: 'bar',
            data: {
                labels: dateShuttle,
                datasets: [{
                    label: 'Person',
                    data: personShuttle,
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // new Chart(chartCharter, {
        //     type: 'line',
        //     data: {
        //         labels: dateCharter,
        //         datasets: [{
        //             label: 'Person',
        //             data: personCharter,
        //             borderWidth: 1
        //         }]
        //     },
        //     options: {
        //         maintainAspectRatio: false,
        //         scales: {
        //             y: {
        //                 beginAtZero: true,
        //                 ticks: {
        //                     stepSize: 1
        //                 }
        //             }
        //         }
        //     }
        // });

        new Chart(chartCharter, {
            type: 'bar',
            data: {
                labels: dateCharter,
                datasets: [{
                    label: 'Vehicle',
                    data: vehicleCharter,
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        $('#dr_shuttle').daterangepicker({
            opens: 'left',
            startDate: fromShuttle,
            endDate: toShuttle,
            locale: {
                format: 'YYYY-MM-DD'
            },
        });

        $('#dr_charter').daterangepicker({
            opens: 'left',
            startDate: fromCharter,
            endDate: toCharter,
            locale: {
                format: 'YYYY-MM-DD'
            },
        });

        $(document).ready(() => {

        })
    </script>
@endsection
