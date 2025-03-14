<?php

namespace App\Http\Controllers\Wms;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use Illuminate\Http\Request;
use App\Models\Wms\Customer;
use Inertia\Inertia;

class CustomerController extends Controller
{

    public function index() 
    {
        $data = Customer::paginate(1000);
        return Inertia::render('WarehouseManagement/CustomerMaster/Index',[
            'records'=> Inertia::merge(fn()=> $data->items()),
            'currentPage' => $data->currentPage(),
            'lastPage' => $data->lastPage(),
        ]);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {

        $customer = new Customer();
        $data['id'] = $request->input('id');
        $data['code'] = $request->input('code');
        $data['name'] = $request->input('name');
        $data['category'] = $request->input('category');
        $data['address'] = $request->input('address');
        $data['status'] = $request->input('status');
        
        $customer = $customer->store($data);
        
        return $customer;

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Customer::find($id);
        $data->delete();
        return response()->json($data, 200);
    }
}
