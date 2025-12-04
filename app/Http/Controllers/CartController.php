<?php

namespace App\Http\Controllers;

use App\Models\Bread;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, Bread $bread)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);
        
        $quantity = $request->quantity;
        
        if(isset($cart[$bread->id])) {
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
        
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('cart.index', compact('cart', 'total'));
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if(isset($cart[$request->id])) {
            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }
        
        return redirect()->back()->with('success', 'Keranjang diperbarui!');
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if(isset($cart[$request->id])) {
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

    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if(empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        $user = auth()->user();
        $userName = $user ? $user->name : 'Guest';
        
        // Build WhatsApp message
        $message = " *MAYRA D'LIGHT BAKERY* \n";
        $message .= "═══════════════════════\n\n";
        $message .= " *Orderan Baru Masuk!* \n\n";
        $message .= "╔═══════════════════════\n";
        $message .= "║  Customer: *{$userName}*\n";
        $message .= "║  " . now()->format('d M Y, H:i') . "\n";
        $message .= "╚═══════════════════════\n\n";
        $message .= " *RINCIAN PESANAN:*\n";
        $message .= "─────────────────────────\n";
        
        $total = 0;
        $itemNumber = 1;
        foreach($cart as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            
            $message .= "\n Item #{$itemNumber}\n";
            $message .= " Produk: *{$item['name']}*\n";
            $message .= " Jumlah: {$item['quantity']} pcs\n";
            $message .= " Harga Satuan: Rp " . number_format($item['price'], 0, ',', '.') . "\n";
            $message .= " Subtotal: *Rp " . number_format($subtotal, 0, ',', '.') . "*\n";
            $message .= "─────────────────────────";
            
            $itemNumber++;
        }
        
        $message .= "\n\n🧾 *TOTAL PEMBAYARAN*\n";
        $message .= "*Rp " . number_format($total, 0, ',', '.') . "*\n\n";
        $message .= "═══════════════════════\n";
        $message .= "Terima kasih telah berbelanja! \n";
        $message .= "_Mohon konfirmasi pesanan ini_ ";
        
        $waNumber = env('WHATSAPP_NUMBER');
        
        // URL encode the message
        $encodedMessage = urlencode($message);
        
        // WhatsApp API URL
        $waUrl = "https://wa.me/{$waNumber}?text={$encodedMessage}";
        
        return redirect($waUrl);
    }
}
