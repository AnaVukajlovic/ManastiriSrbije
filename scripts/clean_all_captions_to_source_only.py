import os
import sqlite3
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

def extract_source_only(cap):
    if not cap:
        return ""
    
    # Check if there is an "Izvor:" indicator
    m = re.search(r'Izvor\s*:\s*([^)<>]+)', cap, re.IGNORECASE)
    if m:
        src = m.group(1).strip()
        # Remove any unwanted trailing markdown characters, brackets or html artifacts
        src = re.sub(r'[\*\(\)]+$', '', src).strip()
        # Clean double spaces
        src = re.sub(r'\s+', ' ', src)
        return f"Izvor: {src}"
    
    # If no "Izvor:" pattern, return empty or stripped
    return cap.strip()

dbs = [
    r"d:\projekti\ManastiriSrbije\backend\database\database.sqlite",
    r"d:\projekti\ManastiriSrbije\backend\storage\database.sqlite"
]

for db_path in dbs:
    if not os.path.exists(db_path):
        print(f"Skipping {db_path} (does not exist)")
        continue
        
    print(f"\nProcessing database: {db_path}")
    conn = sqlite3.connect(db_path)
    cur = conn.cursor()
    
    cur.execute("SELECT id, monastery_id, url, caption FROM monastery_images WHERE caption IS NOT NULL AND caption != ''")
    rows = cur.fetchall()
    
    updated_count = 0
    for r_id, m_id, url, old_caption in rows:
        new_caption = extract_source_only(old_caption)
        if new_caption != old_caption:
            cur.execute("UPDATE monastery_images SET caption = ? WHERE id = ?", (new_caption, r_id))
            updated_count += 1
            
    conn.commit()
    conn.close()
    print(f"Successfully cleaned {updated_count} / {len(rows)} image captions in {db_path} to source-only format.")
