@extends('dashboard.layouts.main')

@section('isi')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
        For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official
            DataTables documentation</a>.</p>

    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Tables {{ $title }}</h6>

            <form class="d-flex mx-3" action="" method="GET">
                <input class="form-control form-control-sm me-2" type="search" name="search"
                    placeholder="Cari produk..." aria-label="Search" value="{{ $search }}">
                <button class="btn btn-outline-secondary btn-sm" type="submit">Cari</button>
            </form>

            <a class="btn btn-primary plus btn-sm" href="/create">
                <i class="bi bi-file-earmark-plus"></i> Tambah Product
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Product</th>
                            <th>Foto Product</th>
                            <th>Ditambahkan Pada Tanggal</th>
                            <th>Stok</th>
                            <th>Harga /Pcs</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_product }}</td>
                                <td>
                                    @if($item->img)
                                        <img src="{{ asset('storage/' . $item->img) }}" alt="Product Image" width="50">
                                    @else
                                        No Images
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                <td>{{ $item->stok }}</td>
                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $item->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <a href="/product/delete/{{ $item->id }}" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Apakah Anda Yakin Untuk Hapus Product {{ $item->nama_product }}?')">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit (Letakkan di luar tr) -->
                            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
                                aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ url('/product/update/' . $item->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit Produk</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                        
                                            <div class="modal-body">
                                                {{-- Nama Produk --}}
                                                <div class="mb-3">
                                                    <label for="nama_product{{ $item->id }}" class="form-label">Nama Produk</label>
                                                    <input type="text" class="form-control" name="nama_product" id="nama_product{{ $item->id }}" value="{{ $item->nama_product }}" required>
                                                </div>
                                        
                                                {{-- Gambar Produk --}}
                                                <div class="mb-3">
                                                    <label for="img" class="form-label">Foto Produk</label>
                                                    <input class="form-control" name="img" type="file" id="img">
                                                    <img src="{{ asset('storage/' . $item->img) }}" class="img-thumbnail mt-3" alt="..."
                                                        style="width: 130px; height: 120px; object-fit: cover;">
                                                    <div class="form-text">Jika Ingin Di Ubah Pastikan Ekstension PNG | JPG | JPEG</div>
                                                </div>
                                        
                                                {{-- Stok --}}
                                                <div class="mb-3">
                                                    <label for="stok{{ $item->id }}" class="form-label">Stok</label>
                                                    <input type="number" class="form-control" name="stok" id="stok{{ $item->id }}" value="{{ $item->stok }}" required>
                                                </div>
                                        
                                                {{-- Harga --}}
                                                <div class="mb-3">
                                                    <label for="harga{{ $item->id }}" class="form-label">Harga</label>
                                                    <input type="number" class="form-control" name="harga" id="harga{{ $item->id }}" value="{{ $item->harga }}" required>
                                                </div>
                                            </div>
                                        
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                        
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                        Showing {{ $product->firstItem() }} to {{ $product->lastItem() }} of {{ $product->total() }} entries
                    </div>
                    <div>{{ $product->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

     {{-- <nav aria-label="Page navigation">
                            <ul class="pagination mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                </li>
                                <li class="page-item active" aria-current="page">
                                    <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav> --}}
