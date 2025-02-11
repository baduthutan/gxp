<!DOCTYPE html>
<html>
    <head>
        <title>{{$title}}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'
        name='viewport'>
        <style>
            body {
                font-family: "Mulish", sans-serif;
            }
            #watermark { position: fixed; width: 200px; height: 200px; opacity: .1; }
            @page { margin-top: 0px; }
        </style>
    </head>
    <body>
    <p style="font-size:10px;">
        Green Express LLC <br>
        A Licensed, Insured, Trusted Transportation Company  <br>
        www.greenexpressone.com<br>
        Phone: +1 (215) 271 8381, +1 (215) 820 8288</p>
        <!-- <div id="watermark"><img src="http://code.google.com/p/dompdf/logo" height="100%" width="100%"></div> -->
        <center><p style="font-size:12px;"><b>BOOKING NUMBER   :   {{substr($bookings->booking_number,5,20)
        }}</b></p></center>
        <center><p style="font-size:10px;"><i>PRESENT THIS DOCUMENT FOR TRAVEL</i></p></center>
        <br>
        <table border="0" style="width:100%;border-collapse:collapse;font-size:11px">
            <tr>
                <td style="width:50%">
                    <table style="width:100%">
                        <tr>
                            <td style="width:30%">From</td>
                            <td style="width:2%">:</td>
                            <td>{{ $bookings->from_master_area_name }} {{ $bookings->from_master_sub_area_name }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width:50%;">
                    <table style="width:100%">
                        <tr>
                        <td style="width:30%">To</td>
                        <td style="width:2%">:</td>
                        <td>{{ $bookings->to_master_area_name }} {{ $bookings->to_master_sub_area_name }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            @if($bookings->schedule_type == 'charter')
                <tr>
                    <td style="width:50%">
                        <table style="width:100%">
                            <tr>
                                <td style="width:30%">Vehicle name</td>
                                <td style="width:2%">:</td>
                                <td>{{ $bookings->vehicle_name }}</td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:50%;">
                        <table style="width:100%">
                            <tr>
                                <td style="width:30%">Total Seat</td>
                                <td style="width:2%">:</td>
                                <td>Up to {{ $charter->total_seat }} passengers</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            @endif
            <tr>
                <td style="width:50%">
                    <table style="width:100%">
                        <tr>
                            <td style="width:30%">Special request drop off option</td>
                            <td style="width:2%">:</td>
                            <td>{{ $bookings->regional_name ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width:50%;">
                    <table style="width:100%">
                        <tr>
                            <td style="width:30%">Special Detail</td>
                            <td style="width:2%">:</td>
                            <td>{!! $bookings->special_area_detail ?? '-' !!}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width:50%">
                    <table style="width:100%">
                        <tr>
                            <td style="width:30%">Date</td>
                            <td style="width:2%">:</td>
                            @if($bookings->schedule_type =='shuttle')
                            <td>{{ $bookings->datetime_departure }}</td>
                            @else
                            <td>{{ substr($bookings->datetime_departure,0,10) }}</td>
                            @endif
                        </tr>
                    </table>
                </td>
                <td style="width:50%;">
                    <table style="width:100%">
                        <tr>
                            <td style="width:30%">Passenger</td>
                            <td style="width:2%">:</td>
                            <td>{{ $bookings->qty_adult }} Adult {{ $bookings->qty_baby }} Child</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width:50%">
                    <table style="width:100%">
                        <tr>
                            <td style="width:30%">Luggage</td>
                            <td style="width:2%">:</td>
                            <td>{{ $bookings->luggage_qty ?? '0' }} Pcs</td>
                        </tr>
                    </table>
                </td>
                <td style="width:50%;">
                    <table style="width:100%">
                        <tr>
                            <td style="width:30%">Overweight / Oversized Luggage</td>
                            <td style="width:2%">:</td>
                            <td>{{ $bookings->overweight_luggage_qty ?? '0' }} Pcs</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width:50%">
                    <table style="width:100%">
                        <tr>
                            <td style="width:30%">Flight Number</td>
                            <td style="width:2%">:</td>
                            <td>{{ $bookings->flight_number ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width:50%;">
                    <table style="width:100%">
                        <tr>
                            <td style="width:30%">Flight Info</td>
                            <td style="width:2%">:</td>
                            <td>{{ $bookings->flight_info ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- <tr>
                <td style="width:50%">
                    <table style="width:100%">
                        <tr>
                            <td style="width:30%">Notes</td>
                            <td style="width:2%">:</td>
                            <td>{!! $bookings->notes ?? '-' !!}</td>
                        </tr>
                    </table>
                </td>
            </tr> -->
            <tr>
                <td style="width:50%">
                    <table style="width:100%">
                        <tr>
                            <td style="width:30%">Customer Name</td>
                            <td style="width:2%">:</td>
                            <td>{{ $bookings->customer_name }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width:50%;">
                    <table style="width:100%">
                        <tr>
                        <td style="width:30%">Customer Phone</td>
                        <td style="width:2%">:</td>
                        <td>{{ $bookings->customer_phone }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width:50%">
                    <table style="width:100%">
                        <tr>
                            {{-- <td style="width:30%">Booking Status</td>
                            <td style="width:2%">:</td>
                            <td>{{ $bookings->booking_status }}</td> --}}
                            <td style="width:30%"></td>
                            <td style="width:2%"></td>
                            <td></td>
                        </tr>
                    </table>
                </td>
                <td style="width:50%;">
                    <table style="width:100%">
                        <tr>
                        <td style="width:30%">Booking Status</td>
                        <td style="width:2%">:</td>
                        <td>{{ $bookings->payment_status == 'paid' ? 'Confirmed' :
                        $booking_customers->payment_status }}</td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
        <hr>
        <table border="1" style="width:100%;border-collapse:collapse;font-size:12px;">
            <tr style="background-color:#D2D2D2">
                <td style="width:5%" align="center"><strong>#</strong></td>
                <td align="center"><strong>Passenger Name</strong></td>
                <td align="center"><strong>Passenger Phone</strong></td>
            </tr>
            @php
                $num=1
            @endphp
            @foreach($booking_customers as $bc)
                <tr>
                    <td align="center">{{$num++}}</td>
                    <td align="center">{{$bc->customer_name}}</td>
                    <td align="center">{{$bc->customer_phone}}</td>
                </tr>
            @endforeach
        </table>
        <br>
        <table border=1 style="width:100%;border-collapse:collapse;font-size:10px;">
            <tr>
                <td style="padding:5px">
                <p><strong>Terms and Conditions</strong></p>
                <p>1. Reservation must be made two days before the schedule date by:</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;A. Web application https://greenexpressone.com/ </p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;B. Whatsapp or text message to 215-271-8381 or with exact full name, airlines flight number on ticket, working USA phone is mandatory</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;C. E-mail : greenexpressone@yahoo.com with exact full name, airlines flight number on ticket, working USA phone is mandatory</p>
                <p>2. There is no refund within 48 hours before pick up time</p>
                <p>3. Shuttle shall not be liable for any loss belongings or any circumtances which delay the arrival such as breakdown, weather, Road Traffic, Etc.</p>
                <p>4. Free of charge luggage per passenger max 2 pieces and 1 piece hand carry bag. Extra baggage will be charged $20 each. Unusual and fragile luggage &nbsp;&nbsp;&nbsp;&nbsp;must be declared and confirmed. It will be rejected if not declared and confirmed.</p>
                <p>5. Box form baggage will be considered as overweight/oversized. Max Weight luggage 50 lbs each, max dimension: L+W+H = 62 inch, Hand carry bag max &nbsp;&nbsp;&nbsp;&nbsp;weight 15 lbs, max dimension 22"+14"+9"= 45".</p>
                <p>6. Oversize or overweight baggage will be charge $10 each.</p>
                <p>7. Passenger under 18 years old traveling alone must sign consent from parents or guardian, bring your car seat.</p>
                <p>8. Please come 15 minutes before departure time and company reserve to change the schedule anytime.</p>
                <p>9. Tips are appreciated, recommend gratuity tip amount is $5/person</p>
                <p>10. Price subject to change anytime.</p>
                </td>
            </tr>
        </table>
    </body>
</html>
