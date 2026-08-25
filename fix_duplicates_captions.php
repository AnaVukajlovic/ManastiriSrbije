<?php
/**
 * fix_duplicates_captions.php
 *
 * Manastir Mislođin (ID 15):
 *   - mislodjin.jpg i mislodjin_gal_2.jpg su iste slike (portal crkve).
 *   - Popraviti caption za mislodjin.jpg (card).
 *   - Ukloniti mislodjin_gal_2.jpg iz baze (duplikat).
 *
 * Manastir Slanci (ID 19):
 *   - slanci.jpg, slanci_gal_1.jpg, slanci_gal_2.jpg su gotovo iste slike.
 *   - Ukloniti duplikate gal_1 i gal_2, popraviti caption za card.
 *
 * Manastir Trojeručica (ID 20):
 *   - trojerucica_gal_1.jpg prikazuje ikonu Bogorodice Trojeručice,
 *     ali caption kaže "Zapadna fasada sa ulazom i zvonikom" — pogrešno.
 *   - Ispraviti caption.
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MonasteryImage;

$src_commons = ' *Izvor: commons.wikimedia.org*';
$src_manastiri = ' *Izvor: manastiri.rs*';

echo "=== POPRAVKA DUPLIKATA I OPISA ===\n\n";

// ─────────────────────────────────────────────────────────────
// 1. MISLOĐIN (ID 15)
// ─────────────────────────────────────────────────────────────

// Ispraviti caption za card sliku (ID 10430) — ona prikazuje ulazni portal, ne "crkvu sa kupolom"
$card = MonasteryImage::find(10430);
if ($card) {
    $card->caption = 'Ulazni portal manastirske crkve Svetog Hristofora u Mislođinu sa rezbarenim drvenim vratima i karakterističnim lučnim svodom' . $src_commons;
    $card->save();
    echo "[UPDATED] Mislođin card caption (ID 10430)\n";
}

// Ukloniti duplikat mislodjin_gal_2.jpg (ID 10432) — ista slika kao card
$dup = MonasteryImage::find(10432);
if ($dup) {
    $dup->delete();
    echo "[DELETED] Mislođin gal_2 duplikat (ID 10432) - mislodjin_gal_2.jpg\n";
}

// Sada gal_3 (ID 10433) postaje de facto gal_2 — ažuriramo sort_order
$gal3 = MonasteryImage::find(10433);
if ($gal3) {
    $gal3->sort_order = 3;
    $gal3->save();
    echo "[UPDATED] Mislođin gal_3 sort_order → 3 (ID 10433)\n";
}

// ─────────────────────────────────────────────────────────────
// 2. SLANCI (ID 19)
// ─────────────────────────────────────────────────────────────
// Sve tri slike su iste crkve sa sličnog ugla.
// Ostavljamo card sliku (ID 10446), brišemo gal_1 (ID 10447) i gal_2 (ID 10448).
// Card caption ostavljamo korigovan.

$slanciCard = MonasteryImage::find(10446);
if ($slanciCard) {
    $slanciCard->caption = 'Manastirska crkva Svetog arhiđakona Stefana u Slancima, metoh manastira Hilandara, sa zvonikom i uređenom portom' . $src_manastiri;
    $slanciCard->save();
    echo "[UPDATED] Slanci card caption (ID 10446)\n";
}

$slanciGal1 = MonasteryImage::find(10447);
if ($slanciGal1) {
    $slanciGal1->delete();
    echo "[DELETED] Slanci gal_1 duplikat (ID 10447) - slanci_gal_1.jpg\n";
}

$slanciGal2 = MonasteryImage::find(10448);
if ($slanciGal2) {
    $slanciGal2->delete();
    echo "[DELETED] Slanci gal_2 duplikat (ID 10448) - slanci_gal_2.jpg\n";
}

// ─────────────────────────────────────────────────────────────
// 3. TROJERUČICA (ID 20)
// ─────────────────────────────────────────────────────────────
// trojerucica_gal_1.jpg prikazuje ikonu Bogorodice Trojeručice,
// ne "Zapadnu fasadu" — ispraviti caption.

$trojerGal1 = MonasteryImage::find(16099);
if ($trojerGal1) {
    $trojerGal1->caption = 'Čudotvorna ikona Bogorodice Trojeručice, po kojoj je manastir dobio ime, smeštena u unutrašnjosti manastirskog hrama u Ripnju' . $src_manastiri;
    $trojerGal1->save();
    echo "[UPDATED] Trojeručica gal_1 caption (ID 16099)\n";
}

// ─────────────────────────────────────────────────────────────
// Rezime
// ─────────────────────────────────────────────────────────────
echo "\n=== ZAVRŠENO ===\n";
echo "Napomena: Za manastir Slanci i Mislođin potrebno je dodati nove, raznolike slike iz galerije.\n";
echo "Preporučeni izvor: commons.wikimedia.org\n";
