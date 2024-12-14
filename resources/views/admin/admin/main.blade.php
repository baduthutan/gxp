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
                                                <th>Picture</th>
                                                <th>Username</th>
                                                <th>Name</th>
                                                <th>Role</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($admins as $admin)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('admin.admin.edit', $admin->id) }}"
                                                            class="btn btn-info btn-sm" title="Edit">
                                                            <i class="fas fa-pencil fa-fw"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            id="reset_{{ $admin->id }}"
                                                            onclick="resetPassword('{{ $admin->id }}')"
                                                            title="Reset Password">
                                                            <i class="fas fa-key fa-fw"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            id="delete_{{ $admin->id }}"
                                                            onclick="deleteData('{{ $admin->id }}')" title="Delete">
                                                            <i class="fas fa-trash fa-fw"></i>
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <img src="{{ URL::to($admin->photo) }}" alt=""
                                                            class="img-thumbnail" />
                                                    </td>
                                                    <td>{{ $admin->username }}</td>
                                                    <td>{{ $admin->name }}</td>
                                                    <td>{{ ucfirst($admin->role) }}</td>
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
                                <form id="form_add" method="POST" action="/admin/admin" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="photo">Picture</label>
                                        <input type="file" class="form-control" id="photo" name="photo"
                                            accept="image/*" />
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username"
                                            value="{{ old('username') }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            value="{{ old('password') }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select class="form-control" id="role" name="role" required>
                                            <option value="super admin">Super Admin</option>
                                            <option value="admin">Admin</option>
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

    <!-- Modal -->
    <form id="form_reset_password">
        <div class="modal fade" id="modal_reset_password" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    @csrf
                    @method('post')
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Reset Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="new_password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control" id="new_password_confirmation"
                                name="new_password_confirmation" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn_save">
                            <i class="fas fa-save fa-fw"></i> Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script>
        const token = $("meta[name='csrf-token']").attr("content");
        let id_edit = null;

        $(document).ready(() => {
            $('.datatables').DataTable({
                order: [
                    [2, 'asc']
                ],
                columnDefs: [{
                    targets: [0, 1],
                    orderable: false,
                }]
            })

            $('#form_reset_password').on('submit', e => {
                e.preventDefault();
                processResetPassword()
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
                        url: `/admin/admin/delete/${id}`,
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

        function resetPassword(id) {
            id_edit = id
            $('#new_password').val('')
            $('#new_password_confirmation').val('')
            $('#modal_reset_password').modal('show')
        }

        function processResetPassword() {
            $.ajax({
                url: `/admin/admin/reset_password/${id_edit}`,
                type: 'POST',
                dataType: 'json',
                data: {
                    "id": id_edit,
                    "new_password": $('#new_password').val(),
                    "new_password_confirmation": $('#new_password_confirmation').val(),
                    "_token": token,
                },
                beforeSend: () => {
                    $(`#btn_save`).prop('disabled', true)
                }
            }).fail(e => {
                console.log(e.responseText)
                $(`#btn_save`).prop('disabled', false)
            }).done(e => {
                if (e.code == 200) {
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
                }
            })
        }
    </script>
@endsection
