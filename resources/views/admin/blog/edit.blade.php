@extends('admin.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Sửa bài viết</h1>
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
                <form action="" id="updateBlogForm">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" class="form-control"
                                               placeholder="Tiêu đề..." value="{{ $blog->title }}">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="slug">Slug <span class="text-danger">*</span></label>
                                        <input type="text" name="slug" id="slug" class="form-control"
                                               placeholder="Slug..." readonly value="{{ $blog->slug }}">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <img src="{{ asset('/uploads/blogs/'.$blog->image) }}" alt="" width="100%">
                                    </div>
                                </div>
                                <div class="col-md-6">
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
                                                  rows="10">{{ $blog->content }}</textarea>
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button class="btn btn-primary" type="submit">Sửa</button>
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

        $('#updateBlogForm').submit(function (event) {
            event.preventDefault();

            const form = $(this);
            const formData = new FormData(this); // Tạo FormData từ form
            const submitButton = form.find("button[type=submit]");

            // Append _method nếu dùng PUT
            formData.append('_method', 'PUT');

            // Disable submit button
            submitButton.prop("disabled", true);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('admin.blog.update', $blog->id) }}",
                type: "POST", // Sử dụng POST thay vì PUT
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (response) {
                    submitButton.prop("disabled", false);
                    if (response.status) {
                        window.location.href = "{{ route('admin.blog.index') }}";
                    } else {
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
                    submitButton.prop("disabled", false);
                },
            });
        });
    </script>
@endsection
