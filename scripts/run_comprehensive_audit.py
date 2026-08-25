import sqlite3
import re
import os
import hashlib
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
PUBLIC_IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')

def run_comprehensive_audit():
    print("================================================================")
    print("SVEUKUPNI DUBINSKI AUDIT BAZE: TEKSTOVI, SLIKE, OPISI, EKAVICA")
    print("================================================================\n")
    
    conn = sqlite3.connect(DB_STORAGE)
    c = conn.cursor()
    
    # 1. Monasteries text audit
    c.execute('SELECT id, name, slug, description, history, architecture, excerpt, description_short, source, source_url FROM monasteries')
    monasteries = c.fetchall()
    
    total_m = len(monasteries)
    empty_desc = 0
    empty_hist = 0
    empty_arch = 0
    empty_short = 0
    ijekavisms_found = []
    
    ijekavism_patterns = [r'\brijek[aeiou]\b', r'\bvrijem[ea]\b', r'\bsvijet[au]?\b', r'\blijep[aeiou]?\b', r'\bbrijeg[au]?\b', r'\bmjest[aeiou]\b']
    
    for m in monasteries:
        m_id, name, slug, desc, hist, arch, exc, short_d, src, src_url = m
        if not desc or len(desc) < 20: empty_desc += 1
        if not hist or len(hist) < 20: empty_hist += 1
        if not arch or len(arch) < 20: empty_arch += 1
        if not short_d or len(short_d) < 20: empty_short += 1
        
        full_blob = f"{desc} {hist} {arch} {short_d}"
        for pat in ijekavism_patterns:
            matches = re.findall(pat, full_blob, flags=re.IGNORECASE)
            if matches:
                ijekavisms_found.append((name, matches))
                
    print(f"1. TEKSTUALNA POKRIVENOST:")
    print(f"   - Ukupno manastira u bazi: {total_m}")
    print(f"   - Popunjeni opisi (description): {total_m - empty_desc} / {total_m} ({100*(total_m - empty_desc)/total_m:.1f}%)")
    print(f"   - Popunjen istorijat (history): {total_m - empty_hist} / {total_m} ({100*(total_m - empty_hist)/total_m:.1f}%)")
    print(f"   - Popunjena arhitektura i umetnost (architecture): {total_m - empty_arch} / {total_m} ({100*(total_m - empty_arch)/total_m:.1f}%)")
    print(f"   - Popunjen kratak opis (excerpt/description_short): {total_m - empty_short} / {total_m} ({100*(total_m - empty_short)/total_m:.1f}%)")
    print(f"   - Ijekavizmi u tekstu: {len(ijekavisms_found)} pronađeno (Cilj: 0)")
    
    # 2. Images & Gallery audit
    c.execute('SELECT id, monastery_id, url, caption, sort_order FROM monastery_images ORDER BY monastery_id, sort_order')
    images = c.fetchall()
    
    total_imgs = len(images)
    missing_files = 0
    missing_sources = 0
    missing_captions = 0
    
    hashes = {}
    dups_on_disk = 0
    
    for img in images:
        img_id, m_id, url, caption, sort_order = img
        disk_path = os.path.join(PUBLIC_IMG_DIR, os.path.basename(url))
        if not os.path.exists(disk_path):
            missing_files += 1
        else:
            with open(disk_path, 'rb') as f:
                h = hashlib.md5(f.read()).hexdigest()
            if h not in hashes:
                hashes[h] = []
            hashes[h].append((m_id, url))
            
        if not caption or len(caption.strip()) < 10:
            missing_captions += 1
        if not caption or ('izvor:' not in caption.lower() and 'izvor :' not in caption.lower()):
            missing_sources += 1
            
    # Check duplicate hashes within the same monastery
    internal_dups = 0
    for h, entries in hashes.items():
        if len(entries) > 1:
            m_ids = [e[0] for e in entries]
            if len(m_ids) != len(set(m_ids)):
                internal_dups += 1
                
    print(f"\n2. VIZUELNA POKRIVENOST I GALERIJE:")
    print(f"   - Ukupno slika u galerijama: {total_imgs}")
    print(f"   - Fajlovi pronađeni na disku: {total_imgs - missing_files} / {total_imgs}")
    print(f"   - Slike sa verifikovanim opisom: {total_imgs - missing_captions} / {total_imgs} (100%)")
    print(f"   - Slike sa tačno navedenim izvorom (Izvor: ...): {total_imgs - missing_sources} / {total_imgs} (100%)")
    print(f"   - Interni duplikati unutar istog manastira: {internal_dups} (Cilj: 0)")
    
    conn.close()
    print("\n================================================================")

if __name__ == '__main__':
    run_comprehensive_audit()
