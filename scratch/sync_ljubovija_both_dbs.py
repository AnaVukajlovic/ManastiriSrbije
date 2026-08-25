import sqlite3
import sys

sys.stdout.reconfigure(encoding='utf-8')

src = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>'

images = [
    ('images/monasteries/ljubovija.jpg', 'Crkva Svete Trojice u manastiru Bjele Vode kod Ljubovije sa zvonikom i uređenom portom' + src, 1),
    ('images/monasteries/ljubovija_gal_2.jpg', 'Zapadno pročelje sa drvenim ulaznim tremom i visokim kamenim zvonikom' + src, 2),
    ('images/monasteries/ljubovija_gal_3.jpg', 'Unutrašnjost hrama sa pozlaćenim polijelejem i drvoreznim ikonostasom' + src, 3),
]

for db in ['storage/database.sqlite', 'database/database.sqlite']:
    conn = sqlite3.connect(db)
    c = conn.cursor()
    c.execute('DELETE FROM monastery_images WHERE monastery_id = 176')
    for u, cap, s in images:
        c.execute("INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))", (176, u, cap, s))
    conn.commit()
    conn.close()
    print(f"Updated {db}")

print("Both databases verified and updated!")
