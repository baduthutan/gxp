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
                                                <th style="min-width: 150px;">Agent Name</th>
                                                <th style="min-width: 100px;">Password</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($agents as $agent)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('admin.agent.edit', $agent->id) }}"
                                                            class="btn btn-info btn-sm">
                                                            <i class="fas fa-pencil fa-fw"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            id="delete_{{ $agent->id }}"
                                                            onclick="deleteData('{{ $agent->id }}')">
                                                            <i class="fas fa-trash fa-fw"></i>
                                                        </button>
                                                    </td>
                                                    <td>{{ $agent->name }}</td>
                                                    <td>{{ $agent->password }}</td>
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
                                <form id="form_add" method="POST" action="/admin/agent">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name">Agent Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" minlength="3" maxlength="100" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="text" class="form-control" id="password" name="password"
                                            value="{{ old('password') }}" minlength="3" maxlength="100" required />
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
                        url: `/admin/agent/delete/${id}`,
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
