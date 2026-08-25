import sqlite3
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

for db_path in ['storage/database.sqlite', 'database/database.sqlite']:
    print(f"=== Checking {db_path} ===")
    conn = sqlite3.connect(db_path)
    c = conn.cursor()
    c.execute("SELECT id, name, description FROM monasteries WHERE description IS NOT NULL")
    rows = c.fetchall()
    
    count_small = 0
    count_tags = 0
    found_ids = []
    
    for r in rows:
        m_id, name, desc = r
        if not desc:
            continue
        if '<small>' in desc or '<смалл>' in desc or '</small>' in desc or '</смалл>' in desc or re.search(r'<\s*small[^>]*>', desc, re.IGNORECASE):
            count_small += 1
            found_ids.append((m_id, name))
            
    print(f"Found {count_small} monasteries with <small> tags in description.")
    for m_id, name in found_ids:
        print(f"  [{m_id}] {name}")
