import urllib.request
import urllib.parse
import json
import os
import sys
from PIL import Image

sys.stdout.reconfigure(encoding='utf-8')

headers = {'User-Agent': 'ManastiriSrbijeAcademic/1.0 (Faculty research; student@example.com)'}

def fetch_wiki_article_media(article_title):
    url = f"https://sr.wikipedia.org/api/rest_v1/page/media-list/{urllib.parse.quote(article_title)}"
    req = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(req, timeout=15) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            items = data.get('items', [])
            print(f"Article '{article_title}': found {len(items)} media items")
            for it in items:
                title = it.get('title')
                srcset = it.get('srcset', [])
                best_src = srcset[-1]['src'] if srcset else None
                if best_src and 'http' not in best_src:
                    best_src = 'https:' + best_src
                print(f"  - Title: {title} | URL: {best_src}")
            return items
    except Exception as e:
        print(f"Error fetching {article_title}: {e}")
        return []

fetch_wiki_article_media("Манастир_Студеница")
fetch_wiki_article_media("Манастир_Жича")
fetch_wiki_article_media("Манастир_Враћевшница")
