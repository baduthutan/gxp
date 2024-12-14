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
                                <form id="form" method="POST" action="/admin/pages/store">
                                    @csrf
                                    <div class="form-group">
                                        <label for="slug">Slug</label>
                                        <input type="text" class="form-control" id="slug" name="slug"
                                            value="{{ old('slug') }}" minlength="3" maxlength="100" />
                                    </div>
                                    <div class="form-group">
                                        <label for="page_title">Page Title</label>
                                        <input type="text" class="form-control" id="page_title" name="page_title"
                                            value="{{ old('page_title') }}" minlength="3" maxlength="100" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="page_content">Page Content</label>
                                        <textarea class="form-control" id="page_content" name="page_content">{{ old('page_content') }}</textarea>
                                    </div>
                                    <div class="mb-1 row">
                                        <div class="col-sm-2">
                                            <label class="col-form-label fw-bolder" for="type">Type</label>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="type"
                                                           id="type2"
                                                           value="header" checked="checked"/>
                                                    <label class="form-check-label" for="type2">Header</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="type"
                                                           id="type1"
                                                           value="footer" />
                                                    <label class="form-check-label" for="type1"
                                                    >Footer</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-1 row">
                                        <div class="col-sm-2">
                                            <label class="col-form-label fw-bolder" for="is_dropdown">Is it
                                                DropDown menu ?</label>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="is_dropdown"
                                                           id="is_dropdown2"
                                                           value="1" checked="checked"/>
                                                    <label class="form-check-label" for="is_dropdown2">Active</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="is_dropdown"
                                                           id="is_dropdown1"
                                                           value="0" />
                                                    <label class="form-check-label" for="is_dropdown1"
                                                    >Not Active</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-1 row" id="parent_menu_input">
                                        <div class="col-sm-2">
                                            <label class="col-form-label fw-bolder" for="parent_page_id">Parent
                                                menu</label>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="input-group input-group-merge">
                                               <select class="form-control" name="parent_page_id">
                                                   <option value=""></option>
                                                   @foreach($dropdown as $item)
                                                       <option value="{{$item->id}}">{{$item->page_title}}</option>
                                                   @endforeach
                                               </select>
                                            </div>
                                            <small class="text-danger">This is used, when the dropdown isn't activated
                                                !</small>
                                        </div>
                                    </div>

                                    <div class="mb-1 row">
                                        <div class="col-sm-2">
                                            <label class="col-form-label fw-bolder" for="url">Url</label>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="edit"></i></span>
                                                <input type="text" class="form-control" name="url"
                                                       placeholder="Url"
                                                       id="url"
                                                       value="{{ empty(old('url')) ? "" : old('url')
                                                       }}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-1 row">
                                        <div class="col-sm-2">
                                            <label class="col-form-label fw-bolder" for="orderNumber">Order
                                                number</label>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="input-group input-group-merge">
                                                <span class="input-group-text"><i data-feather="edit"></i></span>
                                                <input type="text" class="form-control" name="orderNumber"
                                                       placeholder="order_number"
                                                       id="orderNumber"
                                                       value="{{ empty(old('orderNumber')) ? 0 : old('orderNumber')
                                                       }}" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-save fa-fw"></i> Save
                                        </button>
                                        <a href="{{ route('admin.pages') }}" class="btn btn-dark btn-block">
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
    <script src="https://cdn.tiny.cloud/1/y1lbyegpvnufywjd7k7c8p0drp9rfup9gymycg4n6evr6nfs/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        const token = $("meta[name='csrf-token']").attr("content");

        $(document).ready(() => {
            tinymce.init({
                selector: 'textarea',
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            });
        })
    </script>
@endsection
