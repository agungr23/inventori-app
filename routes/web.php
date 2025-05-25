<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/barang-keluar/{id}/invoice', [InvoiceController::class, 'generateInvoice'])->name('barang-keluar.invoice');