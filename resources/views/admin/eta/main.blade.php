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
                                <div class="table-responsive">
                                    <table class="table table-bordered datatables nowrap" style="width: 100%;">
                                        <thead>
                                        <tr>
                                            <th width="5%" class="text-center"><i class="fas fa-cogs"></i></th>
                                            <th>Area Name</th>
                                            <th>trip 1 ETA</th>
                                            <th>trip 2 ETA</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($etas as $eta)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.eta.edit', $eta->id) }}"
                                                       class="btn btn-info btn-sm">
                                                        <i class="fas fa-pencil fa-fw"></i>
                                                    </a>
                                                </td>
                                                <td>{{ $eta->area_name }}</td>
                                                <td>{{ $eta->trip_1 }}</td>
                                                <td>{{ $eta->trip_2 }}</td>
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

    </script>
@endsection