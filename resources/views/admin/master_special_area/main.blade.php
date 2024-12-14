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
                    <div class="col-sm-12 col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h1 class="card-title">Data</h1>
                                <div class="table-responsive">
                                    <table class="table table-bordered datatables">
                                        <thead>
                                            <tr>
                                                <th><i class="fas fa-cogs"></i></th>
                                                <th>Sub Area</th>
                                                <th>Region Name</th>
                                                <th>First Person Price</th>
                                                <th>Extra Person Price</th>
                                                <th>Active</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($master_special_areas as $master_special_area)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('admin.master_special_area.edit', $master_special_area->id) }}"
                                                            class="btn btn-info btn-sm">
                                                            <i class="fas fa-pencil fa-fw"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            id="delete_{{ $master_special_area->id }}"
                                                            onclick="deleteData('{{ $master_special_area->id }}')">
                                                            <i class="fas fa-trash fa-fw"></i>
                                                        </button>
                                                    </td>
                                                    <td>{{ $master_special_area->master_sub_area->name }}</td>
                                                    <td>{{ $master_special_area->regional_name }}
                                                    <td>{{ number_format($master_special_area->first_person_price, 2) }}
                                                    </td>
                                                    <td>{{ number_format($master_special_area->extra_person_price, 2) }}
                                                    </td>
                                                    <td>{{ $master_special_area->is_active ? 'Active' : 'Inactive' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h1 class="card-title">Add Data</h1>
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
                                <form id="form_add" method="POST" action="/admin/master_special_area">
                                    @csrf
                                    <div class="form-group">
                                        <label for="master_sub_area_id">Sub Area</label>
                                        <select class="form-control" id="master_sub_area_id" name="master_sub_area_id"
                                            data-placeholder="Select Sub Area" required>
                                            <option value=""></option>
                                            @foreach ($master_sub_areas as $master_sub_area)
                                                <option value="{{ $master_sub_area->id }}">
                                                    {{ $master_sub_area->main_area }} - {{ $master_sub_area->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="regional_name">Regional Name</label>
                                        <input type="text" class="form-control" id="regional_name" name="regional_name"
                                            minlength="3" maxlength="100" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="first_person_price">First Person Price</label>
                                        <input type="number" class="form-control" id="first_person_price"
                                            name="first_person_price" min="0.01" max="9999.99" step="0.01"
                                            required />
                                    </div>
                                    <div class="form-group">
                                        <label for="extra_person_price">Extra Person Price</label>
                                        <input type="number" class="form-control" id="extra_person_price"
                                            name="extra_person_price" min="0.01" max="9999.99" step="0.01"
                                            required />
                                    </div>
                                    <div class="form-group">
                                        <label for="is_active">Active</label>
                                        <select class="form-control" id="is_active" name="is_active" required>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-save fa-fw"></i> Save
                                        </button>
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
        const token = $("meta[name='csrf-token']").attr("content");

        $(document).ready(() => {
            $('.datatables').DataTable({
                order: [
                    [1, 'asc']
                ],
                columnDefs: [{
                    targets: [0],
                    orderable: false,
                }]
            })

            $('#master_sub_area_id').select2({
                allowClear: true
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
                        url: `/admin/master_special_area/delete/${id}`,
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
    </script>
@endsection
