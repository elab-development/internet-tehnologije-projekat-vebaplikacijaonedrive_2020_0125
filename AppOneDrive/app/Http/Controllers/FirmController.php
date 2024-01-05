<?php

namespace App\Http\Controllers;

use App\Graph\GraphApiCaller;
use App\Graph\GraphHelper;
use App\Graph\OneDriveController;
use App\Http\Resources\FirmResource;
use App\Models\Firm;
use DateTime;
use Illuminate\Http\Request;

class FirmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    // Creates a new firm
    public function store(Request $request)
    {
        $newFirm = new Firm();
        $newFirm->PIB = $request->input('pib');
        $newFirm->Name = $request->input('name');
        $newFirm->Address = $request->input('address');
        $newFirm->CreatedAt = new DateTime();
        $newFirm->user_id = $request->input('founderId');

        $singletonInstance = app(OneDriveController::class);
        $singletonInstance->createFirmFolder($request->input('name'));

        $newFirm->save();
        return response()->json(['message' => 'Company created successfully', 'company' => $newFirm], 201);
    }

    // Gets a specific firm
    public function show(Firm $firm)
    {
        return new FirmResource($firm);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Firm $firm)
    {
        //
    }

    //Updates a firm
    public function update(Request $request, $PIB)
    {
        $updateFirm = Firm::findOrFail($PIB);
        $oldName=$updateFirm->Name;
        $updateFirm->Name = $request->input('name', $updateFirm->Name);
        $updateFirm->Address = $request->input('address', $updateFirm->Address);

        $singletonInstance = app(OneDriveController::class);
        $singletonInstance->renameFirmFolder($oldName, $request->input('name'));

        $updateFirm->save();
        return response()->json(['message' => 'Company updated successfully', 'company' => $updateFirm], 200);
    }

    // Deletes a firm
    public function destroy($PIB)
    {
        $deleteFirm = Firm::findOrFail($PIB);
        $name=$deleteFirm->Name;

        $singletonInstance = app(OneDriveController::class);
        $singletonInstance->deleteFirmFolder($name);
        $deleteFirm->delete();

        return response()->json(['message' => 'Company deleted successfully', 'company' => $deleteFirm], 200);
    }
}
