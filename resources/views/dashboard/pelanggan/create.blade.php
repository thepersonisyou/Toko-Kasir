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

<form action="" method="POST" enctype="multipart/form-data" class="w-50">
    @csrf
    <div class="mb-3">
        <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
        <input type="text" name="nama_pelanggan" class="form-control" id="nama_pelanggan" required>
    </div>

    <div class="mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <input type="text" name="alamat" class="form-control" id="alamat" required>
    </div>

    <div class="mb-3">
        <label for="no_telp" class="form-label">No Telpon</label>
        <input type="number" name="no_telp" class="form-control" id="no_telp" required>
    </div>

    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Pelanggan</button>
</form>

@endsection
