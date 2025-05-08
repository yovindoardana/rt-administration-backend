<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Http\Controllers\Controller;
use App\Http\Resources\HouseResource;
use App\Http\Requests\StoreHouseRequest;
use App\Http\Requests\UpdateHouseRequest;
use Illuminate\Http\Request;

// Request

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $houses = House::orderBy('house_number')->paginate(10);
        return HouseResource::collection($houses);
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
    public function store(StoreHouseRequest $request)
    {
        $house = House::create($request->validated());

        if ($house) {
            return response()->json([
                'message' => 'House created successfully',
                'data'    => new HouseResource($house),
            ], 201);
        }

        return response()->json([
            'message' => 'House creation failed',
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(House $house)
    {
        return new HouseResource($house);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(House $house)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHouseRequest $request, House $house)
    {
        $house->update($request->validated());

        if ($house) {
            return response()->json([
                'message' => 'House updated successfully',
                'data'    => new HouseResource($house),
            ], 200);
        }

        return response()->json([
            'message' => 'House update failed',
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(House $house)
    {
        $house->delete();
        return response()->json([
            'message' => 'House deleted successfully',
        ], 200);
    }
}
