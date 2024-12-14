@extends('layouts.frontend.app')
@section('page_content')
    <section id="home" class="home d-flex align-items-center" data-scroll-index="0">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 my-5">
                    <h1 class="mt-5">{{ $isis->page_title }}</h1>
                    <p>{!! $isis->page_content !!}</p>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('vitamin')
@endsection
