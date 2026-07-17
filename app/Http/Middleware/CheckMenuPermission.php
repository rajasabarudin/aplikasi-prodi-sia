<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckMenuPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // King has access to everything
        if ($user->level === 'king') {
            return $next($request);
        }

        // Get the menu from the second URL segment (e.g. /admin/dosen -> dosen)
        $menu = $request->segment(2);

        // If it's the admin dashboard itself (/admin), allow access
        if (empty($menu)) {
            return $next($request);
        }

        // Only King can access 'akun' (User management) and 'hak-akses' (Permission management)
        if ($menu === 'akun' || $menu === 'hak-akses') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak! Menu ini hanya dapat diakses oleh King.');
        }

        // Map sub-features to their parent menu permissions
        $subFeatureMappings = [
            'hki' => ['dosen', 'mahasiswa'],
            'prestasi-mahasiswa' => ['mahasiswa'],
            'organisasi-mahasiswa' => ['mahasiswa'],
            'tugas-mahasiswa' => ['mahasiswa'],
            'capstone-mahasiswa' => ['mahasiswa'],
            'sertifikasi-dosen' => ['dosen'],
            'kegiatan-dosen' => ['dosen'],
        ];

        if (array_key_exists($menu, $subFeatureMappings)) {
            $allowedParents = $subFeatureMappings[$menu];
            foreach ($allowedParents as $parent) {
                if ($user->hasPermission($parent)) {
                    return $next($request);
                }
            }
        }

        // Check if the user has permission for this menu
        if ($user->hasPermission($menu)) {
            return $next($request);
        }

        // If no permission, redirect to dashboard with an error
        return redirect()->route('dashboard')->with('error', 'Akses ditolak! Anda tidak memiliki hak akses untuk menu ini.');
    }
}
