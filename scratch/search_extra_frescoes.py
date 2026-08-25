import urllib.request
import json
import time

headers = {
    'User-Agent': 'ManastiriSrbijeBot/1.0 (https://manastirisrbije.rs; contact@manastirisrbije.rs)'
}

queries = [
    ("carica-jelena-2.jpg", "Dusan_Jelena_Uros_Decani OR Carica_Jelena OR Fresco_Jelena_Decani"),
    ("jelena-anzujska-2.jpg", "Jelena_Anzujska OR Queen_Helen_of_Anjou OR Sopocani_ktitors"),
    ("knez-lazar-2.jpg", "Knez_Lazar_Ravanica OR Lazar_Hrebeljanovic_fresco"),
    ("simonida-3.jpg", "Simonida_Studenica OR Milutin_Simonida_Nagoricino OR Simonida_fresco"),
    ("stefan-uros-i-2.jpg", "Uros_I_Gracanica OR Uros_I_Decani OR Stefan_Uros_I_fresco"),
    ("car-dusan-4.jpg", "Stefan_Dusan_Decani OR Dusan_fresco_Decani")
]

for filename, q in queries:
    url = f"https://commons.wikimedia.org/w/api.php?action=query&list=search&srsearch={urllib.parse.quote(q)}&srnamespace=6&format=json"
    req = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(req) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            results = data.get('query', {}).get('search', [])
            print(f"=== Results for {q} ===")
            for r in results[:5]:
                print(f"  Title: {r['title']}")
    except Exception as e:
        print(f"Error {q}: {e}")
    time.sleep(1)
