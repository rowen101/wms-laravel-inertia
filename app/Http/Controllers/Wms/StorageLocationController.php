<?php

namespace App\Http\Controllers\Wms;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Models\Wms\Location;
use App\Models\Wms\StorageClass;
use App\Models\Wms\Warehouse;
use Inertia\Inertia;

class StorageLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['lists'] = Location::all()->append(['creator']);
        $data['storageclass'] = StorageClass::select('id', 'description as label')->get()->toArray();
        return Inertia::render('WarehouseManagement/StorageLocation/Index', [
            'masterdata' => $data
        ]);
    }

    /* Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLocationRequest $request)
    {
        $existingRecord = Location::where([
            'loccode'=>$request->loccode,
        ])->first();
        
        if($existingRecord && !$request->id){
            return response()->json(['message' => 'This '.$request->loccode.' data is already in the database.'], 422);
        }

        Location::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'storageclass_id' => is_array($request->storageclass_id) ? $request->storageclass_id['id'] : $request->storageclass_id,
                'loccode' => $request->loccode,
                'tracecode' => $request->tracecode,
                'referencecode' => $request->referencecode,
                'locationtype' => $request->locationtype,
                'sizecode' => $request->sizecode,
                'abccode' => $request->abccode,
                'checkdigit' => $request->checkdigit,
                'capacity' => (int) $request->capacity,
                'drivezone' => $request->drivezone,
               'pickzone' => $request->pickzone,
                'picksequence' => $request->picksequence,
                'putawaysequence' => $request->putawaysequence,
                'islockin' => (bool) $request->islockin,
                'islockout' => (bool)  $request->islockout,
                'isoverflow' => (bool)  $request->isoverflow,
                'status' => is_array($request->status) ? $request->status['id'] : $request->status
            ]
            );
           return redirect()->route('wm/locations');  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $data = Location::find($id);
        $data = Location::where("uuid", $id)->get();
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreLocationRequest $request, $id)
    {
        $data = Location::find($id);
        $data->store($request->validate());
        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Location::find($id);
        $data->delete();
        return response()->json($data, 200);
    }
}
