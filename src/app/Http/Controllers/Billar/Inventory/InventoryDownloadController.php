<?php

namespace App\Http\Controllers\Billar\Inventory;

use App\Http\Controllers\Controller;
use App\Services\Billar\Inventory\InventoryService;
use Illuminate\Http\Request;
use PDF;

class InventoryDownloadController extends Controller
{
    public function __construct(InventoryService $service)
    {
        $this->service = $service;
    }

    public function download(Request $request)
    {
        $id = $request->route('inventory');
        $inventoryInfo = $this->service->find($id);

        $pdf = PDF::loadView('inventory.inventory-generate', [
            'inventory' => $inventoryInfo
        ]);
        
        return $pdf->download('inventory' . $inventoryInfo->id . '.pdf');
    }
}
