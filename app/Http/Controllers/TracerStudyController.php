<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\TracerStudy;
use Illuminate\Http\Request;

class TracerStudyController extends Controller
{
    public function index()
    {
        $alumnis = Alumni::with('tracerStudy')->orderBy('tahun_lulus', 'desc')->get();
        return view('tracer_study.index', compact('alumnis'));
    }

    public function create()
    {
        // Not used, modal approach
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|unique:alumnis',
            'nama' => 'required|string',
            'tahun_masuk' => 'required|integer',
            'tahun_lulus' => 'required|integer|gte:tahun_masuk',
            'ipk' => 'required|numeric|min:0|max:4',
            'status_kerja' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['nim', 'nama', 'tahun_masuk', 'tahun_lulus', 'ipk', 'no_telepon', 'email', 'testimoni', 'linkedin_url']);
        $data['is_featured'] = $request->has('is_featured') ? true : false;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('alumni', 'public');
        }

        $alumni = Alumni::create($data);
        
        $alumni->tracerStudy()->create($request->only([
            'status_kerja', 'waktu_tunggu', 'kesesuaian_bidang', 
            'tingkat_tempat_kerja', 'nama_perusahaan', 'jabatan', 'pendapatan_pertama'
        ]));

        return redirect()->route('tracer-study.index')->with('success', 'Data Tracer Study & Alumni berhasil ditambahkan.');
    }

    public function show($id)
    {
        // 
    }

    public function update(Request $request, $id)
    {
        $alumni = Alumni::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string',
            'tahun_masuk' => 'required|integer',
            'tahun_lulus' => 'required|integer|gte:tahun_masuk',
            'ipk' => 'required|numeric|min:0|max:4',
            'status_kerja' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['nama', 'tahun_masuk', 'tahun_lulus', 'ipk', 'no_telepon', 'email', 'testimoni', 'linkedin_url']);
        $data['is_featured'] = $request->has('is_featured') ? true : false;

        if ($request->hasFile('foto')) {
            if ($alumni->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($alumni->foto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($alumni->foto);
            }
            $data['foto'] = $request->file('foto')->store('alumni', 'public');
        }

        $alumni->update($data);
        
        if ($alumni->tracerStudy) {
            $alumni->tracerStudy->update($request->only([
                'status_kerja', 'waktu_tunggu', 'kesesuaian_bidang', 
                'tingkat_tempat_kerja', 'nama_perusahaan', 'jabatan', 'pendapatan_pertama'
            ]));
        } else {
            $alumni->tracerStudy()->create($request->only([
                'status_kerja', 'waktu_tunggu', 'kesesuaian_bidang', 
                'tingkat_tempat_kerja', 'nama_perusahaan', 'jabatan', 'pendapatan_pertama'
            ]));
        }

        return redirect()->route('tracer-study.index')->with('success', 'Data Tracer Study & Alumni berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $alumni = Alumni::findOrFail($id);
        $alumni->delete();
        return redirect()->route('tracer-study.index')->with('success', 'Data Tracer Study berhasil dihapus.');
    }

    // Public Methods for Alumni Portal
    public function publicForm()
    {
        return view('tracer_study.public_form');
    }

    public function getAlumni($nim)
    {
        $alumni = Alumni::with('tracerStudy')->where('nim', $nim)->first();
        if ($alumni) {
            return response()->json(['success' => true, 'data' => $alumni]);
        }
        return response()->json(['success' => false]);
    }

    public function publicSubmit(Request $request)
    {
        $request->validate([
            'nim' => 'required|string',
            'nama' => 'required|string',
            'tahun_masuk' => 'required|integer',
            'tahun_lulus' => 'required|integer|gte:tahun_masuk',
            'ipk' => 'required|numeric|min:0|max:4',
            'status_kerja' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $alumni = Alumni::where('nim', $request->nim)->first();

        $data = $request->only(['nama', 'tahun_masuk', 'tahun_lulus', 'ipk', 'no_telepon', 'email', 'testimoni', 'linkedin_url']);
        
        if ($request->hasFile('foto')) {
            if ($alumni && $alumni->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($alumni->foto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($alumni->foto);
            }
            $data['foto'] = $request->file('foto')->store('alumni', 'public');
        }

        if ($alumni) {
            $alumni->update($data);
        } else {
            $data['nim'] = $request->nim;
            // By default, not featured for public submissions
            $data['is_featured'] = false;
            $alumni = Alumni::create($data);
        }
        
        $tracerData = $request->only([
            'status_kerja', 'waktu_tunggu', 'kesesuaian_bidang', 
            'tingkat_tempat_kerja', 'nama_perusahaan', 'jabatan', 'pendapatan_pertama'
        ]);

        if ($alumni->tracerStudy) {
            $alumni->tracerStudy->update($tracerData);
        } else {
            $alumni->tracerStudy()->create($tracerData);
        }

        return redirect()->back()->with('success', 'Terima kasih! Data Tracer Study Anda berhasil disimpan. Semoga sukses selalu!');
    }
}
