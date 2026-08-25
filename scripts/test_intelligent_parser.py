import urllib.request
import re
import xml.etree.ElementTree as ET
import sqlite3
import unicodedata
import io
import sys
from bs4 import BeautifulSoup

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

def clean_text(text):
    if not text:
        return ""
    # Ukloni nepotrebne veb/marketinške fraze
    filters = [
        r'U nastavku člakna ćemo vam.*',
        r'U nastavku teksta ćemo vam.*',
        r'Zamislite mesto gde.*',
        r'Više o današnjim praznicima i postovima možeš videti.*',
        r'Ako želite da saznate više.*',
        r'Kliknite ovde.*',
        r'Pročitajte i:.*',
        r'Podelite ovaj tekst.*',
        r'Za više informacija, kontaktirajte.*',
        r'U tom miru, deciji molitvenik.*',
    ]
    for pattern in filters:
        text = re.sub(pattern, '', text, flags=re.IGNORECASE)
    
    text = text.replace('člakna', 'članka')
    text = text.replace('prelepljenoj', 'prelepoj')
    text = text.replace('zgradu srpskog kralja', 'zadužbinu srpskog kralja')
    text = text.replace('historije', 'istorije')
    text = text.replace('historiji', 'istoriji')
    text = text.replace('historiju', 'istoriju')
    text = text.replace('umjetnost', 'umetnost')
    text = text.replace('umjetnička', 'umetnička')
    text = text.replace('umjetnički', 'umetnički')
    text = text.replace('ovdje', 'ovde')
    
    # Ekavica konverzija
    ekavica_map = {
        'vijek': 'vek', 'vijeka': 'veka', 'vijeku': 'veku', 'vijekom': 'vekom', 'vijekovi': 'vekovi', 'vijekovima': 'vekovima',
        'svijet': 'svet', 'svijeta': 'sveta', 'svijetu': 'svetu',
        'vrijeme': 'vreme', 'vremena': 'vremena',
        'mjesto': 'mesto', 'mjesta': 'mesta', 'mjestu': 'mestu', 'mjestima': 'mestima',
        'dijete': 'dete', 'djeteta': 'deteta', 'djeca': 'deca', 'djece': 'dece',
        'lijep': 'lep', 'lijepa': 'lepa', 'lijepo': 'lepo', 'lijepi': 'lepi',
        'rijeka': 'reka', 'rijeke': 'reke', 'rijeci': 'reci',
        'prije': 'pre', 'poslije': 'posle',
        'brijeg': 'breg', 'brijega': 'brega',
        'snijeg': 'sneg', 'snijega': 'snega',
        'vidjeti': 'videti', 'živjeti': 'živeti', 'umrijeti': 'umreti',
    }
    for ije, e in ekavica_map.items():
        text = re.sub(rf'\b{ije}\b', e, text, flags=re.IGNORECASE)
    
    text = re.sub(r'\s+', ' ', text).strip()
    return text

def parse_manastiri_page(url):
    try:
        req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'})
        with urllib.request.urlopen(req, timeout=12) as resp:
            html = resp.read().decode('utf-8', errors='ignore')
            soup = BeautifulSoup(html, 'html.parser')
            
            history_paras = []
            arch_paras = []
            general_paras = []
            
            # Extract main intro
            for p in soup.find_all('p'):
                t = clean_text(p.text)
                if len(t) > 40 and not any(skip in t.lower() for skip in ['radno vreme', 'kako doći', 'prodavnica', 'faq', 'kontakt']):
                    general_paras.append(t)
                    
            # Extract section by section
            for h in soup.find_all(['h2', 'h3']):
                h_text = h.text.strip().lower()
                
                # Skip tourist/commercial headings
                if any(skip in h_text for skip in ['radno vreme', 'kako doći', 'prodavnic', 'faq', 'drugi manastir', 'smernice za posetioc', 'informacije za turist']):
                    continue
                    
                # Collect paragraphs under this heading
                curr = h.next_sibling
                paras = []
                while curr and getattr(curr, 'name', None) not in ['h2', 'h3']:
                    if getattr(curr, 'name', None) == 'p':
                        t = clean_text(curr.text)
                        if len(t) > 35:
                            paras.append(t)
                    curr = curr.next_sibling
                    
                if any(kw in h_text for kw in ['istorij', 'nastan', 'ktitor', 'ko je i zašto', 'stradanj', 'obnov', 'mošti', 'vreme']):
                    history_paras.extend(paras)
                elif any(kw in h_text for kw in ['arhitektur', 'opis', 'crkva', 'fresk', 'umetn', 'zivopis', 'kula', 'utvrdj', 'ikonostas']):
                    arch_paras.extend(paras)
                else:
                    general_paras.extend(paras)
                    
            # Deduplicate paragraphs while preserving order
            def dedup(plist):
                seen = set()
                res = []
                for p in plist:
                    if p not in seen and len(p) > 35:
                        seen.add(p)
                        res.append(p)
                return res
                
            return {
                'history': dedup(history_paras),
                'architecture': dedup(arch_paras),
                'general': dedup(general_paras)
            }
    except Exception as e:
        print(f"Error parsing {url}: {e}")
        return {'history': [], 'architecture': [], 'general': []}

# Test with Manasija, Studenica, Krušedol
for test_url in [
    'https://manastiri.rs/eparhije/branicevska/manastir-manasija/',
    'https://manastiri.rs/eparhije/zicka/manastir-studenica/',
    'https://manastiri.rs/eparhije/sremska/manastir-krusedol/'
]:
    print(f"\n==================== TEST: {test_url} ====================")
    data = parse_manastiri_page(test_url)
    print(f"History paragraphs ({len(data['history'])}):")
    for p in data['history'][:2]:
        print(f"  - {p[:120]}...")
    print(f"Architecture paragraphs ({len(data['architecture'])}):")
    for p in data['architecture'][:2]:
        print(f"  - {p[:120]}...")
