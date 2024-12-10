@extends('admin.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Thêm bài viết</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.blog.index') }}" class="btn btn-primary">Trở về</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form action="" id="createBlogForm">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" class="form-control"
                                               placeholder="Tiêu đề...">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="slug">Slug <span class="text-danger">*</span></label>
                                        <input type="text" name="slug" id="slug" class="form-control"
                                               placeholder="Slug..." readonly>
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="image" class="file-label"><i class="fas fa-upload"></i>Chọn
                                            Ảnh</label>
                                        <input type="file" name="image" id="image" class="form-control">
                                        <!-- Preview Image -->
                                        <img id="imagePreview" class="image-preview" style="display: none;"
                                             alt="Ảnh xem trước">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="content">Nội dung trang <span class="text-danger">*</span></label>
                                        <textarea name="content" id="content" class="summernote" cols="30"
                                                  rows="10"></textarea>
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button class="btn btn-primary" type="submit">Thêm</button>
                        <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-dark ml-3">Hủy</a>
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
        $("#title").change(function () {
            $("button[type=submit]").prop('disabled', true);
            let element = $(this);
            $.ajax({
                url: '{{ route('admin.categories.getSlug') }}',
                type: 'get',
                data: {
                    title: element.val()
                },
                dataType: 'json',
                success: function (response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response['status']) {
                        $("#slug").val(response['slug']);
                    }
                }
            });
        });

        document.getElementById('image').addEventListener('change', function (event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        $('#createBlogForm').submit(function (event) {
            event.preventDefault();

            const form = $(this);
            const formData = new FormData(this); // Tạo đối tượng FormData từ form
            const submitButton = form.find("button[type=submit]");

            // Disable submit button
            submitButton.prop("disabled", true);

            $.ajax({
                url: "{{ route('admin.blog.store') }}",
                type: "POST",
                data: formData,
                processData: false, // Không xử lý dữ liệu
                contentType: false, // Không đặt kiểu Content-Type mặc định
                dataType: "json",
                success: function (response) {
                    // Enable submit button
                    submitButton.prop("disabled", false);

                    if (response.status) {

                        window.location.href = "{{ route('admin.blog.index') }}";

                    } else {
                        // Handle validation errors
                        const errors = response.errors || {};
                        form.find("input, textarea, select").removeClass("is-invalid");
                        form.find(".invalid-feedback").remove();

                        $.each(errors, function (key, value) {
                            const field = form.find(`#${key}`);
                            field.addClass("is-invalid");
                            field.after(`<p class="invalid-feedback">${value}</p>`);
                        });
                    }
                },
                error: function (jqXHR, exception) {
                    console.error("An error occurred: ", exception);
                    // Enable submit button
                    submitButton.prop("disabled", false);
                },
            });
        });

    </script>
@endsection
