<?php

namespace App\Http\Controllers\Billar\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Billar\Inventory\Inventory;
use App\Models\Billar\Inventory\InventoryDetail;
use App\Services\Billar\Inventory\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct(InventoryService $service)
    {
        $this->service = $service;
    }


    public function index()
    {
        return $this->service
            ->orderBy('id', request()->get('orderBy'))
            ->paginate(request('per_page', 10));
    }


    public function store(Request $request)
    {
        $products = json_decode($request->input('products'), true);
        $requestWithDecodedProducts = $request->merge(['products' => $products]);

        $inventory = $this->service
            ->setValidation()
            ->setAttributes($requestWithDecodedProducts->all())
            ->save();

        $signatureFile = $requestWithDecodedProducts->file('signatureBase64');
        $signatureFileName = $signatureFile->store('signatures', 'public');
        // Extract the image file name from the stored path
        $signatureName = basename($signatureFileName);

        // Update the signature filename of the $inventory item
        $inventory->signature = $signatureName;
        $inventory->save();

        $this->service->when($requestWithDecodedProducts->has('products'), fn (InventoryService $service) => $service->inventoryDetails());
        return created_responses('inventory');
    }


    public function show($id)
    {
        return $this->service
            ->with('invoice')
            ->with([
                'inventoryDetails' => function ($query) {
                    $query->join('products', 'inventory_details.product_id', '=', 'products.id')
                        ->join('categories', 'products.category_id', '=', 'categories.id')
                        ->select('inventory_details.*', 'products.name as product_name', 'categories.id as category_id', 'categories.name as category_name');
                }
            ])->find($id);
    }

    public function update(Request $request, Inventory $inventory)
    {
        $this->service
            ->setModel($inventory)
            ->setValidation()
            ->setAttributes($request->only(
                'invoice_id',
                'notes',
            ))
            ->update();

        $this->service->when($request->has('products'), fn (InventoryService $service) => $service->inventoryDetails());
        return updated_responses('inventory');
    }


    public function destroy(Inventory $inventory)
    {
        $this->service
            ->setModel($inventory)
            ->delete();
        return deleted_responses('inventory');
    }

    public function detailsDelete()
    {
        $inventory = $this->service->find(request('id'));
        $inventory->update(request()->all());
        $inventory = InventoryDetail::where('id', request('inventory_detail_id'))->delete();
        return deleted_responses('products', ['inventory' => $inventory]);
    }
}
