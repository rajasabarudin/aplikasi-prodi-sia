import pypdf
import re

reader = pypdf.PdfReader("buku kurikulum.pdf")
print("Total Pages:", len(reader.pages))

text_found = []
for i, page in enumerate(reader.pages):
    text = page.extract_text()
    if any(c in text for c in ["C1", "C2", "C3", "C4", "C5", "C6"]):
        # print match context
        for line in text.split('\n'):
            if any(c in line for c in ["C1", "C2", "C3", "C4", "C5", "C6"]):
                print(f"Page {i+1}: {line.strip()}")
