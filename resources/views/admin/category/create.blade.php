@extends('admin.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Thêm danh mục</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">Trở về</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form action="" method="post" name="categoryForm" id="categoryForm">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Tên danh mục <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Tên...">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="slug">Slug <span class="text-danger">*</span></label>
                                        <input type="text" readonly name="slug" id="slug" class="form-control"
                                            placeholder="Slug...">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <input type="hidden" name="image_id" id="image_id">
                                        <label for="image">Hình ảnh</label>
                                        <div id="image" class="dropzone dz-clickable">
                                            <div class="dz-message needsclick">
                                                <br>Kéo thả hoặc nhấn vào để tải ảnh lên.<br><br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status">Trạng thái <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Block</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button type="submit" class="btn btn-primary">Thêm</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-dark ml-3">Hủy</a>
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
        $("#categoryForm").submit(function(event) {
            event.preventDefault();
            let element = $(this);
            const submitButton = $("button[type=submit]");
            submitButton.prop('disabled', true);

            $.ajax({
                url: '{{ route('admin.categories.store') }}',
                type: 'POST',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    submitButton.prop('disabled', false);

                    if (response.status) {
                        window.location.href = "{{ route('admin.categories.index') }}";
                        clearError("#name");
                        clearError("#slug");
                    } else {
                        displayError("#name", response.errors.name);
                        displayError("#slug", response.errors.slug);
                    }
                },
                error: function() {
                    console.error('Something went wrong!');
                }
            });
        });

        function displayError(field, error) {
            if (error) {
                $(field).addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error);
            } else {
                clearError(field);
            }
        }

        function clearError(field) {
            $(field).removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
        }

        $("#name").change(function() {
            $("button[type=submit]").prop('disabled', true);
            element = $(this);
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

        Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: "{{ route('temp-images.create') }}",
            maxFiles: 1,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) {
                $("#image_id").val(response.image_id);
                //console.log(response)
            }
        });
    </script>
@endsection
