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

                <form id="form_add" method="POST" action="{{ route('admin.eta.update', $eta->id) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="dst_start">Trip 1 ETA</label>
                                    <input type="time" 
                                            class="form-control" 
                                            id="trip_1" 
                                            name="trip_1"
                                            value="{{ $eta->trip_1 }}" 
                                            required />
                                </div>
                                <div class="form-group">
                                    <label for="dst_end">Trip 2 ETA</label>
                                    <input type="time" 
                                            class="form-control" 
                                            id="trip_2" 
                                            name="trip_2"
                                            value="{{ $eta->trip_2 }}" 
                                            required />
                                </div>
                                <div class="form-group">
                                    <button type="submit" 
                                            class="btn btn-primary btn-block">
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

@section('script')
    <script>
        $(document).ready(() => {})
    </script>
@endsection