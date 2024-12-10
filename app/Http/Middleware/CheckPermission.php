<?php

namespace App\Http\Middleware;

use App\Models\Staff;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $staffInfor = Staff::where('user_id', Auth::id())->first();
        // Kiểm tra người dùng có quyền cụ thể không
        if (!auth()->user() || !$staffInfor->hasPermission($permission)) {
            abort(404, 'Trang không tồn tại hoặc bạn không có quyền truy cập.');
        }

        return $next($request);
    }
}
