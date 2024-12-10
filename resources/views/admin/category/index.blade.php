@extends('admin.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid my-2">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Quản lý danh mục</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Thêm</a>
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
                            <button onclick="window.location.href='{{ route('admin.categories.index') }}'"
                                class="btn btn-primary btn-sm"><i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="card-tools">
                            <form action="{{ route('admin.categories.index') }}" method="get">
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
                                    <th width="80">Ảnh</th>
                                    <th>Tên danh mục</th>
                                    <th>Slug</th>
                                    <th width="100">Trạng thái</th>
                                    <th width="100">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($categories))
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>
                                                @if (!empty($category->image))
                                                    <img src="{{ asset('/uploads/category/' . $category->image) }}"
                                                        class="img-thumbnail" width="50">
                                                @else
                                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                                        class="img-thumbnail" width="50">
                                                @endif
                                            </td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->slug }}</td>
                                            @if ($category->status == 1)
                                                <td>
                                                    <span class="btn-sm btn-success">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                </td>
                                            @else
                                                <td>
                                                    <span class="btn-sm btn-danger">
                                                        <i class="fas fa-ban"></i>
                                                    </span>
                                                </td>
                                            @endif
                                            <td>
                                                <a href="{{ route('admin.categories.edit', ['id' => $category->id]) }}"
                                                    class="btn btn-sm btn-info"><i class="fas fa-edit"></i>
                                                </a>
                                                <a href="javascript:void(0);" onclick="deleteCategory({{ $category->id }})"
                                                    class="btn btn-sm
                                                    btn-danger"><i
                                                        class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">Records not found!</td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        <ul class="pagination pagination m-0 float-right">
                            {{ $categories->appends($_GET)->links() }}
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
    <script>
        function deleteCategory(id) {
            let url = '{{ route('admin.categories.destroy', 'ID') }}'
            let newUrl = url.replace("ID", id);
            if (confirm("Bạn chắc chắn! Bạn muốn xóa danh mục này.")) {
                $.ajax({
                    url: newUrl,
                    type: 'delete',
                    data: {},
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response['status']) {
                            window.location.href = "{{ route('admin.categories.index') }}";
                        }
                    }
                });
            }
        }
    </script>
@endsection