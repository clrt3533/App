<?php

use App\Http\Controllers\Billar\Estimate\EstimateController;
use App\Http\Controllers\Billar\Invoice\InvoiceController;
use App\Http\Controllers\Billar\Invoice\InvoiceDownloadController;
use App\Http\Controllers\Billar\Invoice\InvoiceMailController;
use App\Http\Controllers\Billar\PaymentHistory\InvoicePaymentController;
use App\Http\Controllers\Billar\PaymentHistory\PaymentHistoryController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['admin', 'individual_behavior']], function () {
    Route::post('estimate-details-delete', [EstimateController::class, 'detailsDelete'])
        ->name('product.delete_estimate_details');
    Route::get('estimate-download/{estimate}', [EstimateController::class, 'download'])
        ->name('estimate.download');
    Route::get('estimate-resend/{estimate}', [EstimateController::class, 'resendEstimate'])
        ->name('resend.estimate');
    Route::get('clone-as-invoice/{estimate}', [EstimateController::class, 'cloneAsInvoice'])
        ->name('invoice.clone');
    Route::resource('estimates', EstimateController::class);
});

