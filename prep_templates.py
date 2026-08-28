import zipfile
import shutil
import os

def replace_in_docx(file_path, replacements, output_path):
    temp_dir = file_path + '_temp'
    with zipfile.ZipFile(file_path, 'r') as zip_ref:
        zip_ref.extractall(temp_dir)
    
    doc_xml_path = os.path.join(temp_dir, 'word', 'document.xml')
    with open(doc_xml_path, 'r', encoding='utf-8') as f:
        xml_content = f.read()
    
    for old, new in replacements.items():
        xml_content = xml_content.replace(old, new)
        
    with open(doc_xml_path, 'w', encoding='utf-8') as f:
        f.write(xml_content)
        
    with zipfile.ZipFile(output_path, 'w', zipfile.ZIP_DEFLATED) as zip_ref:
        for root, dirs, files in os.walk(temp_dir):
            for file in files:
                abs_path = os.path.join(root, file)
                rel_path = os.path.relpath(abs_path, temp_dir)
                zip_ref.write(abs_path, rel_path)
                
    shutil.rmtree(temp_dir)

laporan_file = "Template Laporan Penelitian Mandiri.docx"
proposal_file = "Template Proposal penelitian mandiri - Copy.docx"

laporan_replacements = {
    "(Tulis judul penelitian)": "${JUDUL}",
    "BULAN TAHUN": "${BULAN_TAHUN}",
    "Judul Penelitian</w:t></w:r><w:r><w:rPr><w:rFonts w:ascii=\"Times New Roman\" w:hAnsi=\"Times New Roman\" w:cs=\"Times New Roman\"/><w:b/><w:sz w:val=\"28\"/><w:szCs w:val=\"28\"/></w:rPr><w:t>(Times New Roman 14, Bold)": "${JUDUL}</w:t></w:r><w:r><w:rPr><w:rFonts w:ascii=\"Times New Roman\" w:hAnsi=\"Times New Roman\" w:cs=\"Times New Roman\"/><w:b/><w:sz w:val=\"28\"/><w:szCs w:val=\"28\"/></w:rPr><w:t>"
}

# The xml is messy. A much safer way is to just look for text nodes that contain "BULAN TAHUN"
# and "(Tulis judul penelitian)". But XML splits text across multiple <w:t> tags. 
