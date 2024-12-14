<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Date Time Departure</th>
            <th>Customer Name</th>
            <th>Customer Phone Number</th>
            <th>Vehicle Number and Type</th>
            <th>Schedule Type</th>
            <th>From Area</th>
            <th>To Area</th>
            <th>Destination</th>
            <th>Flight Number</th>
            <th>Flight Info</th>
            <th>Luggage Qty</th>
{{--            <th>Overweight or Oversized Luggage Qty</th>--}}
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $booking)
            <tr>
                <td>
                    {{ $loop->iteration }}
                </td>
                <td>{{ $booking->datetime_departure }}</td>
                <td>{{ $booking->customer_name }}</td>
                <td>{{ $booking->customer_phone }}</td>
                <td>{{ $booking->vehicle_name }} {{ $booking->vehicle_number ? " | " . $booking->vehicle_number : ""
                }}</td>
                <td>{{ ucfirst($booking->schedule_type) }}</td>
                <td>{{ $booking->from_master_area_name . ', ' }}
                    {{ $booking->from_master_sub_area_name }} </td>
                <td>{{ $booking->to_master_area_name . ', ' }}
                    {{ $booking->to_master_sub_area_name }}</td>
                <td>{{ $booking->regional_name }} , {!! $booking->special_area_detail !!}</td>
                <td>{{ $booking->flight_number }}</td>
                <td>{{ $booking->flight_info }}</td>
                <td>{{ number_format($booking->luggage_qty, 0) }} Kg</td>
{{--                <td>{{ number_format($booking->overweight_luggage_qty, 0) }} Kg</td>--}}
                <td>{{ nl2br($booking->notes) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
