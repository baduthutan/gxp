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

                <form id="form_add" method="POST" action="{{ route('admin.dst.update', $dst->id) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="dst_start">Dst start</label>
                                    <input type="datetime-local" 
                                            class="form-control" 
                                            id="dst_start" 
                                            name="dst_start"
                                            value="{{ $dst->dst_start }}" 
                                            required />
                                </div>
                                <div class="form-group">
                                    <label for="dst_end">Dst end</label>
                                    <input type="datetime-local" 
                                            class="form-control" 
                                            id="dst_end" 
                                            name="dst_end"
                                            value="{{ $dst->dst_end }}" 
                                            required />
                                </div>
                                <div class="form-group">
                                    <label for="morning_schedule_time">Dst end</label>
                                    <input type="number" 
                                            class="form-control" 
                                            id="morning_schedule_time" 
                                            name="morning_schedule_time"
                                            value="{{ $dst->morning_schedule_time }}" 
                                            required />
                                </div>
                                <div class="form-group">
                                    <label for="afternoon_schedule_time">Afternoon Schedule Time</label>
                                    <input type="number" 
                                        class="form-control" 
                                        id="afternoon_schedule_time" 
                                        name="afternoon_schedule_time"
                                        value="{{ $dst->afternoon_schedule_time }}" 
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