@extends('admin.layouts.app')
@section('css')

@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Trang chủ - thống kê</h1>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-6">
                        <div class="small-box card bg-success">
                            <div class="inner">
                                <h3>{{ $totalOrder }}</h3>
                                <p>Tổng số đơn hàng</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="{{ route('admin.orders.index') }}" class="small-box-footer text-dark">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-6">
                        <div class="small-box card bg-blue">
                            <div class="inner">
                                <h3>{{ $totalCustomer }}</h3>
                                <p>Tổng số khách hàng</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ route('admin.user.index') }}" class="small-box-footer text-dark">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-6">
                        <div class="small-box card bg-pink">
                            <div class="inner">
                                <h3>{{ number_format($revenueToday, 0,',', '.') }}</h3>
                                <p>Doanh thu hôm nay</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ route('admin.orders.index') }}" class="small-box-footer text-dark">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-6">
                        <div class="small-box card bg-red">
                            <div class="inner">
                                <h3>{{ number_format($revenueMonth, 0,',', '.') }}</h3>
                                <p>Doanh thu tháng này</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ route('admin.orders.index') }}" class="small-box-footer text-dark">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-6">
                        <div class="small-box card bg-success">
                            <div class="inner">
                                <h3>{{ $currentProductQty }}</h3>
                                <p>Sản phẩm đang bán</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ route('admin.products.index') }}" class="small-box-footer text-dark">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4 class="text-danger">Sản phẩm sắp hết hàng</h4>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th width="80">Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá bán</th>
                                <th>Số lượng</th>
                                <th>SKU</th>
                                <th width="100">Trạng thái</th>
                                <th width="100">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (($runningOutProducts->isNotEmpty()))
                                @foreach ($runningOutProducts as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            @if (!empty($product->product_images->first()))
                                                <img src="{{ asset('/uploads/products/large/' . $product->product_images->first()->image) }}"
                                                     class="img-thumbnail" width="50">
                                            @else
                                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                                     class="img-thumbnail" width="50">
                                            @endif
                                        </td>
                                        <td class="text-primary">{{ $product->title }}</td>
                                        <td>{{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td>{{ $product->qty }} còn lại</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>
                                            @if ($product->status)
                                                <button class="btn btn-sm btn-success"><i class="fas fa-check-circle"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-danger"><i class="fas fa-ban"></i></button>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.products.edit', ['id' => $product->id]) }}"
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0);" onclick="deleteProduct({{ $product->id }})"
                                               class="btn btn-sm
                                                    btn-danger"><i
                                                    class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>Không có sản phẩm!</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        {{ $runningOutProducts->appends($_GET)->links() }}
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4 class="text-success">Sản phẩm đã bán</h4>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th width="80">Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Đã bán</th>
                                <th>Doanh thu</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if ($soldProducts->isNotEmpty())
                                @foreach ($soldProducts as $product)
                                    <tr>
                                        <td>{{ $product->product_id }}</td>
                                        <td>
                                            @if (!empty($product->product->product_images->first()))
                                                <img src="{{ asset('/uploads/products/large/' . $product->product->product_images->first()->image) }}"
                                                     class="img-thumbnail" width="50">
                                            @else
                                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                                     class="img-thumbnail" width="50">
                                            @endif
                                        </td>
                                        <td>{{ $product->product->title }}</td>
                                        <td>{{ $product->total_sold }}</td>
                                        <td>{{ number_format($product->product->price * $product->total_sold, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td>Không có sản phẩm!</td>
                                </tr>
                            @endif

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        {{ $soldProducts->appends($_GET)->links() }}
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4 class="text-success">Thống kê doanh thu
                            <button onclick="window.location.href='{{ route('admin.dashboard') }}'"
                                    class="btn btn-primary btn-sm"><i class="fas fa-sync-alt"></i>
                            </button></h4>
                            <div class="w-50">
                                <form action="{{ route('admin.dashboard') }}" method="get">
                                    @csrf
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label for="start_date">Từ: </label>
                                        <input type="date" required name="start_date" id="start_date"
                                               placeholder="Chọn ngày..."
                                               class="form-control mr-1" value="{{ Request::get('start_date') }}">
                                        <label for="end_date">Đến: </label>
                                        <input type="date" required name="end_date" id="end_date"
                                               placeholder="Chọn ngày..."
                                               class="form-control mr-1" value="{{ Request::get('end_date') }}">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fas fa-filter"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 p-1">
                            <canvas id="orderStatusChart"></canvas>
                        </div>
                        <div class="col-md-8 p-1">
                            <canvas id="monthlyRevenueChart"></canvas>
                        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Nhận dữ liệu từ backend
        const labels = {!! json_encode($labels) !!};
        const data = {!! json_encode($data) !!};

        // Cấu hình Chart.js
        const ctx = document.getElementById('orderStatusChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie', // Loại biểu đồ (có thể thay bằng 'bar', 'line', 'doughnut')
            data: {
                labels: labels, // Tên các trạng thái đơn hàng
                datasets: [{
                    label: 'Số lượng',
                    data: data, // Số lượng đơn hàng theo từng trạng thái
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Trạng thái đơn hàng'
                    }
                }
            }
        });


        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');

            // Dữ liệu từ Blade
            const revenueData = @json($revenueData);

            // Chuyển đổi dữ liệu cho Chart.js
            const labels = revenueData.map(item => item.month); // Tháng
            const revenues = revenueData.map(item => item.total_revenue); // Doanh thu

            // Vẽ biểu đồ
            new Chart(ctx, {
                type: 'bar', // Biểu đồ cột
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: revenues,
                        borderWidth: 1,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Tháng'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Doanh thu (VNĐ)'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection
