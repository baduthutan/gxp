@extends('layouts.frontend.app')
@section('page_content')
    <section id="home" class="home d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 my-3 text-center">
                    <h1 class="text-center mt-5">Booking Number - {{ $bookings->booking_number }}</h1>
                    <span>
                    <small>
                        Please save and keep this number for <b>"Check Booking"</b> ID
                    </small>
                        </span>
                </div>

                <div class="col-12 mb-5">
                    <div class="card bg-light shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-8">
                                    @if (session('message'))
                                        {!! session('message') !!}
                                    @endif
                                    <h3>Departure Detail</h3>
                                    <p>From: {{ $bookings->from_master_area_name }}
                                        {{ $bookings->from_master_sub_area_name }}
                                    </p>
                                    <p>
                                        To: {{ $bookings->to_master_area_name }} {{ $bookings->to_master_sub_area_name }}
                                    </p>
                                    <p>Special request drop off option: {{ $bookings->regional_name ?? '-' }}</p>
                                    <p>Special Detail: {!! $bookings->special_area_detail ?? '-' !!}</p>
                                    <p>Date: {{ $bookings->schedule_type == 'shuttle' ? $bookings->datetime_departure
                                     : \Carbon\Carbon::parse($bookings->datetime_departure)->format('Y-m-d') }}</p>
                                    <p>Passenger: {{ $bookings->qty_adult }} Adult {{ $bookings->qty_baby }} Child
                                    </p>
                                    <p>Luggage: {{ $bookings->luggage_qty ?? '0' }} Pcs</p>
                                    <p>Overweight/Oversized Luggage: {{ $bookings->overweight_luggage_qty ?? '0' }}
                                        Pcs</p>
                                    <p>Flight Number: {{ $bookings->flight_number }}</p>
                                    <p>Flight Info: {{ $bookings->flight_info }}</p>
                                    <p>Notes: {{ $bookings->notes }}</p>
                                    <hr/>
                                    {{-- <p class="text-capitalize">Booking Status: {{ $bookings->booking_status }}</p> --}}
                                    <p class="text-capitalize">Booking Status: {{ $bookings->payment_status == 'paid' ? "Confirmed" : $bookings->payment_status  }}
{{--                                        {{--}}
{{--                                    $bookings->payment_status == 'paid' && ($bookings->payment_method == 'bank' ||--}}
{{--                                    $bookings->payment_method == 'venmo') ? ", subject to confirmation." : ''--}}
{{--                                    }}--}}
                                    </p>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <h3>Price Detail</h3>
                                    <table class="table table-bordered bg-dark text-white">
                                        <tbody>
                                        <tr>
                                            <td>
                                                Base Price:<br/>
                                                @php
                                                    $passanger_total = $bookings->qty_adult + $bookings->qty_baby;
                                                @endphp
                                                @if ($bookings->booking_type == 'shuttle')
                                                {{ $passanger_total }} Passenger(s)
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                ${{ $bookings->total_base_price }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Special request drop off Price:<br/>
                                                {{ $bookings->regional_name }}
                                            </td>
                                            <td class="text-right">
                                                ${{ $bookings->extra_price }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Extra Luggage Price:<br/>
                                                @php
                                                $passanger_total = $bookings->qty_adult + $bookings->qty_baby;
                                                $extra_luggage = $bookings->luggage_qty - ($passanger_total*2);
                                                if ($extra_luggage < 0) $extra_luggage = 0;
                                                @endphp
                                                {{ $extra_luggage }} piece(s)
                                            </td>
                                            <td class="text-right">
                                                ${{ $bookings->luggage_price ?? '0' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Overweight or Oversized Luggage Price:<br/>
                                                {{ $bookings->overweight_luggage_qty ?? '0' }} piece(s)
                                            </td>
                                            <td class="text-right">
                                                ${{ $bookings->overweight_luggage_price ?? '0' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Voucher:<br/>
                                                {{ $vouchers->code ?? '' }} {{-- adding condition when user doesn't input
                                            voucher when creating booking --}}
                                            </td>
                                            <td class="text-right text-warning font-weight-bold">
                                                ${{ $bookings->promo_price }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Sub Total:
                                            </td>
                                            <td class="text-right">
                                                ${{ $bookings->sub_total_price }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Payment Fee ({{ env('PAJAK') }}%):
                                            </td>
                                            <td class="text-right">
                                                ${{ $bookings->fee_price }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">
                                                Grand Total:
                                            </td>
                                            <td class="text-right font-weight-bold">
                                                ${{ $bookings->total_price }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-9 py-auto">
                                    <p class="small font-italic">Created
                                        at: {{ $bookings->created_at }}</p>

                                        <p class="font-italic font-weight-bold {!! $bookings->payment_status == 'paid' ?
                                        'd-none' :
                                         ''
                                        !!}"
                                           id="countdown-note" style="font-size:15px"></p>
                                        <p class="font-italic font-weight-bold {!! $bookings->payment_status == 'paid' ?
                                        'd-none' :
                                         ''
                                        !!}" id="countdown" style="font-size:15px"></p>

                                </div>
                                <div class="col-sm-3">
                                    <div class="d-flex justify-content-center align-items-center flex-column h-100"
                                         style="max-height: max-content;">
                                        @if ($bookings->payment_status == 'waiting')

                                        <img src="img/cc_list.png" class="mb-1" />
                                            <a href="{{ route('booking.payment', ['hcode' => $hashed_code]) }}"
                                               class="btn btn-sm btn-block btn-primary mb-1" id="btn_process">Credit
                                                Card Payment <i class="fas fa-credit-card"></i> </a>

                                            <a href="" class="btn btn-sm btn-block btn-info mb-1" data-toggle="modal"
                                               data-target="#venmoModal">
                                                Venmo Payment <i class="fas fa-qrcode"></i>
                                            </a>

                                            <a href="" class="btn btn-sm btn-block btn-warning mb-1" data-toggle="modal"
                                               data-target="#bankModal">
                                                Transfer to Bank <i class="fas fa-university"></i>
                                            </a>
                                        @endif
                                        @if ($bookings->payment_status == 'paid')
                                        <span class="text-muted font-italic mb-4">
                                                    <small class="font-weight-bolder">
                                                        Note: Please save this page. (There is a save option in your browser menu)
                                                    </small>
                                            <br/>
                                            </span>
                                            <a href="{{ route('booking_ticket', ['code' => $bookings->booking_number]) }}"
                                               class="btn btn-sm btn-block btn-info mb-1 font-weight-bolder" target="_blank">
                                                Download E - Ticket <i class="fas fa-ticket-alt"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal -->
    <div class="modal fade" id="venmoModal" tabindex="-1" role="dialog" aria-labelledby="venmoModalTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Venmo Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column justify-content-center align-items-center">
                            <span class="text-info">*After payment using venmo, please contact admin to verify your venmo
                                payment.</span>
                        <h4>Aprilia susanti</h4>
                        <span>@greenexpress</span>
                        <img src="img/sample-venmo.png" width="150px" alt="venmo" class="mt-2">
                        @if ($bookings->payment_status == 'paid')
                            <a class="btn btn-success"
                               href="https://wa.me/+12152718381?text={{ urlencode("Please confirm if Venmo payment
                               has been accepted. Your Booking number : {$bookings->booking_number}") }}"
                               target="_blank">Please inform if Venmo payment has been made whatsapp Message <i
                                    class='fab fa-whatsapp'></i></a>
                        @endif
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center align-items-center">
                    <div class="">
                        @if ($bookings->payment_status == 'waiting')
                            <form action="{{ route('booking.payment_venmo') }}" method="POST">
                                @csrf
                                <input type="hidden" name="hcode" value="{{ $hashed_code }}">
                                <button type="submit" class="btn btn-primary">Complete payment <br>and inform admin
                                                                                                    whatsapp Message <i
                                        class="fab fa-whatsapp"></i></button>
                            </form>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="bankModal" tabindex="-1" role="dialog" aria-labelledby="bankModalTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Transfer to bank Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column justify-content-center align-items-center">
                        <span class="text-info">*Please contact admin to pay your booking via bank transfer*.</span>
                        <!--                        <h4>Aprilia susanti</h4>-->
                        <!--                        <span>@greenexpress</span>-->
                        <br/>
                        <span class="mb-2">Available Bank</span>
                        <!--                        <h4 class="mb-0 text-primary">BCA</h4>-->
                        <img src="img/bca.png" width="150px" alt="venmo" class="mt-0">

                        <!--                        <h4 class="mb-0 text-success">CITIZEN BANK</h4>-->
                        <img src="img/citizen.png" width="150px" alt="venmo" class="mt-0 mb-1">

                        @if ($bookings->payment_status == 'paid')
                            <a class="btn btn-success"
                               href="https://wa.me/+12152718381?text={{urlencode("Please confirm if Bank Transfer
                               payment has been accepted. Your Booking number : {$bookings->booking_number}")}}"
                               target="_blank">Please inform if Bank Transfer payment has been made whatsapp Message <i
                                    class='fab fa-whatsapp'></i></a>
                        @endif
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center align-items-center">
                    <div class="">
                        @if ($bookings->payment_status == 'waiting')
                            <form action="{{ route('booking.payment_bank') }}" method="POST">
                                @csrf
                                <input type="hidden" name="hcode" value="{{ $hashed_code }}">
                                <button type="submit" class="btn btn-primary">Complete payment <br>and inform admin
                                                                                                    whatsapp Message <i
                                        class="fab fa-whatsapp"></i></button>
                            </form>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script type="text/javascript">
        function countdown(elementName, minutes, seconds) {
            var element, endTime, hours, mins, msLeft, time, created_at;

            function twoDigits(n) {
                return (n <= 9 ? "0" + n : n);
            }

            function updateTimer() {
                msLeft = endTime - (+new Date);
                if (msLeft < 1000) {
                    element.innerHTML = "Payment process exceed the time limit, 10 minutes after booking completion. Please repeat the booking process";
                    element.style.color = "red";
                    document.getElementById("btn_process").href = "javascript:void(0)";
                    document.getElementById("btn_process").classList.add("disabled");
                    document.getElementById("countdown-note").innerHTML = "";

                } else {
                    time = new Date(msLeft);
                    hours = time.getUTCHours();
                    mins = time.getUTCMinutes();
                    element.innerHTML = (hours ? hours + ':' + twoDigits(mins) : mins) + ':' + twoDigits(time
                        .getUTCSeconds());
                    element.style.color = "black";
                    document.getElementById("btn_process").href = "<?php echo route('booking.payment', ['hcode' => $hashed_code]); ?>";
                    document.getElementById("btn_process").classList.remove("disabled");
                    setTimeout(updateTimer, time.getUTCMilliseconds() + 500);
                    document.getElementById("countdown-note").innerHTML = "Please complete the payment within 10 minutes after the booking process accepted";
                }
            }

            element = document.getElementById(elementName);
            created_at = new Date("<?php echo $bookings->created_at; ?>").getTime();
            endTime = (created_at) + 1000 * (60 * minutes + seconds) + 500;
            updateTimer();
            // location.reload();
        }

        countdown("countdown", 10, 0);
    </script>
@endsection
@section('vitamin')
@endsection
