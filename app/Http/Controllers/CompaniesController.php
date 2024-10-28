<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

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

        $companies = Companies::with('user')->get();

        $validatorRelationship = Validator::make(['companied' => $companies], [
            'companies.*.user' => 'required|exists:users,id',
        ]);

        if ($validatorRelationship->fails()) {
            return response()->json([
                'errors' => $validatorRelationship->errors(),
                'message' => "Error relationship"
            ], 422);}

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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => "Error",
                'message' => 'Validation failed',
                'data' => $validator->errors(),

            ], 400);
        }

        $companies = Companies::create([
            'representative' => $request->representative,
            'company_name' => $request->company_name,
            'short_name' => $request->short_name,
            'phone_number' => $request->phone_number,
            'slug' => $request->slug,
            'content' => $request->content,
            'link' => $request->link,
            'user_id' => $request->user_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $format = [
            'id' => $companies->id,
            'representative' => $companies->representative,
            'company_name' => $companies->company_name,
            'short_name' => $companies->short_name,
            'phone_number' =>  $companies->phone_number,
            'slug' => $companies->slug,
            'content' => $companies->content,
            'link' => $companies->link,
            'user' => $companies->user,
            'created_at' => $companies->created_at,
            'updated_at' => $companies->updated_at
        ];

        return response()->json([
            'status' => 'Success',
            'message' => 'Company created successfully',
            'data' => $format,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $companies = Companies::with('user')->find($id);

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
        try{
            // Validate data
            $validatedData = $request->validate([
                'representative' => 'required|string|max:255',
                'company_name' => 'required|string|max:255',
                'short_name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:20',
                'slug' => 'required|string|max:255|unique:companies,slug,' . $id,
                'content' => 'nullable|string',
                'link' => 'nullable|url',
                'user_id' => 'required|exists:users,id',
            ]);

            $company = Companies::findOrFail($id);
            $company->update($validatedData);

            return response()->json([
                'status' => "Success",
                'message' => "Update company success",
                'data' => $company
            ]);
        } catch (ValidationException $e) {
            // Handling authentication errors
            return response()->json([
                'status' => 'Error',
                'message' => $e->validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (ModelNotFoundException $e) {
            // Handling cases where the company is not found
            return response()->json([
                'status' => 'Error',
                'message' => 'Company not found'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            // Handle other errors (e.g. database errors)
            return response()->json([
                'status' => 'Error',
                'message' => 'An error occurred', 'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $company = Companies::findOrFail($id);
            $company->delete();

            return response()->json([
                'status' => "Success",
                'message' => 'Company deleted successfully'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            // Handling cases where the company is not found
            return response()->json([
                'status' => 'Error',
                'message' => 'Company not found'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            // Handle other errors
            return response()->json([
                'status' => 'Error',
                'message' => 'An error occurred', 'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
