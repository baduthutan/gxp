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
                                <form action="{{ route('admin.bookingreportbyvehicle.index') }}" method="GET">
                                    <div class="row">
                                        <div class="col-sm-6 mb-1">
                                            <div class="row mb-1">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label fw-bolder"
                                                           for="vehicle_number">Vehicle Number</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <select class="form-select form-control" id="vehicle_number"
                                                                name="vehicle_number">
                                                            <option value="">All</option>
                                                            @foreach($vehicle as $item)
                                                                @if(request('vehicle_number') == $item->vehicle_number)
                                                                    <option
                                                                        value="{{$item->vehicle_number}}" selected
                                                                    >{{$item->vehicle_name}} [Vehicle Num. {{$item->vehicle_number}}]</option>
                                                                @else
                                                                    <option
                                                                        value="{{$item->vehicle_number}}">{{$item->vehicle_name}} [Vehicle Num. {{$item->vehicle_number}}]</option>
                                                                @endif
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                    @error('vehicle_number')
                                                    <div class="vehicle_numberError text-danger">
                                                        <small>{{ $message }}</small>
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 mb-1">
                                            <div class="mb-1 row">
                                                <div class="col-sm-3">
                                                    <label class="col-form-label fw-bolder" for="datetime_departure">Date Time
                                                        Departure</label>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i data-feather='calendar'></i></span>
                                                        <input type="text" class="form-control" id="datetime_departure"
                                                               placeholder="YYYY-MM-DD" name="datetime_departure"
                                                               style="background-color: #fff;"
                                                               value="{{ !empty(request('datetime_departure'))? request
                                                       ('datetime_departure'):
                                                       \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                               autocomplete="off"/>
                                                    </div>
                                                    @error('datetime_departure')
                                                    <div class="datettime_departureError text-danger">
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
                                                <div class="d-grid col-sm-12">
                                                    <button id="filter-search" type="submit"
                                                            class="btn btn-small btn-primary btn-block"><i
                                                            data-feather='search'></i>
                                                        Filter
                                                    </button>
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
                                            <th style="min-width: 100px;">Total Passenger</th>
{{--                                            <th style="min-width: 100px;">Schedule Type</th>--}}
                                            <th style="min-width: 300px;">From Area</th>
                                            <th style="min-width: 300px;">To Area</th>
                                            <th style="min-width: 300px;">Destination</th>
                                            <th style="min-width: 100px;">Notes</th>
                                            <th style="min-width: 120px;">Voucher Discount</th>
                                            <th style="min-width: 120px;">Payment method</th>
                                            <th style="min-width: 120px;">Total Price</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($bookings as $booking)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>{{ $booking->datetime_departure }}</td>
                                                <td>{{ $booking->customer_name }}</td>
                                                <td>{{ $booking->customer_phone }}</td>
                                                <td>{{ $booking->booking_customers_count }}</td>
{{--                                                <td>{{ ucfirst($booking->schedule_type) }}</td>--}}
                                                <td>{{ $booking->from_master_area_name .', ' }} {{
                                                $booking->from_master_sub_area_name }} </td>
                                                <td>{{ $booking->to_master_area_name .', ' }} {{
                                                $booking->to_master_sub_area_name }}</td>
                                                <td>{{ $booking->regional_name }} , {!!$booking->special_area_detail !!}</td>
                                                <td>{{ nl2br($booking->notes) }}</td>
                                                <td>{{ number_format($booking->promo_price, 2) }} USD</td>
                                                <td>{{ $booking->payment_method == 'venmo' ||
                                                $booking->payment_method == 'bank' || empty($booking->payment_method)
                                                 ? 'Non Credit Card' :
                                                'Credit Card'
                                                 }}</td>
                                                <td>{{ number_format($booking['total_price'], 2) }}</td>
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

        })
    </script>
@endsection
