import os
import sys
import io
import re
import json
import sqlite3
import csv

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_STORAGE_PATH = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE_PATH = os.path.join(BASE_DIR, 'database', 'database.sqlite')
CSV_IMPORT_PATH = os.path.join(BASE_DIR, 'storage', 'app', 'import', 'monasteries.csv')
CSV_SEEDER_PATH = os.path.join(BASE_DIR, 'database', 'seeders', 'data', 'monasteries.csv')

# 1. Bogorodica Ljeviška specific high-accuracy text & images
LJEVISKA_DESC = """OPŠTI PODACI: Crkva Bogorodica Ljeviška je drevna srpska pravoslavna crkva u Prizrenu, zadužbina kralja Stefana Milutina. Podignuta je u periodu 1306—1307. godine na ostacima starijeg katedralnog hrama iz 13. veka, koji je takođe zasnovan na mestu još starije, ranohrišćanske crkve. Hram je vekovima bio saborni i katedralni hram prizrenskih episkopa i mitropolita Srpske pravoslavne crkve. Nalazi se pod zaštitom UNESCO-a kao deo Srednjovekovnih spomenika na Kosovu i Metohiji.

ISTORIJA: Kralj Stefan Uroš II Milutin obnovio je i dogradio crkvu posvećenu Uspenju Presvete Bogorodice početkom 14. stoleća. Tokom osmanske vladavine hram je pretvoren u Džumu džamiju, pri čemu su freske bile prekrečene i oštećene. Sredinom 20. veka izvršeni su opsežni restauratorski radovi i otkriven je neprocenjivi sloj srednjovekovnog živopisa. U martovskom pogromu 2004. godine svetinja je pretrpela teško paljenje i skrnavljenje, nakon čega je usledila međunarodna obnova.

ARHITEKTURA I UMETNOST: Crkva je petokupolna građevina sa pripratom i spratnim otvorenim tremom nad kojim se uzdiže zvonik, delo protomajstora Nikole. Unutrašnjost krase vrhunska dela vizantijskog i srpskog slikarstva ranog 14. veka, koja su oslikali slavni zografi Mihailo Astrapa i Evtihije. Među freskama se ističu čuveni prikaz Bogorodice sa Hristom Hraniteljem, loza Nemanjića i monumentalni ktitorski portret kralja Milutina. Na zidu priprate sačuvan je i dirljivi natpis persijskog pesnika: "Zenica oka moga gnezdo je tvoje".

DUHOVNI ŽIVOT I ZNAČAJ: Bogorodica Ljeviška predstavlja jedan od najvažnijih duhovnih i umetničkih simbola srpskog naroda na Kosovu i Metohiji. Kao katedralni hram Eparhije raško-prizrenske, crkva i danas svedoči o viševekovnom molitvenom i liturgijskom trajanju. Zbog izuzetne univerzalne vrednosti uvrštena je na Listu svetske kulturne baštine u opasnosti. Vernici i poštovaoci umetnosti iz celog sveta dolaze da se poklone ovoj jedinstvenoj svetinji srpske i svetske baštine."""

LJEVISKA_IMAGES = [
    {
        'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ae/Our_Lady_of_Ljevi%C5%A1%2C_Prizren%2C_2010._View_from_clock_tower.jpg/1280px-Our_Lady_of_Ljevi%C5%A1%2C_Prizren%2C_2010._View_from_clock_tower.jpg?utm_source=sr.wikipedia.org&utm_campaign=api&utm_content=thumbnail',
        'caption': 'Glavni hram i zvonik crkve Bogorodica Ljeviška u Prizrenu'
    },
    {
        'url': 'https://upload.wikimedia.org/wikipedia/commons/c/cc/Bogorodica_Ljeviska_southern_aerial_view_PRIZREN_5_GOPR3023-2.jpg?utm_source=sr.wikipedia.org&utm_campaign=imageinfo&utm_content=original',
        'caption': 'Pogled na južnu fasadu i petokupolnu arhitekturu hrama'
    },
    {
        'url': 'https://upload.wikimedia.org/wikipedia/commons/b/b3/Crkva_Bogorodica_Ljevi%C5%A1ka%2C_Prizren.jpg?utm_source=sr.wikipedia.org&utm_campaign=imageinfo&utm_content=original',
        'caption': 'Ulazni trem sa zvonikom i kamenom plastikom'
    },
    {
        'url': 'https://upload.wikimedia.org/wikipedia/commons/1/1f/Christ_Pantocrator_and_the_heavenly_powers_1_-_Bogorodica_Ljevi%C5%A1ka%2C_Prizren.jpg?utm_source=sr.wikipedia.org&utm_campaign=imageinfo&utm_content=original',
        'caption': 'Freska Hrista Pantokratora u kupoli crkve'
    },
    {
        'url': 'https://upload.wikimedia.org/wikipedia/commons/3/35/Crkva_Bogorodica_Ljevi%C5%A1ka%2C_freska_kralja_Milutina_pre_restauracije.jpg?utm_source=sr.wikipedia.org&utm_campaign=imageinfo&utm_content=original',
        'caption': 'Ktitorski portret svetog kralja Stefana Milutina'
    },
    {
        'url': 'https://upload.wikimedia.org/wikipedia/commons/f/f6/Bogorodica_Ljevi%C5%A1ka.jpg?utm_source=sr.wikipedia.org&utm_campaign=imageinfo&utm_content=original',
        'caption': 'Srednjovekovni živopis i freske u unutrašnjosti hrama'
    }
]

def clean_desc_text(desc, name, slug):
    if slug == 'bogorodica-ljeviska' or 'ljevišk' in name.lower():
        return LJEVISKA_DESC

    # Remove bad site/footer sentences
    bad_phrases = [
        'manastiri.rs', 'nezavisan informativni portal', 'posvećen srpskom duhovnom', 'ovaj sajt je vodič',
        'objedinjujemo interaktivnu mapu', 'crkveni kalendar', 'žitija svetih', 'molitvenik', 'psaltir i akatiste',
        'sajt nije zvanična publikacija', 'sve informacije se zasnivaju na javno', 'podelite ovu stranicu',
        'koreni osnivanja manastirskog kompleksa sežu u period srednjeg veka, o čemu svedoče sačuvana predanja i zapisi.'
    ]
    
    sections = desc.split('\n\n')
    cleaned_sections = []
    
    for sec in sections:
        if ':' in sec:
            header, body = sec.split(':', 1)
        else:
            header, body = '', sec
            
        sentences = [s.strip() for s in body.split('.') if s.strip()]
        valid_sentences = []
        for s in sentences:
            low = s.lower()
            if not any(bp in low for bp in bad_phrases):
                valid_sentences.append(s)
                
        # Rebuild body with clean sentences
        clean_body = ". ".join(valid_sentences).strip()
        if clean_body and not clean_body.endswith('.'):
            clean_body += '.'
        
        # Replace semicolons with commas
        clean_body = clean_body.replace(';', ',')
        
        if header:
            cleaned_sections.append(f"{header.strip()}: {clean_body}")
        else:
            cleaned_sections.append(clean_body)
            
    return "\n\n".join(cleaned_sections)

def is_bad_image(url):
    low = url.lower()
    bad_keywords = [
        'flag', 'zastava', 'spomenik', 'tvrdjava', 'grad_uzice', 'srednjevekovni_grad', 'karta', 'map',
        'coat_of_arms', 'grb', 'symbol', 'yugoslavia', 'ambox', 'edit-ltr', 'commons-logo', 'nuvola',
        'wikimedia-button', 'stub', 'question', 'portal', 'pd-icon', '.webm', '.svg'
    ]
    return any(bk in low for bk in bad_keywords)

def main():
    print("=== FINALNO ČIŠĆENJE TEKSTOVA I SLIKA ZA SVE MANASTIRE ===")

    # 1. Update CSV files
    csv_rows = []
    with open(CSV_IMPORT_PATH, 'r', encoding='utf-8-sig') as f:
        reader = csv.DictReader(f, delimiter=';')
        fieldnames = reader.fieldnames
        for r in reader:
            csv_rows.append(r)

    for r in csv_rows:
        slug = r['slug']
        name = r['name']
        cur_desc = r.get('description', '')
        clean_d = clean_desc_text(cur_desc, name, slug)
        r['description'] = clean_d
        if slug == 'bogorodica-ljeviska':
            r['image_url'] = LJEVISKA_IMAGES[0]['url']

    for target_csv in [CSV_IMPORT_PATH, CSV_SEEDER_PATH]:
        with open(target_csv, 'w', encoding='utf-8-sig', newline='') as f:
            writer = csv.DictWriter(f, fieldnames=fieldnames, delimiter=';')
            writer.writeheader()
            for r in csv_rows:
                writer.writerow(r)
        print(f"✓ Ažuriran i pročišćen CSV: {target_csv}")

    # 2. Update SQLite databases
    for db_path in [DB_STORAGE_PATH, DB_DATABASE_PATH]:
        if not os.path.exists(db_path):
            continue
        print(f"\nČišćenje baze: {db_path}")
        conn = sqlite3.connect(db_path)
        cur = conn.cursor()

        # Update descriptions
        for r in csv_rows:
            cur.execute("UPDATE monasteries SET description = ? WHERE slug = ?", (r['description'], r['slug']))

        # Specific Bogorodica Ljeviska update
        cur.execute("SELECT id FROM monasteries WHERE slug = 'bogorodica-ljeviska'")
        m_lj = cur.fetchone()
        if m_lj:
            lj_id = m_lj[0]
            cur.execute("UPDATE monasteries SET description = ?, image_url = ? WHERE id = ?", (LJEVISKA_DESC, LJEVISKA_IMAGES[0]['url'], lj_id))
            cur.execute("DELETE FROM monastery_images WHERE monastery_id = ?", (lj_id,))
            for s_idx, im in enumerate(LJEVISKA_IMAGES, 1):
                cur.execute(
                    "INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))",
                    (lj_id, im['url'], im['caption'], s_idx)
                )

        # Remove bad images across the entire database
        cur.execute("SELECT id, url FROM monastery_images")
        all_imgs = cur.fetchall()
        deleted_count = 0
        for img_id, img_url in all_imgs:
            if is_bad_image(img_url):
                cur.execute("DELETE FROM monastery_images WHERE id = ?", (img_id,))
                deleted_count += 1

        print(f"✓ Uklonjeno {deleted_count} neispravnih/sistemskih slika iz baze {db_path}.")

        # Remove duplicates from monastery_images (keep unique url per monastery)
        cur.execute("""
            DELETE FROM monastery_images 
            WHERE id NOT IN (
                SELECT MIN(id) 
                FROM monastery_images 
                GROUP BY monastery_id, url
            )
        """)
        print("✓ Uklonjeni svi duplikati slika.")

        conn.commit()
        conn.close()

    print("\n✓ ČIŠĆENJE ZAVRŠENO USPEŠNO!")

if __name__ == '__main__':
    main()
