<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangKeluar;
use Barryvdh\DomPDF\Facade\Pdf; // DomPDF facade
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function generateInvoice($id)
    {
        $barangKeluar = BarangKeluar::with(['barang.jenisBarang', 'pembeli', 'barang.satuan'])->findOrFail($id);

        //$pdf = Pdf::loadView('invoices.barang-keluar', compact('barangKeluar'));

        // Ambil harga satuan dari tabel HargaPembeli sesuai barang_id dan pembeli_id
        $hargaPembeli = \App\Models\HargaPembeli::where('barang_id', $barangKeluar->barang_id)
            ->where('pembeli_id', $barangKeluar->pembeli_id)
            ->first();

        $hargaSatuan = $hargaPembeli ? $hargaPembeli->harga_jual : 0;

        // Bisa di-download atau langsung di browser
        Log::info('Harga Satuan:', ['hargaSatuan' => $hargaSatuan]);

        return Pdf::loadView('invoices.barang-keluar', compact('barangKeluar', 'hargaSatuan'))
            ->stream('invoice-' . $barangKeluar->id_transaksi . '.pdf');
    }
}