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
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h1 class="card-title">Data</h1>
                                    </div>
                                    <div>
                                        <a href="/admin/pages/add" class="btn btn-primary"><i class="fas fa-plus"></i> Add
                                            New Data</a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered datatables">
                                        <thead>
                                            <tr>
                                                <th><i class="fas fa-cogs"></i></th>
                                                <th style="min-width: 150px;">Slug</th>
                                                <th style="min-width: 100px;">Page Title</th>
                                                <th style="min-width: 150px;">Type</th>
                                                <th style="min-width: 150px;">Order Number</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pages as $page)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('admin.pages.edit', $page->id) }}"
                                                            class="btn btn-info btn-sm">
                                                            <i class="fas fa-pencil fa-fw"></i>
                                                        </a>
                                                        @if (!in_array($page->id, [1, 2]))
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                id="delete_{{ $page->id }}"
                                                                onclick="deleteData('{{ $page->id }}')">
                                                                <i class="fas fa-trash fa-fw"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                    <td>{{ $page->slug }}</td>
                                                    <td>{{ $page->page_title }}</td>
                                                    <td>{{ $page->type }}</td>
                                                    <td>{{ $page->orderNumber }}</td>
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
                order: [

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
                        url: `/admin/pages/delete/${id}`,
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
