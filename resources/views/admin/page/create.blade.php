@extends('admin.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Thêm trang</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.page.index') }}" class="btn btn-primary">Trở về</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form action="" id="createPageForm">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Tên <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Tên...">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email">Slug <span class="text-danger">*</span></label>
                                        <input type="text" name="slug" id="slug" class="form-control"
                                            placeholder="Slug...">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="content">Nội dung trang <span class="text-danger">*</span></label>
                                        <textarea name="content" id="content" class="summernote" cols="30" rows="10"></textarea>
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button class="btn btn-primary" type="submit">Thêm</button>
                        <a href="{{ route('admin.page.index') }}" class="btn btn-outline-dark ml-3">Hủy</a>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('js')
    <script>
        // get slug
        $("#name").change(function() {
            $("button[type=submit]").prop('disabled', true);
            let element = $(this);
            $.ajax({
                url: '{{ route('admin.categories.getSlug') }}',
                type: 'get',
                data: {
                    title: element.val()
                },
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response['status']) {
                        $("#slug").val(response['slug']);
                    }
                }
            });
        });

        $('#createPageForm').submit(function(e) {
            e.preventDefault();
            let element = $(this).serializeArray();
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route('admin.page.store') }}',
                type: 'post',
                dataType: 'json',
                data: element,
                success: (response) => {
                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"]) {
                        window.location.href = '{{ route('admin.page.index') }}'
                    } else {
                        const errors = response["errors"];
                        $("input[type='text'], input[type='password']").removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('');
                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(value);
                        });
                    }
                },
                error: () => {
                    console.log("Some thing went wrong!");
                }
            })
        })
    </script>
@endsection
