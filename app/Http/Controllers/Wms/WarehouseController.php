<?php

namespace App\Http\Controllers\Wms;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWarehouseRequest;
use App\Models\Core\User;
use App\Models\Wms\Warehouse;
use App\Models\Wms\warehouseUser;
use App\Models\Wms\StorageClass;
use App\Services\WarehouseService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Schema;
class WarehouseController extends Controller
{

    public function __construct(protected WarehouseService $warehouseService = new WarehouseService())
    {
    }
    /**
     * Display a listing of the resource.
     *
     *  @return \Inertia\Response
     */
    public function index()
    {
        $storageClass = StorageClass::select('id', 'description as label')->get()->toArray();

        $data = Warehouse::with(['warehouseUser.users', 'storageclass'])->paginate(25)
            ->through(fn($post) => [
                'id' => $post->id,
                'warehouse_code' => $post->warehouse_code,
                'warehouse_name' => $post->warehouse_name,
                'storageclass_id' => $post->storageclass_id,
                'status' => $post->status,
                'storageclass' => $post->storageclass->description,
                'creator' => $post->append('creator')->creator,
                'created_at' => $post->created_at->format('Y-m-d H:i:s'),
                'warehouseUser' => $post->warehouseUser->map(function ($wr) {
                    $user = $wr->users->toArray();
                    return [
                        'id' => $user[0]['id'],
                        'name' => $user[0]['name'],
                        'email' => $user[0]['email'],
                        'user_type' => $user[0]['user_type']
                    ];
                })
            ]);
        return Inertia::render('WarehouseManagement/WarehouseProfile/Index', [
            'records' => Inertia::merge(fn() => $data->items()),
            'currentPage' => $data->currentPage(),
            'lastPage' => $data->lastPage(),
            'storageClass' => $storageClass
        ]);

    }

    public function SelecWarehouseIsactive()
    {
        $data = Warehouse::where('status', 'A')->get(['id as subid', 'warehouse_name as title']);
        return response()->json($data, 200);
    }


    public function getWarehouseUser($id): mixed
    {
        if (isset($id)) {

            if ($id == 0) {
                $usersNotInWarehouse = User::select('id', 'email', 'name', 'user_type')->where('status', "A")->get()->toArray();

                return response()->json($usersNotInWarehouse, 200);
            } else {
                $getWarehouseUserIds = warehouseUser::select('user_id', )->where('warehouse_id', $id)->get()->toArray();
                $usersNotInWarehouse = User::select('id', 'email', 'name', 'user_type')
                    ->where(function ($query) use ($getWarehouseUserIds) {
                        $query->whereNotIn('id', $getWarehouseUserIds)
                            ->where('status', 'A');
                    })
                    ->get()->toArray();
                return response()->json($usersNotInWarehouse, 200);
            }


        } else {
            return response()->json('Warehouse Id is required', 400);
        }


        $warehouseUsers = warehouseUser::where('warehouse_id', $id)->get()->toArray();
        return response()->json($warehouseUsers, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWarehouseRequest $request)
    {

        $warehouseInsert = [];
        $warehouseColumnsArrayParse = $request->all();
        $warehouseColumns = Schema::getColumnListing('wms_warehouse');
        foreach ($warehouseColumns as $column) {
            if (isset($warehouseColumnsArrayParse[$column])) {
                $warehouseInsert[$column] = $warehouseColumnsArrayParse[$column];
            }
        }

        if (isset($warehouseInsert['id'])) {
            $warehouse = Warehouse::where('id', $warehouseColumnsArrayParse['id'])->first();
            $warehouse->update($warehouseInsert);
        } else {
            $warehouse = Warehouse::create($warehouseInsert);
        }

        $userWarehouse = $request->warehouseUser;
        foreach ($userWarehouse as $key => $value) {
            if (
                !warehouseUser::where('warehouse_id', $warehouse->id)
                    ->where('user_id', $value['id'])->exists()
            ) {
                $warehouseUser = new warehouseUser;
                $uw = [];
                $uw['warehouse_id'] = $warehouse->id;
                $uw['user_id'] = $value['id'];
                $warehouseUser->create($uw);
            }
        }
        return redirect()->route('wm/warehouses');
    }


    public function deleteWarehouseUser($warehouseId, $userId)
    {
        if (
            warehouseUser::where('warehouse_id', $warehouseId)
                ->where('user_id', $userId)->exists()
        ) {
            $warehouseUser = warehouseUser::where('warehouse_id', $warehouseId)->where('user_id', $userId)->first();
            $warehouseUser->delete();
        }
        return response()->json($warehouseUser, 200);
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
        $data = Warehouse::find($id);
        $request->validate([
            'warehouse_code' => 'required',
            'warehouse_name' => 'required',
            'status' => 'required',
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
        $data = Warehouse::find($id);
        $data->delete();
        return response()->json($data, 200);
    }

    public function getWarehouseItemMasterList()
    {
        $itemmaster = $this->warehouseService->getWarehouseItemsMasterLists();
        return response()->json($itemmaster, 200);
    }
}
