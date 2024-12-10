@extends('admin.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Thêm sản phẩm</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Trở về</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <form action="" method="post" name="productForm" id="productForm" enctype='multipart/form-data'>
                @csrf
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="title">Tên sản phẩm <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="title" id="title" class="form-control"
                                                       placeholder="Tên sản phẩm...">
                                                <p class="error"></p>
                                            </div>
                                            <div class="mb-3">
                                                <label for="slug">Slug <span class="text-danger">*</span></label>
                                                <input type="text" name="slug" id="slug" class="form-control"
                                                       placeholder="Slug..." readonly>
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="description">Mô tả sản phẩm</label>
                                                <textarea name="description" id="description" cols="30" rows="10"
                                                          class="summernote"
                                                          placeholder="Mô tả..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Ảnh sản phẩm (một hoặc nhiều hình ảnh)</h2>
                                    <div id="image" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick">
                                            <br>Thả tập tin vào đây hoặc bấm vào để tải lên.<br><br>
                                        </div>
                                    </div>
                                    <div class="product-gallery mt-5 row" id="product-gallery"></div>
                                </div>

                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Giá sản phẩm</h2>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="price">Giá bán <span class="text-danger">*</span></label>
                                                <input type="text" name="price" id="price" class="form-control"
                                                       placeholder="Giá bán chính thức...">
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="compare_price">Giá giảm <span class="text-danger">*</span></label>
                                                <input type="text" name="compare_price" id="compare_price"
                                                       class="form-control" placeholder="Giá giảm...">
                                                <span class="text-muted mt-3">
                                                    Để hiển thị mức giá giảm. Nhập giá trị thấp hơn vào Giá bán.
                                                </span>
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Quản lý kho</h2>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sku">SKU (Stock Keeping Unit) <span class="text-danger">*</span></label>
                                                <input type="text" name="sku" id="sku" class="form-control"
                                                       placeholder="sku...">
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="barcode">Barcode <span class="text-danger">*</span></label>
                                                <input type="text" name="barcode" id="barcode" class="form-control"
                                                       placeholder="Barcode...">
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="hidden" name="track_qty" value="No">
                                                    <input class="custom-control-input" type="checkbox" id="track_qty"
                                                           name="track_qty" checked value="Yes">
                                                    <label for="track_qty" class="custom-control-label">Theo dõi số lượng kho</label>
                                                    <p class="error"></p>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <input type="number" readonly value="0" min="0"
                                                       name="qty" id="qty" class="form-control"
                                                       placeholder="Qty">
                                                <span class="text-muted mt-3">
                                                    Số lượng sản phẩm sẽ tăng tự động khi nhập kho.
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Trạng thái</h2>
                                    <div class="mb-3">
                                        <select name="status" id="status" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Block</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="h4  mb-3">Danh mục sản phẩm</h2>
                                    <div class="mb-3">
                                        <label for="category">Danh mục <span class="text-danger">*</span></label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="">---Chọn danh mục---</option>
                                            @if ($categories->count() > 0)
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p class="error"></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="category">Danh mục con  (tùy chọn)</label>
                                        <select name="sub_category" id="sub_category" class="form-control">
                                            <option value="">---Chọn danh mục con---</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Thương hiệu (tùy chọn)</h2>
                                    <div class="mb-3">
                                        <select name="brand" id="brand" class="form-control">
                                            <option value="">---Chọn thương hiệu---</option>
                                            @if ($brands->count() > 0)
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Sản phẩm nổi bật</h2>
                                    <div class="mb-3">
                                        <select name="is_featured" id="is_featured" class="form-control">
                                            <option value="No">Không</option>
                                            <option value="Yes">Có</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pb-5 pt-3">
                        <button type="submit" class="btn btn-primary">Thêm</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark ml-3">Hủy</a>
                    </div>
                </div>
            </form>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('js')
    <script>
        // get subcategory
        $("#category").change(function () {
            let category_id = $(this).val();
            $.ajax({
                url: '{{ route('admin.products.subcategory.index') }}',
                type: 'get',
                data: {
                    category_id: category_id
                },
                dataType: 'json',
                success: function (response) {
                    // console.log(response)
                    $("#sub_category").find("option").not(":first").remove();
                    $.each(response["subCategories"], function (key, item) {
                        $("#sub_category").append(
                            `<option value='${item.id}' >${item.name}</option>`)
                    });
                },
                error: function () {
                    console.log('Something went wrong!')
                }
            })
        })

        //get slug
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

        //store product
        $("#productForm").submit(function (event) {
            event.preventDefault();
            let formArray = $(this).serializeArray();

            $.ajax({
                url: '{{ route('admin.products.store') }}',
                type: 'post',
                data: formArray,
                dataType: 'json',
                success: function (response) {
                    if (response['status']) {
                        console.log('success');
                        window.location.href = "{{ route('admin.products.index') }}"
                    } else {
                        let errors = response['errors'];

                        $('.error').removeClass('invalid-feedback').html('');
                        $("input[type='text'], input[type='number'], select").removeClass('is-invalid');

                        $.each(errors, function (key, value) {
                            $(`#${key}`).addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(value);
                        })
                    }
                },
                error: function () {
                    console.log('Something went wrong!')
                }
            })
        });


        // dropzone handle image upload
        Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            // init: function() {
            //     this.on('addedfile', function(file) {
            //         if (this.files.length > 1) {
            //             this.removeFile(this.files[0]);
            //         }
            //     });
            // },
            url: "{{ route('temp-images.create') }}",
            maxFiles: 10,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (file, response) {
                // $("#image_id").val(response.image_id);
                console.log(response)
                let html = `<div class="col-md-3" id="image-row-${response.image_id}"><div class="card">
                    <input type="hidden"  name="image_array[]" value="${response.image_id}">
                    <img src="${response.ImagePath}" class="card-img-top" alt="">
                    <div class="card-body">
                        <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
                    </div>
                    </div></div>`;
                $("#product-gallery").append(html);
            },
            complete: function (file) {
                this.removeFile(file);
            }
        });

        function deleteImage(id) {
            $("#image-row-" + id).remove();
        }
    </script>
@endsection
