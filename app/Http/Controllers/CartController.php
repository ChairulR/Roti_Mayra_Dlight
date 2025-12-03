<?php

namespace App\Http\Controllers;

use App\Models\Bread;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class CartController extends Controller
{
    public function add(Request $request, Bread $bread)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);

        $quantity = $request->quantity;

        if (isset($cart[$bread->id])) {
            $cart[$bread->id]['quantity'] += $quantity;
        } else {
            $cart[$bread->id] = [
                "name" => $bread->name,
                "quantity" => $quantity,
                "price" => $bread->price,
                "image" => $bread->image
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Keranjang diperbarui!');
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang!');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Keranjang dikosongkan!');
    }

    public function processOrder(Request $request) // ⬅️ GANTI NAMA METHOD DAN TAMBAH REQUEST
    {
        // 1. VALIDASI DATA PELANGGAN DARI FORM
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:15',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            // Jika keranjang kosong
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        // Ambil data pelanggan dari form, BUKAN dari auth()->user()
        $customerName = $request->customer_name;
        $customerPhone = $request->customer_phone;
        $orderTime = Carbon::now();

        // 2. LOGIKA PEMBENTUKAN PESAN WHATSAPP & DATA PENYIMPANAN
        $message = "*MAYRA D'LIGHT BAKERY*\n═══════════════════════\n\n*Orderan Baru Masuk!* \n\n";
        $message .= "╔═══════════════════════\n";
        $message .= "║ Customer: *{$customerName}*\n"; // Menggunakan input form
        $message .= "║ No. HP: {$customerPhone}\n";     // Nomor HP pelanggan
        $message .= "║ " . $orderTime->format('d M Y, H:i') . "\n";
        $message .= "╚═══════════════════════\n\n";
        $message .= "*RINCIAN PESANAN:*\n─────────────────────────\n";

        $total = 0;
        $itemNumber = 1;
        $storageDetails = []; // ⬅️ Array untuk kolom details_json

        // Loop Keranjang
        foreach ($cart as $id => $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;

            // Format Pesan WA
            $message .= "\n Item #{$itemNumber}\n";
            $message .= " Produk: *{$item['name']}*\n";
            $message .= " Jumlah: {$item['quantity']} pcs\n";
            $message .= " Harga Satuan: Rp " . number_format($item['price'], 0, ',', '.') . "\n";
            $message .= " Subtotal: *Rp " . number_format($subtotal, 0, ',', '.') . "*\n";
            $message .= "─────────────────────────";

            $itemNumber++;

            // Simpan Detail Item
            $storageDetails[] = [
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $subtotal,
            ];
        }

        // Lanjutan Pesan Total
        $message .= "\n\n🧾 *TOTAL PEMBAYARAN*\n";
        $message .= "*Rp " . number_format($total, 0, ',', '.') . "*\n\n";
        $message .= "═══════════════════════\n";
        $message .= "Terima kasih telah berbelanja! \n";
        $message .= "_Mohon konfirmasi pesanan ini_ ";


        // 3. TAHAP KRUSIAL: SIMPAN ORDER KE DATABASE
        Order::create([
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'total_price' => $total,
            'order_date' => $orderTime,
            'status' => 'pending', // Status awal pesanan
            'details_json' => json_encode($storageDetails), // Simpan rincian item
        ]);

        // 4. KOSONGKAN KERANJANG
        session()->forget('cart');

        // 5. REDIRECT KE WHATSAPP
        $waNumber = 6289670655384;
        $encodedMessage = urlencode($message);
        $waUrl = "https://wa.me/{$waNumber}?text={$encodedMessage}";

        return redirect()->away($waUrl); // Menggunakan redirect()->away() untuk URL eksternal
    }
}
