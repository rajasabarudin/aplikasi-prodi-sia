<?php
$count = DB::table('peserta_kegiatans')->where('kategori', 'Dosen')->whereExists(function($q) { $q->select(DB::raw(1))->from('dosens')->whereColumn('dosens.nidn', 'peserta_kegiatans.identifier')->orWhereColumn('dosens.nip', 'peserta_kegiatans.identifier')->orWhereColumn('dosens.kode_dosen', 'peserta_kegiatans.identifier'); })->count();
echo "COUNT IS: " . $count;
