@extends('admin.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Sửa phí vận chuyển</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.shipping.index') }}" class="btn btn-primary">Trở về</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form action="{{ route('admin.shipping.update', ['id' => $shipping_charge->id]) }}" method="post"
                    id="brandForm" name="brandForm">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row flex-wrap align-items-center">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="province_id">Tỉnh thành <span class="text-danger">*</span></label>
                                        <select id="province_id" name="province_id"
                                            class="form-control @error('province_id') is-invalid @enderror">
                                            <option value="">--Chọn tỉnh thành--</option>
                                            @if ($provinces->isNotEmpty())
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->id }}"
                                                        @if ($province->id == $shipping_charge->province_id) selected @endif>
                                                        {{ $province->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('province_id')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="shipping_cost">Phí vận chuyển <span class="text-danger">*</span></label>
                                        <input type="text" name="shipping_cost" id="shipping_cost"
                                            class="form-control @error('shipping_cost') is-invalid @enderror"
                                            placeholder="Phí vận chuyển"
                                            value="{{ old('shipping_cost', $shipping_charge->shipping_cost) }}">
                                        @error('shipping_cost')
                                            <p class="invalid-feedback">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button class="btn btn-primary" type="submit">Sửa</button>
                        <a href="{{ route('admin.shipping.index') }}" class="btn btn-outline-dark ml-3">Hủy</a>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
