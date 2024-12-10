@extends('admin.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Sửa nhà cung cấp</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.producer.index') }}" class="btn btn-primary">Trở về</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <form action="" method="post" id="updateProducerForm" name="updateProducerForm">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name">Tên nhà cung cấp <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control"
                                               placeholder="Tên nhà cung cấp..." value="{{ $producer->name }}">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="text" name="email" id="email" class="form-control"
                                               placeholder="Email..." value="{{ $producer->email }}">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone">Số điện thoại <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" id="phone" class="form-control"
                                               placeholder="Số điện thoại..." value="{{ $producer->phone }}">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="address">Địa chỉ <span class="text-danger">*</span></label>
                                        <input type="text" name="address" id="address" class="form-control"
                                               placeholder="Địa chỉ..." value="{{ $producer->address }}">
                                        <p></p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="pb-5 pt-3">
                        <button class="btn btn-primary" type="submit">Sửa</button>
                        <a href="{{ route('admin.producer.index') }}" class="btn btn-outline-dark ml-3">Hủy</a>
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
        submitUpdateForm("#updateProducerForm", "{{ route('admin.producer.update', $producer->id) }}", "{{ route('admin.producer.index') }}");
    </script>
@endsection
