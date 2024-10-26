<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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

        $companies = Companies::with('user', 'rating')->get();

        $format = $companies->map(function($companies) {
            return [
                'id' => $companies->id,
                'representative' => $companies->representative,
                'company_name' => $companies->company_name,
                'short_name' => $companies->short_name,
                'phone_number' =>  $companies->phone_number,
                'slug' => $companies->slug,
                'content' => $companies->content,
                'link' => $companies->link,
                'rating' => $companies->rating,
                'user' => $companies->user,
                'created_at' => $companies->created_at,
                'updated_at' => $companies->updated_at
            ];
        });

        if($companies == null) {
            return response()->json([
                'status' => "Error",
                'message' => 'Company not found!'
            ], 404);
        }
        else
        {
            return response()->json([
                'status' => 'Success',
                'data' => $format,
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'representative' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'short_name' => 'required|string|max:100',
            'phone_number' => 'required|string|max:20',
            'slug' => 'required|string|max:100',
            'content' => 'nullable|string',
            'link' => 'nullable|url',
            'rating' => 'nullable|'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => "Error",
                'message' => 'Validation failed',
                'data' => $validator->errors(),

            ], 400);
        }

        $company = Companies::create([
            'representative' => $request->representative,
            'company_name' => $request->company_name,
            'short_name' => $request->short_name,
            'phone_number' => $request->phone_number,
            'slug' => $request->slug,
            'content' => $request->content,
            'link' => $request->link,
        ]);

        return response()->json([
            'message' => 'Company created successfully',
            'data' => $company,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $companies = Companies::with('user', 'rating')->find($id);

        if($companies == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Company not found!'
            ], 500);
        }

        $format = [
            'id' => $companies->id,
            'representative' => $companies->representative,
            'company_name' => $companies->company_name,
            'short_name' => $companies->short_name,
            'phone_number' =>  $companies->phone_number,
            'slug' => $companies->slug,
            'content' => $companies->content,
            'link' => $companies->link,
            'rating' => $companies->rating,
            'user' => $companies->user,
            'created_at' => $companies->created_at,
            'updated_at' => $companies->updated_at
        ];

        if($format == null) {
            return response()->json([
                'status' => "Error",
                'message' => 'Company not found!'
            ], 404);
        }
        else
        {
            return response()->json([
                'status' => 'Success',
                'data' => $format,
            ], 200);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
