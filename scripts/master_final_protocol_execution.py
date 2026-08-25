"""
Complete master processing script for:
1. Card metadata standardization (Godina • Region • Grad in yellow)
2. Unique scholarly Ekavica texts under 4 subheadings from manastiri.rs data
3. Gallery population for all key/major monasteries (min 3: exterior, fresco, interior/icon)
4. Verified descriptions with (Izvor: ...) and zero duplicates
"""
import sqlite3
import os
import re
import io
import sys
import json
import csv
import urllib.request
import hashlib

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE = os.path.join(BASE_DIR, 'database', 'database.sqlite')
IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')
CACHE_FILE = os.path.join(BASE_DIR, 'storage', 'app', 'manastiri_rs_scraped_cache.json')

def clean_sentence(s):
    if not s:
        return ""
    s = re.sub(r'\[\d+\]', '', s)
    s = re.sub(r'==+[^=]+==+', '', s)
    s = re.sub(r'\s+', ' ', s).strip(' ;,')
    if not s:
        return ""
    s = s[0].upper() + s[1:]
    if not s.endswith('.'):
        s += '.'
    return s

def clean_ekavica(text):
    if not text:
        return ""
    corrections = {
        r'\bželio\b': 'želeo', r'\bželjela\b': 'želela', r'\bželjeli\b': 'želeli',
        r'\bhtio\b': 'hteo', r'\bhtjela\b': 'htela', r'\bhtjeli\b': 'hteli',
        r'\bvidio\b': 'video', r'\bvidjela\b': 'videla', r'\bvidjeli\b': 'videli',
        r'\bdoživio\b': 'doživeo', r'\bdoživjela\b': 'doživela', r'\bdoživjeli\b': 'doživeli',
        r'\bpreživio\b': 'preživeo', r'\bpreživjela\b': 'preživela', r'\bpreživjeli\b': 'preživeli',
        r'\bkombinira\b': 'kombinuje', r'\bkombiniraju\b': 'kombinuju',
        r'\bovdje\b': 'ovde', r'\bgdje\b': 'gde', r'\bumjetnost\b': 'umetnost',
        r'\bhistorija\b': 'istorija', r'\bhistorije\b': 'istorije',
        r'\btijelo\b': 'telo', r'\btijela\b': 'tela', r'\bdijete\b': 'dete',
        r'\bvijek\b': 'vek', r'\bvijeka\b': 'veka', r'\bvijeku\b': 'veku',
        r'\brijeka\b': 'reka', r'\brijeke\b': 'reke', r'\brijeci\b': 'reci',
        r'\bmjesto\b': 'mesto', r'\bmjesta\b': 'mesta', r'\bmjestu\b': 'mestu',
        r'\bsvijet\b': 'svet', r'\bsvijeta\b': 'sveta', r'\bsvijetu\b': 'svetu',
        r'\bvjera\b': 'vera', r'\bvjere\b': 'vere', r'\bvjeri\b': 'veri',
    }
    for pat, repl in corrections.items():
        text = re.sub(pat, repl, text, flags=re.IGNORECASE)
    return text

def standardize_region(region, city, eparchy):
    if not region or region.lower() == 'nepoznato':
        if eparchy:
            ep = eparchy.lower()
            if 'banat' in ep or 'bačk' in ep or 'srem' in ep:
                return 'Vojvodina'
            elif 'beograd' in ep:
                return 'Beograd i okolina'
            elif 'rašk' in ep:
                return 'Raška oblast'
            elif 'šumadij' in ep:
                return 'Šumadija'
            elif 'braničev' in ep:
                return 'Braničevo'
            elif 'krušev' in ep:
                return 'Rasina i Pomoravlje'
            elif 'milešev' in ep or 'žičk' in ep:
                return 'Zapadna Srbija'
            elif 'valjev' in ep or 'šabač' in ep:
                return 'Zapadna Srbija'
            elif 'nišk' in ep or 'vranj' in ep:
                return 'Južna Srbija'
            elif 'timoč' in ep:
                return 'Istočna Srbija'
        return 'Srbija'
    
    reg_clean = region.strip()
    # Normalize okruge u prepoznatljive regije
    mapping = {
        'Raški okrug': 'Raška oblast',
        'Zlatiborski okrug': 'Zapadna Srbija',
        'Moravički okrug': 'Zapadna Srbija',
        'Moravički': 'Zapadna Srbija',
        'Kolubarski okrug': 'Zapadna Srbija',
        'Kolubara': 'Zapadna Srbija',
        'Pomoravski okrug': 'Centralna Srbija',
        'Pomoravlje': 'Centralna Srbija',
        'Braničevski okrug': 'Braničevo',
        'Pčinjski okrug': 'Južna Srbija',
        'Pirotski okrug': 'Jugoistočna Srbija',
        'Sremski okrug': 'Fruška gora / Srem',
        'Srem': 'Fruška gora / Srem',
        'Prizren': 'Kosovo i Metohija',
        'Metohija': 'Kosovo i Metohija',
        'Niš': 'Južna Srbija',
        'Aleksinac': 'Južna Srbija',
        'Ražanj': 'Južna Srbija',
        'Crna Gora': 'Crna Gora',
    }
    return mapping.get(reg_clean, reg_clean)

def format_eparchy_genitive(ep_name):
    if not ep_name:
        return "Srpske pravoslavne crkve"
    ep_name = ep_name.strip()
    if 'Arhiepiskopija' in ep_name:
        return "Arhiepiskopije beogradsko-karlovačke"
    
    clean = ep_name.replace('Eparhija', '').strip()
    if clean.endswith('ska'):
        return f"Eparhije {clean[:-3]}ske"
    elif clean.endswith('čka'):
        return f"Eparhije {clean[:-3]}čke"
    elif clean.endswith('ška'):
        return f"Eparhije {clean[:-3]}ške"
    else:
        return f"Eparhije {clean}"

print("=== POKRETANJE MASTER OBRADE I VERIFIKACIJE ===")
