@extends('dashboard.layouts.main')

@section('isi')
<h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
<p class="mb-4">
    Halaman ini menggunakan plugin DataTables untuk pengolahan data tabel.
    Kunjungi <a target="_blank" href="https://datatables.net">dokumentasi resmi DataTables</a> untuk informasi lebih lanjut.
</p>

<br>

@if (session('success'))
<div class="alert alert-success" role="alert">
    {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger" role="alert">
    @foreach ($errors->all() as $error)
        {{ $error }}
    @endforeach
</div>
@endif

<form action="{{ route('prostore') }}" method="POST" enctype="multipart/form-data" class="w-50">
    @csrf
    <div class="mb-3">
        <label for="nama_product" class="form-label">Nama Produk</label>
        <input type="text" name="nama_product" class="form-control" id="nama_product" required>
    </div>

    <div class="mb-3">
        <label for="img" class="form-label">Foto Produk (Opsional)</label>
        <input class="form-control" type="file" name="img" id="img">
        <div class="form-text">Jika Ingin ditambahkan Pastikan Ekstension PNG | JPG | JPEG</div>
    </div>

    <div class="mb-3">
        <label for="stok" class="form-label">Stok</label>
        <input type="number" name="stok" class="form-control" id="stok" required>
    </div>

    <div class="mb-3">
        <label for="harga" class="form-label">Harga</label>
        <input type="number" name="harga" class="form-control" id="harga" required>
    </div>

    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Produk</button>
</form>
@endsection
