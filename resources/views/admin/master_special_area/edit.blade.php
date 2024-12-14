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
                                    action="{{ route('admin.master_special_area.update', $master_special_area->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="master_sub_area_id">Sub Area</label>
                                        <select class="form-control" id="master_sub_area_id" name="master_sub_area_id"
                                            required>
                                            <option value=""></option>
                                            @foreach ($master_sub_areas as $master_sub_area)
                                                <option
                                                    {{ $master_sub_area->id == $master_special_area->master_sub_area_id ? 'selected' : null }}
                                                    value="{{ $master_sub_area->id }}">{{ $master_sub_area->main_area }} -
                                                    {{ $master_sub_area->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="regional_name">Regional Name</label>
                                        <input type="text" class="form-control" id="regional_name" name="regional_name"
                                            value="{{ $master_special_area->regional_name }}" minlength="3" maxlength="100"
                                            required />
                                    </div>
                                    <div class="form-group">
                                        <label for="first_person_price">First Person Price</label>
                                        <input type="number" class="form-control" id="first_person_price"
                                            name="first_person_price" value="{{ $master_special_area->first_person_price }}"
                                            min="0.01" maxlength="9999.99" step="0.01" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="extra_person_price">Extra Person Price</label>
                                        <input type="number" class="form-control" id="extra_person_price"
                                            name="extra_person_price"
                                            value="{{ $master_special_area->extra_person_price }}" min="0.01"
                                            maxlength="9999.99" step="0.01" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="is_active">Active</label>
                                        <select class="form-control" id="is_active" name="is_active" required>
                                            <option value="1"
                                                {{ $master_special_area->is_active == '1' ? 'selected' : null }}>
                                                Active</option>
                                            <option value="0"
                                                {{ $master_special_area->is_active == '0' ? 'selected' : null }}>
                                                Inactive</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-save fa-fw"></i> Save
                                        </button>
                                        <a href="{{ route('admin.master_area') }}" class="btn btn-secondary btn-block">
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
