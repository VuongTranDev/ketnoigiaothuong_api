<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Categories::get();

        $format = $category->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at
            ];
        });

        if ($category == null) {
            return response()->json([
                'status' => "Error",
                'message' => 'Category not found!'
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
            'name' => 'required|string',
            'slug' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => "Error",
                'message' => 'Validation failed',
                'data' => $validator->errors(),
            ], 400);
        }

        $category = Categories::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $format = [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at
        ];

        return response()->json([
            'status' => 'Success',
            'message' => 'Category created successfully',
            'data' => $format,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Categories::find($id);

        if ($category == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found!'
            ], 500);
        }

        $format = [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at
        ];

        if ($format == null) {
            return response()->json([
                'status' => "Error",
                'message' => 'Category not found!'
            ], 404);
        } else {
            return response()->json([
                'status' => 'Succe  ss',
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
                'name' => 'required|string',
                'slug' => 'required|string',
            ]);

            $category = Categories::findOrFail($id);
            $category->update($validatedData);

            $format = [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at
            ];

            return response()->json([
                'status' => "Success",
                'message' => "Update category success",
                'data' => $format
            ]);
        } catch (ValidationException $e) {
            // Handling authentication errors
            return response()->json([
                'status' => 'Error',
                'message' => $e->validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (ModelNotFoundException $e) {
            // Handling cases where the category is not found
            return response()->json([
                'status' => 'Error',
                'message' => 'category not found'
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
            $category = Categories::findOrFail($id);
            $category->delete();

            return response()->json([
                'status' => "Success",
                'message' => 'Category deleted successfully'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            // Handling cases where the category is not found
            return response()->json([
                'status' => 'Error',
                'message' => 'Category not found'
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
