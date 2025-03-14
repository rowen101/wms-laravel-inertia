<?php

namespace App\Http\Controllers\Wms;

use App\Http\Controllers\Controller;
use App\Models\Wms\UnitOfMeasure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class UnitofMesasuresConstroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = UnitOfMeasure::all()->append(['creator']);
        return Inertia::render('WarehouseManagement/UnitofMeasure/Index',[
            'masterlist' =>$data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'uomcode' => 'required|string|max:16',
            'description' => 'required|string|max:50',
        ]);

        $existingRecord = UnitOfMeasure::where([
            'uomcode'=>$request->uomcode,
            'description'=>$request->description
        ])->first();

        if($existingRecord && !$request->id){
            return response()->json(['message' => 'This data is already in the database.'], 422);
        }
        $data = new UnitOfMeasure;
        $request->validate([
            'uomcode' => 'required',
            'description' => 'required',

        ]);
     

        UnitOfMeasure::updateOrCreate([
            
                'id' => $request->id
            ],
            [
                'uomcode' => $request->uomcode,
                'description' => $request->description
            ]
            );
            return redirect()->route('wm/uom');
        

     
        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = UnitOfMeasure::find($id);
            if($data){
                return response()->json($data,200);
            } else {
                return response()->json(['message' => 'Data not found'],404);
            }
   
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
    public function update(Request $request, $id)
    {
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = UnitOfMeasure::find($id);
        $data->delete();
        return response($data, 200);
    }
}
