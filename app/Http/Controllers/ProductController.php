<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search; 
        
        $product = Product::latest()
                    ->filter($request->only('search'))
                    ->paginate(10);
    
        return view('dashboard.user.index', [
            'title' => 'Product',
            'product' => $product,
            'search' => $search,
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('dashboard.user.createproduct', [
            'title' => 'Create Product'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        try {
            // Ambil data yang sudah divalidasi dari StoreProductRequest
            $data = $request->validated();

            // Jika ada file gambar yang diunggah
            if ($request->hasFile('img')) {
                $data['img'] = $request->file('img')->store('produk-img', 'public');
            }

            // Simpan data ke database
            Product::create($data);

            return redirect()->route('indexp')->with('success', 'Product berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Log error dan tampilkan pesan kesalahan
            Log::error('Gagal menambahkan Product: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan Product.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('dashboard.user.editproduct', [
            'product' => $product,
            'title' => 'Edit Data Product'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $data = $request->validated();
    
            // Jika ada gambar baru diunggah
            if ($request->hasFile('img')) {
                // Hapus gambar lama jika ada
                if ($product->img && Storage::disk('public')->exists($product->img)) {
                    Storage::disk('public')->delete($product->img);
                }
    
                // Simpan gambar baru
                $data['img'] = $request->file('img')->store('produk-img', 'public');
            }
    
            // Update produk
            $product->update($data);
    
            return redirect()->route('indexp')->with('success', 'Produk berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal update produk: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal memperbarui produk.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->img && Storage::exists($product->img)) {
                Storage::delete($product->img);
            }

            $product->delete();

            return redirect()->route('indexp')->with('success', 'Product berhasil dihapus.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('indexp')->withErrors(['error' => 'Produk tidak ditemukan.']);
        } catch (\Exception $e) {
            Log::error('Error saat menghapus Product: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
