<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Akun;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AkunController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $akuns = Akun::latest()->get();
        $totalAkun = $akuns->count();
        
        // Count by level
        $levelCounts = [
            'king' => $akuns->where('level', 'king')->count(),
            'jendral' => $akuns->where('level', 'jendral')->count(),
            'lecture' => $akuns->where('level', 'lecture')->count(),
            'mhs' => $akuns->where('level', 'mhs')->count(),
        ];

        return view('akun.index', compact('akuns', 'totalAkun', 'levelCounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('akun.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:akun,username',
            'email' => 'required|string|email|max:255|unique:akun,email',
            'password' => 'required|string|min:6',
            'level' => 'required|in:king,jendral,lecture,mhs',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_akun', 'public');
        }

        Akun::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => $request->level,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('akun.index')
            ->with('success', 'Akun berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $akun = Akun::findOrFail($id);
        return view('akun.show', compact('akun'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $akun = Akun::findOrFail($id);
        return view('akun.edit', compact('akun'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $akun = Akun::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255|unique:akun,username,' . $akun->id,
            'email' => 'required|string|email|max:255|unique:akun,email,' . $akun->id,
            'password' => 'nullable|string|min:6',
            'level' => 'required|in:king,jendral,lecture,mhs',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'level' => $request->level,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($akun->foto && Storage::disk('public')->exists($akun->foto)) {
                Storage::disk('public')->delete($akun->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto_akun', 'public');
        }

        $akun->update($data);

        return redirect()->route('akun.index')
            ->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $akun = Akun::findOrFail($id);

        if ($akun->foto && Storage::disk('public')->exists($akun->foto)) {
            Storage::disk('public')->delete($akun->foto);
        }

        $akun->delete();

        return redirect()->route('akun.index')
            ->with('success', 'Akun berhasil dihapus.');
    }
}
