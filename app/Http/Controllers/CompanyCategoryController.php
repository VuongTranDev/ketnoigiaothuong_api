<?php

namespace App\Http\Controllers;

use App\Models\CompanyCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CompanyCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companycategory = CompanyCategory::with('companies', 'categories')->get();

        $format = $companycategory->map(function ($companycategory) {
            return [
                'id' => $companycategory->id,
                'category' => $companycategory->categories,
                'company' => $companycategory->companies,
                'description' => $companycategory->description,
                'created_at' => $companycategory->created_at,
                'updated_at' => $companycategory->updated_at
            ];
        });

        if ($companycategory == null) {
            return response()->json([
                'status' => "Error",
                'message' => 'Company category not found!'
            ], 404);
        } else {
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
            'cate_id' => 'required',
            'company_id' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => "Error",
                'message' => 'Validation failed',
                'data' => $validator->errors(),

            ], 400);
        }

        $companycategory = CompanyCategory::create([
            'cate_id' => $request->cate_id,
            'company_id' => $request->company_id,
            'description' => $request->description,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $format = [
            'id' => $companycategory->id,
            'cate_id' => $companycategory->categories,
            'company_id' => $companycategory->companies,
            'description' => $companycategory->description,
            'created_at' => $companycategory->created_at,
            'updated_at' => $companycategory->updated_at
        ];

        return response()->json([
            'status' => 'Success',
            'message' => 'Company category created successfully',
            'data' => $format,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $companycategory = CompanyCategory::with('companies', 'categories')->find($id);

        if ($companycategory == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Company category not found!'
            ], 500);
        }

        $format = [
            'id' => $companycategory->id,
            'cate_id' => $companycategory->categories,
            'company_id' => $companycategory->companies,
            'description' => $companycategory->description,
            'created_at' => $companycategory->created_at,
            'updated_at' => $companycategory->updated_at
        ];

        if ($format == null) {
            return response()->json([
                'status' => "Error",
                'message' => 'Company category not found!'
            ], 404);
        } else {
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
        try {
            // Validate data
            $validatedData = $request->validate([
                'cate_id' => 'required',
                'company_id' => 'required',
                'description' => 'required',
            ]);

            $companycategory = CompanyCategory::findOrFail($id);
            $companycategory->update($validatedData);

            $format = [
                'id' => $companycategory->id,
                'cate_id' => $companycategory->categories,
                'company_id' => $companycategory->companies,
                'description' => $companycategory->description,
                'created_at' => $companycategory->created_at,
                'updated_at' => $companycategory->updated_at
            ];

            return response()->json([
                'status' => "Success",
                'message' => "Update company category success",
                'data' => $format
            ]);
        } catch (ValidationException $e) {
            // Handling authentication errors
            return response()->json([
                'status' => 'Error',
                'message' => $e->validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (ModelNotFoundException $e) {
            // Handling cases where the company category is not found
            return response()->json([
                'status' => 'Error',
                'message' => 'Company category not found'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            // Handle other errors (e.g. database errors)
            return response()->json([
                'status' => 'Error',
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $companycategory = CompanyCategory::findOrFail($id);
            $companycategory->delete();

            return response()->json([
                'status' => "Success",
                'message' => 'Company category deleted successfully'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            // Handling cases where the company category is not found
            return response()->json([
                'status' => 'Error',
                'message' => 'Company category not found'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            // Handle other errors
            return response()->json([
                'status' => 'Error',
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
