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
                                <form id="form_add" method="POST" action="{{ route('admin.admin.update', $admin->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <img src="{{ $base_url . $admin->photo }}" id="img_preview"
                                        class="img-thumbnail mx-auto d-block" />
                                    <div class="form-group mt-2">
                                        <label for="photo">Photo</label>
                                        <input type="file" class="form-control" id="photo" name="photo"
                                            accept="image/*" />
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username"
                                            value="{{ old('username') ?? $admin->username }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') ?? $admin->name }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select class="form-control" id="role" name="role" required>
                                            <option {{ $admin->role == 'super admin' ? 'selected' : null }}
                                                value="super admin">Super Admin</option>
                                            <option {{ $admin->role == 'admin' ? 'selected' : null }} value="admin">Admin
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-save fa-fw"></i> Save
                                        </button>
                                        <a href="{{ route('admin.admin') }}" class="btn btn-secondary btn-block">
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
