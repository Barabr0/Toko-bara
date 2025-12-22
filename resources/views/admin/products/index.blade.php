@extends('layouts.admin')

@section('title','daftar produk')

@section('content')
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h3 text-smoke-8000">Daftar Produk</h2>
                <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary">Tambah produk</a>
            </div>
            <div class="table-responsive">
                <div class="card shadow-sm mb-4">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Gambar Produk</th>
                                <th>Nama produk</th>
                                <th>Deskripsi Produk</th>
                                <th>Harga produk</th>
                                <th>Stok produk</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                <tr>
                    <td>
                        <img src="{{ $product->primaryImage?->image_url ?? asset('img/no-image.png') }}" class="rounded"
                            width="60">
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>Rp {{ number_format($product->price) }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info">Detail</a>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">Data produk kosong</td>
                </tr>
                @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

@endsection
