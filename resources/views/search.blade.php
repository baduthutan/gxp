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
                                <div class="row" id="list_jadwal">
                                    @forelse($schedule as $item)
                                        <div class="col-12 my-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-5">
                                                            <h5>
                                                                From {{ $item->from_master_area->name }}
                                                                @if ($booking_type == 'shuttle')
                                                                    {{ $item->from_master_sub_area->name }}
                                                                @endif
                                                                To {{ $item->to_master_area->name }}
                                                                @if ($booking_type == 'shuttle')
                                                                    {{ $item->to_master_sub_area->name }}
                                                                @endif
                                                            </h5>
                                                            <span>Date Departure:
                                                                {{ \Carbon\Carbon::parse($date_departure)->format('d M Y') }}</span>
                                                            @if ($booking_type == 'shuttle')
                                                                <br>
                                                                <span>Time Departure:
                                                                    {{ date('h:i A', strtotime($item->time_departure)) }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="col-sm-12 col-md-5">
                                                            <p>
                                                                @if ($booking_type == 'charter')
                                                                    Vehicle: {{ $item->vehicle_name }} (Max:
                                                                    {{ $item->total_seat }} passengers)
                                                                @endif
                                                                <br>Price: ${{ $item->price }}
                                                            </p>
                                                        </div>
                                                        <div class="col-sm-12 col-md-2 text-center">
                                                            <button
                                                                class="btn btn-secondary font-weight-bold choose-schedule"
                                                                data-schedule-id="{{ $item->id }}"
                                                                data-direction="departure"
                                                                @if (!$item->is_available) onclick="show_swal('This schedule is unavailable. Please contact admin.')" @endif>
                                                                Choose
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center">
                                            <h5>No schedules found. Please contact us!</h5>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            @if ($is_roundtrip)
                                <div class="section-title mt-5">
                                    <h1>Return Schedule List</h1>
                                    <div class="row" id="list_jadwal_return">
                                        @forelse($return_schedule as $item)
                                            <!-- Similar structure as above for return schedules -->
                                            <div class="col-12 my-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-5">
                                                                <h5>
                                                                    From {{ $item->from_master_area->name }}
                                                                    @if ($booking_type == 'shuttle')
                                                                        {{ $item->from_master_sub_area->name }}
                                                                    @endif
                                                                    To {{ $item->to_master_area->name }}
                                                                    @if ($booking_type == 'shuttle')
                                                                        {{ $item->to_master_sub_area->name }}
                                                                    @endif
                                                                </h5>
                                                                <span>Date Departure:
                                                                    {{ \Carbon\Carbon::parse($date_departure_2)->format('d M Y') }}</span>
                                                                @if ($booking_type == 'shuttle')
                                                                    <br>
                                                                    <span>Time Departure:
                                                                        {{ date('h:i A', strtotime($item->time_departure)) }}</span>
                                                                @endif
                                                            </div>
                                                            <div class="col-sm-12 col-md-5">
                                                                <p>
                                                                    @if ($booking_type == 'charter')
                                                                        Vehicle: {{ $item->vehicle_name }} (Max:
                                                                        {{ $item->total_seat }} passengers)
                                                                    @endif
                                                                    <br>Price: ${{ $item->price }}
                                                                </p>
                                                            </div>
                                                            <div class="col-sm-12 col-md-2 text-center">
                                                                <button
                                                                    class="btn btn-secondary font-weight-bold choose-schedule"
                                                                    data-schedule-id="{{ $item->id }}"
                                                                    data-direction="return"
                                                                    @if (!$item->is_available) onclick="show_swal('This schedule is unavailable. Please contact admin.')" @endif>
                                                                    Choose
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-center">
                                                <h5>No return schedules found.</h5>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            @endif

                            <div class="col-sm-12 mt-4 text-center">
                                <form id="bookingForm" method="post" action="/booking">
                                    @csrf
                                    <input type="hidden" name="booking_type" value="{{ $booking_type }}" />
                                    <input type="hidden" name="departure_schedule_id" id="departure_schedule_id"
                                        value="">
                                    <input type="hidden" name="date_departure" value="{{ $date_departure }}" />
                                    <input type="hidden" name="passenger_adult" value="{{ $passanger_adult }}" />
                                    <input type="hidden" name="passenger_baby" value="{{ $passanger_baby }}" />
                                    <input type="hidden" name="is_roundtrip" value="{{ $is_roundtrip }}" />
                                    @if ($is_roundtrip)
                                        <input type="hidden" name="date_departure" value="{{ $date_departure_2 }}" />
                                        <input type="hidden" name="return_schedule_id" id="return_schedule_id"
                                            value="">
                                    @endif
                                    <button type="submit" class="btn btn-primary font-weight-bold btn-block"> <i
                                            class="fas fa-save"></i> Submit Booking</button>
                                </form>
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

        document.querySelectorAll('.choose-schedule').forEach(button => {
            button.addEventListener('click', () => {
                const scheduleId = button.dataset.scheduleId;
                const direction = button.dataset.direction;

                if (direction === 'departure') {
                    const currentChosen = document.querySelector(
                        '.choose-schedule[data-direction="departure"].btn-info');
                    if (currentChosen) {
                        currentChosen.classList.remove('btn-info');
                        currentChosen.classList.add('btn-secondary');
                        currentChosen.textContent = 'Choose';
                    }

                    button.classList.remove('btn-secondary');
                    button.classList.add('btn-info');
                    button.textContent = 'Chosen';

                    document.getElementById('departure_schedule_id').value = scheduleId;

                } else if (direction === 'return') {
                    const currentChosen = document.querySelector(
                        '.choose-schedule[data-direction="return"].btn-info');
                    if (currentChosen) {
                        currentChosen.classList.remove('btn-info');
                        currentChosen.classList.add('btn-secondary');
                        currentChosen.textContent = 'Choose';
                    }

                    button.classList.remove('btn-secondary');
                    button.classList.add('btn-info');
                    button.textContent = 'Chosen';

                    document.getElementById('return_schedule_id').value = scheduleId;
                }
            });
        });
    </script>
@endsection
