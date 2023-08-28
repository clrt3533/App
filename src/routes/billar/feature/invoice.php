<?php

use App\Http\Controllers\Billar\Invoice\InvoiceController;
use App\Http\Controllers\Billar\Invoice\InvoiceDownloadController;
use App\Http\Controllers\Billar\Invoice\InvoiceMailController;
use App\Http\Controllers\Billar\PaymentHistory\InvoicePaymentController;
use App\Http\Controllers\Billar\PaymentHistory\PaymentHistoryController;
use App\Http\Controllers\Billar\ReceiptHistory\ReceiptHistoryController;
use App\Http\Controllers\Billar\ReceiptHistory\ReceiptHistoryDownloadController;
use App\Http\Controllers\Billar\PaymentHistory\PaypalController;
use App\Http\Controllers\Billar\BillHistory\BillHistoryController;
use App\Http\Controllers\Billar\BillHistory\BillHistoryDownloadController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['admin', 'individual_behavior']], function () {
    Route::apiResource('invoices', InvoiceController::class);
    Route::post('invoice-details-delete', [InvoiceController::class, 'detailsDelete'])
        ->name('product.delete_invoice_details');
    Route::apiResource('payment-histories', PaymentHistoryController::class);
    Route::post('invoice-send/{invoice}', [InvoiceMailController::class, 'sendInvoice'])
        ->name('send.invoice');
    Route::get('invoice-download/{invoice}', [InvoiceDownloadController::class, 'download'])
        ->name('download.invoice');
    Route::get('invoice-resend/{invoice}', [InvoiceMailController::class, 'resendInvoice'])
        ->name('resend.invoice');
    Route::get('invoice-thankyou/{invoice}', [InvoiceMailController::class, 'thankyouInvoice'])
        ->name('resend.invoice_thankyou');
    Route::post('checkout', [InvoicePaymentController::class, 'checkout'])
        ->name('checkout.invoice');
    Route::get('invoice-export', [InvoiceController::class, 'invoiceExport'])->name('invoice.export');

    Route::apiResource('receipt-histories', ReceiptHistoryController::class);
    Route::get('receipt-download/{receipt}', [ReceiptHistoryDownloadController::class, 'download'])
    ->name('download.receipt');

    Route::apiResource('bill-histories', BillHistoryController::class);
    Route::get('bill-download/{bill}', [BillHistoryDownloadController::class, 'download'])
    ->name('download.bill');
});


Route::get('date-format', [InvoiceMailController::class, 'sendInvoice']);
