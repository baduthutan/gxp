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
                                                <th class="text-center"><i class="fas fa-cogs"></i></th>
                                                <th style="min-width: 100px;">Date Time Departure</th>
                                                <th style="min-width: 100px;">Booking Number</th>
                                                <th style="min-width: 100px;">Schedule Type</th>
                                                <th style="min-width: 100px;">From Type</th>
                                                <th style="min-width: 300px;">From Area</th>
                                                <th style="min-width: 300px;">To Area</th>
                                                <th style="min-width: 100px;">Special Request</th>
                                                <th style="min-width: 100px;">Regional Name</th>
                                                <th style="min-width: 100px;">Special Request Detail</th>
                                                <th style="min-width: 150px;">Vehicle Name</th>
                                                <th style="min-width: 150px;">Vehicle Number</th>
                                                <th style="min-width: 100px;">Customer Phone Number (preferably Whatsapp phone number)</th>
                                                <th style="min-width: 100px;">Customer Name</th>
                                                <th style="min-width: 100px;">Passenger Phone</th>
                                                <th style="min-width: 100px;">Passenger Name</th>
                                                <th style="min-width: 100px;">Qty Adult</th>
                                                <th style="min-width: 100px;">Qty Baby</th>
                                                <th style="min-width: 100px;">Flight Number</th>
                                                <th style="min-width: 100px;">Flight Info</th>
                                                <th style="min-width: 120px;">Luggage Qty</th>
                                                <th style="min-width: 120px;">Luggage Price</th>
                                                <th style="min-width: 120px;">Extra Price</th>
                                                <th style="min-width: 120px;">Voucher</th>
                                                <th style="min-width: 120px;">Promo Price</th>
                                                <th style="min-width: 120px;">Total Price</th>
                                                <th style="min-width: 120px;">Booking Status</th>
                                                <th style="min-width: 120px;">Payment Method</th>
                                                <th style="min-width: 120px;">Payment Status</th>
                                                <th style="min-width: 100px;">Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bookings as $booking)
                                                <tr>
                                                    <td>
                                                        <button type="button" class="btn btn-info btn-sm"
                                                            title="Reschedule"
                                                            onclick="modalReschedule('{{ $booking['id'] }}', '{{ $booking['booking_number'] }}', '{{ $booking['datetime_departure'] }}')">
                                                            <i class="fas fa-calendar-alt fa-fw"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            id="delete_{{ $booking['id'] }}"
                                                            onclick="deleteData('{{ $booking['id'] }}')" title="Delete">
                                                            <i class="fas fa-trash fa-fw"></i>
                                                        </button>
                                                    </td>
                                                    <td>{{ $booking['datetime_departure'] }}</td>
                                                    <td>{{ ucfirst($booking['booking_number']) }}</td>
                                                    <td>{{ ucfirst($booking['schedule_type']) }}</td>
                                                    <td>{{ ucfirst($booking['from_type']) }}</td>
                                                    <td>{{ $booking['from_area'] }}</td>
                                                    <td>{{ $booking['to_area'] }}</td>
                                                    <td>{{ $booking['special_request'] }}</td>
                                                    <td>{{ $booking['regional_name'] }}</td>
                                                    <td>{!! $booking['special_area_detail'] !!}</td>
                                                    <td>{{ $booking['vehicle_name'] }}</td>
                                                    <td>{{ $booking['vehicle_number'] }}</td>
                                                    <td>{{ $booking['customer_phone'] }}</td>
                                                    <td>{{ $booking['customer_name'] }}</td>
                                                    <td>{{ $booking['passenger_phone'] }}</td>
                                                    <td>{{ $booking['passenger_name'] }}</td>
                                                    <td>{{ $booking['qty_adult'] }}</td>
                                                    <td>{{ $booking['qty_baby'] }}</td>
                                                    <td>{{ $booking['flight_number'] }}</td>
                                                    <td>{{ $booking['flight_info'] }}</td>
                                                    <td>{{ number_format($booking['luggage_qty'], 0) }} Kg</td>
                                                    <td>{{ number_format($booking['luggage_price'], 0) }}</td>
                                                    <td>{{ number_format($booking['extra_price'], 0) }}</td>
                                                    <td>{{ $booking['voucher_name'] }}</td>
                                                    <td>{{ number_format($booking['promo_price'], 2) }}</td>
                                                    <td>{{ number_format($booking['total_price'], 2) }}</td>
                                                    <td>{{ $booking['booking_status'] }}</td>
                                                    <td>{{ $booking['payment_method'] == 'venmo' ||
                                                $booking['payment_method'] == 'bank' || empty
                                                ($booking['payment_method'])
                                                 ? 'Non Credit Card' :
                                                'Credit Card'
                                                 }}</td>
                                                    <td>{{ $booking['payment_status'] }}
                                                        @if(auth('admin')->user()->role == 'super admin')
                                                        &nbsp;
                                                        <button type="button" class="btn btn-info btn-sm"
                                                            title="Change Payment Status"
                                                            onclick="modalChangePaymentStatus('{{ $booking['id'] }}', '{{ $booking['booking_number'] }}', '{{ $booking['payment_status'] }}')">
                                                            <i class="fas fa-edit fa-fw"></i>
                                                        </button>
                                                        @endif
                                                    </td>
                                                    <td>{{ nl2br($booking['notes']) }}</td>
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

    <!-- Modal -->
    <form id="form_reschedule">
        <div class="modal fade" id="modal_reschedule" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Reschedule</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="booking_number">Booking Number</label>
                            <input type="text" class="form-control" id="booking_number" name="booking_number" required
                                readonly />
                        </div>
                        <div class="form-group">
                            <label for="date_departure">Date Time Departure</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="date_departure" name="date_departure"
                                    required />
                                <input type="time" class="form-control" id="time_departure" name="time_departure"
                                    required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn_save"><i class="fas fa-save"></i>
                            Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Modal Change Payment Status -->
    <form id="form_change_payment_status">
        <div class="modal fade" id="modal_change_payment_status" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Change Payment Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="book_number">Booking Number</label>
                            <input type="text" class="form-control" id="book_number" name="book_number" required
                                readonly />
                        </div>
                        <div class="form-group">
                            <label for="date_departure">Payment Status</label>
                            <select class="form-control" name="payment_status" id="payment_status">
                                <option value="waiting">waiting</option>
                                <option value="paid">paid</option>
                                <option value="failed">failed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn_save_cps"><i class="fas fa-save"></i>
                            Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        const token = $("meta[name='csrf-token']").attr("content");
        let id_edit = null;

        $(document).ready(() => {
            $('.datatables').DataTable({
                scrollX: true,
                order: [
                ],
                columnDefs: [{
                    targets: [0, 1],
                    orderable: false,
                }]
            })

            $('#form_reschedule').on('submit', e => {
                e.preventDefault();
                rescheduleBooking()
            })

            $('#form_change_payment_status').on('submit', e => {
                e.preventDefault();
                changepaymentstatusBooking()
            })
        })

        function deleteData(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/booking/charter/delete/${id}`,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            "id": id,
                            "_token": token,
                        },
                        beforeSend: () => {
                            $(`#delete_${id}`).prop('disabled', true)
                        }
                    }).fail(e => {
                        console.log(e.responseText)
                        $(`#delete_${id}`).prop('disabled', false)
                    }).done(e => {
                        Swal.fire({
                            icon: 'success',
                            title: e.message,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 1500,
                            toast: true
                        }).then(() => {
                            window.location.reload()
                        })
                    })
                }
            })
        }

        function modalReschedule(id, booking_number, datetime_schedule) {
            id_edit = id
            let moment_schedule = moment(datetime_schedule)
            console.log(moment_schedule.format('YYYY-MM-DD'))
            console.log(moment_schedule.format('hh:mm'))
            $('#booking_number').val(booking_number)
            $('#date_departure').val(moment_schedule.format('YYYY-MM-DD'))
            $('#time_departure').val(moment_schedule.format('hh:mm'))
            $('#modal_reschedule').modal('show')
        }

        function modalChangePaymentStatus(id, booking_number, payment_status) {
            id_edit = id
            $('#book_number').val(booking_number)
            $('#payment_status').val(payment_status).change()
            $('#modal_change_payment_status').modal('show')
        }

        function rescheduleBooking() {
            let datetime_departure = $('#date_departure').val() + " " + $('#time_departure').val() + ":00"
            $.ajax({
                url: `/api/booking/reschedule`,
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id_edit,
                    datetime_departure: datetime_departure,
                    "_token": token,
                },
                beforeSend: () => {
                    $.blockUI()
                    $('#btn_save').prop('disabled', true)
                }
            }).fail(e => {
                console.log(e.responseText)
                $.unblockUI()
                $('#btn_save').prop('disabled', false)
            }).done(e => {
                console.log(e.message)
                if (e.success == false) {
                    $.unblockUI()
                    $('#btn_save').prop('disabled', false)
                    Swal.fire({
                        icon: 'warning',
                        title: e.message,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        toast: true
                    }).then(() => {
                        $.unblockUI()
                    })
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: e.message,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true
                    }).then(() => {
                        $.unblockUI()
                        window.location.reload()
                    })
                }

            })
        }

        function changepaymentstatusBooking() {
            let payment_status = $('#payment_status').val()
            $.ajax({
                url: `/api/booking/change_payment_status`,
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id_edit,
                    payment_status: payment_status,
                    "_token": token,
                },
                beforeSend: () => {
                    $.blockUI()
                    $('#btn_save_cps').prop('disabled', true)
                }
            }).fail(e => {
                console.log(e.responseText)
                $.unblockUI()
                $('#btn_save_cps').prop('disabled', false)
            }).done(e => {
                console.log(e.message)
                if (e.success == false) {
                    $.unblockUI()
                    $('#btn_save_cps').prop('disabled', false)
                    Swal.fire({
                        icon: 'warning',
                        title: e.message,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        toast: true
                    }).then(() => {
                        $.unblockUI()
                    })
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: e.message,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true
                    }).then(() => {
                        $.unblockUI()
                        window.location.reload()
                    })
                }

            })
        }
    </script>
@endsection
