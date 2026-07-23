<?php
$data = DB::table('peserta_kegiatans')->join('kegiatans', 'kegiatans.id', '=', 'peserta_kegiatans.kegiatan_id')->where('peserta_kegiatans.kategori', 'Dosen')->select('peserta_kegiatans.nama as Nama_Dosen', 'peserta_kegiatans.identifier as NIDN_NIP', 'kegiatans.nama_kegiatan as Kegiatan', 'kegiatans.tanggal as Tanggal', 'kegiatans.jenis_kegiatan as Jenis_Kegiatan', 'peserta_kegiatans.status_kehadiran as Status_Kehadiran')->get();
$rows = is_array($data) ? $data : (method_exists($data, 'toArray') ? $data->toArray() : (array)$data);
$first = (array) reset($rows);
echo json_encode($first);
