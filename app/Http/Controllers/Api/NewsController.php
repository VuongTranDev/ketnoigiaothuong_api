<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function statistics()
    {
        $currentUser = auth()->user();
        if ($currentUser->role->name !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized: You do not have permission to access this resource.',
                'status' => false
            ], 403);
        }

        $totalPosts = News::count();

        $postsByUser  = News::select('user_id', DB::raw('count(*) as post_count'))
            ->groupBy('user_id')
            ->get();

        return response()->json([
            'total_posts' => $totalPosts,
            'posts_by_user' => $postsByUser ,
        ], 200);
    }
}
