<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - EPARHIJA BEOGRADSKA (ID 3)
 * Pravoslavni Svetionik — Master rad
 */

use App\Models\Monastery;
use App\Models\MonasteryImage;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "====================================================================\n";
echo "POKRETANJE REVIZIJE I USKLAĐIVANJA ZA EPARHIJU BEOGRADSKU (ID 3)\n";
echo "====================================================================\n\n";

$eparchy_data = [
    // 15: Manastir Mislođin
    15 => [
        'name' => 'Manastir Mislođin',
        'card_image' => 'images/monasteries/mislodjin.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/mislodjin.jpg',
                'caption' => 'Ulazni portal manastirske crkve Svetog Hristofora u Mislođinu sa rezbarenim drvenim vratima i karakterističnim lučnim svodom *Izvor: commons.wikimedia.org*',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/mislodjin_gal_1.jpg',
                'caption' => 'Zaštićeni arheološki ostaci i temelj srednjovekovne crkve kralja Dragutina vidljivi pod staklom unutar hrama *Izvor: commons.wikimedia.org*',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/mislodjin_gal_3.jpg',
                'caption' => 'Konzervirani arheološki ostaci i temelji srednjovekovnog manastira u kripti ispod hrama Svetog Hristofora u Mislođinu *Izvor: commons.wikimedia.org*',
                'sort_order' => 3,
            ],
        ],
    ],

    // 16: Manastir Rajinovac
    16 => [
        'name' => 'Manastir Rajinovac',
        'card_image' => 'images/monasteries/rajinovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/rajinovac.jpg',
                'caption' => 'Crkva Rođenja Presvete Bogorodice sa kamenim zvonikom u manastiru Rajinovac uokvirena rascvetalim ružama u porti *Izvor: commons.wikimedia.org*',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/rajinovac_gal_1.jpg',
                'caption' => 'Pogled na crkvu manastira Rajinovac sa zvonikom i konacima u živopisnom zelenilu begaljičkih brda *Izvor: commons.wikimedia.org*',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/rajinovac_gal_2.jpg',
                'caption' => 'Unutrašnjost manastirske crkve u Rajinovcu sa sunčevim zracima, freskama svetitelja i zlatnim polijelejem *Izvor: commons.wikimedia.org*',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/rajinovac_gal_3.jpg',
                'caption' => 'Freskopis na zidovima i oko prozora hrama u Rajinovcu sa likovima svetih apostola i arhijereja *Izvor: commons.wikimedia.org*',
                'sort_order' => 4,
            ],
        ],
    ],

    // 17: Manastir Rakovica
    17 => [
        'name' => 'Manastir Rakovica',
        'card_image' => 'images/monasteries/rakovica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/rakovica.jpg',
                'caption' => 'Ulazni prilaz, zvonik-kapija, konaci i prostrana zelena porta manastira Rakovica u Beogradu *Izvor: commons.wikimedia.org*',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/rakovica_gal_1.jpg',
                'caption' => 'Raskošna unutrašnjost manastirske crkve u Rakovici sa rezbarenim ikonostasom i freskama na zlatnoj pozadini *Izvor: commons.wikimedia.org*',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/rakovica_gal_2.jpg',
                'caption' => 'Mermerni grob prvog patrijarha obnovljene Srpske patrijaršije Dimitrija u porti manastira Rakovica *Izvor: commons.wikimedia.org*',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/rakovica_gal_3.jpg',
                'caption' => 'Reljefni zabat spomen-česme sa krunom i kraljevskim grbom dinastije Obrenović u porti manastira Rakovica *Izvor: commons.wikimedia.org*',
                'sort_order' => 4,
            ],
        ],
    ],

    // 18: Manastir Senjak (Vavedenje)
    18 => [
        'name' => 'Manastir Senjak',
        'card_image' => 'images/monasteries/senjak.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/senjak.jpg',
                'caption' => 'Monumentalna bela fasada sa pet kupola hrama Vavedenja Presvete Bogorodice na Senjaku, zadužbine Perse Milenković *Izvor: commons.wikimedia.org*',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/senjak_gal_1.jpg',
                'caption' => 'Unutrašnjost hrama na Senjaku sa monumentalnim mermernim stubom, vizantijskim kapitelom i oslikanim svodovima *Izvor: commons.wikimedia.org*',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/senjak_gal_2.jpg',
                'caption' => 'Mermerni ikonostas sa pozlaćenim carskim dverima i freskama u manastiru Vavedenje na Senjaku *Izvor: commons.wikimedia.org*',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/senjak_gal_3.jpg',
                'caption' => 'Zidna freska Svetog Save Srpskog u unutrašnjosti hrama manastira Vavedenje *Izvor: commons.wikimedia.org*',
                'sort_order' => 4,
            ],
        ],
    ],

    // 19: Manastir Slanci
    19 => [
        'name' => 'Manastir Slanci',
        'card_image' => 'images/monasteries/slanci.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/slanci.jpg',
                'caption' => 'Manastirska crkva Svetog arhiđakona Stefana u Slancima, metoh manastira Hilandara, sa zvonikom i uređenom portom *Izvor: manastiri.rs*',
                'sort_order' => 1,
            ],
        ],
    ],

    // 20: Manastir Trojeručica
    20 => [
        'name' => 'Manastir Trojeručica',
        'card_image' => 'images/monasteries/trojerucica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/trojerucica.jpg',
                'caption' => 'Crkva brvnara posvećena Bogorodici Trojeručici u manastiru Trojeručica u Ripnju pod Avalom *Izvor: commons.wikimedia.org*',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/trojerucica_gal_1.jpg',
                'caption' => 'Čudotvorna ikona Bogorodice Trojeručice, po kojoj je manastir dobio ime, smeštena u unutrašnjosti manastirskog hrama u Ripnju *Izvor: manastiri.rs*',
                'sort_order' => 2,
            ],
        ],
    ],
];

DB::beginTransaction();
try {
    foreach ($eparchy_data as $id => $data) {
        $monastery = Monastery::find($id);
        if (!$monastery) {
            echo "UPOZORENJE: Manastir ID {$id} ({$data['name']}) nije pronađen u bazi.\n";
            continue;
        }

        // 1. Ažuriranje card slike
        $monastery->image_url = $data['card_image'];
        $monastery->save();

        // 2. Brisanje postojećih slika iz galerije i unos novih verifikovanih
        MonasteryImage::where('monastery_id', $id)->delete();

        foreach ($data['images'] as $imgData) {
            MonasteryImage::create([
                'monastery_id' => $id,
                'url' => $imgData['url'],
                'caption' => $imgData['caption'],
                'sort_order' => $imgData['sort_order'],
            ]);
        }

        $imgCount = count($data['images']);
        echo "[USPEŠNO AŽURIRANO] ID {$id}: {$data['name']} (Galerija: {$imgCount} slika)\n";
    }

    DB::commit();
    echo "\nSVE IZMENE SU USPEŠNO SAČUVANE U BAZI!\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "\nGREŠKA: " . $e->getMessage() . "\n";
}
