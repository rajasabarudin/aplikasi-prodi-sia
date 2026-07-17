<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Rute Bantuan untuk menjalankan Migrasi Database di Hosting cPanel
Route::get('/migrate-db', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return 'Migrasi Database Berhasil! Silakan kembali ke aplikasi.';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
use App\Http\Controllers\DosenController;
use App\Http\Controllers\TendikController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\TSController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RekognisiDosenController;
use App\Http\Controllers\PrestasiDosenController;
use App\Http\Controllers\PenelitianDosenController;
use App\Http\Controllers\HibahPenelitianController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HakAksesController;
use App\Http\Controllers\PmbController;
use App\Http\Controllers\KerjasamaController;
use App\Http\Controllers\PksIaController;
use App\Http\Controllers\IpkMahasiswaController;
use App\Http\Controllers\HkiController;
use App\Http\Controllers\PrestasiMahasiswaController;
use App\Http\Controllers\OrganisasiMahasiswaController;
use App\Http\Controllers\SertifikasiMahasiswaController;
use App\Http\Controllers\TugasMahasiswaController;
use App\Http\Controllers\CapstoneMahasiswaController;
use App\Http\Controllers\MatakuliahController;
use App\Http\Controllers\SertifikasiDosenController;
use App\Http\Controllers\KegiatanDosenController;
use App\Http\Controllers\PKMDosenController;
use App\Http\Controllers\PraktisiController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\CplController;
use App\Http\Controllers\CpmkController;
use App\Http\Controllers\RpsBahanKajianController;
use App\Http\Controllers\RpsReferensiController;
use App\Http\Controllers\ProfilProdiController;
use App\Http\Controllers\BeritaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Public Frontend Routes
Route::get('/', [DashboardController::class, 'welcome'])->name('welcome');
Route::get('/profil', [DashboardController::class, 'profilProdiPublic'])->name('profil-prodi.public');
Route::get('/berita', [DashboardController::class, 'beritaPublic'])->name('berita.public');
Route::get('/berita/{slug}', [DashboardController::class, 'bacaBerita'])->name('berita.baca');

// Public Kegiatan Registration Routes
Route::get('/kegiatan-portal/cek-identitas', [DashboardController::class, 'cekIdentitas'])->name('portal.kegiatan.cek-identitas');
Route::get('/kegiatan-portal', [DashboardController::class, 'portalKegiatan'])->name('portal.kegiatan');
Route::post('/kegiatan-portal/{kegiatan}/daftar', [DashboardController::class, 'daftarKegiatan'])->name('portal.kegiatan.daftar');
Route::get('/kegiatan-portal/kartu/{peserta}', [DashboardController::class, 'kartuKegiatan'])->name('portal.kegiatan.kartu');
Route::post('/kegiatan-portal/peserta/{peserta}/upload-bukti', [DashboardController::class, 'uploadBuktiPembayaran'])->name('portal.kegiatan.upload-bukti');
Route::post('/kegiatan-portal/peserta/{peserta}/upload-foto', [DashboardController::class, 'uploadFotoPeserta'])->name('portal.kegiatan.upload-foto');
Route::get('/kegiatan-portal/{kegiatan}/peserta/{peserta}/sertifikat', [DashboardController::class, 'cetakSertifikatPublic'])->name('portal.kegiatan.sertifikat');
Route::post('/kegiatan-portal/{kegiatan}/presensi-mandiri', [DashboardController::class, 'presensiMandiriOnline'])->name('portal.kegiatan.presensi-mandiri');
Route::get('/kegiatan-portal/{kegiatan}/absensi-mandiri', [DashboardController::class, 'absensiMandiriPage'])->name('portal.kegiatan.absensi-mandiri-page');
Route::post('/kegiatan-portal/{kegiatan}/absensi-mandiri', [DashboardController::class, 'absensiMandiriSubmit'])->name('portal.kegiatan.absensi-mandiri-submit');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout']); // Fallback GET for easy logout

// Protected Backend Routes (Only accessible by authenticated users with 'king' level)
Route::middleware(['auth', 'permission'])->prefix('admin')->group(function () {
    
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('profil-prodi', [ProfilProdiController::class, 'index'])->name('profil-prodi.index');
    Route::post('profil-prodi', [ProfilProdiController::class, 'update'])->name('profil-prodi.update');
    Route::resource('berita', BeritaController::class)->parameters([
        'berita' => 'berita'
    ]);

    Route::post('dosen/{dosen}/update-photo', [DosenController::class, 'updatePhoto'])->name('dosen.update-photo');
    Route::post('dosen/{dosen}/update-keanggotaan', [DosenController::class, 'updateKeanggotaan'])->name('dosen.update-keanggotaan');
    Route::get('dosen/{dosen}/card', [DosenController::class, 'card'])->name('dosen.card');
    Route::get('dosen/{dosen}/cetak-profil', [DosenController::class, 'cetakProfil'])->name('dosen.cetak-profil');
    Route::resource('dosen', DosenController::class);
    Route::resource('tendik', TendikController::class);
    
    Route::get('mahasiswa/template', [MahasiswaController::class, 'template'])->name('mahasiswa.template');
    Route::post('mahasiswa/import', [MahasiswaController::class, 'import'])->name('mahasiswa.import');
    Route::get('mahasiswa/{mahasiswa}/card', [MahasiswaController::class, 'card'])->name('mahasiswa.card');
    Route::get('mahasiswa/{mahasiswa}/cetak-profil', [MahasiswaController::class, 'cetakProfil'])->name('mahasiswa.cetak-profil');
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::post('hki', [HkiController::class, 'store'])->name('hki.store');
    Route::put('hki/{hki}', [HkiController::class, 'update'])->name('hki.update');
    Route::delete('hki/{hki}', [HkiController::class, 'destroy'])->name('hki.destroy');
    Route::post('prestasi-mahasiswa', [PrestasiMahasiswaController::class, 'store'])->name('prestasi-mahasiswa.store');
    Route::put('prestasi-mahasiswa/{prestasi_mahasiswa}', [PrestasiMahasiswaController::class, 'update'])->name('prestasi-mahasiswa.update');
    Route::delete('prestasi-mahasiswa/{prestasi_mahasiswa}', [PrestasiMahasiswaController::class, 'destroy'])->name('prestasi-mahasiswa.destroy');
    Route::post('organisasi-mahasiswa', [OrganisasiMahasiswaController::class, 'store'])->name('organisasi-mahasiswa.store');
    Route::put('organisasi-mahasiswa/{organisasi_mahasiswa}', [OrganisasiMahasiswaController::class, 'update'])->name('organisasi-mahasiswa.update');
    Route::delete('organisasi-mahasiswa/{organisasi_mahasiswa}', [OrganisasiMahasiswaController::class, 'destroy'])->name('organisasi-mahasiswa.destroy');
    Route::post('tugas-mahasiswa', [TugasMahasiswaController::class, 'store'])->name('tugas-mahasiswa.store');
    Route::put('tugas-mahasiswa/{tugas_mahasiswa}', [TugasMahasiswaController::class, 'update'])->name('tugas-mahasiswa.update');
    Route::delete('tugas-mahasiswa/{tugas_mahasiswa}', [TugasMahasiswaController::class, 'destroy'])->name('tugas-mahasiswa.destroy');
    Route::post('capstone-mahasiswa', [CapstoneMahasiswaController::class, 'store'])->name('capstone-mahasiswa.store');
    Route::put('capstone-mahasiswa/{capstone_mahasiswa}', [CapstoneMahasiswaController::class, 'update'])->name('capstone-mahasiswa.update');
    Route::delete('capstone-mahasiswa/{capstone_mahasiswa}', [CapstoneMahasiswaController::class, 'destroy'])->name('capstone-mahasiswa.destroy');
    Route::get('ipk/template', [IpkMahasiswaController::class, 'template'])->name('ipk.template');
    Route::post('ipk/import', [IpkMahasiswaController::class, 'import'])->name('ipk.import');
    Route::resource('ipk', IpkMahasiswaController::class);
    
    Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);
    Route::resource('matakuliah', MatakuliahController::class);
    Route::put('matakuliah/{matakuliah}/update-sks', [MatakuliahController::class, 'updateSks'])->name('matakuliah.update-sks');
    Route::delete('matakuliah/{matakuliah}/clear-document/{type}', [MatakuliahController::class, 'clearDocument'])->name('matakuliah.clear-document');
    
    Route::get('sertifikasi-mahasiswa/template', [SertifikasiMahasiswaController::class, 'template'])->name('sertifikasi-mahasiswa.template');
    Route::post('sertifikasi-mahasiswa/import', [SertifikasiMahasiswaController::class, 'import'])->name('sertifikasi-mahasiswa.import');
    Route::resource('sertifikasi-mahasiswa', SertifikasiMahasiswaController::class);
    Route::resource('sertifikasi-dosen', SertifikasiDosenController::class);
    Route::get('kegiatan-dosen/get-dosen/{kode}', [KegiatanDosenController::class, 'getDosen'])->name('kegiatan-dosen.get-dosen');
    Route::resource('kegiatan-dosen', KegiatanDosenController::class);

    Route::get('kegiatan-tendik/get-tendik/{kode}', [\App\Http\Controllers\KegiatanTendikController::class, 'getTendik'])->name('kegiatan-tendik.get-tendik');
    Route::resource('kegiatan-tendik', \App\Http\Controllers\KegiatanTendikController::class);
    Route::get('pkm-dosen/template', [PKMDosenController::class, 'template'])->name('pkm-dosen.template');
    Route::post('pkm-dosen/import', [PKMDosenController::class, 'import'])->name('pkm-dosen.import');
    Route::get('pkm-dosen/get-dosen/{kode}', [PKMDosenController::class, 'getDosen'])->name('pkm-dosen.get-dosen');
    Route::get('pkm-dosen/get-mahasiswa/{nim}', [PKMDosenController::class, 'getMahasiswa'])->name('pkm-dosen.get-mahasiswa');
    Route::resource('pkm-dosen', PKMDosenController::class)->except(['show']);

    Route::resource('ts', TSController::class)->parameters(['ts' => 'ts']);
    
    Route::get('rekognisi-dosen/get-dosen/{kode}', [RekognisiDosenController::class, 'getDosen'])->name('rekognisi-dosen.get-dosen');
    Route::resource('rekognisi-dosen', RekognisiDosenController::class);
    
    Route::get('prestasi-dosen/get-dosen/{kode}', [PrestasiDosenController::class, 'getDosen'])->name('prestasi-dosen.get-dosen');
    Route::resource('prestasi-dosen', PrestasiDosenController::class);

    Route::get('penelitian-dosen/get-dosen/{kode}', [PenelitianDosenController::class, 'getDosen'])->name('penelitian-dosen.get-dosen');
    Route::get('penelitian-dosen/get-mahasiswa/{nim}', [PenelitianDosenController::class, 'getMahasiswa'])->name('penelitian-dosen.get-mahasiswa');
    Route::post('penelitian-dosen/{penelitian_dosen}/update-document', [PenelitianDosenController::class, 'updateDocument'])->name('penelitian-dosen.update-document');
    Route::resource('penelitian-dosen', PenelitianDosenController::class);

    Route::get('hibah-penelitian/get-dosen/{kode}', [HibahPenelitianController::class, 'getDosen'])->name('hibah-penelitian.get-dosen');
    Route::get('hibah-penelitian/get-mahasiswa/{nim}', [HibahPenelitianController::class, 'getMahasiswa'])->name('hibah-penelitian.get-mahasiswa');
    Route::post('hibah-penelitian/{hibah_penelitian}/update-document', [HibahPenelitianController::class, 'updateDocument'])->name('hibah-penelitian.update-document');
    Route::resource('hibah-penelitian', HibahPenelitianController::class);

    Route::resource('akun', AkunController::class);
    Route::resource('hak-akses', HakAksesController::class)->only(['index', 'store']);
    Route::resource('pmb', PmbController::class);
    Route::resource('kerjasama', KerjasamaController::class);
    Route::resource('pks-ia', PksIaController::class)->parameters([
        'pks-ia' => 'pks_ia'
    ]);
    Route::post('praktisi/{praktisi}/upload-dokumen', [PraktisiController::class, 'uploadDokumen'])->name('praktisi.upload-dokumen');
    Route::resource('praktisi', PraktisiController::class);

    // Kegiatan (Events) Management
    Route::post('kegiatan/{kegiatan}/peserta/{peserta}/verifikasi-pembayaran', [KegiatanController::class, 'verifikasiPembayaran'])->name('kegiatan.peserta.verifikasi-pembayaran');
    Route::post('kegiatan/{kegiatan}/peserta', [KegiatanController::class, 'addPeserta'])->name('kegiatan.peserta.store');
    Route::delete('kegiatan/{kegiatan}/peserta/{peserta}', [KegiatanController::class, 'destroyPeserta'])->name('kegiatan.peserta.destroy');
    Route::post('kegiatan/{kegiatan}/peserta/{peserta}/hadir', [KegiatanController::class, 'markHadir'])->name('kegiatan.peserta.hadir');
    Route::get('kegiatan/{kegiatan}/presensi', [KegiatanController::class, 'presensi'])->name('kegiatan.presensi');
    Route::post('kegiatan/{kegiatan}/presensi', [KegiatanController::class, 'submitPresensi'])->name('kegiatan.presensi.submit');
    Route::get('kegiatan/{kegiatan}/cetak-presensi', [KegiatanController::class, 'cetakPresensi'])->name('kegiatan.cetak-presensi');
    Route::get('kegiatan/{kegiatan}/peserta/{peserta}/sertifikat', [KegiatanController::class, 'cetakSertifikat'])->name('kegiatan.sertifikat.cetak');
    Route::post('kegiatan/{kegiatan}/toggle-presensi-online', [KegiatanController::class, 'togglePresensiOnline'])->name('kegiatan.toggle-presensi-online');
    Route::resource('kegiatan', KegiatanController::class);

    // RPS Master Data
    Route::resource('rps-cpl', CplController::class)->except(['show'])->parameters(['rps-cpl' => 'rpsCpl']);
    Route::resource('rps-cpmk', CpmkController::class)->except(['show'])->parameters(['rps-cpmk' => 'rpsCpmk']);
    Route::resource('rps-bahan-kajian', RpsBahanKajianController::class)->except(['show'])->parameters(['rps-bahan-kajian' => 'rpsBahanKajian']);
    Route::resource('rps-referensi', RpsReferensiController::class)->except(['show'])->parameters(['rps-referensi' => 'rpsReferensi']);
    Route::get('penyusunan-rps/get-bahan-kajian/{kode}', [\App\Http\Controllers\RpsController::class, 'getBahanKajian'])->name('penyusunan-rps.get-bahan-kajian');
    Route::get('penyusunan-rps/{penyusunan_rp}/cetak', [\App\Http\Controllers\RpsController::class, 'cetak'])->name('penyusunan-rps.cetak');
    Route::resource('penyusunan-rps', \App\Http\Controllers\RpsController::class);

    // Penyusunan RTM
    Route::post('/penyusunan-rtm/{id}/generate', [\App\Http\Controllers\RtmController::class, 'generate'])->name('penyusunan-rtm.generate');
    Route::get('/penyusunan-rtm/{id}/cetak', [\App\Http\Controllers\RtmController::class, 'cetak'])->name('penyusunan-rtm.cetak');
    Route::resource('penyusunan-rtm', \App\Http\Controllers\RtmController::class);

    // Penyusunan Silabus
    Route::post('/penyusunan-silabus/{id}/generate', [\App\Http\Controllers\SilabusController::class, 'generate'])->name('penyusunan-silabus.generate');
    Route::get('/penyusunan-silabus/{id}/cetak', [\App\Http\Controllers\SilabusController::class, 'cetak'])->name('penyusunan-silabus.cetak');
    Route::resource('penyusunan-silabus', \App\Http\Controllers\SilabusController::class)->parameters(['penyusunan-silabus' => 'silabus']);

    // OBE Accreditation Portal
    Route::get('obe-portal', [\App\Http\Controllers\ObePortalController::class, 'index'])->name('obe.index');
    Route::post('obe-portal/save-narrative', [\App\Http\Controllers\ObePortalController::class, 'saveNarrative'])->name('obe.save-narrative');
    Route::get('obe-portal/cetak', [\App\Http\Controllers\ObePortalController::class, 'cetak'])->name('obe.cetak');
    Route::get('obe-portal/input-nilai', [\App\Http\Controllers\ObePortalController::class, 'inputScore'])->name('obe.input-score');
    Route::get('obe-portal/get-cpmks/{rpsId}', [\App\Http\Controllers\ObePortalController::class, 'getCpmks']);
    Route::post('obe-portal/get-students', [\App\Http\Controllers\ObePortalController::class, 'getStudents']);
    Route::post('obe-portal/store-score', [\App\Http\Controllers\ObePortalController::class, 'storeScore'])->name('obe.store-score');
    Route::post('obe-portal/store-cqi', [\App\Http\Controllers\ObePortalController::class, 'storeCqi'])->name('obe.store-cqi');
    Route::post('obe-portal/store-survey', [\App\Http\Controllers\ObePortalController::class, 'storeSurvey'])->name('obe.store-survey');
    Route::post('obe-portal/upload-baak', [\App\Http\Controllers\ObePortalController::class, 'uploadBaak'])->name('obe.upload-baak');
    Route::get('obe-portal/download-template', [\App\Http\Controllers\ObePortalController::class, 'downloadTemplate'])->name('obe.download-template');
    Route::post('obe-portal/upload-ppepp-document', [\App\Http\Controllers\ObePortalController::class, 'uploadPpeppDocument'])->name('obe.upload-ppepp');
    Route::get('obe-portal/download-ppepp-document/{id}', [\App\Http\Controllers\ObePortalController::class, 'downloadPpeppDocument'])->name('obe.download-ppepp');
    Route::get('obe-portal/view-ppepp-document/{id}', [\App\Http\Controllers\ObePortalController::class, 'viewPpeppDocument'])->name('obe.view-ppepp');
    Route::delete('obe-portal/delete-ppepp-document/{id}', [\App\Http\Controllers\ObePortalController::class, 'deletePpeppDocument'])->name('obe.delete-ppepp');
    Route::get('obe-portal/pdf-recap/{kriteria}/{ppepp}', [\App\Http\Controllers\ObePortalController::class, 'pdfRecap'])->name('obe.pdf-recap');
    Route::get('obe-portal/transkrip-cpl', [\App\Http\Controllers\ObePortalController::class, 'transkripCpl'])->name('obe.transkrip');
    Route::get('obe-portal/get-student-cpl', [\App\Http\Controllers\ObePortalController::class, 'getStudentCpl']);

    // Tracer Study & Alumni
    Route::resource('tracer-study', \App\Http\Controllers\TracerStudyController::class);

    // Tabel Kohort Lulusan & Drop Out
    Route::get('kohort', [\App\Http\Controllers\MahasiswaDropOutController::class, 'index'])->name('kohort.index');
    Route::get('kohort/cetak', [\App\Http\Controllers\MahasiswaDropOutController::class, 'cetak'])->name('kohort.cetak');
    Route::post('kohort', [\App\Http\Controllers\MahasiswaDropOutController::class, 'store'])->name('kohort.store');

    // Keuangan Prodi
    Route::get('keuangan-prodi/cetak', [\App\Http\Controllers\KeuanganProdiController::class, 'cetak'])->name('keuangan-prodi.cetak');
    Route::resource('keuangan-prodi', \App\Http\Controllers\KeuanganProdiController::class)->except(['create', 'edit', 'show', 'update']);

    // Survei Kepuasan
    Route::resource('survei-kepuasan', \App\Http\Controllers\SurveiKepuasanController::class)->except(['create', 'edit', 'show', 'update']);
});
