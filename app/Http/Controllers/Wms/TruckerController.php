<?php

namespace App\Http\Controllers\Wms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wms\Trucker;
use Inertia\Inertia;

class TruckerController extends Controller
{

    public function index() 
    {
        $data = Trucker::all()->append(['creator']);
        return Inertia::render('WarehouseManagement/TruckerMaster/Index',[
            'masterlist'=> $data
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
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'category' => 'required'
        ]);

        $trucker = new Trucker();
        $data['id'] = $request->input('id');
        $data['code'] = $request->input('code');
        $data['name'] = $request->input('name');
        $data['category'] = $request->input('category');
        $data['address'] = $request->input('address');
        $data['status'] = $request->input('status');
        
        $trucker = $trucker->store($data);
        
        return $trucker;

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Trucker::find($id);
        $data->delete();
        return response()->json($data, 200);
    }
}
