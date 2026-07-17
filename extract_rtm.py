import fitz
import sys
import os
import glob
import re
import json

# Ensure standard output is UTF-8 encoded
sys.stdout.reconfigure(encoding='utf-8')

def extract_rtm(kode, out_file=None):
    base_dir = os.path.dirname(os.path.abspath(__file__))
    rtm_dir = os.path.join(base_dir, "rtm")
    
    # Match RTM_<kode>_*.pdf
    pattern = os.path.join(rtm_dir, f"RTM_{kode}_*.pdf")
    files = glob.glob(pattern)
    if not files:
        pattern_exact = os.path.join(rtm_dir, f"RTM_{kode}.pdf")
        files = glob.glob(pattern_exact)
        
    if not files:
        res = {"error": f"File RTM untuk kode {kode} tidak ditemukan di folder rtm"}
        if out_file:
            with open(out_file, 'w', encoding='utf-8') as f:
                json.dump(res, f, ensure_ascii=False)
        else:
            print(json.dumps(res))
        return

    file_path = files[0]
    doc = fitz.open(file_path)
    
    tasks = []
    
    # Parse page by page
    for page_idx, page in enumerate(doc):
        block = page.get_text()
        
        if "RENCANA TUGAS MAHASISWA" not in block and "TUGAS ke" not in block:
            continue
            
        # Parse fields
        nomor_dokumen = ""
        doc_m = re.search(r'(?:Kode Dokumen|Kode)\s*\n\s*(UBSI/[^\n]+)', block)
        if doc_m:
            nomor_dokumen = doc_m.group(1).strip()
            
        dosen_pengampu = ""
        dosen_m = re.search(r'DOSEN PENGAMPU\s*\n\s*([^\n]+)', block)
        if dosen_m:
            dosen_pengampu = dosen_m.group(1).strip()
            
        semester = ""
        sem_m = re.search(r'SEMESTER\s*\n\s*([^\n]+)', block)
        if sem_m:
            semester = sem_m.group(1).strip()

        minggu_m = re.search(r'MINGGU KE\s*\n\s*([^\n]+)', block)
        tugas_m = re.search(r'TUGAS ke-?\s*\n\s*([^\n]+)', block)
        bentuk_m = re.search(r'BENTUK TUGAS\s*\n\s*([^\n]+)', block)
        judul_m = re.search(r'JUDUL TUGAS\s*\n\s*([^\n]+)', block)
        
        sub_cpmk_m = re.search(r'Sub CPMK\s*\n\s*(.*?)\n\s*(?:URAIAN TUGAS|INDIKATOR|WAKTU)', block, re.DOTALL)
        
        # Uraian Tugas
        obyek_m = re.search(r'Obyek Garapan\s*\n\s*(.*?)\n\s*(?:Metode|Bentuk dan|INDIKATOR)', block, re.DOTALL)
        metode_m = re.search(r'Metode Pengerjaan Tugas\s*\n\s*(.*?)\n\s*(?:Bentuk dan|INDIKATOR)', block, re.DOTALL)
        if not metode_m:
            metode_m = re.search(r'Metode\s*\n\s*Pengerjaan\s*\n\s*Tugas\s*\n\s*(.*?)\n\s*(?:Bentuk dan|INDIKATOR)', block, re.DOTALL)
            
        luaran_m = re.search(r'Bentuk dan\s*\n\s*Format Luaran\s*\n\s*(.*?)\n\s*(?:INDIKATOR|WAKTU)', block, re.DOTALL)
        if not luaran_m:
            luaran_m = re.search(r'Bentuk dan\s*\n\s*Luaran\s*\n\s*(.*?)\n\s*(?:INDIKATOR|WAKTU)', block, re.DOTALL)
            
        waktu_kerja_m = re.search(r'Waktu Pengerjaan\s*\n\s*(.*?)\n\s*(?:Jadwal|WAKTU|Lain)', block, re.DOTALL)
        waktu_kumpul_m = re.search(r'Waktu Pengumpulan\s*\n\s*(.*?)\n\s*(?:Lain|Daftar|Rujukan)', block, re.DOTALL)
        if not waktu_kumpul_m:
            waktu_kumpul_m = re.search(r'Dikumpulkan pada\s*\n\s*([^\n]+)', block)
            
        lain_m = re.search(r'Lain-Lain\s*\n\s*(.*?)\n\s*(?:Daftar|Rujukan)', block, re.DOTALL)
        rujuk_m = re.search(r'Daftar Rujukan\s*\n\s*(.*?)$', block, re.DOTALL)
        
        # Penilaian parsing
        penilaians = []
        try:
            tabs = page.find_tables()
            if tabs.tables:
                for tab in tabs.tables:
                    data = tab.extract()
                    is_penilaian = False
                    for row in data:
                        if row and any(h in str(row).upper() for h in ['INDIKATOR', 'TEKNIK', 'BOBOT']):
                            is_penilaian = True
                            break
                    if is_penilaian:
                        for row in data:
                            if not row or len(row) < 3: continue
                            if any(h in str(row).upper() for h in ['INDIKATOR', 'TEKNIK', 'BOBOT', 'NO']):
                                continue
                            no_str = str(row[0]).strip()
                            if re.match(r'^\d+$', no_str) or (len(row) >= 4 and re.match(r'^\d+$', str(row[1]).strip())):
                                ind = str(row[1]).strip() if re.match(r'^\d+$', no_str) else str(row[2]).strip()
                                tek = str(row[2]).strip() if re.match(r'^\d+$', no_str) else str(row[3]).strip()
                                bob = str(row[3]).strip() if len(row) > 3 and re.match(r'^\d+$', no_str) else (str(row[4]).strip() if len(row) > 4 else "")
                                if ind and ind.lower() != 'total':
                                    penilaians.append({
                                        "indikator": ind,
                                        "teknik_penilaian": tek,
                                        "bobot_penilaian": bob
                                    })
        except Exception:
            pass
            
        def clean(val):
            if not val: return ""
            return val.strip()
            
        minggu_str = clean(minggu_m.group(1)) if minggu_m else ""
        tugas_str = clean(tugas_m.group(1)) if tugas_m else ""
        
        tasks.append({
            "nomor_dokumen": clean(nomor_dokumen),
            "dosen_pengampu": clean(dosen_pengampu),
            "semester": clean(semester),
            "minggu_ke": int(minggu_str) if minggu_str.isdigit() else (page_idx + 1),
            "tugas_ke": int(tugas_str) if tugas_str.isdigit() else 1,
            "bentuk_tugas": clean(bentuk_m.group(1)) if bentuk_m else "",
            "judul_tugas": clean(judul_m.group(1)) if judul_m else "",
            "sub_cpmk": clean(sub_cpmk_m.group(1)) if sub_cpmk_m else "",
            "obyek_garapan": clean(obyek_m.group(1)) if obyek_m else "",
            "metode_pengerjaan": clean(metode_m.group(1)) if metode_m else "",
            "bentuk_format_luaran": clean(luaran_m.group(1)) if luaran_m else "",
            "waktu_pengerjaan": clean(waktu_kerja_m.group(1)) if waktu_kerja_m else (clean(waktu_kerja_m.group(0)) if waktu_kerja_m else ""),
            "waktu_pengumpulan": clean(waktu_kumpul_m.group(1)) if waktu_kumpul_m else (clean(waktu_kumpul_m.group(0)) if waktu_kumpul_m else ""),
            "lain_lain": clean(lain_m.group(1)) if lain_m else "",
            "daftar_rujukan": clean(rujuk_m.group(1)) if rujuk_m else "",
            "penilaians": penilaians
        })

    # Deduplicate metadata and find common fields
    nomor_dokumen = ""
    dosen_pengampu = ""
    semester = ""
    for t in tasks:
        if t["nomor_dokumen"]: nomor_dokumen = t["nomor_dokumen"]
        if t["dosen_pengampu"]: dosen_pengampu = t["dosen_pengampu"]
        if t["semester"]: semester = t["semester"]

    res = {
        "metadata": {
            "nomor_dokumen": nomor_dokumen,
            "dosen_pengampu": dosen_pengampu,
            "semester": semester
        },
        "tasks": tasks
    }
    
    if out_file:
        with open(out_file, 'w', encoding='utf-8') as f:
            json.dump(res, f, ensure_ascii=False)
    else:
        print(json.dumps(res, ensure_ascii=False))

if __name__ == "__main__":
    if len(sys.argv) > 1:
        kode_mk = sys.argv[1]
        out_file = sys.argv[2] if len(sys.argv) > 2 else None
        extract_rtm(kode_mk, out_file)
    else:
        print(json.dumps({"error": "Kode matakuliah belum diberikan"}))
