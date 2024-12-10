<?php

namespace App\Http\Controllers\admin;

use App\Charts\MonthlySaleChart;
use App\Charts\OrderStatusChart;
use App\Charts\UserAccountChart;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ReceiptDetail;
use App\Models\TempImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentDay = Carbon::now()->day;

        // Delete temp image
        $dayBeforeToday = Carbon::now()->subDays(1)->format('Y-m-d');
        $tempImages = TempImage::where('created_at', '<=', $dayBeforeToday)->get();
        foreach ($tempImages as $tempImage) {
            $path = public_path('/temp/' . $tempImage->name);
            // delete image in temp folder
            if (File::exists($path)) {
                File::delete($path);
            }
            TempImage::find($tempImage->id)->delete();
        }

        $totalCustomer = User::where('role', 1)->count();
        $totalOrder = Order::count();
        $revenueToday = Order::whereDate('paid_date', today())->where('order_status', 'delivered')->sum('grand_total');
        $revenueMonth = Order::whereMonth('paid_date', $currentMonth)
            ->where('order_status', 'delivered')
            ->sum('grand_total');
        $currentProductQty = Product::count();
        $runningOutProducts = Product::where('qty', '<', 10)->orderBy('qty', 'asc')
            ->paginate(5, ['*'], 'running_out_page')
            ->appends(request()->except('sold_page'));
        $soldProducts = ReceiptDetail::whereColumn('remaining_qty', '<>', 'import_qty')->
            select('product_id', DB::raw('SUM(COALESCE(import_qty, 0) - COALESCE(remaining_qty, 0)) as total_sold'))
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->with('product')
            ->paginate(5, ['*'], 'sold_page')
            ->appends(request()->except('running_out_page'));
        // Đếm số lượng đơn hàng theo trạng thái
        $orderStatusCounts = DB::table('orders')
            ->select('order_status', DB::raw('COUNT(*) as count'))
            ->groupBy('order_status')
            ->pluck('count', 'order_status');
        // Chuyển dữ liệu sang mảng
        $labels = $orderStatusCounts->keys(); // Các trạng thái đơn hàng
        $data = $orderStatusCounts->values(); // Số lượng tương ứng

        $startDate = $request->input('start_date', Carbon::now()->subMonth(12)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $revenueData = Order::selectRaw('DATE_FORMAT(delivered_date, "%Y-%m") as month, SUM(grand_total) as total_revenue')
            ->where('order_status', 'delivered') // Chỉ tính đơn đã hoàn thành
            ->whereBetween('delivered_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $dataCompact = [
            'totalCustomer' => $totalCustomer,
            'totalOrder' => $totalOrder,
            'revenueToday' => $revenueToday,
            'revenueMonth' => $revenueMonth,
            'currentProductQty' => $currentProductQty,
            'runningOutProducts' => $runningOutProducts,
            'soldProducts' => $soldProducts,
            'labels' => $labels,
            'data' => $data,
            'revenueData' => $revenueData,
        ];

        return view('admin.dashboard', $dataCompact);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    private function MonthlySaleChart()
    {
    }
}
