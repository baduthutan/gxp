@extends('layouts.frontend.app')
@section('gaya')
    <!--    <style>-->
    <!--        .slick-slide {-->
    <!--            height: 500px;-->
    <!--        }-->
    <!---->
    <!--        .slick-slide img {-->
    <!--            height: 100%;-->
    <!--        }-->
    <!--    </style>-->
@endsection
@section('page_content')
<section id="home" class="home d-flex align-items-center" data-scroll-index="0">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div id="slick_slider" class="shadow" style="margin-top: 0px;">
                    <div>
                    <img src="img/slider/1674635582.png" alt="" loading="eager"
                        onClick="window.location.replace('#')" class="img-fluid" style="max-height: 500px">
                    </div>
                    <div>
                    <img src="img/slider/1674635593.png" alt="" loading="eager"
                        onClick="window.location.replace('#')" class="img-fluid" style="max-height: 500px">
                    </div>
                    <div>
                    <img src="img/slider/1674635602.png" alt="" loading="eager"
                        onClick="window.location.replace('#')" class="img-fluid" style="max-height: 500px">
                    </div>
                </div>           
            </div>
        </div>
    </div>
</section>

<!--    <section id="booking" class="booking section-padding" data-scroll-index="1">-->
<!--        <img src="img/8493.jpg" class="bg" loading="lazy" />-->
<!--        <div class="container">-->
<!--            <div class="row">-->
<!--                <div class="col-sm-12 col-md-12">-->
<!--                    <div class="card shadow-lg">-->
<!--                        <div class="card-body">-->
<!--                            <div class="section-title">-->
<!--                                <h6><strong>Are you ready for a better trip experience?</strong></h6>-->
<!--                            </div>-->
<!--                            <div class="section-title">-->
<!--                                <h6><strong>Ride with the Green Express!</strong></h6>-->
<!--                                <p><i>A licensed, Insured and Trusted Transportation Company</i></p>-->
<!--                            </div>-->
<!--                            <div class="booking-text">-->
<!--                                <p>Get an affordable, comfortable, reliable shuttle and charter service covering-->
<!--                                    Philadelphia - New Jersey - Newark Airport - JFK Airport.</p>-->
<!--                            </div>-->
<!--                            <div class="booking-text">-->
<!--                                <p><i>We have traveled for over 25,000 hours of driving, transporting more than 80,000-->
<!--                                        passengers from many reputable world-best airlines!</i></p>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </section>-->

    <section id="booking" class="booking section-padding" data-scroll-index="2">
        <!--        <img src="img/8493.jpg" class="bg" loading="lazy" />-->
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 mb-5">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <h6 class="text-center font-weight-bold mb-4">Let's Have a Better Trip Experience!<br>Booking in Just a Few Clicks</h6>
                            <form action="{{ route('search') }}" method="get">
                                @csrf
                                <input type="hidden" name="area_type" id="area_type" value="{{@$request->area_type}}"/>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="form-text font-weight-bold">Type <small
                                                            class="text-danger font-weight-bolder">*</small></label>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="booking_type"
                                                               id="shuttle" value="shuttle" checked="checked">
                                                        <label class="form-check-label" for="shuttle">Shuttle</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="booking_type"
                                                               id="charter" value="charter">
                                                        <label class="form-check-label" for="charter">Charter</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="from_master_sub_area_id"
                                                           class="form-text font-weight-bold">From <small
                                                            class="text-danger font-weight-bolder">*</small></label>
                                                    <select class="form-control select2" id="from_master_sub_area_id"
                                                            name="from_master_sub_area_id"
                                                            data-placeholder="Select from location" style="width: 100%;"
                                                            required>
                                                        <option value=""></option>
                                                        @foreach ($master_area as $item)
                                                            <optgroup label="{{ $item->name }}">
                                                                @foreach ($item->master_sub_area as $subItem)
                                                                    <option value="{{ $subItem->id }}"
                                                                            data-area-type="{{ $item->area_type }}"
                                                                            data-master-area-id="{{ $subItem->master_area_id }}">
                                                                        {{ $subItem->name }}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>

                                                    <div id="roundtrips_1">
                                                        <label for="from_master_sub_area_id_2" id="label_from_master_sub_area_id_2"
                                                            class="form-text font-weight-bold">From <small
                                                                class="text-danger font-weight-bolder">*</small></label>
                                                        <select class="form-control select2" id="from_master_sub_area_id_2"
                                                                name="from_master_sub_area_id_2"
                                                                data-placeholder="Select from location" style="width: 100%;"
                                                                >
                                                            <option value=""></option>
                                                            @foreach ($master_area as $item)
                                                                <optgroup label="{{ $item->name }}">
                                                                    @foreach ($item->master_sub_area as $subItem)
                                                                        <option value="{{ $subItem->id }}"
                                                                                data-area-type="{{ $item->area_type }}"
                                                                                data-master-area-id="{{ $subItem->master_area_id }}">
                                                                            {{ $subItem->name }}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="to_master_sub_area_id"
                                                           class="form-text font-weight-bold">To
                                                        <small class="text-danger font-weight-bolder">*</small></label>
                                                    <select class="form-control select2" id="to_master_sub_area_id"
                                                            name="to_master_sub_area_id"
                                                            data-placeholder="Select to location"
                                                            disabled style="width: 100%;" required>
                                                        <option value=""></option>
                                                    </select>

                                                    <div id="roundtrips_2">
                                                        <label for="to_master_sub_area_id_2" id="label_to_master_sub_area_id_2"
                                                            class="form-text font-weight-bold">To
                                                            <small class="text-danger font-weight-bolder">*</small></label>
                                                        <select class="form-control select2" id="to_master_sub_area_id_2"
                                                                name="to_master_sub_area_id_2"
                                                                data-placeholder="Select to location"
                                                                disabled style="width: 100%;">
                                                            <option value=""></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="alert alert-warning" role="alert"
                                                     id="alert-charter" style="display: none;">
                                                    <small>
                                                        Private Charter for other destination, please send us your
                                                        itinerary (date and time, from, to, visiting places if any,
                                                        estimated visiting time) <a
                                                            class="font-weight-bold text-success"
                                                            href="https://wa.me/+12152718381?text=Please%20inform%20us%20the%20vehicle%20availability%20for%20charter.%20Travel%20itinerary%20as%20follows:%0ADate%20and%20time:%0ANumber%20of%20people:%0AFrom:%0ATo:%0AVisiting%20places%20(if%20any):%0AEstimated%20visiting%20time%20(if%20any):"
                                                            target="_blank">Whatsapp message. <i
                                                                class='fab fa-whatsapp'></i></a>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label for="date_departure" class="form-text font-weight-bold">Departure
                                                        or Arrival Date<small
                                                            class="text-danger font-weight-bolder">*</small></label>
                                                    <input type="text" class="form-control form-control-sm"
                                                           id="date_departure" name="date_departure"
                                                           placeholder="Departure / Arrival Date"
                                                           value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                           required/>

                                                    <div id="roundtrips_3">
                                                        <label for="date_departure_2" id="label_date_departure_2" class="form-text font-weight-bold">Departure
                                                            or Arrival Date<small
                                                                class="text-danger font-weight-bolder">*</small></label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            id="date_departure_2" name="date_departure_2"
                                                            placeholder="Departure / Arrival Date"
                                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                            />
                                                    </div>

                                                    <small id="booking-info" class="form-text text-muted">Booking within one day before
                                                        departure / arrival date please call us or send
                                                        <a
                                                            class="font-weight-bold text-success"
                                                            href="https://wa.me/+12152718381?text=Please%20inform%20us%20the%20vehicle%20availability%20for%20charter.%20Travel%20itinerary%20as%20follows:%0ADate%20and%20time:%0ANumber%20of%20people:%0AFrom:%0ATo:%0AVisiting%20places%20(if%20any):%0AEstimated%20visiting%20time%20(if%20any):"
                                                            target="_blank">Whatsapp message. <i
                                                                class='fab fa-whatsapp'></i></a>
                                                    </small>
                                                </div>

                                                <input type="hidden" name="is_roundtrip" value="0">
                                                <input type="checkbox" id="is_roundtrip" name="is_roundtrip" value="1">
                                                <label for="is_roundtrip">Round Trip</label>
                                            </div>

                                            <div class="col-sm-6 passenger_adult_input">
                                                <div class="form-group">
                                                    <label for="passanger_adult" class="form-text font-weight-bold">Adult
                                                        Passangers <small
                                                            class="text-danger font-weight-bolder">*</small></label>
                                                    <div class="input-group">
                                                        {{--                                                        <input type="number" class="form-control" id="passanger_adult"--}}
                                                        {{--                                                            name="passanger_adult" placeholder="Adult Passangers"--}}
                                                        {{--                                                            min="1" value="1" required />--}}
                                                        <select class="form-control" id="passanger_adult"
                                                                name="passanger_adult" required>
                                                            @for ($i = 1; $i <= 20; $i++)
                                                                <option value="{{$i}}">{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                        <div class="input-group-append bg-light">
                                                            <span class="input-group-text">Adult</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 passenger_baby_input">
                                                <div class="form-group">
                                                    <label for="passanger_baby" class="form-text font-weight-bold">Child
                                                        Passangers <small
                                                            class="text-danger font-weight-bolder">*</small></label>
                                                    <div class="input-group">
                                                        <select class="form-control" id="passanger_baby"
                                                                name="passanger_baby" required>
                                                            @for ($i = 0; $i <= 20; $i++)
                                                                <option value="{{$i}}">{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                        <div class="input-group-append bg-light"
                                                             id="inputGroup-sizing-sm">
                                                            <span class="input-group-text text-sm">Child</span>
                                                        </div>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        Child is up to 8 years old
                                                    </small>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search fa-fw"></i> <strong>Search</strong>
                                    </button>
                                </div>
                                <small class="text-info font-weight-bolder">*(asterisk symbol) Field required to be
                                    filled</small>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{--    <section id="profile" class="profile section-padding" data-scroll-index="3">--}}
    {{--        <div class="container">--}}
    {{--            <div class="section-title">--}}
    {{--                <h1><strong>Our Services</strong></h1>--}}
    {{--            </div>--}}
    {{--            <div class="row">--}}
    {{--                <div class="col-md-4 mb-3">--}}
    {{--                    <div class="card card-shadow">--}}
    {{--                        <img src="{{ asset('img/ontime.jpg') }}"--}}
    {{--                             class="card-img-top" alt="">--}}
    {{--                        <div class="card-body">--}}
    {{--                            <p class="card-text">Whether you’re a business traveller, a couple, a big group of friends--}}
    {{--                                or family--}}
    {{--                                travelling with lots of luggage, by reserving your airport shuttle or private car you--}}
    {{--                                can have the reassurance and security that you’ll be picked up on time and taken--}}
    {{--                                straight to your home, hotel or attraction</p>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="col-md-4 mb-3">--}}
    {{--                    <div class="card card-shadow">--}}
    {{--                        <img src="{{ asset('img/drive_safely.jpg') }}" class="card-img-top"--}}
    {{--                             alt="">--}}
    {{--                        <div class="card-body">--}}
    {{--                            <p class="card-text">We drive safely and follow all rules of the road to ensure you have a--}}
    {{--                                safe and--}}
    {{--                                pleasurable trip</p>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="col-md-4 mb-3">--}}
    {{--                    <div class="card card-shadow">--}}
    {{--                        <img src="{{ asset('img/passenger_pickup.jpg') }}"--}}
    {{--                             class="card-img-top" alt="">--}}
    {{--                        <div class="card-body">--}}
    {{--                            <p class="card-text">Passenger Pick Up And Drop Off Services</p>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </section>--}}


@endsection
@section('vitamin')
    <script>
        let booking_type = null;

        document.getElementById('is_roundtrip').addEventListener('change', function() {
            var isChecked = this.checked ? 1 : 0;

            if (isChecked) {
                $("#roundtrips_1").show();
                $("#roundtrips_2").show();

                $("#date_departure_2").flatpickr({
                    altInput: true,
                    altFormat: "d F Y",
                    dateFormat: "Y-m-d",
                })
                $("#roundtrips_3").show();

                $("#from_master_sub_area_id_2").attr("required", "required");
                $("#to_master_sub_area_id_2").attr("required", "required");
                $("#date_departure_2").attr("required", "required");

                document.getElementById('booking-info').style.display = 'none';
            } else {
                $("#roundtrips_1").hide();
                $("#roundtrips_2").hide();

                $("#date_departure_2").flatpickr().destroy();
                $("#roundtrips_3").hide();

                $("#from_master_sub_area_id_2").removeAttr("required");
                $("#to_master_sub_area_id_2").removeAttr("required");
                $("#date_departure_2").removeAttr("required");

                document.getElementById('booking-info').style.display = 'block';
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="js/booking.js"></script>
@endsection
