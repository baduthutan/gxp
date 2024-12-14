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
<!--        <img src="img/8493.jpg" class="bg" loading="lazy" />-->
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-4 mb-5">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <h5 class="text-center font-weight-bold mb-4">Airport Shuttle & Charter Booking</h5>
                            <form id="form_booking">
                                <div class="form-group">
                                    <label for="from_type" class="form-text font-weight-bold">From Type</label>
                                    <select class="form-control select2 w-100" id="from_type" name="from_type"
                                        data-placeholder="From Type" required>
                                        <option value="airport">Airport</option>
                                        <option value="city">City</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="from_master_area_id"
                                        class="form-text font-weight-bold">From/Departure</label>
                                    <select class="form-control select2 w-100" id="from_master_area_id"
                                        name="from_master_area_id" data-placeholder="From/Departure" required disabled>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="from_master_sub_area_id" class="form-text font-weight-bold">Sub
                                        From/Departure</label>
                                    <select class="form-control select2 w-100" id="from_master_sub_area_id"
                                        name="from_master_sub_area_id"
                                        data-placeholder="Sub
                                        From/Departure"
                                        disabled>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="to_master_area_id" class="form-text font-weight-bold">To/Arrival</label>
                                    <select class="form-control select2 w-100" id="to_master_area_id"
                                        name="to_master_area_id" data-placeholder="To/Arrival" required disabled>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="to_master_sub_area_id" class="form-text font-weight-bold">Sub
                                        To/Arrival</label>
                                    <select class="form-control select2 w-100" id="to_master_sub_area_id"
                                        name="to_master_sub_area_id"
                                        data-placeholder="Sub
                                        To/Arrival" disabled>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="form-group text-center">
                                    <div class="form-check form-check-inline">
                                        <input {{ $request->booking_type == 'shuttle' ? 'checked' : null }}
                                            class="form-check-input" type="radio" name="booking_type" id="shuttle"
                                            value="shuttle" checked>
                                        <label {{ $request->booking_type == 'charter' ? 'checked' : null }}
                                            class="form-check-label" for="shuttle">Shuttle</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="booking_type" id="charter"
                                            value="charter">
                                        <label class="form-check-label" for="charter">Charter</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="date_departure" class="form-text font-weight-bold">Outward
                                        journey</label>
                                    <input type="date" class="form-control" id="date_departure" name="date_departure"
                                        value="{{ $request->date_departure }}" placeholder="Outward Journey" required />
                                </div>
                                <div class="form-group">
                                    <label for="passanger_adult" class="form-text font-weight-bold">Adult
                                        Passengers</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="passanger_adult"
                                            name="passanger_adult" value="{{ $request->passanger_adult }}"
                                            placeholder="Adult Passengers" min="1" max="9" value="1"
                                            required />
                                        <div class="input-group-append bg-light">
                                            <span class="input-group-text">Adult
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="passanger_baby" class="form-text font-weight-bold">Child
                                        Passengers</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="passanger_baby"
                                            name="passanger_baby" value="{{ $request->passanger_baby }}"
                                            placeholder="Adult Passengers" min="0" max="9" value="0"
                                            required />
                                        <div class="input-group-append bg-light">
                                            <span class="input-group-text">Child
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        Child is under 8 years old
                                    </small>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search fa-fw"></i> Search
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-8">
                    <div class="card card-semi shadow">
                        <div class="card-body">
                            <div class="section-title">
                                <h1>Schedue List</h1>
                                <div class="row" id="list_jadwal">
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
        let csrf = @json(csrf_token());
        let from_type = @json($request->from_type);
        let from_master_area_id = @json($request->from_master_area_id);
        let from_master_sub_area_id = @json($request->from_master_sub_area_id);
        let to_master_area_id = @json($request->to_master_area_id);
        let to_master_sub_area_id = @json($request->to_master_sub_area_id);
        let booking_type = @json($request->booking_type);
        let date_departure = @json($request->date_departure);
        let passanger_adult = @json($request->passanger_adult);
        let passanger_baby = @json($request->passanger_baby);
    </script>
    <script src="js/booking.js"></script>
@endsection
