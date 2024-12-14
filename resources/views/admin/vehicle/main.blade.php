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
                        <div class="card">
                            <div class="card-body">
                                <a href="{{route('admin.vehicle.create')}}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add Data
                                </a>
                                <div class="table-responsive">
                                    <table class="table table-bordered datatables nowrap" style="width: 100%;">
                                        <thead>
                                        <tr>
                                            <th width="5%" class="text-center"><i class="fas fa-cogs"></i></th>
                                            <th>Photo</th>
                                            <th>Vehicle Number</th>
                                            <th>Vehicle Name</th>
                                            <th>Driver contact</th>
                                            <th>Total Seat</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($vehicle as $item)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.vehicle.edit', $item['id']) }}"
                                                       class="btn btn-info btn-sm">
                                                        <i class="fas fa-pencil fa-fw"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                            id="delete_{{ $item['id'] }}"
                                                            onclick="deleteData('{{ $item['id'] }}')">
                                                        <i class="fas fa-trash fa-fw"></i>
                                                    </button>
                                                </td>
                                                <td><img src="/{{ $item['photo'] }}" alt=""
                                                         class="img-thumbnail" width="200px"></td>
                                                <td>{{ $item['vehicle_number'] }}</td>
                                                <td>{{ $item['vehicle_name'] }}</td>
                                                <td>{{ $item['driver_contact'] }}</td>
                                                <td>{{ $item['total_seat'] }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
                scrollX: true,
                order: [],
                columnDefs: [{
                    targets: [0, 1],
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
                        url: `/admin/vehicle/${id}`,
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
