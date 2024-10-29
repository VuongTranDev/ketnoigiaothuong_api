<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Companies;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    public function index(Request $request){

        $validator = Validator::make($request->all(), [
            'page' => 'min:1|max:1000|integer',
            'size' => 'min:1|max:2000|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'data' => $validator->errors(),
                'status' => false,

            ], 400);
        }
        $companies = Companies::query();
        
    }
}
