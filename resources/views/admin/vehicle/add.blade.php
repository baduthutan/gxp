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

                <form id="form_add" method="POST" action="{{ route('admin.vehicle.store') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="vehicle_number">Vehicle Number</label>
                                        <input type="text" class="form-control" id="vehicle_number" name="vehicle_number"
                                            value="{{ old('vehicle_number') }}" minlength="3" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="vehicle_name">Vehicle Name</label>
                                        <input type="text" class="form-control" id="vehicle_name" name="vehicle_name"
                                               value="{{ old('vehicle_name') }}" minlength="3" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="photo">Photo</label>
                                        <input type="file" class="form-control" id="photo" name="photo"
                                            accept="image/*" />
                                    </div>
                                    <div class="form-group">
                                        <label for="driver_contact">Driver Contact</label>
                                        <input type="text" class="form-control" id="driver_contact"
                                            name="driver_contact" value="{{ old('driver_contact') }}" minlength="3" />
                                    </div>
                                    <div class="form-group">
                                        <label for="total_seat">Total Seat</label>
                                        <input type="number" class="form-control" id="total_seat" name="total_seat"
                                            value="{{ old('total_seat') }}" min="1" max="99" />
                                    </div>
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea class="form-control" id="notes" name="notes">{{ old('notes') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-save fa-fw"></i> Save
                                        </button>
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
