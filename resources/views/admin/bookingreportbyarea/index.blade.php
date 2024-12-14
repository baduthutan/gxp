@extends('layouts.app')
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
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                @csrf
                                <form action="{{ route('admin.bookingreportbyarea.index') }}" method="GET"
                                    id="filter-form">
                                    <div class="row">
                                        <div class="col-sm-6 mb-1">
                                            <div class="row mb-1">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label fw-bolder"
                                                        for="from_master_sub_area_id">Area Type</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <select class="form-select form-control" id="area_type"
                                                            name="area_type">
                                                            <option value="">All Area</option>
                                                            <option value="city"
                                                                {{ request('area_type') == 'city' ? 'selected' : '' }}>City
                                                            </option>
                                                            <option value="airport"
                                                                {{ request('area_type') == 'airport' ? 'selected' : '' }}>
                                                                Airport</option>
                                                        </select>
                                                    </div>
                                                    @error('area_type')
                                                        <div class="area_typeError text-danger">
                                                            <small>{{ $message }}</small>
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 mb-1">
                                            <div class="mb-1 row">
                                                <div class="col-sm-4">
                                                    <label class="col-form-label fw-bolder" for="datetime_departure">Date
                                                        Time
                                                        Departure</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                data-feather='calendar'></i></span>
                                                        <input type="text" class="form-control" id="datetime_departure"
                                                            placeholder="YYYY-MM-DD" name="datetime_departure"
                                                            style="background-color: #fff;"
                                                            value="{{ !empty(request('datetime_departure'))
                                                                ? request('datetime_departure')
                                                                : \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                            autocomplete="off" />
                                                    </div>
                                                    @error('datetime_departure')
                                                        <div class="datettime_departureError text-danger">
                                                            <small>{{ $message }}</small>
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 mb-1">
                                            <div class="row mb-1">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label fw-bolder" for="trip_number">Trip
                                                        Number</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <select class="form-select form-control" id="trip_number"
                                                            name="trip_number">
                                                            <option value="">All</option>
                                                            <option value="1"
                                                                {{ request('trip_number') == '1' ? 'selected' : '' }}>1st
                                                            </option>
                                                            <option value="2"
                                                                {{ request('trip_number') == '2' ? 'selected' : '' }}>
                                                                2nd</option>
                                                        </select>
                                                    </div>
                                                    @error('trip_number')
                                                        <div class="trip_numberError text-danger">
                                                            <small>{{ $message }}</small>
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 mb-1">
                                            <div class="mb-1 row">
                                                <div class="d-grid col-sm-12 mb-1">
                                                    <button id="filter-search" type="submit"
                                                        class="btn btn-small btn-primary btn-block"><i
                                                            data-feather='search'></i>
                                                        Filter
                                                    </button>
                                                </div>

                                                <div class="d-grid col-sm-12">
                                                    <button id="export-xlx" class="btn btn-small btn-success btn-block"><i
                                                            data-feather='file-text'></i> Export
                                                        Excel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                {{-- <a href="/admin/booking/add" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add Data
                                </a> --}}
                                <div class="table-responsive">
                                    <table class="table table-bordered datatables">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th style="min-width: 100px;">Date Time Departure</th>
                                                <th style="min-width: 100px;">Customer Name</th>
                                                <th style="min-width: 100px;">Customer Phone Number</th>
{{--                                                <th style="min-width: 100px;">Schedule Type</th>--}}
                                                <th style="min-width: 300px;">From Area</th>
                                                <th style="min-width: 300px;">To Area</th>
                                                <th style="min-width: 300px;">Destination</th>
                                                <th style="min-width: 100px;">Trip Number</th>
                                                <th style="min-width: 100px;">Flight Number</th>
{{--                                                <th style="min-width: 100px;">Flight Info</th>--}}
                                                <th style="min-width: 120px;">Total Passenger</th>
                                                <th style="min-width: 120px;">Luggage Qty</th>
                                                <th style="min-width: 120px;">Overweight or Oversized Luggage Qty</th>
                                                <th style="min-width: 100px;">Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bookings as $booking)
                                                @php
                                                    $passanger = get_passenger($booking->booking_number)
                                                @endphp
                                                <tr>
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>{{ $booking->datetime_departure }}</td>
                                                    <td>{{ $booking->customer_name }}</td>
                                                    <td>{{ $booking->customer_phone }}</td>
{{--                                                    <td>{{ ucfirst($booking->schedule_type) }}</td>--}}
                                                    <td>{{ $booking->from_master_area_name . ', ' }}
                                                        {{ $booking->from_master_sub_area_name }} </td>
                                                    <td>{{ $booking->to_master_area_name . ', ' }}
                                                        {{ $booking->to_master_sub_area_name }}</td>
                                                    <td>{{ $booking->regional_name }} , {!! $booking->special_area_detail !!}</td>
                                                    <td>{{ $booking->trip_number }}</td>
                                                    <td>{{ $booking->flight_number }}</td>
{{--                                                    <td>{{ $booking->flight_info }}</td>--}}
                                                    <td>{{ number_format($booking->qty_adult + $booking->qty_baby)
                                                    }}</td>
                                                    <td>{{ number_format($booking->luggage_qty, 0) }}</td>
                                                    <td>{{ number_format($booking->overweight_luggage_qty, 0) }}</td>
                                                    <td>{!! $passanger !!}{{ nl2br($booking->notes) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(() => {
            $('.datatables').DataTable({
                scrollX: true,
                order: [],
            })



        })


        $(document).ready(function() {
            $(".form-select").select2({
                allowClear: true,
            });

            $('#datetime_departure').flatpickr({
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d F Y",
                altInputClass: "bg-white form-control",
                // locale: "id"
            });

            var form_filter = $('#filter-form')

            $('#export-xlx').on('click', function(e) {
                e.preventDefault();
                window.open('{{ url('/admin/bookingreportbyarea') }}' + '/export_xlx?' + form_filter
                    .serialize(),
                    '_blank')
            })

            // $("#from_master_sub_area_id").on("change", function (e) {
            //     let master_area_id = $(this).find(":selected").data('master-area-id');
            //     let area_type = $(this).find(":selected").data('area-type');
            //     let token = $("input[name=_token]").val();

            //     $.ajax({
            //         url: `{{ url('/api/booking_filter/get_arrival_filter') }}`,
            //         method: "post",
            //         dataType: "json",
            //         data: {
            //             area_type: area_type,
            //             booking_type: 'shuttle',
            //             "_token" : token
            //         },
            //         beforeSend: function () {
            //             $(`#to_master_sub_area_id`)
            //                 .html(`<option value=""></option>`)
            //                 .prop("disabled", true);
            //         },
            //         success: function (res) {
            //             let html = `<option value=""></option>`
            //             console.log(res.data)
            //             res.data.forEach((x) => {
            //                 html += `<optgroup label="${x.text}">`
            //                 if(x.children){
            //                     x.children.forEach((y) => {
            //                         html += `<option value="${y.master_area_id}|${y.id}|${y.area_type}"  data-area-type="${y.area_type}"
        //                 data-master-area-id="${y.master_area_id}">${y.name}</option>`;
            //                     })
            //                 }

            //                 html += `</optgroup>`
            //             });
            //             $(`#to_master_sub_area_id`)
            //                 .html(html)
            //                 .prop("disabled", false);
            //         },
            //         error: function (err) {
            //             console.log(err.responseJSON)
            //         },
            //         complete: function () {
            //             //
            //         }
            //     });
            // })
        })
    </script>
@endsection
