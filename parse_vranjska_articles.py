import urllib.request
import urllib.parse
import re
import os
import json
import ssl
import sys

sys.stdout.reconfigure(encoding='utf-8')

ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

HEADERS = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36'}

monasteries = [
    {
        "id": 167,
        "slug": "bresnica",
        "name": "Manastir Bresnica",
        "wiki_urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%91%D1%80%D0%B5%D1%81%D0%BD%D0%B8%D1%86%D0%B0",
            "https://sr.wikipedia.org/wiki/%D0%A6%D1%80%D0%BA%D0%B2%D0%B0_%D0%A1%D0%B2%D0%B5%D1%82%D0%B5_%D0%9F%D0%B5%D1%82%D0%BA%D0%B5_%D1%83_%D0%91%D1%80%D0%B5%D1%81%D0%BD%D0%B8%D1%86%D0%B8",
            "https://sr.wikipedia.org/wiki/%D0%91%D1%80%D0%B5%D1%81%D0%BD%D0%B8%D1%86%D0%B0_(%D0%91%D0%BE%D1%81%D0%B8%D0%BB%D0%B5%D0%B3%D1%80%D0%B0%D0%B4)"
        ]
    },
    {
        "id": 168,
        "slug": "kacapun",
        "name": "Manastir Kacapun",
        "wiki_urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%9A%D0%B0%D1%86%D0%B0%D0%BF%D1%83%D0%BD",
            "https://sr.wikipedia.org/wiki/%D0%9A%D0%B0%D1%86%D0%B0%D0%BF%D1%83%D0%BD"
        ]
    },
    {
        "id": 169,
        "slug": "lopardince",
        "name": "Manastir Lopardince",
        "wiki_urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%9B%D0%BE%D0%BF%D0%B0%D1%80%D0%B4%D0%B8%D0%BD%D1%86%D0%B5",
            "https://sr.wikipedia.org/wiki/%D0%A6%D1%80%D0%BA%D0%B2%D0%B0_%D0%A1%D0%B2%D0%B5%D1%82%D0%BE%D0%B3_%D0%82%D0%BE%D1%80%D1%92%D0%B0_%D1%83_%D0%9B%D0%BE%D0%BF%D0%B0%D1%80%D0%B4%D0%B8%D0%BD%D1%86%D1%83",
            "https://sr.wikipedia.org/wiki/%D0%9B%D0%BE%D0%BF%D0%B0%D1%80%D0%B4%D0%B8%D0%BD%D1%86%D0%B5"
        ]
    },
    {
        "id": 170,
        "slug": "prohor-pcinjski",
        "name": "Manastir Prohor Pčinjski",
        "wiki_urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%9F%D1%80%D0%BE%D1%85%D0%BE%D1%80_%D0%9F%D1%87%D0%B8%D1%9A%D1%81%D0%BA%D0%B8",
            "https://commons.wikimedia.org/wiki/Category:Prohor_P%C4%8Dinjski_Monastery"
        ]
    },
    {
        "id": 171,
        "slug": "zapsko",
        "name": "Manastir Žapsko",
        "wiki_urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%96%D0%B0%D0%BF%D1%81%D0%BA%D0%BE",
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%A1%D0%B2%D0%B5%D1%82%D0%BE%D0%B3_%D0%A1%D1%82%D0%B5%D1%84%D0%B0%D0%BD%D0%B0_%D1%83_%D0%96%D0%B0%D0%BF%D1%81%D0%BA%D0%BE%D0%BC",
            "https://sr.wikipedia.org/wiki/%D0%96%D0%B0%D0%BF%D1%81%D0%BA%D0%BE"
        ]
    },
    {
        "id": 240,
        "slug": "dubnica-milesevska",
        "name": "Manastir Dubnica",
        "wiki_urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%94%D1%83%D0%B1%D0%BD%D0%B8%D1%86%D0%B0_(%D0%92%D1%80%D0%B0%D1%9 accidents)",
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%A1%D0%B2%D0%B5%D1%82%D0%B8%D1%85_%D0%B0%D0%BF%D0%BE%D1%81%D1%82%D0%BE%D0%BB%D0%B0_%D0%9F%D0%B5%D1%82%D1%80%D0%B0_%D0%B8_%D0%9F%D0%B0%D0%B2%D0%BB%D0%B0_%D1%83_%D0%94%D1%83%D0%B1%D0%BD%D0%B8%D1%86%D0%B8",
            "https://sr.wikipedia.org/wiki/%D0%94%D1%83%D0%B1%D0%BD%D0%B8%D1%86%D0%B0_(%D0%92%D1%80%D0%B0%D1%9A%D0%B5)"
        ]
    },
    {
        "id": 246,
        "slug": "kozji-dol",
        "name": "Manastir Kozji Dol",
        "wiki_urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%9A%D0%BE%D0%B7%D1%98%D0%B8_%D0%94%D0%BE%D0%BB",
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%9F%D1%80%D0%B5%D0%BE%D0%B1%D1%80%D0%B0%D0%B6%D0%B5%D1%9A%D0%B0_%D0%93%D0%BE%D1%81%D0%BF%D0%BE%D0%B4%D1%9A%D0%B5%D0%B3_%D1%83_%D0%9A%D0%BE%D0%B7%D1%98%D0%B5%D0%BC_%D0%94%D0%BE%D0%BB%D1%83",
            "https://sr.wikipedia.org/wiki/%D0%9A%D0%BE%D0%B7%D1%98%D0%B8_%D0%94%D0%BE%D0%BB"
        ]
    },
    {
        "id": 247,
        "slug": "lepcince",
        "name": "Manastir Lepčince",
        "wiki_urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%9B%D0%B5%D0%BF%D1%87%D0%B8%D0%BD%D1%86%D0%B5",
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%A1%D0%B2%D0%B5%D1%82%D0%BE%D0%B3_%D0%9F%D0%B0%D0%BD%D1%82%D0%B5%D0%BB%D0%B5%D1%98%D0%BC%D0%BE%D0%BD%D0%B0_%D1%83_%D0%9B%D0%B5%D0%BF%D1%87%D0%B8%D0%BD%D1%86%D1%83",
            "https://sr.wikipedia.org/wiki/%D0%9B%D0%B5%D0%BF%D1%87%D0%B8%D0%BD%D1%86%D0%B5"
        ]
    },
    {
        "id": 249,
        "slug": "simeon-stolpnik",
        "name": "Manastir Simeon Stolpnik",
        "wiki_urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%A1%D0%B2%D0%B5%D1%82%D0%BE%D0%B3_%D0%A1%D0%B8%D0%BC%D0%B5%D0%BE%D0%BD%D0%B0_%D0%A1%D1%82%D0%BE%D0%BB%D0%BF%D0%BD%D0%B8%D0%BA%D0%B0_%D1%83_%D0%A1%D0%BE%D0%B1%D0%B8%D0%BD%D0%B8",
            "https://sr.wikipedia.org/wiki/%D0%A1%D0%BE%D0%B1%D0%B8%D0%BD%D0%B0_(%D0%92%D1%80%D0%B0%D1%9A%D0%B5)"
        ]
    },
    {
        "id": 251,
        "slug": "mrtvica",
        "name": "Manastir Mrtvica",
        "wiki_urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%9C%D1%80%D1%82%D0%B2%D0%B8%D1%86%D0%B0",
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%A3%D1%81%D0%BF%D0%B5%D1%9A%D0%B0_%D0%9F%D1%80%D0%B5%D1%81%D0%B2%D0%B5%D1%82%D0%B5_%D0%91%D0%BE%D0%B3%D0%BE%D1%80%D0%BE%D0%B4%D0%B8%D1%86%D0%B5_%D1%83_%D0%9C%D1%80%D1%82%D0%B2%D0%B8%D1%86%D0%B8",
            "https://sr.wikipedia.org/wiki/%D0%9C%D1%80%D1%82%D0%B2%D0%B8%D1%86%D0%B0_(%D0%92%D0%BB%D0%B0%D0%B4%D0%B8%D1%87%D0%B8%D0%BD_%D0%A5%D0%B0%D0%BD)"
        ]
    },
    {
        "id": 252,
        "slug": "palja",
        "name": "Manastir Palja",
        "wiki_urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%9F%D0%B0%D1%99%D0%B0",
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%92%D0%B0%D0%B2%D0%B5%D0%B4%D0%B5%D1%9A%D0%B0_%D0%9F%D1%80%D0%B5%D1%81%D0%B2%D0%B5%D1%82%D0%B5_%D0%91%D0%BE%D0%B3%D0%BE%D1%80%D0%BE%D0%B4%D0%B8%D1%86%D0%B5_%D1%83_%D0%9F%D0%B0%D1%99%D0%B8",
            "https://sr.wikipedia.org/wiki/%D0%9F%D0%B0%D1%99%D0%B0"
        ]
    },
    {
        "id": 253,
        "slug": "sveti-nikola-vranje",
        "name": "Manastir Sveti Nikola",
        "wiki_urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%A1%D0%B2%D0%B5%D1%82%D0%BE%D0%B3_%D0%9D%D0%B8%D0%BA%D0%BE%D0%BB%D0%B5_%D1%83_%D0%92%D1%80%D0%B0%D1%9A%D1%83",
            "https://sr.wikipedia.org/wiki/%D0%A6%D1%80%D0%BA%D0%B2%D0%B0_%D0%A1%D0%B2%D0%B5%D1%82%D0%BE%D0%B3_%D0%9D%D0%B8%D0%BA%D0%BE%D0%BB%D0%B5_%D1%83_%D0%92%D1%80%D0%B0%D1%9A%D1%83"
        ]
    },
]

def fetch_html(url):
    req = urllib.request.Request(url, headers=HEADERS)
    try:
        with urllib.request.urlopen(req, context=ctx, timeout=8) as r:
            return r.read().decode('utf-8', errors='ignore')
    except Exception as e:
        return ""

def extract_images_from_html(html):
    imgs = []
    # Match upload.wikimedia.org image links
    matches = re.findall(r'//upload\.wikimedia\.org/wikipedia/commons/(?:thumb/)?([a-f0-9]/[a-f0-9]{2}/[^/\s"\'\?]+(?:\.jpg|\.jpeg|\.png))', html, re.IGNORECASE)
    for m in matches:
        # full resolution url
        full_url = f"https://upload.wikimedia.org/wikipedia/commons/{m}"
        # clean filename
        fname = urllib.parse.unquote(m.split('/')[-1])
        if not any(ign in fname.lower() for ign in ['icon', 'logo', 'flag', 'map', 'stub', 'location', 'portal', 'cross', 'edit', 'history', 'commons', 'px-']):
            imgs.append({'filename': fname, 'url': full_url})
    return imgs

parsed_results = {}

for m in monasteries:
    slug = m['slug']
    print(f"\n[{m['id']}] {m['name']} ({slug})")
    all_imgs = []
    seen = set()
    for u in m['wiki_urls']:
        html = fetch_html(u)
        imgs = extract_images_from_html(html)
        for im in imgs:
            if im['url'] not in seen:
                seen.add(im['url'])
                all_imgs.append(im)
                print(f"  -> Found: {im['filename']} ({im['url']})")
    parsed_results[slug] = all_imgs

with open(r'd:\projekti\ManastiriSrbije\backend\vranjska_parsed_html_imgs.json', 'w', encoding='utf-8') as f:
    json.dump(parsed_results, f, ensure_ascii=False, indent=2)

print("\nParsing complete! Saved to vranjska_parsed_html_imgs.json")
