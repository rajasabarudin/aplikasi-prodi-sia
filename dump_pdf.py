import fitz
import sys

def extract(pdf_path):
    doc = fitz.open(pdf_path)
    text = ""
    for page in doc:
        text += page.get_text()
    
    with open("pdf_dump.txt", "w", encoding="utf-8") as f:
        f.write(text)

if __name__ == "__main__":
    extract("rps/RPS_101_1130.pdf")
