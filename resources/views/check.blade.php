@extends('layouts.frontend.app')
@section('page_content')
    <section id="home" class="home d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 my-3 text-center">
                    <h1 class="text-center mt-5">Booking Number - {{ $booking->booking_number }}</h1>
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
                                    <p>From: {{ $booking->from_master_area_name }}
                                        {{ $booking->from_master_sub_area_name }}
                                    </p>
                                    <p>
                                        To: {{ $booking->to_master_area_name }} {{ $booking->to_master_sub_area_name }}
                                    </p>
                                    <p>Special request drop off option: {{ $booking->regional_name ?? '-' }}</p>
                                    <p>Special Detail: {!! $booking->special_area_detail ?? '-' !!}</p>
                                    <p>Date: {{ $booking->schedule_type == 'shuttle' ? $booking->datetime_departure
                                     : \Carbon\Carbon::parse($booking->datetime_departure)->format('Y-m-d') }}</p>
                                    <p>Passenger: {{ $booking->qty_adult }} Adult {{ $booking->qty_baby }} Child
                                    </p>
                                    <p>Luggage: {{ $booking->luggage_qty ?? '0' }} Pcs</p>
                                    <p>Overweight/Oversized Luggage: {{ $booking->overweight_luggage_qty ?? '0' }}
                                        Pcs</p>
                                    <p>Flight Number: {{ $booking->flight_number }}</p>
                                    <p>Flight Info: {{ $booking->flight_info }}</p>
                                    <p>Notes: {{ $booking->notes }}</p>
                                    @if($return_booking != null)
                                    <h3>Return Detail</h3>
                                    <p>From: {{ $return_booking->from_master_area_name }}
                                        {{ $return_booking->from_master_sub_area_name }}
                                    </p>
                                    <p>
                                        To: {{ $return_booking->to_master_area_name }} {{ $return_booking->to_master_sub_area_name }}
                                    </p>
                                    <p>Special request drop off option: {{ $return_booking->regional_name ?? '-' }}</p>
                                    <p>Special Detail: {!! $return_booking->special_area_detail ?? '-' !!}</p>
                                    <p>Date: {{ $return_booking->schedule_type == 'shuttle' ? $return_booking->datetime_departure
                                     : \Carbon\Carbon::parse($return_booking->datetime_departure)->format('Y-m-d') }}</p>
                                    <p>Passenger: {{ $return_booking->qty_adult }} Adult {{ $return_booking->qty_baby }} Child
                                    </p>
                                    <p>Luggage: {{ $return_booking->luggage_qty ?? '0' }} Pcs</p>
                                    <p>Overweight/Oversized Luggage: {{ $return_booking->overweight_luggage_qty ?? '0' }}
                                        Pcs</p>
                                    <p>Flight Number: {{ $return_booking->flight_number }}</p>
                                    <p>Flight Info: {{ $return_booking->flight_info }}</p>
                                    <p>Notes: {{ $return_booking->notes }}</p>
                                    @endif
                                    <hr/>
                                    <p class="text-capitalize">Booking Status: {{ $booking->payment_status == 'paid' ? "Confirmed" : $booking->payment_status  }}
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
                                                    $passenger_total = $booking->qty_adult + $booking->qty_baby;
                                                @endphp
                                                @if ($booking->booking_type == 'shuttle')
                                                {{ $passenger_total }} Passenger(s)
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if($return_booking != null)
                                                    ${{ $booking->total_base_price + $return_booking->total_base_price }}
                                                    @else
                                                    ${{ $booking->total_base_price }}
                                                @endif
                                            

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Special request drop off Price:<br/>

                                                @if($return_booking->regional_name != null)
                                                    {{ $return_booking->regional_name }}
                                                    @else
                                                    {{ $booking->regional_name }}
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if($return_booking->extra_price != 0)
                                                    ${{ $return_booking->extra_price }}
                                                @else
                                                    ${{ $booking->extra_price }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Extra Luggage Price:<br/>
                                                @php
                                                $passenger_total = $booking->qty_adult + $booking->qty_baby;
                                                $extra_luggage = $booking->luggage_qty - ($passenger_total*2);
                                                if($return_booking != null) $extra_luggage = ($booking->luggage_qty + $return_booking->luggage_qty) - $passenger_total*4;
                                                if ($extra_luggage < 0) $extra_luggage = 0;
                                                @endphp
                                                {{ $extra_luggage }} piece(s)
                                            </td>
                                            <td class="text-right">
            
                                                @if($return_booking != null)
                                                ${{ $booking->luggage_price + $return_booking->luggage_price ?? '0' }}
                                                @else
                                                ${{ $booking->luggage_price ?? '0' }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Overweight or Oversized Luggage Price:<br/>
                                                @if($return_booking != null)
                                                {{ $booking->overweight_luggage_qty + $return_booking->overweight_luggage_qty ?? '0' }} piece(s)
                                                @else
                                                {{ $booking->overweight_luggage_qty ?? '0' }} piece(s)
                                                @endif
                                            
                                            </td>
                                            <td class="text-right">
                                                @if($return_booking != null)
                                                ${{ $booking->overweight_luggage_price + $return_booking->overweight_luggage_price ?? '0' }}
                                                @else
                                                ${{ $booking->overweight_luggage_price ?? '0' }}
                                                @endif
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Voucher:<br/>
                                                {{ $voucher->code ?? '' }} {{-- adding condition when user doesn't input
                                            voucher when creating booking --}}
                                            </td>
                                            <td class="text-right text-warning font-weight-bold">
                                                @if($return_booking != null)
                                                ${{ $booking->promo_price + $return_booking->promo_price }}
                                                @else
                                                ${{ $booking->promo_price }}
                                                @endif
                                                
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
                                                @if($return_booking != null)
                                                ${{ $booking->sub_total_price + $return_booking->sub_total_price }}
                                                @else
                                                ${{ $booking->sub_total_price }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Payment Fee ({{ env('PAJAK') }}%):
                                            </td>
                                            <td class="text-right">
                                                @if($return_booking != null)
                                                ${{ $booking->fee_price + $return_booking->fee_price }}
                                                @else
                                                ${{ $booking->fee_price }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">
                                                Grand Total:
                                            </td>
                                            <td class="text-right font-weight-bold">
                                                @if($return_booking != null)
                                                ${{ $booking->total_price + $return_booking->total_price }}
                                                @else
                                                ${{ $booking->otal_price }}
                                                @endif
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
                                    <p class="small font-italic">Created at: 
                                        <span class="js-local-datetime" 
                                              data-utc="{{ $booking->created_at->toIso8601String() }}" 
                                              data-format="datetime"></span>
                                    </p>

                                        <p class="font-italic font-weight-bold {!! $booking->payment_status == 'paid' ?
                                        'd-none' :
                                         ''
                                        !!}"
                                           id="countdown-note" style="font-size:15px"></p>
                                        <p class="font-italic font-weight-bold {!! $booking->payment_status == 'paid' ?
                                        'd-none' :
                                         ''
                                        !!}" id="countdown" style="font-size:15px"></p>

                                </div>
                                <div class="col-sm-3">
                                    <div class="d-flex justify-content-center align-items-center flex-column h-100"
                                         style="max-height: max-content;">
                                        @if ($booking->payment_status == 'waiting')

                                        <img src="/img/cc_list.png" class="mb-1" />
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
                                        @if ($booking->payment_status == 'paid')
                                        <span class="text-muted font-italic mb-4">
                                                    <small class="font-weight-bolder">
                                                        Note: Please save this page. (There is a save option in your browser menu)
                                                    </small>
                                            <br/>
                                            </span>
                                            <a href="{{ route('booking_ticket', ['code' => $booking->booking_number]) }}"
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
                        <img src="/img/sample-venmo.png" width="150px" alt="venmo" class="mt-2">
                        @if ($booking->payment_status == 'paid')
                            <a class="btn btn-success"
                               href="https://wa.me/+12152718381?text={{ urlencode("Please confirm if Venmo payment
                               has been accepted. Your Booking number : {$booking->booking_number}") }}"
                               target="_blank">Please inform if Venmo payment has been made via whatsapp Message <i
                                    class='fab fa-whatsapp'></i></a>
                        @endif
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center align-items-center">
                    <div class="">
                        @if ($booking->payment_status == 'waiting')
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
                        <img src="/img/bca.png" width="150px" alt="venmo" class="mt-0">

                        <!--                        <h4 class="mb-0 text-success">CITIZEN BANK</h4>-->
                        <img src="/img/citizen.png" width="150px" alt="venmo" class="mt-0 mb-1">

                        @if ($booking->payment_status == 'paid')
                            <a class="btn btn-success"
                               href="https://wa.me/+12152718381?text={{urlencode("Please confirm if Bank Transfer
                               payment has been accepted. Your Booking number : {$booking->booking_number}")}}"
                               target="_blank">Please inform if Bank Transfer payment has been made whatsapp Message <i
                                    class='fab fa-whatsapp'></i></a>
                        @endif
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center align-items-center">
                    <div class="">
                        @if ($booking->payment_status == 'waiting')
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
            created_at = new Date("<?php echo $booking->created_at->toIso8601String();  ?>").getTime();
            endTime = (created_at) + 1000 * (60 * minutes + seconds) + 500;
            updateTimer();
            // location.reload();
        }

        countdown("countdown", 10, 0);
    </script>
@endsection
@section('vitamin')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function formatLocalDateTime(utcString, format) {
            const date = new Date(utcString);
            if (isNaN(date.getTime())) {
                return 'Invalid date';
            }
            
            const options = {};
            if (format === 'date') {
                options.year = 'numeric';
                options.month = '2-digit';
                options.day = '2-digit';
                return date.toLocaleDateString(undefined, options);
            } else if (format === 'datetime') {
                options.year = 'numeric';
                options.month = '2-digit';
                options.day = '2-digit';
                options.hour = '2-digit';
                options.minute = '2-digit';
                options.hour12 = false;
                return date.toLocaleString(undefined, options).replace(/,/g, '');
            }
            return date.toLocaleString();
        }

        document.querySelectorAll('.js-local-datetime').forEach(element => {
            const utcString = element.getAttribute('data-utc');
            const format = element.getAttribute('data-format');
            element.textContent = formatLocalDateTime(utcString, format);
        });
    });
</script>
@endsection
