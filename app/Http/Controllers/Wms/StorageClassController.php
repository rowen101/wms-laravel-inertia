<?php

namespace App\Http\Controllers\Wms;
use App\Http\Requests\StorageClassRequest;
use App\Http\Controllers\Controller;
use App\Models\Wms\StorageClass;
use Illuminate\Http\Request;
use Inertia\Inertia;
class StorageClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = StorageClass::all()->append(['creator']);
         return Inertia::render('WarehouseManagement/StorageClass/Index', [
             'masterlist' => $data
         ]);
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
    public function store(StorageClassRequest $request)
    {
        $existingRecord = StorageClass::where([
            'classcode'=>$request->loccode,
        ])->first();
        
        if($existingRecord && !$request->id){
            return response()->json(['message' => 'This data is already in the database.'], 422);
        }

        StorageClass::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
               
                'classcode' => $request->classcode,
                'description' => $request->description,
            ]
            );
           return redirect()->route('wm/storage-class');  
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
