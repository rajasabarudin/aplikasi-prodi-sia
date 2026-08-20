<?php

namespace App\Http\Controllers;

use App\Models\PenghargaanUniversitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PenghargaanUniversitasController extends Controller
{
    public function index()
    {
        $penghargaan = PenghargaanUniversitas::orderBy('tahun', 'desc')->get();
        return view('penghargaan-universitas.index', compact('penghargaan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|string|max:4',
            'nama_mitra' => 'required|string|max:255',
            'nomor_penghargaan' => 'nullable|string|max:255',
            'link_dokumen_penghargaan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'link_berita' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('link_dokumen_penghargaan')) {
            $file = $request->file('link_dokumen_penghargaan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/penghargaan'), $filename);
            $validated['link_dokumen_penghargaan'] = 'uploads/penghargaan/' . $filename;
        }

        PenghargaanUniversitas::create($validated);
        return redirect()->route('penghargaan-universitas.index')->with('success', 'Data Penghargaan Universitas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $penghargaan = PenghargaanUniversitas::findOrFail($id);
        
        $validated = $request->validate([
            'tahun' => 'required|string|max:4',
            'nama_mitra' => 'required|string|max:255',
            'nomor_penghargaan' => 'nullable|string|max:255',
            'link_dokumen_penghargaan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'link_berita' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('link_dokumen_penghargaan')) {
            // Hapus file lama jika ada
            if ($penghargaan->link_dokumen_penghargaan && file_exists(public_path($penghargaan->link_dokumen_penghargaan))) {
                unlink(public_path($penghargaan->link_dokumen_penghargaan));
            }
            
            $file = $request->file('link_dokumen_penghargaan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/penghargaan'), $filename);
            $validated['link_dokumen_penghargaan'] = 'uploads/penghargaan/' . $filename;
        } else {
            // Jangan timpa jika tidak ada file baru
            unset($validated['link_dokumen_penghargaan']);
        }

        $penghargaan->update($validated);
        return redirect()->route('penghargaan-universitas.index')->with('success', 'Data Penghargaan Universitas berhasil diupdate.');
    }

    public function destroy($id)
    {
        $penghargaan = PenghargaanUniversitas::findOrFail($id);
        
        if ($penghargaan->link_dokumen_penghargaan && file_exists(public_path($penghargaan->link_dokumen_penghargaan))) {
            unlink(public_path($penghargaan->link_dokumen_penghargaan));
        }
        
        $penghargaan->delete();
        return redirect()->route('penghargaan-universitas.index')->with('success', 'Data Penghargaan Universitas berhasil dihapus.');
    }
}
