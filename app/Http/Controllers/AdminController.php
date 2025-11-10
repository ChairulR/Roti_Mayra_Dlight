<?php

namespace App\Http\Controllers;

use App\Models\Bread;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Dashboard utama admin
     */
    public function index()
    {
        $categories = Category::all();
        $breads = Bread::with('category')->get();
        return view('admin.dashboard', compact('categories', 'breads'));
    }

    /**
     * Add Menu Page
     */
    public function addMenu()
    {
        $categories = Category::all();
        $breads = Bread::all();

        return view('admin.add-menu', compact('categories', 'breads'));
    }

    /**
     * Manajemen kategori
     */
    public function categories()
    {
        $categories = Category::all();
        return view('admin.categories', compact('categories'));
    }

    /**
     * Simpan kategori baru
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100'
        ]);

        $category = Category::create([
            'name' => $validated['name']
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan.',
                'category' => $category
            ]);
        }

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * ✅ Halaman Manajemen Roti (Grid Card + Pencarian)
     */
    public function breads(Request $request)
    {
        $search = $request->search;

        $breads = Bread::with('category')
            ->when($search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->get();

        return view('admin.breads.index', compact('breads', 'search'));
    }

    /**
     * ✅ Simpan roti baru
     */
    public function storeBread(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->only(['name', 'description', 'price', 'category_id']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('breads', 'public');
        }

        Bread::create($data);

        return redirect()->back()->with('success', 'Roti berhasil ditambahkan.');
    }

    /**
     * ✅ Hapus roti
     */
    public function destroyBread(Bread $bread)
    {
        if ($bread->image && Storage::disk('public')->exists($bread->image)) {
            Storage::disk('public')->delete($bread->image);
        }

        $bread->delete();

        return redirect()->back()->with('success', 'Roti berhasil dihapus.');
    }

    /**
     * ✅ Halaman Edit Roti
     */
    public function editBread($id)
    {
        $bread = Bread::findOrFail($id);
        $categories = Category::all();

        return view('admin.edit-bread', compact('bread', 'categories'));
    }

    /**
     * ✅ Update Roti
     */
    public function updateBread(Request $request, $id)
    {
        $bread = Bread::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['name', 'price', 'category_id', 'description']);

        if ($request->hasFile('image')) {
            if ($bread->image && Storage::disk('public')->exists($bread->image)) {
                Storage::disk('public')->delete($bread->image);
            }
            $data['image'] = $request->file('image')->store('breads', 'public');
        }

        $bread->update($data);

        return redirect()->route('admin.breads.index')->with('success', 'Data roti berhasil diperbarui!');
    }
}
