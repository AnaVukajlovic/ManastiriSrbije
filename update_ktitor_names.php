<?php

/**
 * SISTEMSKO AŽURIRANJE KTITORA I PUNIH IMENA NEMANJIĆA
 * Pravoslavni Svetionik
 */

use App\Models\Ktitor;
use App\Models\Monastery;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "====================================================================\n";
echo "POKRETANJE AŽURIRANJA KTITORA I PUNIH IMENA VLADARA (NEMANJIĆA)\n";
echo "====================================================================\n\n";

$ktitors_updates = [
    'stefan-nemanja' => [
        'name' => 'Stefan Nemanja (Sveti Simeon Mirotočivi)',
        'title' => 'Veliki župan',
        'dynasty' => 'Nemanjići',
    ],
    'stefan-prvovencani' => [
        'name' => 'Stefan Prvovenčani (Stefan Nemanjić)',
        'title' => 'Kralj',
        'dynasty' => 'Nemanjići',
    ],
    'stefan-radoslav' => [
        'name' => 'Stefan Radoslav Nemanjić',
        'title' => 'Kralj',
        'dynasty' => 'Nemanjići',
    ],
    'stefan-vladislav' => [
        'name' => 'Stefan Vladislav Nemanjić',
        'title' => 'Kralj',
        'dynasty' => 'Nemanjići',
    ],
    'stefan-uros-i' => [
        'name' => 'Stefan Uroš I Nemanjić',
        'title' => 'Kralj',
        'dynasty' => 'Nemanjići',
    ],
    'kralj-dragutin' => [
        'name' => 'Stefan Dragutin Nemanjić',
        'title' => 'Kralj',
        'dynasty' => 'Nemanjići',
    ],
    'kralj-milutin' => [
        'name' => 'Stefan Uroš II Milutin',
        'title' => 'Kralj (Sveti Kralj)',
        'dynasty' => 'Nemanjići',
    ],
    'stefan-decanski' => [
        'name' => 'Stefan Uroš III Dečanski',
        'title' => 'Kralj (Sveti Kralj)',
        'dynasty' => 'Nemanjići',
    ],
    'car-dusan' => [
        'name' => 'Stefan Uroš IV Dušan Silni',
        'title' => 'Car',
        'dynasty' => 'Nemanjići',
    ],
    'uros-nejaki' => [
        'name' => 'Stefan Uroš V Nejaki',
        'title' => 'Car (Sveti Car Uroš)',
        'dynasty' => 'Nemanjići',
    ],
    'sveti-sava' => [
        'name' => 'Sveti Sava (Rastko Nemanjić)',
        'title' => 'Arhiepiskop',
        'dynasty' => 'Nemanjići',
    ],
    'vukan-nemanjic' => [
        'name' => 'Veliki knez Vukan Nemanjić',
        'title' => 'Veliki knez Duklje i Zete',
        'dynasty' => 'Nemanjići',
    ],
    'ana-dandolo' => [
        'name' => 'Kraljica Ana Dandolo',
        'title' => 'Kraljica',
        'dynasty' => 'Nemanjići',
    ],
    'ana-zena-stefana-nemanje' => [
        'name' => 'Ana Nemanjić (Sveta Anastasija)',
        'title' => 'Kraljica / Monahinja',
        'dynasty' => 'Nemanjići',
    ],
    'carica-jelena' => [
        'name' => 'Carica Jelena (Sveta Jelisaveta)',
        'title' => 'Carica',
        'dynasty' => 'Nemanjići',
    ],
    'jelena-anzujska' => [
        'name' => 'Kraljica Jelena Anžujska (Sveta Jelena)',
        'title' => 'Kraljica',
        'dynasty' => 'Anžujci',
    ],
    'kneginja-milica' => [
        'name' => 'Kneginja Milica Hrebeljanović (Sveta Evgenija)',
        'title' => 'Kneginja',
        'dynasty' => 'Hrebeljanovići',
    ],
    'knez-lazar' => [
        'name' => 'Sveti Knez Lazar Hrebeljanović',
        'title' => 'Knez',
        'dynasty' => 'Hrebeljanovići',
    ],
    'simonida' => [
        'name' => 'Kraljica Simonida Paleolog',
        'title' => 'Kraljica',
        'dynasty' => 'Nemanjići',
    ],
    'stefan-lazarevic' => [
        'name' => 'Sveti Despot Stefan Lazarević',
        'title' => 'Despot',
        'dynasty' => 'Lazarevići',
    ],
];

$monastery_ktitor_map = [
    'Kralj Milutin' => 'Stefan Uroš II Milutin',
    'Kralj Milutin / Obnovio iguman Danilo' => 'Stefan Uroš II Milutin / Obnovio iguman Danilo',
    'Vizantijski car Roman IV Diogen (obnovio Kralj Milutin)' => 'Vizantijski car Roman IV Diogen (obnovio Stefan Uroš II Milutin)',
    'Sveti Kralj Stefan Uroš II Milutin' => 'Stefan Uroš II Milutin',
    
    'Kralj Dragutin' => 'Stefan Dragutin Nemanjić',
    'Kralj Stefan Dragutin' => 'Stefan Dragutin Nemanjić',
    'Kralj Stefan Dragutin Nemanjić (kraj 13. veka; obnovljen 2004)' => 'Stefan Dragutin Nemanjić (kraj 13. veka; obnovljen 2004)',
    
    'Stefan Nemanja' => 'Stefan Nemanja (Sveti Simeon)',
    'Veliki Župan Stefan Nemanja' => 'Stefan Nemanja (Veliki župan Stefan Nemanja)',
    'Veliki župan Stefan Nemanja' => 'Stefan Nemanja (Veliki župan Stefan Nemanja)',
    
    'Kralj Stefan Prvovenčani i Sveti Sava' => 'Stefan Prvovenčani i Sveti Sava',
    'Kralj Stefan Vladislav' => 'Stefan Vladislav Nemanjić',
    'Kralj Vladislav' => 'Stefan Vladislav Nemanjić',
    
    'Stefan Uroš I' => 'Stefan Uroš I Nemanjić',
    'Stefan Uroš I (predanje)' => 'Stefan Uroš I Nemanjić (predanje)',
    'Kralj Uroš I Nemanjić' => 'Stefan Uroš I Nemanjić',
    'Sveti Kralj Stefan Uroš I' => 'Stefan Uroš I Nemanjić',
    
    'Stefan Dečanski' => 'Stefan Uroš III Dečanski',
    'Kralj Stefan Dečanski' => 'Stefan Uroš III Dečanski',
    'Sveti Kralj Stefan Dečanski i Car Dušan' => 'Stefan Uroš III Dečanski i Stefan Uroš IV Dušan',
    
    'Car Dušan' => 'Stefan Uroš IV Dušan Silni',
    'Car Dušan (predanje)' => 'Stefan Uroš IV Dušan Silni (predanje)',
    
    'Sveti Sava' => 'Sveti Sava (Rastko Nemanjić)',
    'Sveti Sava (predanje)' => 'Sveti Sava (Rastko Nemanjić, predanje)',
    'Sveti Sava (predanje) / Obnova 1707.' => 'Sveti Sava (Rastko Nemanjić, predanje) / Obnova 1707.',
    
    'Vukan Nemanjić' => 'Veliki knez Vukan Nemanjić',
    
    'Kraljica Jelena Anžujska' => 'Kraljica Jelena Anžujska (Sveta Jelena)',
    'Kraljica Katalina Nemanjić' => 'Kraljica Katalina Nemanjić',
    'Kneginja Milica' => 'Kneginja Milica Hrebeljanović',
    
    'Knez Lazar' => 'Sveti Knez Lazar Hrebeljanović',
    'Knez Lazar (predanje)' => 'Sveti Knez Lazar Hrebeljanović (predanje)',
    'Sveti Velikomučenik Knez Lazar Hrebeljanović' => 'Sveti Knez Lazar Hrebeljanović',
    'Sveti Đorđe / Knez Lazar (predanje)' => 'Sveti Knez Lazar Hrebeljanović (predanje)',
    
    'Despot Stefan Lazarević' => 'Sveti Despot Stefan Lazarević',
    'Protovestijar Bogdan sa ženom Milicom i Despot Stefan Lazarević' => 'Protovestijar Bogdan sa ženom Milicom i Sveti Despot Stefan Lazarević',
    
    'Đurađ Branković' => 'Despot Đurađ Branković',
    'Despot Jovan Branković' => 'Sveti Despot Jovan Branković',
    'Sveti Arsenije Sremac / Despot Jovan Branković' => 'Sveti Arsenije Sremac / Sveti Despot Jovan Branković',
    'Stefan i Angelina Branković' => 'Sveti Stefan i Sveta Mati Angelina Branković',
    'Vladika Maksim (Despot Đorđe Branković) i mati Angelina' => 'Sveti Vladika Maksim (Despot Đorđe Branković) i Sveta Mati Angelina',
    'Despotica Angelina Branković' => 'Sveta Mati Angelina Branković',
    'Stefan Štiljanović' => 'Sveti Stefan Štiljanović',
    
    'Braća Mrnjavčevići' => 'Braća Mrnjavčevići (Kralj Vukašin i Despot Uglješa)',
    'Braća Mrnjavčevići (Jovan Uglješa i Vukašin)' => 'Braća Mrnjavčevići (Kralj Vukašin i Despot Jovan Uglješa)',
    'Braća Musići (Stefan i Lazar)' => 'Braća Musići (Čelnik Stefan i Lazar Musić)',
    
    'Vladika Nikolaj Velimirović' => 'Sveti Vladika Nikolaj Velimirović',
    'Obnovio Vladika Nikolaj Velimirović' => 'Obnovio Sveti Vladika Nikolaj Velimirović',
    'Svetogorski monasi / Obnovio vladika Nikolaj Velimirović' => 'Svetogorski monasi / Obnovio Sveti Vladika Nikolaj Velimirović',
    'Svetogorski monasi / Obnovio episkop Nikolaj' => 'Svetogorski monasi / Obnovio Sveti Vladika Nikolaj Velimirović',
    
    'Vladika Lavrentije' => 'Episkop Lavrentije (Trifunović)',
    'Episkop Lavrentije' => 'Episkop Lavrentije (Trifunović)',
    'Episkop šabačko-valjevski Lavrentije i verni narod Podrinja (obnova 1966; vaspostavljen 2006)' => 'Episkop Lavrentije i verni narod Podrinja',
    'Karađorđe Ristanović (sa porodicom, u spomen roditeljima Kosti i Božani; osveštao episkop Lavrentije)' => 'Karađorđe Ristanović (sa porodicom)',
    
    'Srednjovekovna zadužbina (obnova 1923/24. arh. Dragutin Maslać; vaspostavljen 2006)' => 'Nepoznati srednjovekovni ktitor (obnova arh. Dragutin Maslać)',
    'Predanje / Zadužbinar' => 'Predanje / Nepoznati ktitor',
    'nepoznato' => 'Nepoznat',
    'Nije sačuvano' => 'Nepoznat',
];

$dbPaths = [
    database_path('database.sqlite'),
    storage_path('database.sqlite')
];

foreach ($dbPaths as $dbPath) {
    if (!file_exists($dbPath)) {
        echo "Baza ne postoji na putanji: {$dbPath}\n";
        continue;
    }

    echo "\n----------------------------------------------------\n";
    echo "AŽURIRANJE BAZE: {$dbPath}\n";
    echo "----------------------------------------------------\n";

    config(['database.connections.sqlite.database' => $dbPath]);
    DB::purge('sqlite');
    DB::reconnect('sqlite');

    // 1. Ažuriraj ktitore u tabeli ktitors
    foreach ($ktitors_updates as $slug => $data) {
        $ktitor = Ktitor::where('slug', $slug)->first();
        if ($ktitor) {
            $ktitor->name = $data['name'];
            $ktitor->title = $data['title'];
            $ktitor->dynasty = $data['dynasty'];
            $ktitor->save();
            echo "[+] [Ktitor] {$slug} -> {$data['name']}\n";
        }
    }

    // 2. Ažuriraj kolonu ktitor u tabeli monasteries
    $updatedCount = 0;
    foreach ($monastery_ktitor_map as $oldName => $newName) {
        $affected = DB::table('monasteries')
            ->where('ktitor', $oldName)
            ->update(['ktitor' => $newName]);
        if ($affected > 0) {
            $updatedCount += $affected;
            echo "[+] [Monasteries] '{$oldName}' -> '{$newName}' ({$affected} manastira)\n";
        }
    }
    echo "Ukupno ažurirano manastira sa novim nazivima ktitora: {$updatedCount}\n";
}

echo "\n====================================================================\n";
echo "AŽURIRANJE KTITORA USPEŠNO ZAVRŠENO!\n";
echo "====================================================================\n";
