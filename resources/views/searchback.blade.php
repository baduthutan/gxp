@extends('layouts.frontend.app')
@section('page_content')
    <section id="home" class="home d-flex align-items-center" data-scroll-index="0">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 mt-5">
                    <h1 class="text-center mt-5">Search Airport Shuttle & Charter Booking Schedule</h1>
                </div>
            </div>
        </div>
    </section>

    <section id="booking" class="booking section-padding" data-scroll-index="1">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 mb-5">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <h5 class="text-center font-weight-bold mb-4">Airport Shuttle & Charter Booking</h5>
                            <div class="form-group">
                                <a href="/" class="btn btn-primary btn-block">
                                    <i class="fas fa-exchange"></i> Change Schedule
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-semi shadow">
                        <div class="card-body">
                            <div class="section-title">
                                <h1>Schedule List</h1>
                                @if($request->booking_type == 'charter')
                                    <div class="alert alert-warning" role="alert">
                                        Please send us your itinerary (Date and Time, Number of people, From, To,
                                        Visiting
                                        places (if any), Estimated visiting time (if any):
                                        <br>
                                        <a class="font-weight-bold text-success"
                                           href="https://wa.me/+12152718381?text=Please%20send%20us%20your%20Date and time:%0ANumber of people%0AFrom:%0ATo:%0AVisiting places (if any): Estimated visiting time (if Any):%0A"
                                           target="_blank">Contact us via Whatsapp message <i
                                                class='fab fa-whatsapp'></i></a>
                                    </div>
                                @endif
                                <div class="row" id="list_jadwal">
                                    @forelse($schedule as $item)
                                        <div class="col-12 my-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-5">
                                                            <div class="row">
                                                                <div
                                                                    class="col-sm-7 pr-2 d-flex align-items-center
                                                                    justify-content-center flex-column">
                                                                    <h5 style="font-size: 1rem; font-weight: 700;">From
                                                                        {{ $item->from_master_area->name }}
                                                                        @if($request->booking_type == 'shuttle')
                                                                            {{ $item->from_master_sub_area->name }}
                                                                        @endif
                                                                        <br>
                                                                        <br>
                                                                        To
                                                                        {{ $item->to_master_area->name }}
                                                                        @if($request->booking_type == 'shuttle')
                                                                            {{ $item->to_master_sub_area->name }}
                                                                        @endif
                                                                    </h5>
                                                                </div>
                                                                <div
                                                                    class="col-sm-5 text-left d-flex align-items-center
                                                                    sm:justify-content-start">
                                                                    <div>
                                                                        <span class="font-weight-bold">Date Departure :
                                                                            <br>
                                                                            <span
                                                                                class="text-danger">{{ \Carbon\Carbon::parse($request->date_departure)->format('d M Y') }}</span>
                                                                            </span>
                                                                        <br>
                                                                        @if ($request->booking_type == 'shuttle')
                                                                            <span class="font-weight-bold">Time Departure :
                                                                            </span>
                                                                            <br>
                                                                            <h3 class="font-weight-bold text-danger">
                                                                                {{ date('h:i A', strtotime($item->time_departure))
                                                                                }}
                                                                            </h3>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-5 d-flex align-items-center">
                                                            <div class="row">
                                                                <div
                                                                    class="col-sm-12 col-md-12 d-flex align-items-center">
                                                                    <div>
                                                                        <p>
                                                                            @if($request->booking_type ==
                                                                            'charter')
                                                                                Vehicle data: <span
                                                                                    class="font-weight-bold">{{
                                                                                    $item->vehicle_name }} , Max :{{
                                                                                    $item->total_seat }} Passenger)
                                                                                    </span>
                                                                                <br>
                                                                            @endif
                                                                            Ticket Price :
                                                                            <span
                                                                                class="font-weight-bold text-primary">${{ $item->price }}</span>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-1 my-auto">
                                                                <?php
                                                                $btn_type = 'submit';
                                                                if (!$item->is_available)  $btn_type = 'button';
                                                                ?>
                                                            @if($request->is_roundtrip == false)
                                                            <form method="post" action="/booking">
                                                                @csrf
                                                                <input type="hidden" name="from_type"
                                                                       value="{{ $item->from_type }}"/>
                                                                <input type="hidden" name="from_master_area_id"
                                                                       value="{{ $item->from_master_area_id }}"/>
                                                                <input type="hidden" name="from_master_sub_area_id"
                                                                       value="{{ $request->from_master_sub_area_id }}"/>
                                                                <input type="hidden" name="to_master_area_id"
                                                                       value="{{ $item->to_master_area_id }}"/>
                                                                <input type="hidden" name="to_master_sub_area_id"
                                                                       value="{{ $request->to_master_sub_area_id }}"/>
                                                                <input type="hidden" name="booking_type"
                                                                       value="{{ $request->booking_type }}"/>
                                                                <input type="hidden" name="date_departure"
                                                                       value="{{ $request->date_departure }}"/>
                                                                <input type="hidden" name="passanger_adult"
                                                                       value="{{ $request->passanger_adult }}"/>
                                                                <input type="hidden" name="passanger_baby"
                                                                       value="{{ $request->passanger_baby }}"/>
                                                                <input type="hidden" name="special_area_id"
                                                                       value="{{ $item->special_area_id }}"/>
                                                                <input type="hidden" name="schedule_id"
                                                                       value="{{ $item->id }}"/>
                                                                <input type="hidden" name="is_available"
                                                                       value="{{ $item->is_available }}"/>
                                                                <input type="hidden" name="is_roundtrip"
                                                                       value="{{ $is_roundtrip }}"/>
                                                                <input type="hidden" name="flagged"
                                                                       value="{{ $flagged }}"/>
                                                                <input type="hidden" name="code1"
                                                                       value="{{ $code1 }}"/>
                                                                <input type="hidden" name="from_master_sub_area_id_2"
                                                                       value="{{ $from_master_sub_area_id_2 }}"/>
                                                                <input type="hidden" name="to_master_sub_area_id_2"
                                                                       value="{{ $to_master_sub_area_id_2 }}"/>
                                                                <input type="hidden" name="date_departure_2"
                                                                       value="{{ $date_departure_2 }}"/>
                                                                <button type="{{ $btn_type }}"
                                                                        class="btn btn-info font-weight-bold sm:btn-block"
                                                                        @if (!$item->is_available && $request->booking_type
                                                                         !== 'charter' )
                                                                            onclick="show_swal('This booking doesn\'t have any available seat at moment, Please contact admin, so that can immediately provide info when seats are available.')"
                                                                        @endif
                                                                        @if (!$item->is_available && $request->booking_type
                                                                                 == 'charter' )
                                                                            onclick="show_swal('This charter car ' +
                                                                             'isn\'t ' +
                                                                             'available at moment, Please contact ' +
                                                                              'admin, so that can immediately provide' +
                                                                               ' info when this car is available.')"
                                                                    @endif
                                                                >

                                                                    <i class="fas fa-shuttle-van"></i> Choose
                                                                </button>
                                                            </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @empty
                                        <div class="col-12 text-center">
                                            <img src="img/undraw_empty_re_opql.svg" alt="not found"
                                                 style="width: 400px;"/>
                                        </div>
                                        <div class="col-12 text-center">
                                            <h5 class="my-3 font-weight-bold text-info">Please contact us !</h5>
                                            <div class="alert alert-warning" role="alert">
                                                Booking within one day before departure / arrival date please call us or
                                                send text messages
                                                <br>
                                                <a class="font-weight-bold text-success"
                                                   href="https://wa.me/+12152718381?text=Please%20inform%20us%20the%20seat%20availability%20for%0AName:%0AFrom:%0ATo:%0ADate and time:%0ANo. of people:%0APhone number (Preferably Whatsapp phone number):"
                                                   target="_blank">Contact us via Whatsapp <i
                                                        class='fab fa-whatsapp'></i></a>
                                            </div>
                                        </div>
                                    @endforelse

                                    <div class="col-sm-12 mt-2 d-flex justify-content-center">
                                        <div> {{ $schedule->links() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('vitamin')
    <script>
        function show_swal(message) {
            Swal.fire({
                position: 'top-end',
                icon: 'warning',
                title: message,
                showConfirmButton: false,
                toast: true,
                timer: 3000,
            });
        }
    </script>
@endsection
