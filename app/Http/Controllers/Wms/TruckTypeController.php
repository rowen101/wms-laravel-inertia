<?php

namespace App\Http\Controllers\Wms;

use App\Http\Controllers\Controller;
use App\Models\Wms\TruckType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TruckTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TruckType::all();
        return Inertia::render('WarehouseManagement/TruckType/Index',[
            'masterlist' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'typecode' => 'required|string|max:16',
            'description' => 'required|string|max:50',
            'handlingweight' => 'required|numeric',
            'handlingvolume' => 'required|numeric',
        ]);

        $existingRecord = TruckType::where([
            'typecode'=>$request->typecode,
            'description'=>$request->description
        ])->first();

        if($existingRecord && !$request->id){
            return response()->json(['message' => 'This data is already in the database.'], 422);
        }
        $data = new TruckType;
        $request->validate([
            'typecode' => 'required',
            'description' => 'required',

        ]);
     

        TruckType::updateOrCreate([
            
                'id' => $request->id
            ],
            [
                'typecode' => $request->typecode,
                'description' => $request->description,
                'handlingweight' => $request->handlingweight,
                'handlingvolume' => $request->handlingvolume,
                'sortorder' => $request->sortorder
            ]
            );
            return redirect()->route('wm/truck-types');
        
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
        //
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
        $data =  TruckType::find($id);
        $data->delete();
        return response()->json($data, 200);
    }
}
