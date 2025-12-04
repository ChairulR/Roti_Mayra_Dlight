<?php

namespace App\Http\Controllers;

use App\Models\Bread;
use App\Models\Category;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Dashboard utama admin
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $selectedCategory = $request->category ?? null;

        $breads = Bread::with('category')
            ->when($selectedCategory, fn($q) => $q->where('category_id', $selectedCategory))
            ->get();

        $latestRatings = Rating::where('rateable_type', Bread::class)
            ->with('user', 'rateable')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $newRatingsCount = Rating::where('rateable_type', Bread::class)
            ->count();

        return view('admin.dashboard', [
            'categories'        => $categories,
            'breads'            => $breads,
            'selectedCategory'  => $selectedCategory,
            'totalCategories'   => $categories->count(),
            'totalBreads'       => Bread::count(),
            'latestRatings' => $latestRatings,
            'newRatingsCount' => $newRatingsCount,
        ]);
    }

    /**
     * Halaman Add Menu
     */
    public function addMenu()
    {
        return view('admin.add-menu', [
            'categories' => Category::all(),
            'breads'     => Bread::all(),
        ]);
    }

    /**
     * Halaman daftar kategori
     */
    public function categories()
    {
        return view('admin.categories', [
            'categories' => Category::all()
        ]);
    }

    /**
     * Tambah kategori baru
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100'
        ]);

        $category = Category::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success'   => true,
                'message'   => 'Kategori berhasil ditambahkan.',
                'category'  => $category
            ]);
        }

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Hapus kategori
     */
    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    /**
     * Halaman manajemen roti (dengan filter kategori)
     */
    public function breads(Request $request)
    {
        $categories = Category::all();
        $selectedCategory = $request->category ?? null;

        $breads = Bread::with('category')
            ->when($selectedCategory, function ($q) use ($selectedCategory) {
                $q->where('category_id', $selectedCategory);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->get();

        return view('admin.breads', compact('breads', 'categories', 'selectedCategory'));
    }

    /**
     * Simpan roti baru
     */
    public function storeBread(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric',
            'category_id'   => 'nullable|exists:categories,id',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->only(['name', 'description', 'price', 'category_id']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('breads', 'public');
        }

        Bread::create($data);

        return back()->with('success', 'Roti berhasil ditambahkan.');
    }

    /**
     * Hapus roti
     */
    public function destroyBread(Bread $bread)
    {
        if ($bread->image && Storage::disk('public')->exists($bread->image)) {
            Storage::disk('public')->delete($bread->image);
        }

        $bread->delete();

        return back()->with('success', 'Roti berhasil dihapus.');
    }

    /**
     * Halaman edit roti
     */
    public function editBread($id)
    {
        return view('admin.edit-bread', [
            'bread'      => Bread::findOrFail($id),
            'categories' => Category::all()
        ]);
    }

    /**
     * Update roti
     */
    public function updateBread(Request $request, $id)
    {
        $bread = Bread::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric',
            'category_id'   => 'nullable|exists:categories,id',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->only(['name', 'price', 'description', 'category_id']);

        if ($request->hasFile('image')) {
            if ($bread->image && Storage::disk('public')->exists($bread->image)) {
                Storage::disk('public')->delete($bread->image);
            }

            $data['image'] = $request->file('image')->store('breads', 'public');
        }

        $bread->update($data);

        return redirect()->route('admin.breads.index')
            ->with('success', 'Data roti berhasil diperbarui!');
    }

    public function togglePromoted(Request $request, Bread $bread)
    {
        try {
            \Log::info('Toggle Promoted Request', [
                'bread_id' => $bread->id,
                'current_status' => $bread->is_promoted,
                'ip' => $request->ip(),
                'is_ajax' => $request->ajax() || $request->wantsJson()
            ]);

            $maxPromoted = 3;
            $currentPromotedCount = Bread::where('is_promoted', true)->count();
            $isCurrentlyPromoted = $bread->is_promoted;

            if (!$isCurrentlyPromoted) {

                if ($currentPromotedCount >= $maxPromoted) {
                    \Log::warning('Toggle Promoted Failed: Max limit reached', [
                        'bread_id' => $bread->id,
                        'current_count' => $currentPromotedCount
                    ]);
                    
                    $message = 'Gagal: Batas maksimum ' . $maxPromoted . ' menu promosi sudah tercapai.';
                    
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'is_promoted' => false,
                            'count' => $currentPromotedCount,
                            'message' => $message,
                        ], 400);
                    }
                    
                    return redirect()->route('admin.breads.index')
                        ->with('error', $message);
                }

                $bread->is_promoted = true;
                $bread->save();

                \Log::info('Promoted Successfully', ['bread_id' => $bread->id]);

                $message = $bread->name . ' berhasil dipromosikan.';
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'is_promoted' => true,
                        'count' => $currentPromotedCount + 1,
                        'message' => $message,
                    ]);
                }
                
                return redirect()->route('admin.breads.index')
                    ->with('success', $message);
            }

            else {
                $bread->is_promoted = false;
                $bread->save();

                \Log::info('Unpromoted Successfully', ['bread_id' => $bread->id]);

                $message = $bread->name . ' promosi telah dimatikan.';
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'is_promoted' => false,
                        'count' => $currentPromotedCount - 1,
                        'message' => $message,
                    ]);
                }
                
                return redirect()->route('admin.breads.index')
                    ->with('success', $message);
            }
        } catch (\Exception $e) {
            \Log::error('Toggle Promoted Error', [
                'bread_id' => $bread->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $message = 'Terjadi kesalahan: ' . $e->getMessage();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 500);
            }
            
            return redirect()->route('admin.breads.index')
                ->with('error', $message);
        }
    }
}
