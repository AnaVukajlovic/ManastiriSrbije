import os
import sys
import io
import re
import json
import sqlite3
import urllib.request
import urllib.parse
from bs4 import BeautifulSoup

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
CACHE_DIR = os.path.join(BASE_DIR, 'storage', 'cache_manastiri_rs')
DB_STORAGE_PATH = os.path.join(BASE_DIR, 'storage', 'database.sqlite')

HEADERS = {
    'User-Agent': 'PravoslavniSvetionik/1.0 (https://github.com/manastiri-srbije; kontakt@svetionik.org.rs)'
}

def cyr_to_lat(text):
    if not text:
        return ""
    table = {
        'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'ђ': 'đ', 'е': 'e', 'ж': 'ž', 'з': 'z', 'и': 'i',
        'ј': 'j', 'к': 'k', 'л': 'l', 'љ': 'lj', 'м': 'm', 'н': 'n', 'њ': 'nj', 'о': 'o', 'п': 'p', 'р': 'r',
        'с': 's', 'т': 't', 'ћ': 'ć', 'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c', 'ч': 'č', 'џ': 'dž', 'ш': 'š',
        'А': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D', 'Ђ': 'Đ', 'Е': 'E', 'Ж': 'Ž', 'З': 'Z', 'И': 'I',
        'Ј': 'J', 'К': 'K', 'Л': 'L', 'Љ': 'Lj', 'М': 'M', 'Н': 'N', 'Њ': 'Nj', 'О': 'O', 'П': 'P', 'Р': 'R',
        'С': 'S', 'Т': 'T', 'Ћ': 'Ć', 'У': 'U', 'Ф': 'F', 'Х': 'H', 'Ц': 'C', 'Ч': 'Č', 'Џ': 'Dž', 'Ш': 'Š'
    }
    # Handle digraphs first
    digraphs = {'Љ': 'Lj', 'Њ': 'Nj', 'Џ': 'Dž', 'љ': 'lj', 'њ': 'nj', 'џ': 'dž'}
    for cyr, lat in digraphs.items():
        text = text.replace(cyr, lat)
    res = []
    for ch in text:
        res.append(table.get(ch, ch))
    return "".join(res)

def fetch_wiki_text(monastery_name):
    # 1. Search Wikipedia
    search_terms = [
        monastery_name,
        f"Манастир {monastery_name.replace('Manastir ', '').strip()}",
        f"Црква {monastery_name.replace('Manastir ', '').strip()}"
    ]
    if 'ljevišk' in monastery_name.lower() or 'ljevisk' in monastery_name.lower():
        search_terms = ['Црква Богородица Љевишка']

    best_title = None
    for st in search_terms:
        try:
            surl = f"https://sr.wikipedia.org/w/api.php?action=query&list=search&srsearch={urllib.parse.quote(st)}&format=json"
            req = urllib.request.Request(surl, headers=HEADERS)
            sdata = json.loads(urllib.request.urlopen(req, timeout=4).read().decode('utf-8'))
            hits = sdata.get('query', {}).get('search', [])
            if hits:
                best_title = hits[0]['title']
                break
        except Exception:
            pass

    if not best_title:
        return ""

    try:
        eurl = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(best_title)}&prop=extracts&explaintext=1&format=json"
        req = urllib.request.Request(eurl, headers=HEADERS)
        edata = json.loads(urllib.request.urlopen(req, timeout=5).read().decode('utf-8'))
        pages = edata.get('query', {}).get('pages', {})
        for pid, p in pages.items():
            return cyr_to_lat(p.get('extract', ''))
    except Exception:
        pass
    return ""

print("Test Wiki Text Fetch:")
txt = fetch_wiki_text("Bogorodica Ljeviška")
print("Bogorodica Ljeviška length:", len(txt))
print("First 400 chars:\n", txt[:400])
