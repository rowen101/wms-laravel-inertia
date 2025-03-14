<?php

namespace App\Http\Controllers\Wms;

use App\Http\Controllers\Controller;
use App\Models\Wms\ReceiptHeader;
use Illuminate\Http\Request;
use App\Models\Wms\Customer;
use App\Services\ReceiptService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{

    public function __construct(protected ReceiptService $receiptService){
    }
    
    public function index() 
    {
    
        return Inertia::render('WarehouseManagement/Receipt/Index',[
            'listdata'=> $this->receiptService->getOpenReceipts(Auth::user()->warehouse_id)
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

        $rr = new ReceiptHeader();
        $data['id'] = $request->input('id');
        $data['receiptno'] = $request->input('receiptno');
        $data['referenceno'] = $request->input('referenceno');
        $data['receiptdate'] = $request->input('receiptdate');
        $data['supplier_id'] = $request->input('supplier_id');
        $data['remarks'] = $request->input('remarks');
        $data['status'] = $request->input('status');
        $data['receipttype'] = 'R01';
        $data['warehouse_id'] = Auth::user()->warehouse_id;

        $result = $rr->store($data);
        if ($result) {
            return response()->json(['success' => true, 'data' => $result], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to store receipt'], 500);
        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = ReceiptHeader::find($id);
        $data->delete();
        return response()->json($data, 200);
    }
}
