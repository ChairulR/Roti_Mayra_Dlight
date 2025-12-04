<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order; // Pastikan Anda sudah membuat Model Order
use Illuminate\Http\Request;
use Carbon\Carbon; // Untuk mempermudah manipulasi tanggal

class OrderController extends Controller
{
    /**
     * Menampilkan daftar riwayat pesanan (Order History)
     */
    public function index(Request $request)
    {
        // Mendapatkan filter dari request
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $status = $request->input('status');

        // Membangun query dasar
        $query = Order::orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($status && in_array($status, ['pending', 'completed'])) {
            $query->where('status', $status);
        }

        // Filter berdasarkan rentang tanggal
        if ($fromDate) {
            // Pastikan kita mengambil data dari awal hari (00:00:00)
            $query->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay());
        }

        if ($toDate) {
            // Pastikan kita mengambil data hingga akhir hari (23:59:59)
            $query->where('created_at', '<=', Carbon::parse($toDate)->endOfDay());
        }

        // Ambil data dengan pagination (misal 15 per halaman)
        $orders = $query->paginate(15)->withQueryString();

        // Mengirim data ke view
        return view('admin.orders.index', [
            'orders' => $orders,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'status' => $status,
        ]);
    }

    /**
     * Mengubah status pesanan (Pending <-> Completed)
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Validasi input status
        $request->validate([
            'status' => 'required|in:pending,completed',
        ]);

        // Update status
        $order->status = $request->input('status');
        $order->save();

        return redirect()->back()->with('success', "Status Pesanan #{$order->id} berhasil diubah menjadi " . ucfirst($order->status) . ".");
    }
}
