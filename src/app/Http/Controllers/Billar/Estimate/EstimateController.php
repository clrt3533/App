<?php

namespace App\Http\Controllers\Billar\Estimate;

use App\Filters\Billar\Estimate\EstimateFilter;
use App\Http\Controllers\Controller;
use App\Jobs\EstimateAttachmentJob;
use App\Jobs\SendMessageJob;
use App\Models\Billar\Estimates\Estimate;
use App\Models\Billar\Estimates\EstimateDetails;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Billar\EstimateService;
use Illuminate\Http\Request;
use PDF;

class EstimateController extends Controller
{
    public function __construct(EstimateService $service, EstimateFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index()
    {
        return $this->service
            ->with('status:id,name,class', 'client:id,first_name,last_name')
            ->filters($this->filter)
            ->orderBy('id', request()->get('orderBy'))
            ->paginate(request('per_page', 10));
    }


    public function store(Request $request)
    {
        $request['status_id'] = resolve(StatusRepository::class)->estimatePending();
        $estimate = $this->service
            ->setValidation()
            ->setAttributes($request->all())
            ->save();

        $this->service->when($request->has('products'), fn(EstimateService $service) => $service->estimateDetails());

        $estimateInfo = $this->service->loadEstimateInfo($estimate);

        $this->service
            ->setAttribute('file_path', 'public/pdf/estimate_' . $estimate->id . '.pdf')
            ->pdfGenerate($estimateInfo);

        EstimateAttachmentJob::dispatch($estimateInfo)->onQueue('high');
        SendMessageJob::dispatch('estimate-generation-message',$estimate->client->profile->contact);
        return created_responses('estimates');
    }


    public function show($id)
    {
        return $this->service
            ->with([
                'client.profile', 'createdBy.profile',
                'estimateDetails' => function ($query) {
                    $query->with('tax:id,name,value', 'product:id,name');
                }
            ])->find($id);
    }

    public function update(Request $request, Estimate $estimate)
    {
        $this->service
            ->setModel($estimate)
            ->setValidation()
            ->update($request->only(
                'client_id',
                'estimate_number',
                'date',
                'status_id',
                'sub_total',
                'discount_type',
                'discount',
                'discount_amount',
                'total',
                'notes',
                'terms'
            ));
        $this->service->when($request->has('products'), fn(EstimateService $service) => $service->estimateDetails());
        return updated_responses('estimates');

    }


    public function destroy(Estimate $estimate)
    {
        $this->service
            ->setModel($estimate)
            ->delete();
        return deleted_responses('invoices');
    }

    public function detailsDelete()
    {
        $invoices = $this->service->find(request('id'));
        $invoices->update(request()->all());
        $invoice = EstimateDetails::where('id', request('estimate_detail_id'))->delete();
        return deleted_responses('products', ['invoice' => $invoice]);
    }

    public function estimateNumber()
    {
        return Estimate::query()->count() + 1;
    }

    protected function productTaxSum($quantity, $price, $taxValue)
    {
        return (($quantity * $price) * ($taxValue / 100));
    }

    public function download(Estimate $estimate)
    {
        $estimateInfo = $estimate->load(['estimateDetails' => function ($query) {
            $query->with('product:id,name', 'tax:id,name,value');
        }, 'client.profile']);
        $estimateInfo->totalTax = $estimateInfo->estimateDetails->map(function ($item) {
            $tax = $item->load('tax')->tax ? $item->load('tax')->tax->value : 0;
            return $this->productTaxSum($item->quantity, $item->price, $tax);
        })->sum();


        $pdf = PDF::loadView('estimates.estimate-generate', [
            'estimate' => $estimateInfo
        ]);

        return $pdf->download('estimate' . $estimate->estimate_number . '.pdf');
    }

    public function resendEstimate(Estimate $estimate): \Illuminate\Http\JsonResponse
    {
        $estimateInfo = $this->service->loadEstimateInfo($estimate);

        $this->service
            ->setAttribute('file_path', 'public/pdf/estimate_' . $estimate->id . '.pdf')
            ->pdfGenerate($estimateInfo);

        EstimateAttachmentJob::dispatch($estimateInfo)->onQueue('high');

        return response()->json([
            'status' => true,
            'message' => trans('default.estimate_send_success'),
        ], 200);
    }

    public function cloneAsInvoice(Estimate $estimate): \Illuminate\Http\JsonResponse
    {
        $this->service->setModel($estimate);
        $this->service->cloneInvoice();

        return response()->json([
            'status' => true,
            'message' => trans('default.estimate_cloned_as_invoice'),
        ], 200);
    }


}
