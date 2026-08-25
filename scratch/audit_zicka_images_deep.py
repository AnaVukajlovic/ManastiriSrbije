import os
import glob
from PIL import Image
import numpy as np
import sys

sys.stdout.reconfigure(encoding='utf-8')

# List of 35 Žička monasteries and their prefixes/slugs
zicka_monasteries = [
    (206, 'blagovestenje-ovcar', 'Manastir Blagoveštenje (Ovčar-Kablar)'),
    (207, 'dubrava', 'Manastir Dubrava'),
    (208, 'godovik', 'Manastir Godovik'),
    (209, 'gradac', 'Manastir Gradac'),
    (210, 'ilinje-ovcar', 'Manastir Ilinje (Ovčar-Kablar)'),
    (211, 'isposnica-svetog-save', 'Manastir Gornja Isposnica Svetog Save'),
    (212, 'jezevica', 'Manastir Ježevica'),
    (213, 'jovanje-ovcar-kablar', 'Manastir Jovanje (Ovčar-Kablar)'),
    (214, 'klisura', 'Manastir Klisura'),
    (215, 'kovilje', 'Manastir Kovilje'),
    (216, 'moravci', 'Manastir Moravci'),
    (217, 'nikolje-ovcar-kablar', 'Manastir Nikolje (Ovčar-Kablar)'),
    (218, 'nova-pavlica', 'Manastir Nova Pavlica'),
    (219, 'preobrazenje-ovcar-kablar', 'Manastir Preobraženje (Ovčar-Kablar)'),
    (220, 'pridvorica', 'Manastir Pridvorica'),
    (221, 'raca', 'Manastir Rača'),
    (222, 'rujan', 'Manastir Rujan'),
    (223, 'sabor', 'Manastir Sabor Srpskih Svetitelja'),
    (224, 'savinac', 'Manastir Savinac'),
    (225, 'sretenje', 'Manastir Sretenje (Ovčar-Kablar)'),
    (226, 'stara-pavlica', 'Manastir Stara Pavlica'),
    (227, 'stubal', 'Manastir Stubal'),
    (228, 'studenica', 'Manastir Studenica'),
    (229, 'sveta-trojica-ovcar', 'Manastir Sveta Trojica (Ovčar)'),
    (230, 'trnava', 'Manastir Trnava'),
    (231, 'uspenje-kablar', 'Manastir Uspenje (Kablar)'),
    (232, 'uvac', 'Manastir Uvac'),
    (233, 'vavedenje-ovcar', 'Manastir Vavedenje (Ovčar-Kablar)'),
    (234, 'vaznesenje-ovcar', 'Manastir Vaznesenje (Ovčar-Kablar)'),
    (235, 'voljavca-bresnica', 'Manastir Voljavča (Bresnica)'),
    (236, 'vracevsnica', 'Manastir Vraćevšnica'),
    (237, 'vujan', 'Manastir Vujan'),
    (238, 'zgodacica', 'Manastir Zgodačica'),
    (239, 'zica', 'Manastir Žiča'),
    (254, 'stjenik', 'Manastir Stjenik'),
]

def get_image_signature(filepath):
    try:
        with Image.open(filepath) as im:
            im = im.convert('L').resize((32, 32), Image.Resampling.BILINEAR)
            arr = np.array(im, dtype=np.float32)
            # normalize
            arr = (arr - arr.mean()) / (arr.std() + 1e-5)
            return arr, im.size
    except Exception as e:
        return None, None

def similarity(sig1, sig2):
    if sig1 is None or sig2 is None:
        return 0.0
    # Correlation coefficient
    return float(np.mean(sig1 * sig2))

print("====================================================================")
print("POČINJE DETALJNA ANALIZA SLIKA ZA EPARHIJU ŽIČKU")
print("====================================================================\n")

duplicate_report = []

for mid, prefix, name in zicka_monasteries:
    # Find all files for this prefix
    pattern = f"public/images/monasteries/{prefix}*"
    files = glob.glob(pattern)
    # filter out _sumadijska or other unrelated
    files = [f for f in files if '_sumadijska' not in f and not f.endswith('.temp')]
    files = sorted(files)
    
    print(f"\n[{mid}] {name} ({prefix}) - pronađeno {len(files)} fajlova:")
    
    signatures = {}
    for f in files:
        fname = os.path.basename(f)
        sz = os.path.getsize(f)
        sig, dims = get_image_signature(f)
        signatures[fname] = (sig, dims, sz)
        print(f"   • {fname} (veličina: {sz} B)")
        
    # Check pairwise similarity to catch duplicate / cropped images
    fnames = list(signatures.keys())
    for i in range(len(fnames)):
        for j in range(i + 1, len(fnames)):
            f1, f2 = fnames[i], fnames[j]
            sig1, _, sz1 = signatures[f1]
            sig2, _, sz2 = signatures[f2]
            sim = similarity(sig1, sig2)
            if sim > 0.85:
                print(f"   ⚠️ UPOZORENJE: Visoka sličnost ({sim*100:.1f}%) između {f1} i {f2} (MOGUĆI DUPLIKAT/IZREZ!)")
                duplicate_report.append((mid, name, f1, f2, sim))
            elif sim > 0.70:
                print(f"   ℹ️ Umerena sličnost ({sim*100:.1f}%) između {f1} i {f2}")

print("\n\n====================================================================")
print("REZIME DETEKCIJE DUPLIKATA / SLIČNIH SLIKA:")
print("====================================================================")
if duplicate_report:
    for mid, name, f1, f2, sim in duplicate_report:
        print(f"  [{mid}] {name}: {f1} <=> {f2} (Sličnost: {sim*100:.1f}%)")
else:
    print("  NEMA detektovanih duplikata!")
