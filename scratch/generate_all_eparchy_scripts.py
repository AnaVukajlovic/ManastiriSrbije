import sqlite3
import os
import sys

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

cursor.execute("SELECT id, name FROM eparchies ORDER BY id")
eparchies = cursor.fetchall()

eparchy_files = {
    1: 'update_zicka.php',
    2: 'update_raskoprizrenska.php',
    3: 'update_beogradska.php',
    4: 'update_sumadijska.php',
    5: 'update_niska.php',
    6: 'update_banatska.php',
    7: 'update_backa.php',
    8: 'update_branicevska.php',
    9: 'update_krusevacka.php',
    10: 'update_milesevska.php',
    11: 'update_sremska.php',
    12: 'update_timocka.php',
    13: 'update_valjevska.php',
    14: 'update_vranjska.php',
    15: 'update_sabacka.php',
}

def generate_eparchy_script(ep_id, ep_name, script_filename):
    # Fetch monasteries
    cursor.execute("""
        SELECT m.id, m.name, m.image_url
        FROM monasteries m
        WHERE m.eparchy_id = ?
        ORDER BY m.id
    """, (ep_id,))
    monasteries = cursor.fetchall()
    
    # Fetch images for each monastery
    code_entries = []
    for m in monasteries:
        m_id, m_name, m_card = m
        cursor.execute("""
            SELECT url, caption, sort_order
            FROM monastery_images
            WHERE monastery_id = ?
            ORDER BY sort_order ASC
        """, (m_id,))
        images = cursor.fetchall()
        
        # If no gallery images, create default from card_image
        if not images:
            images = [(m_card or f"images/monasteries/{m_name.lower().replace(' ', '-')}.jpg", f"{m_name}", 1)]
        
        img_php = []
        for img in images:
            u, cap, so = img
            # clean cap from old src tags
            cap_clean = cap.replace('<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>', '')
            cap_clean = cap_clean.replace('<br><small style="color: #eab308;"><em>*Izvor: manastiri.rs*</em></small>', '')
            cap_clean = cap_clean.strip()
            if not cap_clean:
                cap_clean = m_name
            
            # escape single quotes
            cap_clean_esc = cap_clean.replace("'", "\\'")
            
            img_php.append(f"""            [
                'url' => '{u}',
                'caption' => '{cap_clean_esc}' . $src,
                'sort_order' => {so}
            ],""")
        
        imgs_str = "\n".join(img_php)
        code_entries.append(f"""    // {m_id}: {m_name}
    {m_id} => [
        'name' => '{m_name.replace("'", "\\'")}',
        'card_image' => '{m_card}',
        'images' => [
{imgs_str}
        ]
    ],""")

    all_entries_str = "\n\n".join(code_entries)
    
    php_content = f"""<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - {ep_name.upper()} (ID {ep_id})
 * Pravoslavni Svetionik — Master rad
 * Izvor: manastiri.rs
 */

use App\Models\Monastery;
use App\Models\MonasteryImage;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\\Contracts\\Console\\Kernel::class);
$kernel->bootstrap();

echo "====================================================================\\n";
echo "POKRETANJE REVIZIJE I ČIŠĆENJA ZA {ep_name.upper()} (ID {ep_id})\\n";
echo "====================================================================\\n\\n";

$src = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>';

// 1. Definicija verifikovanih podataka, tačnih kartičnih slika i galerija
$eparchy_data = [
{all_entries_str}
];

echo "2. Ažuriranje primarne baze podataka (database/database.sqlite):\\n";
DB::beginTransaction();

try {{
    foreach ($eparchy_data as $monasteryId => $data) {{
        $monastery = Monastery::find($monasteryId);
        if (!$monastery) {{
            echo "  [UPOZORENJE] Manastir ID {{$monasteryId}} nije pronađen!\\n";
            continue;
        }}

        if (isset($data['name'])) {{
            $monastery->name = $data['name'];
        }}

        $monastery->image_url = $data['card_image'];
        $monastery->save();

        MonasteryImage::where('monastery_id', $monasteryId)->delete();

        foreach ($data['images'] as $imgData) {{
            MonasteryImage::create([
                'monastery_id' => $monasteryId,
                'url' => $imgData['url'],
                'caption' => $imgData['caption'],
                'sort_order' => $imgData['sort_order'],
            ]);
        }}

        $count = count($data['images']);
        echo "  [AŽURIRAN] [{{$monasteryId}}] {{$monastery->name}} | Kartica: {{$data['card_image']}} | Galerija: {{$count}} slika\\n";
    }}

    DB::commit();
    echo "\\nPrimarna baza je uspešno ažurirana!\\n\\n";
}} catch (\\Exception $e) {{
    DB::rollBack();
    echo "GREŠKA pri radu sa primarnom bazom: " . $e->getMessage() . "\\n";
    exit(1);
}}

// 3. Sinhronizacija u storage/database.sqlite
$storageDbPath = storage_path('database.sqlite');
if (file_exists($storageDbPath)) {{
    echo "3. Ažuriranje storage baze podataka ({{$storageDbPath}}):\\n";
    try {{
        $pdo = new PDO('sqlite:' . $storageDbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->beginTransaction();

        foreach ($eparchy_data as $monasteryId => $data) {{
            $stmt = $pdo->prepare("UPDATE monasteries SET name = :name, image_url = :image_url, image = :img WHERE id = :id");
            $stmt->execute([
                ':name' => $data['name'],
                ':image_url' => $data['card_image'],
                ':img' => $data['card_image'],
                ':id' => $monasteryId
            ]);

            $stmtDel = $pdo->prepare("DELETE FROM monastery_images WHERE monastery_id = :id");
            $stmtDel->execute([':id' => $monasteryId]);

            $stmtIns = $pdo->prepare("INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (:m_id, :url, :caption, :sort_order, datetime('now'), datetime('now'))");
            foreach ($data['images'] as $imgData) {{
                $stmtIns->execute([
                    ':m_id' => $monasteryId,
                    ':url' => $imgData['url'],
                    ':caption' => $imgData['caption'],
                    ':sort_order' => $imgData['sort_order'],
                ]);
            }}

            echo "  [STORAGE AŽURIRAN] [{{$monasteryId}}]\\n";
        }}

        $pdo->commit();
        echo "Storage baza je uspešno ažurirana!\\n\\n";
    }} catch (\\Exception $e) {{
        $pdo->rollBack();
        echo "GREŠKA pri radu sa storage bazom: " . $e->getMessage() . "\\n";
    }}
}}

echo "====================================================================\\n";
echo "REVIZIJA I SINHRONIZACIJA ZA {ep_name.upper()} ZAVRŠENE USPEŠNO!\\n";
echo "====================================================================\\n";
"""
    return php_content

for ep in eparchies:
    ep_id, ep_name = ep
    fn = eparchy_files.get(ep_id)
    if not fn:
        continue
    
    # Don't overwrite the customized ones
    if ep_id in [1, 2, 4, 15]:
        print(f"Skipping already customized {fn} (ID {ep_id}: {ep_name})")
        continue
        
    content = generate_eparchy_script(ep_id, ep_name, fn)
    with open(fn, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Generated {fn} for Eparhija {ep_name} (ID {ep_id})")

