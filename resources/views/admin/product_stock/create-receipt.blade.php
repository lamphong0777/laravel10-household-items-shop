@extends('admin.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Thêm hóa đơn nhập hàng</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.product-stocks.receipt') }}" class="btn btn-primary">Trở về</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form action="" method="post" id="createReceiptForm" name="createReceiptForm">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="producer_id">Nhà cung cấp <span class="text-danger">*</span></label>
                                        <select id="producer_id" name="producer_id" required class="form-control">
                                            <option value="">---Chọn nhà cung cấp---</option>
                                            <!-- Add producer options dynamically -->
                                            @if($producers->isNotEmpty())
                                                @foreach($producers as $producer)
                                                    <option value="{{ $producer->id }}">{{ $producer->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="staff_id">Nhân viên lập hóa đơn</label>
                                        <input type="text" name="staff_id" id="staff_id" class="form-control"
                                               value="{{ Auth::user()->name }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="import_date">Ngày nhập hàng <span
                                                class="text-danger">*</span></label>
                                        <input type="date" id="import_date" name="import_date" required
                                               class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="notes">Ghi chú</label>
                                        <input type="text" id="notes" name="notes" required
                                               class="form-control" placeholder="Ghi chú (tùy chọn)">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <template id="productTemplate">
                                            <div class="product-item row align-items-center">
                                                <div class="col-md-3">
                                                    <label for="product_id">Sản phẩm <span class="text-danger">*</span></label>
                                                    <select name="products[INDEX][product_id]" required class="form-control">
                                                        <option value="">---Chọn sản phẩm---</option>
                                                        @if($products->isNotEmpty())
                                                            @foreach($products as $product)
                                                                <option value="{{ $product->id }}">{{ $product->title }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="import_qty">Số lượng nhập <span class="text-danger">*</span></label>
                                                    <input type="number" name="products[INDEX][import_qty]" min="1" required class="form-control" value="1">
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="import_price">Giá nhập <span class="text-danger">*</span></label>
                                                    <input type="number" name="products[INDEX][import_price]" min="0" step="1000" required class="form-control" value="0">
                                                </div>
                                                <div class="col-md-3 d-flex align-items-center">
                                                    <button type="button" class="remove-btn btn btn-sm btn-danger ms-md-2 mt-4">
                                                        <i class="fas fa-minus"></i> Xóa
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="products">
                                        <h3>Nhập sản phẩm</h3>
                                        <div id="productList">
                                            <!-- Product items will be added dynamically -->
                                        </div>
                                        <button type="button" id="addProductBtn" class="btn-sm btn-success mt-2">
                                            <i class="fas fa-plus"></i>
                                            Thêm sản phẩm
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button class="btn btn-primary" type="submit">Thêm</button>
                        <a href="{{ route('admin.product-stocks.receipt') }}" class="btn btn-outline-dark ml-3">Hủy</a>
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
    <script src="{{ asset('admin-assets/js/custom.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addProductBtn = document.getElementById('addProductBtn'); // Nút thêm sản phẩm
            const productList = document.getElementById('productList'); // Vùng chứa danh sách sản phẩm
            const productTemplate = document.getElementById('productTemplate').content; // Template sản phẩm

            addProductBtn.addEventListener('click', () => {
                const newProduct = productTemplate.cloneNode(true);

                // Tính chỉ số sản phẩm mới
                const index = productList.children.length;

                // Cập nhật name của các trường input/select
                newProduct.querySelectorAll('select, input').forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace('INDEX', index)); // Thay thế "INDEX" bằng chỉ số thực
                    }
                });

                productList.appendChild(newProduct);
            });

            // Xóa sản phẩm
            productList.addEventListener('click', (event) => {
                if (event.target.closest('.remove-btn')) {
                    event.target.closest('.product-item').remove();
                }
            });
        });


        createFormSubmit("#createReceiptForm", {
            url: "{{ route('admin.product-stocks.receipt.store') }}",
            successRedirect: "{{ route('admin.product-stocks.receipt') }}",
            onSuccess: function (response) {
                console.log("Goods receipt created successfully!", response);
            },
            onError: function (jqXHR, exception) {
                console.error("Failed to create goods receipt", exception);
            },
        });

    </script>
@endsection
