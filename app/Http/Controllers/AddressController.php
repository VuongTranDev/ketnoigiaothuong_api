<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $address = Address::with('companies')->get();

        $format = $address->map(function ($address) {
            return [
                'id' => $address->id,
                'details' => $address->details,
                'address' => $address->address,
                'company_id' => $address->companies,
                'created_at' => $address->created_at,
                'updated_at' => $address->updated_at
            ];
        });

        if ($address == null) {
            return response()->json([
                'status' => "Error",
                'message' => 'Address not found!'
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
            'details' => 'required',
            'address' => 'required',
            'company_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => "Error",
                'message' => 'Validation failed',
                'data' => $validator->errors(),

            ], 400);
        }

        $address = Address::create([
            'details' => $request->details,
            'address' => $request->address,
            'company_id' => $request->company_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $format = [
            'id' => $address->id,
            'details' => $address->details,
            'address' => $address->address,
            'company_id' => $address->companies,
            'created_at' => $address->created_at,
            'updated_at' => $address->updated_at
        ];

        return response()->json([
            'status' => 'Success',
            'message' => 'Address created successfully',
            'data' => $format,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $address = Address::with('companies')->find($id);

        if ($address == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Address not found!'
            ], 500);
        }

        $format = [
            'id' => $address->id,
            'details' => $address->details,
            'address' => $address->address,
            'company_id' => $address->companies,
            'created_at' => $address->created_at,
            'updated_at' => $address->updated_at
        ];

        if ($format == null) {
            return response()->json([
                'status' => "Error",
                'message' => 'Address not found!'
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
                'details' => 'required',
                'address' => 'required',
                'company_id' => 'required',
            ]);

            $address = Address::findOrFail($id);
            $address->update($validatedData);

            $format = [
                'id' => $address->id,
                'details' => $address->details,
                'address' => $address->address,
                'company_id' => $address->companies,
                'created_at' => $address->created_at,
                'updated_at' => $address->updated_at
            ];

            return response()->json([
                'status' => "Success",
                'message' => "Update address success",
                'data' => $format
            ]);
        } catch (ValidationException $e) {
            // Handling authentication errors
            return response()->json([
                'status' => 'Error',
                'message' => $e->validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (ModelNotFoundException $e) {
            // Handling cases where the address is not found
            return response()->json([
                'status' => 'Error',
                'message' => 'Address not found'
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
            $address = Address::findOrFail($id);
            $address->delete();

            return response()->json([
                'status' => "Success",
                'message' => 'Address deleted successfully'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            // Handling cases where the address is not found
            return response()->json([
                'status' => 'Error',
                'message' => 'Address not found'
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
