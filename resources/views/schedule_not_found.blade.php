@extends('layouts.frontend.app')
@section('page_content')
    <section id="home" class="home d-flex align-items-center" data-scroll-index="0">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 my-5 text-center">
                    <img src="img/undraw_empty_re_opql.svg" alt="Not Found" class="mx-auto"
                        style="width: 400px;">
                    <h1 class="text-center mt-5">Schedule Not Found</h1>
                    <h5>Schedule only available max 2 day before
                        departure. Please contact admin for other schedule.</h5>
                    <a href="/" class="btn btn-success">
                        <i class="fas fa-home fa-fw"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('vitamin')
@endsection
