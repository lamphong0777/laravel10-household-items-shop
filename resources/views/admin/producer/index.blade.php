@extends('admin.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Quản lý nhà cung cấp</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.producer.create') }}" class="btn btn-primary">Thêm</a>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                @include('admin.message')
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <button onclick="window.location.href='{{ route('admin.producer.index') }}'"
                                    class="btn btn-primary btn-sm"><i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="card-tools">
                            <form action="{{ route('admin.producer.index') }}" method="get">
                                <div class="input-group input-group" style="width: 250px;">

                                    <input type="text" name="searchText" class="form-control float-right"
                                           placeholder="Tìm kiếm..." value="{{ Request::get('searchText') }}">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th>Tên nhà cung cấp</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Địa chỉ</th>
                                <th width="100">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if ($producers->count() > 0)
                                @foreach ($producers as $producer)
                                    <tr>
                                        <td>{{ $producer->id }}</td>
                                        <td>{{ $producer->name }}</td>
                                        <td>{{ $producer->email }}</td>
                                        <td>{{ $producer->phone }}</td>
                                        <td>{{ $producer->address }}</td>
                                        <td>
                                            <a href="{{ route('admin.producer.edit', $producer->id) }}"
                                               class="btn btn-sm btn-info"><i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0);" onclick="deleteRecord({{ $producer->id }},
                                            '{{ route('admin.producer.destroy', 'ID') }}',
                                            '{{ route('admin.producer.index') }}'
                                            )"
                                               class="btn btn-sm
                                                    btn-danger"><i
                                                    class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">Không có bản ghi nào!</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        <ul class="pagination pagination m-0 float-right">
                            {{ $producers->appends($_GET)->links() }}
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('js')
    <script src="{{ asset('admin-assets/js/custom.js') }}"></script>
@endsection
