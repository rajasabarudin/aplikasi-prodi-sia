import fitz
import sys

def extract(pdf_path):
    doc = fitz.open(pdf_path)
    text = ""
    for page in doc:
        text += page.get_text()
    
    print(text[:2000]) # print first 2000 chars

if __name__ == "__main__":
    extract("rps/RPS_101_1130.pdf")
