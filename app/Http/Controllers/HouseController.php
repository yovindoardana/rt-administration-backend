<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResidentHouseHistoryRequest;
use App\Http\Resources\ResidentHouseHistoryResource;
use App\Models\House;
use App\Http\Controllers\Controller;
use App\Http\Resources\HouseResource;
use App\Http\Requests\StoreHouseRequest;
use App\Http\Requests\UpdateHouseRequest;
use App\Models\ResidentHouseHistory;
use Illuminate\Http\Request;

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
        $house->load(['histories.resident', 'payments.resident']);
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

    public function addOccupant(StoreResidentHouseHistoryRequest $request, House $house)
    {
        $data = $request->validated();

        $previous = ResidentHouseHistory::where('house_id', $house->id)
            ->where('is_current', true)
            ->first();

        if ($previous) {
            $previous->update([
                'end_date'    => $data['start_date'],
                'is_current'  => false,
            ]);
        }

        $newHistory = ResidentHouseHistory::create([
            'house_id'    => $house->id,
            'resident_id' => $data['resident_id'],
            'start_date'  => $data['start_date'],
            'end_date'    => null,
            'is_current'  => true,
        ]);

        $house->update([
            'status' => 'occupied',
        ]);

        return new ResidentHouseHistoryResource(
            $newHistory->load(['house', 'resident'])
        );
    }

    public function listOccupants(Request $request, House $house)
    {
        $page    = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $paginator = $house
            ->histories()
            ->with('resident')
            ->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'page', $page);

        return ResidentHouseHistoryResource::collection($paginator);
    }
}
