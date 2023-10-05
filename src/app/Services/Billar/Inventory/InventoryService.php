<?php

namespace App\Services\Billar\Inventory;

use App\Helpers\Core\Traits\HasWhen;
use App\Models\Billar\Inventory\Inventory;
use App\Models\Billar\Inventory\InventoryDetail;
use App\Services\Billar\ApplicationBaseService;
use Illuminate\Support\Facades\Storage;
use PDF;

class InventoryService extends ApplicationBaseService
{
    use HasWhen;

    public function __construct(Inventory $inventory)
    {
        $this->model = $inventory;
    }

    public function individualClient(): self
    {
        $this->builder->when(!auth()->user()->isAppAdmin(), function ($query) {
            $query->where('client_id', auth()->id());
        });
        return $this;
    }

    public function setValidation(): self
    {
        validator(request()->all(), [
            'invoice_id' => 'required',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required',
            'products.*.quantity' => 'required',
            'products.*.condition' => 'required',
        ], [
            'products.*.condition.required' => 'Product condition is required.',
        ])->validate();

        return $this;
    }

    public function inventoryDetails(): self
    {
        if (request()->products) {
            foreach (request()->products as $item) {
                if (collect($item)->has('id')) {
                    InventoryDetail::where('id', $item['id'])->update([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'condition' => $item['condition'],
                    ]);
                } else {
                    InventoryDetail::create([
                        'inventory_id' => $this->model->id,
                        'product_id' => $item['product_id'],
                        'quantity'   => $item['quantity'],
                        'condition' => $item['condition'],
                    ]);
                }
            }
        }

        return $this;
    }

    public function pdfGenerate($inventoryInfo): self
    {
        $pdf = PDF::loadView('inventory.inventory-generate', [
            'inventory' => $inventoryInfo
        ]);

        $output = $pdf->output();
        $filePath = $this->getAttribute('file_path');
        Storage::put($filePath, $output);
        return $this;
    }
}