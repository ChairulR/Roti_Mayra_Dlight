<?php

namespace App\Http\Controllers;

use App\Models\Bread;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $breads = Bread::with('category')->get();
        return view('admin.dashboard', compact('categories', 'breads'));
    }

    // Tambah kategori
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        Category::create(['name' => $request->name]);
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    // Tambah roti
    public function storeBread(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price', 'category_id']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('breads', 'public');
            $data['image'] = $path;
        }

        Bread::create($data);
        return redirect()->back()->with('success', 'Roti berhasil ditambahkan.');
    }

    // Hapus roti
    public function destroyBread(Bread $bread)
    {
        if ($bread->image) {
            Storage::disk('public')->delete($bread->image);
        }

        $bread->delete();
        return redirect()->back()->with('success', 'Roti dihapus.');
    }
}
