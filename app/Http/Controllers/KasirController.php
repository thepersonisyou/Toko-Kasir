<?php

namespace App\Http\Controllers;

use App\Models\Kasir;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;


class KasirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.kasir.index', [
            'pelanggans' => Pelanggan::all(),
            'products' => Product::all(),
            'title' => 'Transaksi Baru',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $request->validate([
            'no_telp' => 'nullable|string',
            'nama_pelanggan' => 'nullable|string',
            'produk_id' => 'required|array',
            'jumlah' => 'required|array',
            'subtotal' => 'required|array',
            'bayar' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Cari pelanggan berdasarkan nomor telepon jika tersedia
            $pelanggan = null;

            if ($request->filled('no_telp')) {
                $pelanggan = Pelanggan::where('no_telp', $request->no_telp)->first();

                // Jika pelanggan tidak ditemukan, buat pelanggan baru jika ada nama
                if (!$pelanggan && $request->filled('nama_pelanggan')) {
                    $pelanggan = Pelanggan::create([
                        'no_telp' => $request->no_telp,
                        'nama' => $request->nama_pelanggan,
                    ]);
                }
            }

            // Hitung total harga
            $total_harga = array_sum($request->subtotal);

            // Diskon 1% jika pelanggan terdaftar
            $diskon = $pelanggan ? 0.01 : 0;
            $total_harga_setelah_diskon = $total_harga - ($total_harga * $diskon);

            // Simpan penjualan
            $penjualan = Penjualan::create([
                'nomor_transaksi' => 'TRX-' . time(),
                'tanggal_penjualan' => now(),
                'total_harga' => $total_harga_setelah_diskon,
                'bayar' => $request->bayar,
                'pelanggan_id' => $pelanggan ? $pelanggan->id : null,
                // Hanya simpan nama_pelanggan_manual dan no_telp_manual jika ada data
                'nama_pelanggan_manual' => $request->nama_pelanggan ?? null,
                'no_telp_manual' => $request->no_telp ?? null,
            ]);

            // Simpan detail penjualan
            foreach ($request->produk_id as $index => $produk_id) {
                $produk = Product::findOrFail($produk_id);

                if ($produk->stok < $request->jumlah[$index]) {
                    throw new \Exception("Stok produk {$produk->nama_product} tidak mencukupi.");
                }

                $produk->decrement('stok', $request->jumlah[$index]);

                PenjualanDetail::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $produk_id,
                    'jumlah_produk' => $request->jumlah[$index],
                    'subtotal' => $request->subtotal[$index],
                ]);
            }

            DB::commit();

            // Kirim data ke view untuk pembuatan PDF
            $pdf = PDF::loadView('dashboard.transaksi.struk', [
                'penjualan' => $penjualan,
                'request' => $request, // Mengirimkan data request
            ]);

            return $pdf->download('struk-transaksi-' . $penjualan->id . '.pdf');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan transaksi: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan transaksi.'])->withInput();
        }
    }





    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kasir  $kasir
     * @return \Illuminate\Http\Response
     */
    public function show(Kasir $kasir)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kasir  $kasir
     * @return \Illuminate\Http\Response
     */
    public function edit(Kasir $kasir)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kasir  $kasir
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kasir $kasir)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kasir  $kasir
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kasir $kasir)
    {
        //
    }
}
