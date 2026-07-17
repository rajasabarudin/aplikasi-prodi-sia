import fitz
import sys
import os
import glob
import re
import json

# Ensure standard output is UTF-8 encoded
sys.stdout.reconfigure(encoding='utf-8')

def extract_silabus(kode, out_file=None):
    base_dir = os.path.dirname(os.path.abspath(__file__))
    silabus_dir = os.path.join(base_dir, "silabus")
    
    # Match Silabus files
    pattern_wildcard = os.path.join(silabus_dir, f"*ilabus*{kode}*.pdf")
    files = glob.glob(pattern_wildcard)
    
    if not files:
        res = {"error": f"File Silabus untuk kode {kode} tidak ditemukan di folder silabus"}
        if out_file:
            with open(out_file, 'w', encoding='utf-8') as f:
                json.dump(res, f, ensure_ascii=False)
        else:
            print(json.dumps(res))
        return
        
    file_path = files[0]
    doc = fitz.open(file_path)
    text = "".join([page.get_text() for page in doc])
    
    # 1. Kode Dokumen
    kode_dok = ""
    kd_m = re.search(r'(?:Kode Dokumen|Kode)\s*\n\s*(UBSI/[^\n]+)', text)
    if kd_m:
        kode_dok = kd_m.group(1).strip()
    else:
        # Fallback search
        kd_m_alt = re.search(r'(UBSI/DA/[^\n]+)', text)
        if kd_m_alt:
            kode_dok = kd_m_alt.group(1).strip()
        
    # 2. CPMK
    cpmk_text = ""
    cpmk_m = re.search(r'CAPAIAN PEMBELAJARAN MATA KULIAH \(CPMK\)\s*\n\s*(.*?)\n\s*(?:SUB CAPAIAN|MATERI PEMBELAJARAN)', text, re.DOTALL)
    if cpmk_m:
        cpmk_text = cpmk_m.group(1).strip()
        
    # 3. Sub-CPMK
    sub_cpmk_text = ""
    sub_cpmk_m = re.search(r'SUB CAPAIAN PEMBELAJARAN MATA KULIAH \(Sub-CPMK\)\s*\n\s*(.*?)\n\s*MATERI PEMBELAJARAN', text, re.DOTALL)
    if sub_cpmk_m:
        sub_cpmk_text = sub_cpmk_m.group(1).strip()
        
    # 4. Materi Pembelajaran
    materi_block = ""
    materi_m = re.search(r'MATERI PEMBELAJARAN\s*\n\s*(.*?)$', text, re.DOTALL)
    if materi_m:
        materi_block = materi_m.group(1).strip()
        materi_block = re.split(r'PUSTAKA|PRASYARAT|Dosen Pengampu', materi_block, flags=re.IGNORECASE)[0].strip()
        
    meetings = {}
    current_meeting = None
    lines = materi_block.split('\n')
    for line in lines:
        line_clean = line.strip()
        if not line_clean: continue
        
        # Check if line is a meeting number 1 to 16
        if re.match(r'^([1-9]|1[0-6])$', line_clean):
            current_meeting = int(line_clean)
            meetings[current_meeting] = []
        elif current_meeting is not None:
            # ignore footer or empty lines or signatures
            if any(k in line_clean for k in ["Agustus", "September", "UBSI", "Halaman", "Silabus"]):
                continue
            meetings[current_meeting].append(line_clean)
            
    materi_list = []
    for m_id in sorted(meetings.keys()):
        materi_list.append({
            "pertemuan": m_id,
            "materi": "\n".join(meetings[m_id])
        })
        
    res = {
        "kode_dokumen": kode_dok,
        "cpmk": cpmk_text,
        "sub_cpmk": sub_cpmk_text,
        "materis": materi_list
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
        extract_silabus(kode_mk, out_file)
    else:
        print(json.dumps({"error": "Kode matakuliah belum diberikan"}))
