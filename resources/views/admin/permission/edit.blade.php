@extends('admin.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Sửa quyền</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.permission.index') }}" class="btn btn-primary">Trở về</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form action="" method="post" id="updatePermissionForm"
                      name="updatePermissionForm">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row flex-wrap align-items-center">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="permission_name">Tên quyền <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="permission_name" id="permission_name"
                                               class="form-control"
                                               placeholder="Tên quyền..." value="{{ $permission->name }}">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="slug">Slug <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="slug" id="slug"
                                               class="form-control"
                                               placeholder="Slug..." readonly value="{{ $permission->slug }}">
                                        <p></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary mt-md-3" type="submit">Sửa</button>
                                </div>
                            </div>
                        </div>
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
        $("#permission_name").change(function () {
            $("button[type=submit]").prop('disabled', true);
            const element = $(this);
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

        $("#updatePermissionForm").submit(function (event) {
            event.preventDefault();
            let element = $(this).serializeArray();

            $.ajax({
                url: '{{ route('admin.permission.update', $permission->id) }}',
                type: 'put',
                dataType: 'json',
                data: element,
                success: (response) => {
                    if (response.status) {
                        window.location.href = '{{ route('admin.permission.index') }}';
                    } else {
                        const errors = response.errors;
                        $("input[type='text']").removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('');

                        $.each(errors, function (key, value) {
                            $(`#${key}`).addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(value);
                        })
                    }
                },
                error: () => {
                    console.log('Some thing went wrong!');
                }
            })
        });
    </script>
@endsection
