<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $com = Comments::with('news', 'users')->get();
        $formatcomment = $com->map(function($com) {
            return[
                'id' => $com->id,
                'content' => $com->content,
                'new' =>$com->news,
                'users' => $com->users
            ];
        }) ;

        return response()->json([
            'message' => 'Successfully request',
            'data' => $formatcomment,
            'status' => true,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'content'=>'required|string',
            'new_id' => 'required|exists:news,id',
            'user_id' => 'required|exists:users,id',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'message' => 'Validation failed',
                'data' => $validator->errors(),
                'status' => false,
            ], 400);
        }
        // $existingComment = Comments::where('user_id', $request->input('user_id'))
        // ->where('new_id', $request->input('new_id')) // Nếu bạn cũng muốn kiểm tra theo new_id
        // ->first();

        // if ($existingComment) {
        //     return response()->json([
        //         'message' => 'User has already commented on this news',
        //         'status' => false,
        //     ], 409); // 409 Conflict
        // }
        $comment = Comments::create([
            'content' => $request->input('content'),
            'new_id' => $request->input('new_id'),
            'user_id' => $request->input('user_id'),
        ]) ;

        return response()->json([
            'message' => 'Comments created',
            'status' => true,
            'data' => $comment
        ],201);
    }

    public function show(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $comment = Comments::find($id);

        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found',
                'status' => false], 404);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'status' => false,
            ], 400);
        }

        $comment->update([
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Comment updated successfully',
            'data' => $comment,
            'status' => true,
        ], 200);
    }


    public function destroy(string $id)
    {
        $comment = Comments::find($id) ;
        if(!$comment)
        {
            return response()->json([
                'message' => 'Comment not found',
                'status' => false,
            ], 404);
        }
        else
        {
            $comment->delete();
        }
        return response()->json([
            'message' => 'Comment deleted successfully',
            'status' => true,
        ], 200);

    }

    public function countCommentInNews(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'new_id' => 'required|exists:news,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'status' => false,
            ], 400);
        }

        $newsId = $request->input('new_id');
        $countAllComments = Comments::where('new_id', $newsId)->count();
        return response()->json([
            'message' => 'Số lượt comments của bài viết',
            'news_count' => $countAllComments,
            'status' => true,
        ], 200);

    }
}
