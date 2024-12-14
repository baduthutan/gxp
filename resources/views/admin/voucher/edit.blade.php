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
                    <div class="col-sm-12 col-md-4 offset-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h1 class="card-title">Edit Data</h1>
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
                                <form id="form_add" method="POST"
                                    action="{{ route('admin.voucher.update', $voucher->id) }}">
                                    @csrf
                                    @method('PUT')
                                    {{-- <div class="form-group">
                                        <label for="agent_id">Agent</label>
                                        <select class="form-control" id="agent_id" name="agent_id" required>
                                            <option value=""></option>
                                            @foreach ($agents as $agent)
                                                <option {{ $voucher->agent_id == $agent->id ? 'selected' : '' }}
                                                    value="{{ $agent->id }}">{{ $agent->name }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="form-group">
                                        <label for="name">Voucher Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') ?? $voucher->name }}" minlength="3" maxlength="100"
                                            required />
                                    </div>
                                    <div class="form-group">
                                        <label for="code">Voucher Code</label>
                                        <input type="text" class="form-control" id="code" name="code"
                                            value="{{ old('code') ?? $voucher->code }}" minlength="3" maxlength="100"
                                            required />
                                    </div>
                                    <div class="form-group">
                                        <label for="date_start">Date Start</label>
                                        <input type="date" class="form-control" id="date_start" name="date_start"
                                            value="{{ old('date_start') ?? $voucher->date_start }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="date_expired">Date Expired</label>
                                        <input type="date" class="form-control" id="date_expired" name="date_expired"
                                            value="{{ old('date_expired') ?? $voucher->date_expired }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="discount_type">Discount Type</label>
                                        <select class="form-control" id="discount_type" name="discount_type" required>
                                            <option {{ $voucher->discount_type == 'percentage' ? 'selected' : null }}
                                                value="percentage">Percentage</option>
                                            <option {{ $voucher->discount_type == 'value' ? 'selected' : null }}
                                                value="value">Value</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="discount_value">Discount Value</label>
                                        <input type="number" class="form-control" id="discount_value" name="discount_value"
                                            value="{{ old('discount_value') ?? $voucher->discount_value }}" min="0.01"
                                            max="9999.99" step="0.01" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="is_active">Active</label>
                                        <select class="form-control" id="is_active" name="is_active" required>
                                            <option {{ $voucher->is_active == '1' ? 'selected' : null }} value="1">
                                                Active</option>
                                            <option {{ $voucher->is_active == '0' ? 'selected' : null }} value="0">
                                                Inactive</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-save fa-fw"></i> Save
                                        </button>
                                        <a href="{{ route('admin.voucher') }}" class="btn btn-secondary btn-block">
                                            <i class="fas fa-backward fa-fw"></i> Back
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(() => {})
    </script>
@endsection
