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
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="pb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <div class="alert-body">
                                    {{ $message }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <form id="form_add" method="POST" action="{{ url('/admin/shuttle/update', $shuttles->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">From Type</label>
                                        <select class="form-control" id="from_type" name="from_type" required>
                                            <option value=""></option>
                                            <option value="airport">Airport</option>
                                            <option value="city">City</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="vehicle">Vehicle</label>
                                        <select class="form-control" id="vehicle" name="vehicle" required>
                                            <option value=""></option>
                                            @foreach($vehicle as $item)
                                                @if($item->vehicle_number === $shuttles->vehicle_number)
                                                    <option value="{{$item->id}}" selected>{{$item->vehicle_name}}
                                                        [{{$item->vehicle_number}}]
                                                    </option>
                                                @else
                                                    <option value="{{$item->id}}">{{$item->vehicle_name}}
                                                        [{{$item->vehicle_number}}]
                                                    </option>
                                                @endif

                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="from_master_area_id">From Main Area</label>
                                        <select class="form-control select2" id="from_master_area_id"
                                            name="from_master_area_id" data-placeholder="Select From Main Area" required
                                            disabled>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="from_master_sub_area_id">From Sub Area</label>
                                        <select class="form-control select2" id="from_master_sub_area_id"
                                            name="from_master_sub_area_id" data-placeholder="Select From Sub Area" required
                                            disabled>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="to_master_area_id">To Main Area</label>
                                        <select class="form-control select2" id="to_master_area_id" name="to_master_area_id"
                                            data-placeholder="Select To Main Area" required disabled>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="to_master_sub_area_id">To Sub Area</label>
                                        <select class="form-control select2" id="to_master_sub_area_id"
                                            name="to_master_sub_area_id" data-placeholder="Select To Sub Area" required
                                            disabled>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="trip_number">Trip Number</label>
                                        <select class="form-control" id="trip_number" name="trip_number" required>
                                            <option value="1" {{$shuttles->trip_number == '1' ?
                                            "selected" : ""}}>1st</option>
                                            <option value="2" {{$shuttles->trip_number == '2' ?
                                            "selected" : ""}}>2nd</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="time_departure">Time Departure</label>
                                        <input type="time" class="form-control" id="time_departure" name="time_departure"
                                               value="{{ \Carbon\Carbon::parse($shuttles->time_departure)->format('H:i') }}"
                                               required />
                                    </div>
                                    <div class="form-group">
                                        <label for="luggage_price">Extra Luggage Price</label>
                                        <input type="number" class="form-control" id="luggage_price" name="luggage_price"
                                               value="{{ $shuttles->luggage_price }}" min="0" max="9999.99"
                                               step="0.01" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Rent Price</label>
                                        <input type="number" class="form-control" id="price" name="price"
                                               value="{{ $shuttles->price }}" min="0.01" max="9999.99" step="0.01"
                                               required />
                                    </div>
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea class="form-control" id="notes" name="notes">{{ $shuttles->notes }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="is_active">Available</label>
                                        <select class="form-control" id="is_active" name="is_active" required>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-save fa-fw"></i> Save
                                        </button>
                                        <a href="/admin/shuttle" class="btn btn-secondary btn-block">
                                            <i class="fas fa-backward fa-fw"></i> Back to list
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="vehicle_name">Vehicle Name</label>
                                        <input type="text" class="form-control" id="vehicle_name" name="vehicle_name"
                                            value="{{ $shuttles->vehicle_name }}" minlength="3" disabled />
                                    </div>
                                    <div class="form-group">
                                        <label for="vehicle_number">Vehicle Number</label>
                                        <input type="text" class="form-control" id="vehicle_number" name="vehicle_number"
                                            value="{{ $shuttles->vehicle_number }}" minlength="3" disabled />
                                    </div>

                                    <div class="form-group">
                                        <label for="total_seat">Total Seat</label>
                                        <input type="number" class="form-control" id="total_seat" name="total_seat"
                                            value="{{ $shuttles->total_seat }}" min="1" max="100"
                                            step="1" disabled />
                                    </div>
                                    <div class="form-group">
                                        <label for="driver_contact">Driver Contact</label>
                                        <input type="text" class="form-control" id="driver_contact"
                                            name="driver_contact" value="{{ $shuttles->driver_contact }}"
                                            minlength="3" disabled/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(() => {
            $('#from_master_area_id').select2({
                allowClear: true
            })

            $('#from_master_sub_area_id').select2({
                allowClear: true
            })

            $('#to_master_area_id').select2({
                allowClear: true
            })

            $('#to_master_sub_area_id').select2({
                allowClear: true
            })

            $('#trip_number').select2({
                allowClear: true
            })

            $('#from_type').on('change', e => {
                e.preventDefault();
                if ($('#from_type').val()) {
                    getFromList()
                } else {
                    $('#from_master_area_id').val(null).trigger('change').prop('disabled', true)
                    $('#from_master_sub_area_id').val(null).trigger('change').prop('disabled', true)
                    $('#to_master_area_id').val(null).trigger('change').prop('disabled', true)
                    $('#to_master_sub_area_id').val(null).trigger('change').prop('disabled', true)
                }
            })

            $('#from_master_area_id').on('change', e => {
                if ($('#from_type').val() == "city" && $('#from_master_area_id').val()) {
                    let master_area_id = $('#from_master_area_id').val()
                    getSubArea(master_area_id, '#from_master_sub_area_id')
                } else {
                    $('#from_master_sub_area_id').val(null).trigger('change').prop('disabled', true)
                }
            })

            $('#to_master_area_id').on('change', e => {
                if ($('#from_type').val() == "airport" && $('#to_master_area_id').val()) {
                    let master_area_id = $('#to_master_area_id').val()
                    getSubArea(master_area_id, '#to_master_sub_area_id')
                } else {
                    $('#to_master_sub_area_id').val(null).trigger('change').prop('disabled', true)
                }
            })

            initData()
        })

        function initData() {
            $.blockUI()
            $('#from_type').val('{{ $shuttles->from_type }}').trigger('change')
            $('#is_active').val('{{ $shuttles->is_active }}').trigger('change')
            setTimeout(() => {
                $('#from_master_area_id').val('{{ $shuttles->from_master_area_id }}').trigger('change')
                setTimeout(() => {
                    $('#to_master_area_id').val('{{ $shuttles->to_master_area_id }}').trigger('change')
                    setTimeout(() => {
                        $('#from_master_sub_area_id').val(
                            '{{ $shuttles->from_master_sub_area_id }}').trigger(
                            'change')
                        $('#to_master_sub_area_id').val('{{ $shuttles->to_master_sub_area_id }}')
                            .trigger(
                                'change')
                        $.unblockUI()
                    }, 1000);
                }, 1000);
            }, 1000);
        }

        function getFromList() {
            $.ajax({
                url: `{{ $base_url }}api/get_list_from_departure`,
                method: 'get',
                dataType: 'json',
                data: {
                    from_type: $('#from_type').val()
                },
                beforeSend: () => {
                    $('#from_master_area_id').html('<option value=""></option>').prop('disabled', true)
                    $('#from_master_sub_area_id').html('<option value=""></option>').prop('disabled', true)
                }
            }).fail(e => {
                console.log(e.responseText)
            }).done(e => {
                console.log(e)
                let data = e.data
                let htmlnya = '<option value=""></option>';

                data.forEach(x => {
                    let id = x.id
                    let name = x.name
                    let sub_area = x.sub_area
                    htmlnya += `<option value="${id}">${name}</option>`
                })
                $('#from_master_area_id').html(htmlnya).prop('disabled', false)

                getToList()
            })
        }

        function getToList() {
            $.ajax({
                url: `{{ $base_url }}api/get_list_to_destination`,
                method: 'get',
                dataType: 'json',
                data: {
                    from_type: ($('#from_type').val() == "airport") ? "city" : "airport"
                },
                beforeSend: () => {
                    $('#to_master_area_id').html('<option value=""></option>').prop('disabled', true)
                    $('#to_master_sub_area_id').html('<option value=""></option>').prop('disabled', true)
                }
            }).fail(e => {
                console.log(e.responseText)
            }).done(e => {
                console.log(e)
                let data = e.data
                let htmlnya = '<option value=""></option>';

                data.forEach(x => {
                    let id = x.id
                    let name = x.name
                    htmlnya += `<option value="${id}">${name}</option>`
                })
                $('#to_master_area_id').html(htmlnya).prop('disabled', false)
            })
        }

        function getSubArea(master_area_id, selector) {
            $.ajax({
                url: `{{ $base_url }}api/get_list_sub_area`,
                method: 'get',
                dataType: 'json',
                data: {
                    master_area_id
                },
                beforeSend: () => {
                    $(`${selector}`).html('<option value=""></option>').prop('disabled', true)
                }
            }).fail(e => {
                console.log(e.responseText)
            }).done(e => {
                console.log(e)
                let data = e.data
                let htmlnya = '<option value=""></option>';

                data.forEach(x => {
                    let id = x.id
                    let name = x.name
                    htmlnya += `<option value="${id}">${name}</option>`
                })
                $(`${selector}`).html(htmlnya).prop('disabled', false)
            })
        }
    </script>
@endsection
