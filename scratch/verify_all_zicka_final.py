import os
import sys

sys.stdout.reconfigure(encoding='utf-8')

zicka_monasteries = [
    (206, 'blagovestenje-ovcar', ['blagovestenje-ovcar.jpg', 'blagovestenje-ovcar_gal_1.jpg', 'blagovestenje-ovcar_gal_2.jpg', 'blagovestenje-ovcar_gal_3.jpg']),
    (207, 'dubrava', ['dubrava.jpg', 'dubrava_gal_1.jpg', 'dubrava_gal_2.jpg', 'dubrava_gal_3.jpg']),
    (208, 'godovik', ['godovik.jpg', 'godovik_gal_1.jpg', 'godovik_gal_2.jpg', 'godovik_gal_3.jpg']),
    (209, 'gradac', ['gradac.jpg', 'gradac_gal_1.jpg', 'gradac_gal_2.jpg', 'gradac_gal_3.jpg']),
    (210, 'ilinje-ovcar', ['ilinje-ovcar.jpg', 'ilinje-ovcar_gal_1.jpg', 'ilinje-ovcar_gal_2.jpg', 'ilinje-ovcar_gal_3.jpg']),
    (211, 'isposnica-svetog-save', ['isposnica-svetog-save.jpg', 'isposnica-svetog-save_gal_1.jpg', 'isposnica-svetog-save_gal_2.jpg', 'isposnica-svetog-save_gal_3.jpg']),
    (212, 'jezevica', ['jezevica.jpg', 'jezevica_gal_1.jpg', 'jezevica_gal_2.jpg', 'jezevica_gal_3.jpg']),
    (213, 'jovanje-ovcar-kablar', ['jovanje-ovcar-kablar.jpg', 'jovanje-ovcar-kablar_gal_1.jpg', 'jovanje-ovcar-kablar_gal_2.jpg', 'jovanje-ovcar-kablar_gal_3.jpg']),
    (214, 'klisura', ['klisura.jpg', 'klisura_gal_1.jpg', 'klisura_gal_2.jpg', 'klisura_gal_3.jpg']),
    (215, 'kovilje', ['kovilje.jpg', 'kovilje_gal_1.jpg', 'kovilje_gal_2.jpg', 'kovilje_gal_3.jpg']),
    (216, 'moravci', ['moravci.jpg', 'moravci_gal_1.jpg', 'moravci_gal_2.jpg', 'moravci_gal_3.jpg']),
    (217, 'nikolje-ovcar-kablar', ['nikolje-ovcar-kablar.jpg', 'nikolje-ovcar-kablar_gal_1.jpg', 'nikolje-ovcar-kablar_gal_2.jpg', 'nikolje-ovcar-kablar_gal_3.jpg']),
    (218, 'nova-pavlica', ['nova-pavlica.jpg', 'nova-pavlica_gal_1.jpg', 'nova-pavlica_gal_2.jpg', 'nova-pavlica_gal_3.jpg']),
    (219, 'preobrazenje-ovcar-kablar', ['preobrazenje-ovcar-kablar.jpg', 'preobrazenje-ovcar-kablar_gal_1.jpg', 'preobrazenje-ovcar-kablar_gal_2.jpg', 'preobrazenje-ovcar-kablar_gal_3.jpg']),
    (220, 'pridvorica', ['pridvorica.jpg', 'pridvorica_gal_1.jpg', 'pridvorica_gal_2.jpg', 'pridvorica_gal_3.jpg']),
    (221, 'raca', ['raca.jpg', 'raca_gal_1.jpg', 'raca_gal_2.jpg', 'raca_gal_3.jpg']),
    (222, 'rujan', ['rujan.jpg', 'rujan_gal_1.jpg', 'rujan_gal_2.jpg', 'rujan_gal_3.jpg']),
    (223, 'sabor', ['sabor.jpg', 'sabor_gal_1.jpg', 'sabor_gal_2.jpg', 'sabor_gal_3.jpg']),
    (224, 'savinac', ['savinac.jpg', 'savinac_gal_1.jpg', 'savinac_gal_2.jpg', 'savinac_gal_3.jpg']),
    (225, 'sretenje', ['sretenje.jpg', 'sretenje_gal_1.jpg', 'sretenje_gal_2.jpg', 'sretenje_gal_3.jpg']),
    (226, 'stara-pavlica', ['stara-pavlica.jpg', 'stara-pavlica_gal_1.jpg', 'stara-pavlica_gal_2.jpg', 'stara-pavlica_gal_3.jpg']),
    (227, 'stubal', ['stubal.jpg', 'stubal_gal_1.jpg', 'stubal_gal_2.jpg', 'stubal_gal_3.jpg']),
    (228, 'studenica', ['studenica.jpg', 'studenica_gal_1.jpg', 'studenica_gal_2.jpg', 'studenica_gal_3.jpg']),
    (229, 'sveta-trojica-ovcar', ['sveta-trojica-ovcar.jpg', 'sveta-trojica-ovcar_gal_1.jpg', 'sveta-trojica-ovcar_gal_2.jpg', 'sveta-trojica-ovcar_gal_3.jpg']),
    (230, 'trnava', ['trnava.jpg', 'trnava_gal_1.jpg', 'trnava_gal_2.jpg', 'trnava_gal_3.jpg']),
    (231, 'uspenje-kablar', ['uspenje-kablar.jpg', 'uspenje-kablar_gal_1.jpg', 'uspenje-kablar_gal_2.jpg', 'uspenje-kablar_gal_3.jpg']),
    (232, 'uvac', ['uvac.jpg', 'uvac_gal_1.jpg', 'uvac_gal_2.jpg', 'uvac_gal_3.jpg']),
    (233, 'vavedenje-ovcar', ['vavedenje-ovcar.jpg', 'vavedenje-ovcar_gal_1.jpg', 'vavedenje-ovcar_gal_2.jpg', 'vavedenje-ovcar_gal_3.jpg']),
    (234, 'vaznesenje-ovcar', ['vaznesenje-ovcar.jpg', 'vaznesenje-ovcar_gal_1.jpg', 'vaznesenje-ovcar_gal_2.jpg', 'vaznesenje-ovcar_gal_3.jpg']),
    (235, 'voljavca-bresnica', ['voljavca-bresnica.jpg', 'voljavca-bresnica_gal_1.jpg', 'voljavca-bresnica_gal_2.jpg', 'voljavca-bresnica_gal_3.jpg']),
    (236, 'vracevsnica', ['vracevsnica.jpg', 'vracevsnica_gal_1.jpg', 'vracevsnica_gal_2.jpg', 'vracevsnica_gal_3.jpg']),
    (237, 'vujan', ['vujan.jpg', 'vujan_gal_1.jpg', 'vujan_gal_2.jpg', 'vujan_gal_3.jpg']),
    (238, 'zgodacica', ['zgodacica.jpg', 'zgodacica_gal_1.png']),
    (239, 'zica', ['zica.jpg', 'zica_gal_1.jpg', 'zica_gal_2.jpg', 'zica_gal_3.jpg']),
    (254, 'stjenik', ['stjenik.jpg', 'stjenik_gal_1.jpg', 'stjenik_gal_2.jpg', 'stjenik_gal_3.jpg']),
]

missing = []
total_count = 0
for mid, name, files in zicka_monasteries:
    for f in files:
        total_count += 1
        p = os.path.join('public/images/monasteries', f)
        if not os.path.exists(p) or os.path.getsize(p) < 1000:
            missing.append(f)

print(f"Provereno ukupno {total_count} slika za svih 35 manastira Žičke eparhije.")
if missing:
    print(f"Nedostaje {len(missing)} fajlova:", missing)
else:
    print("SVE SLIKE 100% POSTOJE I FIZICKI SU PRISUTNE NA DISKU BEZ IKAKVIH NEDOSTATAKA!")
