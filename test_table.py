import fitz
import sys
import json

def test_table(pdf_path):
    doc = fitz.open(pdf_path)
    extracted = []
    
    for page_num, page in enumerate(doc):
        # find_tables is available in PyMuPDF >= 1.22
        tabs = page.find_tables()
        if tabs.tables:
            for i, tab in enumerate(tabs.tables):
                extracted.append({
                    "page": page_num,
                    "table_index": i,
                    "content": tab.extract()
                })
    
    with open("table_dump.json", "w", encoding="utf-8") as f:
        json.dump(extracted, f, indent=4, ensure_ascii=False)

if __name__ == "__main__":
    test_table("rps/RPS_101_1130.pdf")
