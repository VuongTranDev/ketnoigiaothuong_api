<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use User;

class ReportAPIController extends Controller
{
    public function statisticMember(Request $request)
    {
        // Lấy tháng và năm từ request, nếu không có sẽ mặc định là tháng và năm hiện tại
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        if (!$start_date || !$end_date) {
            return response()->json([
                'message' => 'Both start_date and end_date are required.',
                'status' => false,
            ], 400);
        }

        // Đếm số lượng người dùng được tạo trong tháng và năm chỉ định
        $startDate = Carbon::parse($start_date)->startOfDay();
        $endDate = Carbon::parse($end_date)->endOfDay();

        // Đếm số lượng người dùng được tạo trong khoảng thời gian này
        $userCount = User::whereBetween('created_at', [$startDate, $endDate])->count();

        // Trả về kết quả dưới dạng JSON
        return response()->json([
            'message' => 'Số lượng người gia nhập',
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'user_count' => $userCount,
            'status' => true,
        ], 200);

    }
}
