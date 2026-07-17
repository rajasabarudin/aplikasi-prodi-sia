import fitz
import sys
import glob
import json
import re
import os

# Ensure standard output is UTF-8 encoded
sys.stdout.reconfigure(encoding='utf-8')

def extract(kode, out_file=None):
    # Find PDF using absolute path based on script location
    base_dir = os.path.dirname(os.path.abspath(__file__))
    rps_dir = os.path.join(base_dir, "rps")
    
    pdf_files = glob.glob(os.path.join(rps_dir, f"*_{kode}_*.pdf"))
    if not pdf_files:
        pdf_files = glob.glob(os.path.join(rps_dir, f"*{kode}*.pdf"))
        
    if not pdf_files:
        print(json.dumps({"error": f"PDF not found for kode {kode} in {rps_dir}"}))
        return

    pdf_path = pdf_files[0]
    
    try:
        doc = fitz.open(pdf_path)
    except Exception as e:
        print(json.dumps({"error": str(e)}))
        return
        
    extracted_rows = []
    
    for page in doc:
        tabs = page.find_tables()
        if tabs.tables:
            for tab in tabs.tables:
                data = tab.extract()
                for row in data:
                    # Row is a list of strings
                    # We are looking for rows where the first column is a number 1-16
                    if not row or not row[0]: continue
                    
                    minggu_str = row[0].strip()
                    if re.match(r'^([1-9]|1[0-6])$', minggu_str):
                        # Extract the data. Usually it has 9 columns:
                        # 0: Minggu
                        # 1: Sub-CPMK
                        # 2: Bahan Kajian
                        # 3: Tatap Muka (Metode)
                        # 4: Daring (or sometimes combined)
                        # 5: Pengalaman Belajar
                        # 6: Teknik Penilaian (Kriteria)
                        # 7: Indikator
                        # 8: Bobot
                        
                        # Sometimes columns are merged or empty.
                        sub_cpmk = row[1] if len(row) > 1 and row[1] else ""
                        bahan = row[2] if len(row) > 2 and row[2] else ""
                        
                        # Combine Tatap Muka and Daring for Metode Pembelajaran
                        metode = ""
                        if len(row) > 3 and row[3]: metode += row[3]
                        if len(row) > 4 and row[4]: 
                            if metode: metode += "\n"
                            metode += row[4]
                            
                        # Extract waktu_pembelajaran from metode
                        waktu_list = []
                        # 1. Brackets containing time keywords
                        for m in list(re.finditer(r'\[([^\]]*?(?:TM|TT|BM|BT|menit|Menit|x)[^\]]*?)\]', metode, re.IGNORECASE)):
                            match_str = m.group(0)
                            waktu_list.append(match_str.strip('[]'))
                            metode = metode.replace(match_str, "")
                            
                        # 2. Loose patterns like TM: 3x50 Menit
                        loose_patterns = re.findall(r'(?:TM|TT|BM|BT)[\s:]*\d+[\s*x\s*\d+]*[\s\n]*(?:Menit|menit|\'|\")?', metode, re.IGNORECASE)
                        for lp in loose_patterns:
                            waktu_list.append(lp)
                            metode = metode.replace(lp, "")
                            
                        waktu_pembelajaran = "; ".join([re.sub(r'\s+', ' ', w.strip()) for w in waktu_list if w.strip()])
                        metode = re.sub(r'\n+', '\n', metode).strip()
                        
                        pengalaman = row[5] if len(row) > 5 and row[5] else ""
                        kriteria = row[6] if len(row) > 6 and row[6] else ""
                        indikator = row[7] if len(row) > 7 and row[7] else ""
                        bobot_str = row[8] if len(row) > 8 and row[8] else "0"
                        
                        # Clean bobot
                        m = re.search(r'\d+', bobot_str)
                        bobot = int(m.group(0)) if m else 0
                        
                        extracted_rows.append({
                            "minggu_ke": int(minggu_str),
                            "sub_cpmk": sub_cpmk,
                            "bahan_kajian": bahan,
                            "metode_pembelajaran": metode,
                            "waktu_pembelajaran": waktu_pembelajaran,
                            "pengalaman_belajar": pengalaman,
                            "kriteria_penilaian": kriteria,
                            "indikator_penilaian": indikator,
                            "bobot_penilaian": bobot
                        })

    # Sort by minggu_ke
    extracted_rows.sort(key=lambda x: x["minggu_ke"])
    
    # Remove duplicates if any
    unique_rows = []
    seen = set()
    for r in extracted_rows:
        if r["minggu_ke"] not in seen:
            unique_rows.append(r)
            seen.add(r["minggu_ke"])
            
    if out_file:
        with open(out_file, 'w', encoding='utf-8') as f:
            json.dump(unique_rows, f, ensure_ascii=False)
    else:
        print(json.dumps(unique_rows, ensure_ascii=False))

if __name__ == "__main__":
    if len(sys.argv) > 1:
        kode_mk = sys.argv[1]
        out_file = sys.argv[2] if len(sys.argv) > 2 else None
        extract(kode_mk, out_file)
    else:
        print(json.dumps({"error": "Kode matakuliah belum diberikan"}))
