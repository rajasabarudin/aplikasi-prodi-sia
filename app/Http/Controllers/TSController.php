<?php

namespace App\Http\Controllers;

use App\Models\Ts;
use Illuminate\Http\Request;

class TSController extends Controller
{
    public function index()
    {
        $ts = TS::latest()->get();
        return view('ts.index', compact('ts'));
    }

    public function create()
    {
        return view('ts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_sekarang' => [
                'required',
                \Illuminate\Validation\Rule::unique('ts')->where(function ($query) use ($request) {
                    return $query->where('semester', $request->semester);
                })
            ],
            'semester' => 'required|in:Gasal,Genap',
            'label_ts' => 'nullable|string|max:10',
        ]);

        TS::create($request->all());

        return redirect()->route('ts.index')
            ->with('success', 'Data Akademik berhasil ditambahkan.');
    }

    public function show(TS $ts)
    {
        return view('ts.show', compact('ts'));
    }

    public function edit(TS $ts)
    {
        return view('ts.edit', compact('ts'));
    }

    public function update(Request $request, TS $ts)
    {
        $request->validate([
            'tahun_sekarang' => [
                'required',
                \Illuminate\Validation\Rule::unique('ts')->ignore($ts->id)->where(function ($query) use ($request) {
                    return $query->where('semester', $request->semester);
                })
            ],
            'semester' => 'required|in:Gasal,Genap',
            'label_ts' => 'nullable|string|max:10',
        ]);

        $ts->update($request->all());

        return redirect()->route('ts.index')
            ->with('success', 'Data Akademik berhasil diperbarui.');
    }

    public function destroy(TS $ts)
    {
        $ts->delete();

        return redirect()->route('ts.index')
            ->with('success', 'Data Akademik berhasil dihapus.');
    }
}
