import sqlite3
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

# Regex to remove <small>...</small> or <смалл>...</смалл> or *(Izvor: ...)* at the end of description
pattern = re.compile(r'\s*<(?:small|смалл)[^>]*>.*?</(?:small|смалл)>\s*$', re.IGNORECASE | re.DOTALL)
pattern_inline = re.compile(r'<(?:small|смалл)[^>]*>.*?</(?:small|смалл)>', re.IGNORECASE | re.DOTALL)

for db_path in ['storage/database.sqlite', 'database/database.sqlite']:
    print(f"=== Čišćenje baze: {db_path} ===")
    conn = sqlite3.connect(db_path)
    c = conn.cursor()
    
    # 1. Čišćenje <small> iz description u tabeli monasteries
    c.execute("SELECT id, name, description FROM monasteries WHERE description IS NOT NULL")
    rows = c.fetchall()
    updated_count = 0
    
    for m_id, name, desc in rows:
        if not desc:
            continue
        cleaned_desc = pattern_inline.sub('', desc).strip()
        if cleaned_desc != desc:
            c.execute("UPDATE monasteries SET description = ? WHERE id = ?", (cleaned_desc, m_id))
            updated_count += 1
            
    print(f"  Očišćeno {updated_count} opisa manastira od <small> tagova.")
    
    # 2. Sređivanje opisa slika za manastir Ljubovija (ID 176)
    src_tag = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>'
    ljubovija_images = [
        ('images/monasteries/ljubovija.jpg', 'Crkva Svete Trojice sa zvonikom i konacima u manastiru Bjele Vode kod Ljubovije' + src_tag, 1),
        ('images/monasteries/ljubovija_gal_2.jpg', 'Pozlaćeni duborezni ikonostas i unutrašnjost hrama Svete Trojice' + src_tag, 2),
        ('images/monasteries/ljubovija_gal_3.jpg', 'Panoramski pogled na manastirski kompleks i planinske visove Azbukovice' + src_tag, 3),
    ]
    
    c.execute("DELETE FROM monastery_images WHERE monastery_id = 176")
    for url, caption, sort_order in ljubovija_images:
        c.execute("INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))",
                  (176, url, caption, sort_order))
    
    c.execute("UPDATE monasteries SET image_url = 'images/monasteries/ljubovija.jpg', image = 'images/monasteries/ljubovija.jpg' WHERE id = 176")
    
    conn.commit()
    conn.close()
    print(f"  Baza {db_path} uspešno ažurirana i sačuvana!\n")

print("Svi manastiri i Ljubovija su uspešno očišćeni i sinhronizovani!")
