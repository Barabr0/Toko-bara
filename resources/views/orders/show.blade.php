@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm">

        {{-- Header Order --}}
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h4 class="fw-bold mb-1">
                        Order {{ $order->order_number }}
                    </h4>
                    <small class="text-muted">
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </small>
                </div>

                {{-- Status --}}
                @php
                    $statusClass = match($order->status) {
                        'pending' => 'warning',
                        'processing' => 'primary',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    };
                @endphp

                <span class="badge rounded-pill bg-{{ $statusClass }} px-3 py-2">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>

        {{-- Detail Produk --}}
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Produk yang Dipesan</h5>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td class="text-end">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        @if ($order->shipping_cost > 0)
                        <tr>
                            <td colspan="3" class="text-end">Ongkos Kirim</td>
                            <td class="text-end">
                                Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th colspan="3" class="text-end fs-5">TOTAL</th>
                            <th class="text-end fs-5 text-primary">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Alamat --}}
        <div class="card-footer bg-light">
            <h5 class="fw-semibold mb-2">Alamat Pengiriman</h5>
            <p class="mb-1 fw-medium">{{ $order->shipping_name }}</p>
            <p class="mb-1">{{ $order->shipping_phone }}</p>
            <p class="mb-0">{{ $order->shipping_address }}</p>
        </div>

        {{-- Tombol Bayar --}}
        @if ($order->status === 'pending' && $snapToken)
        <div class="card-footer text-center bg-primary bg-opacity-10">
            <p class="text-muted mb-3">
                Selesaikan pembayaran sebelum batas waktu berakhir.
            </p>
            <button id="pay-button" class="btn btn-primary btn-lg px-5">
                💳 Bayar Sekarang
            </button>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
@if ($snapToken)
<script src="{{ config('midtrans.snap_url') }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.getElementById('pay-button')?.addEventListener('click', function () {
    const btn = this;
    btn.disabled = true;
    btn.innerText = 'Memproses...';

    snap.pay('{{ $snapToken }}', {
        onSuccess: () => window.location.href = '{{ route("orders.success", $order) }}',
        onPending: () => window.location.href = '{{ route("orders.pending", $order) }}',
        onError: () => {
            alert('Pembayaran gagal');
            btn.disabled = false;
            btn.innerText = '💳 Bayar Sekarang';
        },
        onClose: () => {
            btn.disabled = false;
            btn.innerText = '💳 Bayar Sekarang';
        }
    });
});
</script>
@endif
@endpush
