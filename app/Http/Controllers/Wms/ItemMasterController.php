<?php

namespace App\Http\Controllers\Wms;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Wms\ItemMaster;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemMasterRequest;

class ItemMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $data = ItemMaster::all()->append(['creator']);
        return Inertia::render('WarehouseManagement/ItemMaster/Index', [
            'masterlist' => $data
        ]);
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreItemMasterRequest $request)
    {
        $existingRecord = ItemMaster::where([
            'itemcode'=>$request->itemcode,
            'description'=>$request->description
        ])->first();
        
        if($existingRecord && !$request->id){
            return response()->json(['message' => 'This data is already in the database.'], 422);
        }
        $statusId = $request->input('status.id');

        ItemMaster::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'storageclass_id' => $request->storageclass_id,
                'producttype' => $request->producttype,
                'itemcode' => $request->itemcode,
                'referencecode' => $request->referencecode,
                'description' => $request->description,
                'movementclass' => $request->movementclass,
                'unitprice' => $request->unitprice ? round((float) $request->unitprice, 3) : null,
                'shelflife' => (int) $request->shelflife,
                'shelflifeunit' => $request->shelflifeunit,
                'salvagedays' => (int) $request->salvagedays,
                'stockrestriction' => $request->stockrestriction,
                'lotformat' => $request->lotformat,
               'lotformatdate' => $request->lotformatdate,
                'batchfindstrategy' => $request->batchfindstrategy,
                'safestocklevel' => (int) $request->safestocklevel,
                'handlinguom' => $request->handlinguom,
                'eachuom' => $request->eachuom,
                'eachtohandlingqty' =>  $request->eachtohandlingqty ? round((float) $request->unitprice, 3) : null,
                'handlingtopalletqty' =>  $request->handlingtopalletqty ? round((float) $request->unitprice, 3) : null,
                'minlevelhandlingqty' => (int) $request->minlevelhandlingqty,
                'maxreplineshmentforcaseloc' => (int) $request->maxreplineshmentforcaseloc,
                'handlinglocation' => $request->handlinglocation,
                'eachlocation' => $request->eachlocation,
                'isbatchmanaged' => (bool) $request->isbatchmanaged,
                'extraproperties' => $request->extraproperties
                ? (is_array($request->extraproperties) ? json_encode($request->extraproperties) : json_encode([$request->extraproperties]))
                : null,
                'status' => $statusId
            ]
            );
           return redirect()->route('wm/item-master');
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = DB::table("wms_itemmaster")
            ->join("wms_dropdown", "wms_dropdown.identity_code", "=", "wms_itemmaster.abccode")
            ->select("wms_itemmaster.*", "wms_dropdown.description")
            ->where("wms_itemmaster.uuid", $id)
            ->get();
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
    public function update(StoreItemMasterRequest $request, $id)
    {
        $data =  ItemMaster::find($id);
        $request->validate([
            'storageclass_id' => 'required',
            'itemcode' => 'required',
            'description' => 'required',
            'handlingunit' => 'required',
            'abccode' => 'required',
            'unitcost' => 'required',
            'safestocklevel' => 'required',
            'shelflifeunit' => 'required',
            'salvagedays' => 'required',
            'stockrestriction' => 'required',
            'lotformat' => 'required',
            'lotformatdate' => 'required',
            'batchfindstrategy' => 'required',
            'unitqtyperbatch' => 'required',
            'eachuom' => 'required', 'max:20',
            'eachqtyperhandlingunit' => 'required',
            'unitvolume' => 'required',
            'unitweight' => 'required',
            'minreplenishmentlvl' => 'required',
            'maxreplenishmentqty' => 'required',
            'eachreplenishmentlvl' => 'required',
            'eachhureplenishmentqty' => 'required',
            'isbatchmanaged' => 'required',
        ]);
        $data->store($request->all());
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
        $data =  ItemMaster::find($id);
        $data->delete();
        return response($data, 200);
    }
}
