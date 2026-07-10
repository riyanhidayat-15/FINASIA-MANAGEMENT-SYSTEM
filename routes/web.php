<?php

use App\Http\Controllers\Auth\RegisterTenantController;
use App\Models\Company;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/register', RegisterTenantController::class . '@show');
Route::post('/register', RegisterTenantController::class . '@register');

Route::get('/invoice/{invoice}/pdf', function (Invoice $invoice) {
    $company = Company::first();
 
    abort_if(! $company, 404, 'Data perusahaan belum diisi');
    abort_if(! $invoice->receipt, 404, 'Invoice ini belum memiliki kwitansi');
 
    $pdf = Pdf::loadView('pdf.invoice-receipt', [
        'invoice' => $invoice,
        'receipt' => $invoice->receipt,
        'company' => $company,
    ]);
 
    return $pdf->stream("Invoice-{$invoice->invoice_number}.pdf");
})
    ->middleware(['web', 'auth'])
    ->name('invoice.pdf');
