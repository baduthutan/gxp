@extends('layouts.frontend.app')
@section('gaya')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"
        integrity="sha512-kq3FES+RuuGoBW3a9R2ELYKRywUEQv0wvPTItv3DSGqjpbNtGWVdvT8qwdKkqvPzT93jp8tSF4+oN4IeTEIlQA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .input-group>.select2-container--bootstrap {
            width: auto;
            flex: 1 1 auto;
        }

        .input-group>.select2-container--bootstrap .select2-selection--single {
            height: 100%;
            line-height: inherit;
            padding: 0.5rem 1rem;
        }
    </style>
@endsection
@section('page_content')
    <section id="home" class="home d-flex align-items-center" data-scroll-index="0">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 mt-5">
                    <h1 class="text-center mt-5">
                        @if ($booking_type == 'shuttle')
                            Booking Shuttle
                        @else
                            Booking Charter
                        @endif
                    </h1>
                </div>
            </div>
        </div>
    </section>
    {{-- @if (session('booking_type') == 'shuttle' and empty($total_seat))
        <div class="alert alert-warning font-weight-bold" role="alert">
            <i class="fas fa-exclamation-triangle"></i> This booking doesn't have any
            available
            seat at moment, Please contact admin, so that can immediately provide info when
            seats are available.
        </div>
    @endif --}}
    <section id="booking" class="booking section-padding" data-scroll-index="1">
        <form id="form_booking">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 col-md-8 mb-5">
                        <div class="card shadow-lg">
                            <div class="card-body">
                                <h5 class="text-center font-weight-bold mb-4">Customer & Passenger Data</h5>
                                <div class="row">
                                    <!-- Customer Data Section -->
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="customer_name" class="form-text font-weight-bold">
                                                Customer Name
                                                <small class="text-danger font-weight-bolder">*</small>
                                            </label>
                                            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Customer Name" required />
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="same_passenger">
                                                <label class="form-check-label" for="same_passenger">
                                                    Passenger Name is the same as the Customer Name above
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="customer_phone" class="form-text font-weight-bold">
                                                Customer Phone
                                                <small class="text-danger font-weight-bolder">*</small>
                                            </label>
                                            <input type="tel" id="customer_phone" class="form-control" name="customer_phone" placeholder="Customer Phone" required onkeypress="return onlyNumberKey(event)" />
                                            <span class="text-muted font-italic">
                                                <small>Please fill in US phone number if available</small>
                                            </span>
                                        </div>
                                        <input type="hidden" class="form-control" id="customer_password" name="customer_password" autocomplete="new-password" value="123456" />
                                        <hr />
                                        <div class="form-group">
                                            <label for="customer_email" class="form-text font-weight-bold">Customer Email</label>
                                            <input type="email" class="form-control" id="customer_email" name="customer_email" placeholder="Customer Email" />
                                        </div>
                                        <hr />
                                    </div>
                                    <!-- Passenger Data Section -->
                                    <div class="col-sm-12 col-md-6">
                                        @for ($i = 1; $i <= $passenger_total; $i++)
                                            <div class="form-group">
                                                <label for="passenger_name_{{ $i }}" class="form-text font-weight-bold">
                                                    Passenger Name {{ $i }}
                                                    <small class="text-danger font-weight-bolder">*</small>
                                                </label>
                                                <input type="text" class="form-control" id="passenger_name_{{ $i }}" name="passenger_name[]" placeholder="Passenger Name" required />
                                            </div>
                                            <div class="form-group">
                                                <label for="passenger_phone_{{ $i }}" class="form-text font-weight-bold">
                                                    Passenger Phone {{ $i }}
                                                    <small class="text-danger font-weight-bolder">*</small>
                                                </label>
                                                <input type="tel" class="form-control" id="passenger_phone_{{ $i }}" name="passenger_phone[]" placeholder="Passenger Phone" required onkeypress="return onlyNumberKey(event)" />
                                                <span class="text-muted font-italic">
                                                    <small>Please fill in US phone number if available</small>
                                                </span>
                                            </div>
                                            <hr />
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>                        
                        <br>
                        <div class="card shadow-lg">
                            <div class="card-body">
                                @if($is_roundtrip == 1)
                                <h5 class="text-center font-weight-bold mb-4">First Trip Detail</h5>
                                @else
                                <h5 class="text-center font-weight-bold mb-4">Order Detail</h5>
                                @endif
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        @if ($from_type == 'airport')
                                            <div class="form-group">
                                                <label for="special_area_id" class="form-text font-weight-bold">Private Drop
                                                    Off (Optional)</label>
                                                <select class="form-control" id="special_area_id" name="special_area_id">
                                                    <option value="">None</option>
                                                    @foreach ($special_areas as $s)
                                                        <option value="{{ $s->id }}"
                                                            data-first_person_price="{{ $s->first_person_price }}"
                                                            data-extra_person_price="{{ $s->extra_person_price }}">
                                                            {{ $s->regional_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-muted font-italic">
                                                    <small>
                                                        Additional $ 30 / $50 charges per address in Philadelphia city /
                                                        Philadelphia suburb for the 1st passenger (+ $ 10 per passenger for
                                                        the additional passenger)
                                                    </small>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="special_area_detail"
                                                    class="form-text font-weight-bold">Destination Address</label>
                                                <textarea class="form-control" id="special_area_detail" name="special_area_detail"
                                                    placeholder="Destination address including zip code"></textarea>
                                            </div>
                                        @endif

                                        @if ($booking_type == 'shuttle')
                                            <div class="form-group">
                                                <label for="luggage_qty" class="form-text font-weight-bold">Luggage
                                                    Qty</label>
                                                <div class="input-group">
                                                    <select class="form-control" id="luggage_qty" name="luggage_qty">
                                                        @for ($i = 0; $i <= 50; $i++)
                                                            <option value="{{ $i }}">{{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text bg-dark text-white">Pcs</span>
                                                    </div>
                                                </div>
                                                <span class="text-muted font-italic">
                                                    <small>
                                                        Free of charge luggage max 2 pieces and 1 piece hand carry bag.
                                                        Unusual and fragile luggage must be declared and confirmed.
                                                        It will be rejected if not declared and confirmed
                                                    </small>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="overweight_luggage_qty"
                                                    class="form-text font-weight-bold">Overweight/Oversized Luggage
                                                    Qty</label>
                                                <div class="input-group">
                                                    <select class="form-control" id="overweight_luggage_qty"
                                                        name="overweight_luggage_qty">
                                                        @for ($i = 0; $i <= 50; $i++)
                                                            <option value="{{ $i }}">{{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text bg-dark text-white">Pcs</span>
                                                    </div>
                                                    <span class="text-muted font-italic">
                                                        <small>
                                                            Boxes baggage will be considered as overweight/oversized.
                                                            Max Weight luggage 50 lbs each, max dimension: L+W+H = 62 inch
                                                            Hand carry bag max weight 15 lbs, max dimension 22"+14"+9"= 45"
                                                        </small>
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="flight_number" class="form-text font-weight-bold">Flight
                                                Number</label>
                                            <input type="text" class="form-control" id="flight_number"
                                                name="flight_number" placeholder="Flight Number" />
                                        </div>
                                        <div class="form-group">
                                            <label for="flight_info" class="form-text font-weight-bold">Flight
                                                Info</label>
                                            <textarea class="form-control" id="flight_info" name="flight_info"
                                                placeholder="Flight info ie: inform us your {{ $from_type == 'city' ? 'departure' : 'arrival' }} time or other info
                                                "></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="notes" class="form-text font-weight-bold">Notes</label>
                                            <textarea class="form-control" id="notes" name="notes"
                                                placeholder="Pick up time (for Charter Service).Put your other notes here "></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <br>
                        @if($is_roundtrip == 1)
                        <div class="card shadow-lg">
                            <div class="card-body">
                                <h5 class="text-center font-weight-bold mb-4">Second Trip Detail</h5>
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        @if ($return_from_type == 'airport')
                                            <div class="form-group">
                                                <label for="special_area_id" class="form-text font-weight-bold">Private Drop
                                                    Off (Optional)</label>
                                                <select class="form-control" id="special_area_id" name="special_area_id">
                                                    <option value="">None</option>
                                                    @foreach ($special_areas as $s)
                                                        <option value="{{ $s->id }}"
                                                            data-first_person_price="{{ $s->first_person_price }}"
                                                            data-extra_person_price="{{ $s->extra_person_price }}">
                                                            {{ $s->regional_name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-muted font-italic">
                                                    <small>
                                                        Additional $ 30 / $50 charges per address in Philadelphia city /
                                                        Philadelphia suburb for the 1st passenger (+ $ 10 per passenger for
                                                        the additional passenger)
                                                    </small>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="special_area_detail"
                                                    class="form-text font-weight-bold">Destination Address</label>
                                                <textarea class="form-control" id="special_area_detail" name="special_area_detail"
                                                    placeholder="Destination address including zip code"></textarea>
                                            </div>
                                        @endif

                                        @if ($booking_type == 'shuttle')
                                            <div class="form-group">
                                                <label for="luggage_qty_return" class="form-text font-weight-bold">Luggage
                                                    Qty</label>
                                                <div class="input-group">
                                                    <select class="form-control" id="luggage_qty_return" name="luggage_qty_return">
                                                        @for ($i = 0; $i <= 50; $i++)
                                                            <option value="{{ $i }}">{{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text bg-dark text-white">Pcs</span>
                                                    </div>
                                                </div>
                                                <span class="text-muted font-italic">
                                                    <small>
                                                        Free of charge luggage max 2 pieces and 1 piece hand carry bag.
                                                        Unusual and fragile luggage must be declared and confirmed.
                                                        It will be rejected if not declared and confirmed
                                                    </small>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="overweight_luggage_qty_return"
                                                    class="form-text font-weight-bold">Overweight/Oversized Luggage
                                                    Qty</label>
                                                <div class="input-group">
                                                    <select class="form-control" id="overweight_luggage_qty_return"
                                                        name="overweight_luggage_qty_return">
                                                        @for ($i = 0; $i <= 50; $i++)
                                                            <option value="{{ $i }}">{{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text bg-dark text-white">Pcs</span>
                                                    </div>
                                                    <span class="text-muted font-italic">
                                                        <small>
                                                            Boxes baggage will be considered as overweight/oversized.
                                                            Max Weight luggage 50 lbs each, max dimension: L+W+H = 62 inch
                                                            Hand carry bag max weight 15 lbs, max dimension 22"+14"+9"= 45"
                                                        </small>
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group">
                                            <label for="flight_number_return" class="form-text font-weight-bold">Flight
                                                Number</label>
                                            <input type="text" class="form-control" id="flight_number_return"
                                                name="flight_number_return" placeholder="Flight Number" />
                                        </div>
                                        <div class="form-group">
                                            <label for="flight_info" class="form-text font-weight-bold">Flight
                                                Info</label>
                                            <textarea class="form-control" id="flight_info_return" name="flight_info_return"
                                                placeholder="Flight info ie: inform us your {{ $return_from_type == 'city' ? 'departure' : 'arrival' }} time or other info
                                                "></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="notes" class="form-text font-weight-bold">Notes</label>
                                            <textarea class="form-control" id="notes" name="notes"
                                                placeholder="Pick up time (for Charter Service).Put your other notes here "></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endif
                    </div>
                    {{-- CARD IJO --}}
                    <div class="col-sm-12 col-md-4 mb-5">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h3>Booking Detail</h3>
                                <p class="text-capitalize">Booking Type: {{ $booking_type }}</p>
                                @if($is_roundtrip == 1)
                                <p>First From: {{ $from_main_name }} - {{ $from_sub_name }}</p>
                                <p>First To: {{ $to_main_name }} - {{ $to_sub_name }}</p>
                                <p>Second From: {{ $return_from_main_name }} - {{ $return_from_sub_name }}</p>
                                <p>Second To: {{ $return_to_main_name }} - {{ $return_to_sub_name }}</p>
                                @else
                                <p>From: {{ $from_main_name }} - {{ $from_sub_name }}</p>
                                <p>To: {{ $to_main_name }} - {{ $to_sub_name }}</p>                              
                                @endif
                                <p {!! $from_type == 'airport' && $return_from_type == 'airport' ? 'style="display:none"' : '' !!}>Private Drop Off :
                                    <span class="special_area_name">None</span>
                                </p>
                                <p>Date: {{ $date_time_departure }}</p>
                                <p {!! $booking_type == 'shuttle' ? '' : 'style="display:none;"' !!}>Passenger:
                                    {{ $passenger_adult }}
                                    Adult {{ $passenger_baby }} Child</p>
                                @if ($booking_type == 'shuttle')
                                    <p>Luggage: <span class="luggage_qty">0</span> Pcs</p>
                                    <p>Overweight or Oversized Luggage: <span class="overweight_luggage_qty">0</span>
                                        Pcs
                                    </p>
                                @endif
                                <hr />
                                <table class="table table-borderless text-white">
                                    <tbody>
                                        <tr>
                                            <td>
                                                Base Price:<br />
                                                @if ($booking_type == 'shuttle')
                                                    {{ $passenger_total }} Passenger(s)
                                                @endif
                                                <input type="hidden" name="passenger_total"
                                                    value="{{ $passenger_total }}" />
                                            </td>
                                            <td class="text-right">
                                                ${{ $base_price_total }}
                                                <input type="hidden" name="base_price_total"
                                                    value="{{ $base_price_total }}" />
                                            </td>
                                        </tr>
                                        @if ($booking_type == 'shuttle')
                                            <tr>
                                                <td>
                                                    Special request drop off Price:<br />
                                                    <span class="special_area_name">-</span>
                                                </td>
                                                <td class="text-right">
                                                    <span id="special_area_price">$0</span>
                                                    <input type="hidden" name="special_area_price" value="0" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Extra Luggage Price:<br />
                                                    <span class="extra_luggage_qty">0</span> piece(s)
                                                </td>
                                                <td class="text-right">
                                                    <span id="luggage_price">$0</span>
                                                    <input type="hidden" name="luggage_base_price"
                                                        value="{{ $luggage_price }}" />
                                                    <input type="hidden" name="luggage_price" value="0" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Overweight or Oversized Luggage Price:<br />
                                                    <span class="overweight_luggage_qty">0</span> piece(s)
                                                </td>
                                                <td class="text-right">
                                                    <span id="overweight_luggage_price">$0</span>
                                                    <input type="hidden" name="overweight_luggage_price"
                                                        value="0" />
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td>
                                                Voucher:<br />
                                                <input type="text" class="form-control" id="voucher" name="voucher"
                                                    placeholder="Voucher" />
                                            </td>
                                            <td class="text-right text-warning font-weight-bold">
                                                <span id="voucher_price">$0</span>
                                                <input type="hidden" name="voucher_price" value="0" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <hr />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Sub Total:
                                            </td>
                                            <td class="text-right">
                                                <span id="sub_total">${{ $base_price_total }}</span>
                                                <input type="hidden" name="sub_total"
                                                    value="{{ $base_price_total }}" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Payment Fee ({{ env('PAJAK') }}%):
                                            </td>
                                            <td class="text-right">
                                                @php
                                                    $service_fee = ($base_price_total * $pajak) / 100;
                                                    $gt = $base_price_total + $service_fee;
                                                @endphp
                                                <span
                                                    id="service_fee">${{ number_format($service_fee ? $service_fee : 0, 2) }}</span>
                                                <input type="hidden" name="service_fee" value="{{ $service_fee }}" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Grand Total:
                                            </td>
                                            <td class="text-right">
                                                <span id="grand_total">${{ number_format($gt, 2) }}</span>
                                                <input type="hidden" name="grand_total" value="{{ $gt }}" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <input type="hidden" id="is_roundtrip" name="is_roundtrip"
                                    value="{{ $is_roundtrip }}" />
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block" id="btn_save">
                                        <i class="fas fa-save fa-fw"></i> Booking
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
@section('vitamin')
    <script src="/intl-tel-input-master/js/intlTelInput.js"></script>
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");

        let option_tel = {
            customContainer: "form-control",
            allowDropdown: true,
            autoHideDialCode: false,
            autoPlaceholder: "on",
            // dropdownContainer: document.body,
            // excludeCountries: ["us"],
            formatOnDisplay: true,
            // geoIpLookup: function(callback) {
            //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
            //     var countryCode = (resp && resp.country) ? resp.country : "";
            //     callback(countryCode);
            //   });
            // },
            // hiddenInput: "full_number",
            initialCountry: "US",
            // localizedCountries: { 'de': 'Deutschland' },
            nationalMode: true,
            // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
            // placeholderNumberType: "MOBILE",
            // preferredCountries: ['cn', 'jp'],
            separateDialCode: true,
            utilsScript: "/intl-tel-input-master/js/utils.js",
        }

        let subTotal = {{ $base_price_total }}
        let
            pajak = {{ $pajak }}
        let
            serviceFee = {{ $service_fee }}
        let
            grandTotal = {{ $gt }}

        let
            xxx = []

        let passenger_phone = document.querySelectorAll('input[type="tel"]');
        for (i = 0; i < passenger_phone.length; i++) {
            xxx[i] = window.intlTelInput(passenger_phone[i], option_tel);
        }

        $(document).ready(() => {

            $('#same_passenger').on('change', () => {
                if ($('#same_passenger:checked').val()) {
                    $('#passenger_name_1').attr('readonly', true).val($('#customer_name').val()).addClass(
                        'bg-secondary text-white')
                    $('#passenger_phone_1').attr('readonly', true).val($('#customer_phone').val()).addClass(
                        'bg-secondary text-white')
                } else {
                    $('#passenger_name_1').attr('readonly', false).val('').removeClass(
                        'bg-secondary text-white')
                    $('#passenger_phone_1').attr('readonly', false).val('').removeClass(
                        'bg-secondary text-white')
                }
            })

            $('#customer_phone').on('keyup', e => {
                if ($('#same_passenger:checked').val()) {
                    $('#passenger_phone_1').val($('#customer_phone').val())
                }
            })

            $('#customer_name').on('keyup', e => {
                if ($('#same_passenger:checked').val()) {
                    $('#passenger_name_1').val($('#customer_name').val())
                }
            })

            $('#special_area_id').on('change', e => {
                let special_area_id = $('#special_area_id :selected').val()
                let special_area_name = $('#special_area_id :selected').text()
                let first_person_price = $('#special_area_id :selected').data('first_person_price')
                let extra_person_price = $('#special_area_id :selected').data('extra_person_price')
                let passenger_total = $('input[name="passenger_total"]').val()
                let passenger_first = 1
                let passenger_extra = passenger_total - 1;
                if (passenger_extra < 0) {
                    passenger_extra = 0
                }
                let a = passenger_first * first_person_price
                let b = passenger_extra * extra_person_price
                let c = a + b
                let cFormated = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD'
                }).format(c)

                if (special_area_id) {
                    $('.special_area_name').text(special_area_name)
                    $('#special_area_price').text(cFormated)
                    $('input[name="special_area_price"]').val(c)
                    generateGrandTotal()
                } else {
                    $('.special_area_name').text('-')
                    $('#special_area_price').text('$0')
                    $('input[name="special_area_price"]').val(0)
                    generateGrandTotal()
                }
            })

            $('#luggage_qty').on('change', e => {
                let luggage_qty = parseFloat($('#luggage_qty').val())
                let luggage_base_price = $('input[name="luggage_base_price"]').val()
                let passenger_total = $('input[name="passenger_total"]').val()
                let luggage_base_price = 20

                let extra_luggage_qty = 0
                let lp = 0
                // lp = extra_luggage_qty *luggage_base_price

                if (luggage_qty > (2 * passenger_total)) {
                    lp = (luggage_qty - (2 * passenger_total)) * luggage_base_price
                    extra_luggage_qty = luggage_qty - (2 * passenger_total)
                }

                let lpFormated = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD'
                }).format(lp)
                $('.extra_luggage_qty').text(extra_luggage_qty)
                $('.luggage_qty').text(luggage_qty)
                $('input[name="luggage_price"]').val(lp)
                $('#luggage_price').text(lpFormated)
                generateGrandTotal()
            })

            $('#overweight_luggage_qty').on('change', e => {
                let overweight_luggage_qty = parseFloat($('#overweight_luggage_qty').val())
                let overweight_luggage_base_price = 10

                let olp = 0

                olp = overweight_luggage_qty * overweight_luggage_base_price

                let olpFormated = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD'
                }).format(olp)
                $('.overweight_luggage_qty').text(overweight_luggage_qty)
                $('input[name="overweight_luggage_price"]').val(olp)
                $('#overweight_luggage_price').text(olpFormated)
                generateGrandTotal()
            })

            $('#luggage_qty_return').on('change', e => {
                let luggage_qty = parseFloat($('#luggage_qty_return').val())
                let luggage_base_price = $('input[name="luggage_base_price_return"]').val()
                let passenger_total = $('input[name="passenger_total"]').val()
                let luggage_base_price = 20

                let extra_luggage_qty = 0
                let lp = 0
                // lp = extra_luggage_qty *luggage_base_price

                if (luggage_qty > (2 * passenger_total)) {
                    lp = (luggage_qty - (2 * passenger_total)) * luggage_base_price
                    extra_luggage_qty = luggage_qty - (2 * passenger_total)
                }

                let lpFormated = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD'
                }).format(lp)
                $('.extra_luggage_qty_return').text(extra_luggage_qty)
                $('.luggage_qty_return').text(luggage_qty)
                $('input[name="luggage_price"]').add(lp)
                $('#luggage_price').text(lpFormated)
                generateGrandTotal()
            })

            $('#overweight_luggage_qty_return').on('change', e => {
                let overweight_luggage_qty = parseFloat($('#overweight_luggage_qty_return').val())
                let overweight_luggage_base_price = 10

                let olp = 0

                olp = overweight_luggage_qty * overweight_luggage_base_price

                let olpFormated = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD'
                }).format(olp)
                $('.overweight_luggage_qty_return').text(overweight_luggage_qty)
                $('input[name="overweight_luggage_price_return"]').val(olp)
                $('#overweight_luggage_price_return').text(olpFormated)
                generateGrandTotal()
            })

            $('#voucher').on('change', e => {
                generateGrandTotal()
            })

            $('#agent_password').on('change', e => {
                let voucher_code = $('#voucher').val()
                let agent_password = $('#agent_password').val()
                if (voucher_code.length > 0 && agent_password.length > 0) {
                    checkVoucher(voucher_code, agent_password)
                } else {
                    $('#voucher_price').text('$0')
                    $('input[name="voucher_price"]').val(0)
                    generateGrandTotal()
                }
            })

            $('#form_booking').on('submit', e => {
                e.preventDefault()

                //jika customr name kosong
                let custName = $('#customer_name').val();

                if (custName == '') {

                    $("#customer_name").focus();

                    Swal.fire({
                        position: 'top-end',
                        icon: 'warning',
                        title: `Please fill in the Customer Name column`,
                        showConfirmButton: false,
                        toast: true,
                        timer: 3000,
                    });

                    throw new Error();

                }

                let arrPassName = $("input[name='passenger_name[]']").map(function() {
                    var val = $(this).val();
                    var label = $(this).data("label")

                    if (val == '') {

                        $(this).focus();

                        Swal.fire({
                            position: 'top-end',
                            icon: 'warning',
                            title: `Please fill in the ${label} column`,
                            showConfirmButton: false,
                            toast: true,
                            timer: 3000,
                        });

                        throw new Error();

                    }

                    return $(this).val();
                }).get()

                bookingNow()
            })
        })

        function generateGrandTotal() {
            let voucher_code = $('#voucher').val()
            if (voucher_code.length > 0) {
                checkVoucher(voucher_code).fail(e => {
                    console.log(e.responseText)
                }).done(e => {
                    console.log(e)
                    if (e.success === false) {
                        $('#voucher_price').text('$0')
                        $('input[name="voucher_price"]').val(0)
                        $('input[name="voucher"]').val('')

                        Swal.fire({
                            position: 'top-end',
                            icon: 'warning',
                            title: e.message,
                            showConfirmButton: false,
                            toast: true,
                            timer: 3000,
                        })

                        // generateGrandTotalFinal()
                    } else {
                        let discount_type = e.data.discount_type
                        let discount_value = parseFloat(e.data.discount_value)

                        let nilaiDiskon = 0
                        if (discount_type == "percentage") {
                            nilaiDiskon = (subTotal * discount_value) / 100
                        } else if (discount_type == "value") {
                            nilaiDiskon = discount_value
                        }

                        let dcFormated = new Intl.NumberFormat('en-US', {
                            style: 'currency',
                            currency: 'USD'
                        }).format(nilaiDiskon)

                        $('#voucher_price').text(dcFormated)
                        $('input[name="voucher_price"]').val(nilaiDiskon)
                    }
                    generateGrandTotalFinal()
                })
            } else {
                $('#voucher_price').text('$0')
                $('input[name="voucher_price"]').val(0)
                generateGrandTotalFinal()
            }
        }

        function generateGrandTotalFinal() {
            let base_price_total = parseFloat($('input[name="base_price_total"]').val())
            let special_area_price = parseFloat($('input[name="special_area_price"]').val())
            let luggage_price = parseFloat($('input[name="luggage_price"]').val())
            let overweight_luggage_price = parseFloat($('input[name="overweight_luggage_price"]').val())
            let voucher_price = parseFloat($('input[name="voucher_price"]').val())

            // handle NaN value for charter
            special_area_price = special_area_price || 0
            luggage_price = luggage_price || 0
            overweight_luggage_price = overweight_luggage_price || 0

            // console.log(base_price_total)
            // console.log(special_area_price)
            // console.log(luggage_price)
            // console.log(overweight_luggage_price)
            // console.log(voucher_price)

            let st = base_price_total + special_area_price + luggage_price + overweight_luggage_price - voucher_price
            let stFormated = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(st)
            subTotal = st
            $('#sub_total').text(stFormated)
            $('input[name="sub_total"]').val(subTotal)

            let sf = ((subTotal * pajak) / 100)
            let sfFormated = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(sf)
            serviceFee = sf
            $('#service_fee').text(sfFormated)
            $('input[name="service_fee"]').val(serviceFee)

            let gt = st + sf
            let gtFormated = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(gt)
            grandTotal = gt
            $('#grand_total').text(gtFormated)
            $('input[name="grand_total"]').val(grandTotal)
        }

        function checkVoucher(voucher_code) {
            return $.ajax({
                url: '/api/check_voucher',
                method: 'get',
                dataType: 'json',
                data: {
                    voucher_code: voucher_code,
                }
            })
        }

        function bookingNow() {
            let arrpassengerName = $("input[name='passenger_name[]']").map(function() {
                return $(this).val();
            }).get()

            let arrpassengerPhone = [];
            xxx.forEach(function(i, k) {
                // if (xxx[k].isValidNumber()) {
                if (xxx[k]) {
                    if (xxx[k] != 0) {
                        arrpassengerPhone.push(xxx[k].getNumber())
                    }
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'warning',
                        title: `${xxx[k].getNumber()} Invalid Phone Number`,
                        showConfirmButton: false,
                        toast: true,
                        timer: 3000,
                    });
                    throw new Error(`${xxx[k].getNumber()} Invalid Phone Number`);
                }
            })

            let arrpassenger = arrpassengerName.map((v, i) => {
                return {
                    name: v,
                    phone: arrpassengerPhone[i],
                }
            })

            // validasi
            $.ajax({
                url: '/api/booking',
                method: 'post',
                dataType: 'json',
                data: {
                    schedule_type: `{{ session('booking_type') }}`,
                    //from_type: `{{ session('from_type') }}`,
                    schedule_id: `{{ session('schedule_id') }}`,
                    date_departure: `{{ session('date_departure') }}`,
                    from_master_area_id: `{{ session('from_master_area_id') }}`,
                    from_master_sub_area_id: `{{ session('from_master_sub_area_id') }}`,
                    to_master_area_id: `{{ session('to_master_area_id') }}`,
                    to_master_sub_area_id: `{{ session('to_master_sub_area_id') }}`,
                    qty_adult: `{{ session('passenger_adult') }}`,
                    qty_baby: `{{ session('passenger_baby') }}`,
                    special_request: ($('#special_area_id').val()) ? 1 : 0,
                    special_area_id: $('#special_area_id').val(),
                    special_area_detail: $('#special_area_detail').val(),
                    luggage_qty: $('#luggage_qty').length === 0 ? 0 : $('#luggage_qty').val(),
                    overweight_luggage_qty: $('#overweight_luggage_qty').length === 0 ? 0 : $(
                        '#overweight_luggage_qty').val(),
                    flight_number: $('#flight_number').val(),
                    flight_info: $('#flight_info').val(),
                    notes: $('#notes').val(),
                    voucher_code: $('#voucher').val(),
                    customer_phone: arrpassengerPhone[0],
                    customer_password: $('#customer_password').val(),
                    customer_name: $('#customer_name').val(),
                    customer_email: $('#customer_email').val(),
                    is_roundtrip: $('#is_roundtrip').val(),
                    passenger: arrpassenger
                },
                beforeSend: function() {
                    $.blockUI()
                    $('#btn_save').prop('disabled', true)
                }
            }).fail(e => {
                $.unblockUI()
                $('#btn_save').prop('disabled', false)
                console.log(e.responseText)
            }).done(e => {
                if (e.success === false) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'warning',
                        title: e.message,
                        showConfirmButton: false,
                        toast: true,
                        timer: 3000,
                    });
                    $('#btn_save').prop('disabled', false)
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: e.message,
                        showConfirmButton: false,
                        toast: true,
                        timer: 3000,
                    })
                }
                $.unblockUI()
            })
        }

        function showPassword(target_input, target_icon) {
            if ($(`${target_input}`).prop('type') == "password") {
                $(`${target_input}`).prop('type', 'text')
                $(`${target_icon}`).removeClass('fa-eye').addClass('fa-eye-slash')
            } else {
                $(`${target_input}`).prop('type', 'password')
                $(`${target_icon}`).removeClass('fa-eye-slash').addClass('fa-eye')
            }
        }

        function onlyNumberKey(evt) {
            // Only ASCII character in that range allowed
            var ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true;
        }
    </script>
@endsection
