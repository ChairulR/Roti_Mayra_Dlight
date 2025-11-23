<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'gambar' => 'required|image|max:2048',
        ]);

        $path = $request->file('gambar')->store('banner', 'public');

        Banner::create([
            'gambar' => $path,
            'judul'  => $request->judul,
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil ditambahkan');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->back()->with('success', 'Banner dihapus');
    }
}

