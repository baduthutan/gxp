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
                                                <th style="min-width: 150px;">Voucher Name</th>
                                                <th style="min-width: 100px;">Code</th>
                                                <th style="min-width: 100px;">Date Start</th>
                                                <th style="min-width: 100px;">Date Expired</th>
                                                <th style="min-width: 120px;">Discount Type</th>
                                                <th style="min-width: 120px;">Discount Value</th>
                                                <th>Active</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($vouchers as $voucher)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('admin.voucher.edit', $voucher->id) }}"
                                                            class="btn btn-info btn-sm">
                                                            <i class="fas fa-pencil fa-fw"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            id="delete_{{ $voucher->id }}"
                                                            onclick="deleteData('{{ $voucher->id }}')">
                                                            <i class="fas fa-trash fa-fw"></i>
                                                        </button>
                                                    </td>
                                                    <td>{{ $voucher->name }}</td>
                                                    <td>{{ $voucher->code }}</td>
                                                    <td>{{ $voucher->date_start }}</td>
                                                    <td>{{ $voucher->date_expired }}</td>
                                                    <td class="text-capitalize">{{ $voucher->discount_type }}</td>
                                                    <td>{{ number_format($voucher->discount_value, 2) }}</td>
                                                    <td>{{ $voucher->is_active ? 'Active' : 'Inactive' }}</td>
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
                                <form id="form_add" method="POST" action="/admin/voucher">
                                    @csrf
                                    {{-- <div class="form-group">
                                        <label for="agent_id">Agent</label>
                                        <select class="form-control" id="agent_id" name="agent_id" required>
                                            <option value=""></option>
                                            @foreach ($agents as $agent)
                                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="form-group">
                                        <label for="name">Voucher Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" minlength="3" maxlength="100" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="code">Voucher Code</label>
                                        <input type="text" class="form-control" id="code" name="code"
                                            value="{{ old('code') }}" minlength="3" maxlength="100" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="date_start">Date Start</label>
                                        <input type="date" class="form-control" id="date_start" name="date_start"
                                            value="{{ old('date_start') }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="date_expired">Date Expired</label>
                                        <input type="date" class="form-control" id="date_expired" name="date_expired"
                                            value="{{ old('date_expired') }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="discount_type">Discount Type</label>
                                        <select class="form-control" id="discount_type" name="discount_type" required>
                                            <option value="percentage">Percentage</option>
                                            <option value="value">Value</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="discount_value">Discount Value</label>
                                        <input type="number" class="form-control" id="discount_value" name="discount_value"
                                            value="{{ old('discount_value') }}" min="0.01" max="9999.99"
                                            step="0.01" required />
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
                    [3, 'asc']
                ],
                columnDefs: [{
                    targets: [0],
                    orderable: false,
                }]
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
                        url: `/admin/voucher/delete/${id}`,
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
