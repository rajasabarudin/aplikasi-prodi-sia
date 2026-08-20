<?php
$file = 'resources/views/layouts/app.blade.php';
$content = file_get_contents($file);

// Add permission to masterDataMenu condition
$content = str_replace(
    "Auth::user()->hasPermission('pks-ia') || Auth::user()->hasPermission('praktisi')",
    "Auth::user()->hasPermission('pks-ia') || Auth::user()->hasPermission('praktisi') || Auth::user()->hasPermission('penghargaan-universitas')",
    $content
);

$content = str_replace(
    "request()->routeIs('pks-ia.*') || request()->routeIs('praktisi.*')",
    "request()->routeIs('pks-ia.*') || request()->routeIs('praktisi.*') || request()->routeIs('penghargaan-universitas.*')",
    $content
);

$insert = <<<HTML
                            @if(Auth::user()->hasPermission('pks-ia'))
                            <li class="nav-item">
                                <a href="{{ route('pks-ia.index') }}" class="nav-link text-white {{ request()->routeIs('pks-ia.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-file-earmark-text me-2 text-success"></i>
                                    <span class="sidebar-text">Data PKS & IA</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('penghargaan-universitas'))
                            <li class="nav-item">
                                <a href="{{ route('penghargaan-universitas.index') }}" class="nav-link text-white {{ request()->routeIs('penghargaan-universitas.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-award me-2 text-warning"></i>
                                    <span class="sidebar-text">Penghargaan Univ</span>
                                </a>
                            </li>
                            @endif
HTML;

$content = preg_replace(
    "/@if\(Auth::user\(\)->hasPermission\('pks-ia'\)\).*?<\/li>\s*@endif/s",
    $insert,
    $content,
    1
);

file_put_contents($file, $content);
