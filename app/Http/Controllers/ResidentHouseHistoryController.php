<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ResidentHouseHistory;
use App\Http\Resources\ResidentHouseHistoryResource;
use App\Http\Requests\StoreResidentHouseHistoryRequest;
use App\Http\Requests\UpdateResidentHouseHistoryRequest;

class ResidentHouseHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $histories = ResidentHouseHistory::with(['house', 'resident'])
            ->orderByDesc('start_date')
            ->paginate();

        return ResidentHouseHistoryResource::collection($histories);
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
    public function store(StoreResidentHouseHistoryRequest $request)
    {
        $data = $request->validated();

        // if the history is current, set all other histories to not current
        if (!empty($data['is_current'])) {
            ResidentHouseHistory::where('house_id', $data['house_id'])
                ->update(['is_current' => false]);
        }

        $history = ResidentHouseHistory::create($data);

        return response()->json([
            'message' => 'History created',
            'data'    => new ResidentHouseHistoryResource($history->load(['house', 'resident'])),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ResidentHouseHistory $resident_house_history)
    {
        return new ResidentHouseHistoryResource(
            $resident_house_history->load(['house', 'resident'])
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ResidentHouseHistory $residentHouseHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResidentHouseHistoryRequest $request, ResidentHouseHistory $resident_house_history)
    {
        $data = $request->validated();

        if (array_key_exists('is_current', $data) && $data['is_current']) {
            ResidentHouseHistory::where('house_id', $resident_house_history->house_id)
                ->update(['is_current' => false]);
        }

        $updatedData = $resident_house_history->update($data);

        if ($updatedData) {
            $resident_house_history->refresh();
            return response()->json([
                'message' => 'History updated',
                'data'    => new ResidentHouseHistoryResource($resident_house_history->load(['house', 'resident'])),
            ], 200);
        }

        return response()->json([
            'message' => 'Update failed',
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResidentHouseHistory $residentHouseHistory)
    {
        $residentHouseHistory->delete();

        return response()->json([
            'message' => 'History deleted',
        ], 200);
    }
}
