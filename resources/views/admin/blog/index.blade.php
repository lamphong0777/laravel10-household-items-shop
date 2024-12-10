@extends('admin.layouts.app')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Quản lý bài viết</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">Thêm</a>
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
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input type="text" name="table_search" class="form-control float-right"
                            placeholder="Tìm kiếm...">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th width="80">Ảnh</th>
                                <th>Tiêu đề</th>
                                <th>Slug</th>
                                <th>Tác giả</th>
                                <th width="110">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($blogs->isNotEmpty())
                            @foreach ($blogs as $blog)
                            <tr>
                                <td>{{ $blog->id }}</td>
                                <td>
                                    @if (!empty($blog->image))
                                    <img src="{{ asset('/uploads/blogs/' . $blog->image) }}"
                                    class="img-thumbnail" width="50">
                                    @else
                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                    class="img-thumbnail" width="50">
                                    @endif
                                </td>
                                <td>{{ $blog->title }}</td>
                                <td>{{ $blog->slug }}</td>
                                <td>{{ $blog->staff->name }}</td>
                                <td>
                                    <a href="{{ route('admin.blog.edit', $blog->id) }}"
                                    class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0);" onclick="deleteRecord({{ $blog->id }},
                                        '{{ route('admin.blog.destroy', 'ID') }}',
                                        '{{ route('admin.blog.index') }}'
                                        )"
                                        class="btn btn-sm
                                        btn-danger"><i
                                    class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td>Không có bảng ghi nào.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination m-0 float-right">
                        {{ $blogs->appends($_GET)->links() }}
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