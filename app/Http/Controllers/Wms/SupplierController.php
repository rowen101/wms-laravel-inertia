<?php

namespace App\Http\Controllers\Wms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wms\Supplier;
use Inertia\Inertia;

class SupplierController extends Controller
{

    public function index() 
    {
        $data = Supplier::all()->append(['creator']);
        return Inertia::render('WarehouseManagement/SupplierMaster/Index',[
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

        $supplier = new Supplier();
        $data['id'] = $request->input('id');
        $data['code'] = $request->input('code');
        $data['name'] = $request->input('name');
        $data['category'] = $request->input('category');
        $data['address'] = $request->input('address');
        $data['status'] = $request->input('status');
        
        $supplier = $supplier->store($data);
        
        return $supplier;

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Supplier::find($id);
        $data->delete();
        return response()->json($data, 200);
    }
}
