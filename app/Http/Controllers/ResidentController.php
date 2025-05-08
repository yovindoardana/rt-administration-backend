<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ResidentResource;
use App\Http\Requests\StoreResidentRequest;
use App\Http\Requests\UpdateResidentRequest;

class ResidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $residents = Resident::orderBy('id')->paginate(10);
        return ResidentResource::collection($residents);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResidentRequest $request)
    {

        $resident = Resident::create($request->validated());

        if ($resident) {
            return response()->json([
                'message' => 'Resident created successfully',
                'data'    => new ResidentResource($resident),
            ], 201);
        }

        return response()->json([
            'message' => 'Resident creation failed',
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Resident $resident)
    {
        return new ResidentResource($resident);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resident $resident)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResidentRequest $request, Resident $resident)
    {
        $resident->update($request->validated());

        if ($resident) {
            return response()->json([
                'message' => 'Resident updated successfully',
                'data'    => new ResidentResource($resident),
            ], 200);
        }

        return response()->json([
            'message' => 'Resident update failed',
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resident $resident)
    {
        if ($resident) {
            $resident->delete();
            $resident->delete();
            if ($resident->id_card) {
                $idCardPath = str_replace(url('/storage'), '', $resident->id_card);
                Storage::delete($idCardPath);
            }
            return response()->json([
                'message' => 'Resident deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Resident not found',
            ], 404);
        }
    }
}
