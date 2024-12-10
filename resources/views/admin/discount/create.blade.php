@extends('admin.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Thêm giảm giá</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.discount.index') }}" class="btn btn-primary">Trở về</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form action="{{ route('admin.discount.store') }}" method="post" name="discountForm" id="discountForm">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="code">Mã giảm giá <span class="text-danger">*</span></label>
                                        <input type="text" name="code" id="code"
                                            class="form-control @error('code') is-invalid @enderror"
                                            placeholder="Mã giảm giá..." value="{{ old('code') }}">
                                        @error('code')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Tên giảm giá</label>
                                        <input type="text" name="name" id="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="Tên giảm giá..." value="{{ old('name') }}">
                                        @error('name')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="discount_value">Giảm giá <span class="text-danger">*</span></label>
                                        <input type="text" name="discount_value" id="discount_value"
                                            class="form-control @error('discount_value') is-invalid @enderror"
                                            placeholder="Số tiền hoặc phần trăm...">
                                        @error('discount_value')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_uses">Lượt dùng</label>
                                        <input type="text" name="max_uses" id="max_uses" class="form-control"
                                            placeholder="Lượt dùng...">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Lượt dùng cho 1 khách hàng</label>
                                        <input type="text" name="max_uses_user" id="max_uses_user" class="form-control"
                                            placeholder="Lượt dùng cho 1 khách hàng...">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="type">Loại <span class="text-danger">*</span></label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="percent">Phần trăm</option>
                                            <option value="fixed" selected>Giá cố định</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="min_discount_value">Số tiền tối thiểu <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="min_discount_value" id="min_discount_value"
                                            class="form-control" placeholder="Tổng tiền hóa đơn tối thiểu để được giảm...">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="starts_at">Ngày bắt đầu <span class="text-danger">*</span></label>
                                        <input type="text" name="starts_at" id="starts_at"
                                            class="form-control @error('starts_at') is-invalid @enderror"
                                            placeholder="Ngày bắt đầu..." value="{{ old('starts_at') }}">
                                        @error('starts_at')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="expires_at">Ngày kết thúc <span class="text-danger">*</span></label>
                                        <input type="text" name="expires_at" id="expires_at"
                                            class="form-control @error('expires_at') is-invalid @enderror"
                                            placeholder="Ngày kết thúc..." value="{{ old('expires_at') }}">
                                        @error('expires_at')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
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

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Mô tả</label><br>
                                        <textarea name="description" id="description" cols="30" rows="3" class="w-100" placeholder="Mô tả..."></textarea>
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button type="submit" class="btn btn-primary">Thêm</button>
                        <a href="{{ route('admin.discount.index') }}" class="btn btn-outline-dark ml-3">Hủy</a>
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
        $(document).ready(function() {
            $('#starts_at').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });
            $('#expires_at').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });

        });
    </script>
@endsection
