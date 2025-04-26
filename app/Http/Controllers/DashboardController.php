<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Pelanggan;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();

        // Penghasilan Hari Ini
        $penghasilanHariIni = DB::table('penjualan')
            ->whereDate('created_at', $today)
            ->sum('total_harga');

        // Penghasilan Bulan Ini
        $penghasilanBulanIni = DB::table('penjualan')
            ->whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->sum('total_harga');

        // Total Pelanggan
        $totalPelanggan = Pelanggan::count();

        // Total Transaksi Hari Ini
        $totalTransaksiHariIni = DB::table('penjualan')
            ->whereDate('created_at', $today)
            ->count();

        // Total Produk Terjual Hari Ini
        $totalProdukTerjualHariIni = DB::table('penjualan_details')
            ->whereDate('created_at', $today)
            ->sum('jumlah_produk'); // <<< fix disini

        // Produk Terlaris Bulan Ini
        $produkTerlarisBulanIni = DB::table('penjualan_details')
            ->select('produk_id', DB::raw('SUM(jumlah_produk) as total_terjual')) // <<< fix disini
            ->whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->groupBy('produk_id')
            ->orderByDesc('total_terjual')
            ->first();

        $namaProdukTerlaris = null;
        $jumlahProdukTerlaris = null;

        if ($produkTerlarisBulanIni) {
            $produk = Product::find($produkTerlarisBulanIni->produk_id);
            $namaProdukTerlaris = $produk ? $produk->nama_produk : 'Produk tidak ditemukan';
            $jumlahProdukTerlaris = $produkTerlarisBulanIni->total_terjual;
        }

        return view('dashboard.index', [
            'penghasilanHariIni' => $penghasilanHariIni,
            'penghasilanBulanIni' => $penghasilanBulanIni,
            'totalPelanggan' => $totalPelanggan,
            'totalTransaksiHariIni' => $totalTransaksiHariIni,
            'totalProdukTerjualHariIni' => $totalProdukTerjualHariIni,
            'namaProdukTerlaris' => $namaProdukTerlaris,
            'jumlahProdukTerlaris' => $jumlahProdukTerlaris,
            'title' => 'Dashboard'
        ]);
    }
}
