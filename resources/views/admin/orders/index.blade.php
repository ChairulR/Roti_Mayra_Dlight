@extends('admin.layouts.admin')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="container-fluid">
    <h1>Riwayat Pesanan</h1>
    @include('admin.partials.alerts')

    {{-- Form Filter --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="form-inline">
                <div class="form-group mr-3">
                    <label for="from_date" class="mr-2">Dari Tanggal:</label>
                    <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
                </div>
                <div class="form-group mr-3">
                    <label for="to_date" class="mr-2">Sampai Tanggal:</label>
                    <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
                </div>
                <div class="form-group mr-3">
                    <label for="status" class="mr-2">Status:</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Semua</option>
                        <option value="Belum Selesai" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Selesai" {{ $status == 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mr-2">Filter</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Reset</a>
            </form>
        </div>
    </div>
    {{-- End Form Filter --}}

    {{-- Tabel Riwayat Pesanan --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-white">Daftar Pesanan (Total: {{ $orders->total() }})</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Total Harga</th>
                            <th width="25%">Detail Pesanan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <strong>{{ $order->customer_name }}</strong><br>
                                <a href="https://wa.me/{{ $order->customer_phone }}" target="_blank">{{ $order->customer_phone }}</a>
                            </td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>
                                @php
                                $details = is_array($order->details_json)
                                ? $order->details_json
                                : (json_decode($order->details_json, true) ?? []);
                                @endphp

                                @foreach ($details as $item)
                                {{--Menggunakan operator ?? untuk mencegah error 'Undefined array key'--}}
                                {{ $item['quantity'] ?? 0 }}x {{ $item['name'] ?? 'Item tidak diketahui' }} (@Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }})<br>
                                @endforeach
                            </td>
                            <td>
                                @if ($order->status == 'pending')
                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Tandai pesanan ini sebagai Selesai?')">
                                        PENDING
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="status" value="pending">
                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Tandai Selesai dibatalkan?')">
                                        DONE
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada riwayat pesanan yang ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection