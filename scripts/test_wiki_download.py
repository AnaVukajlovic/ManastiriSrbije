import urllib.request
import urllib.parse
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

test_url = 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Manastir_Rajinovac_1.jpg/1280px-Manastir_Rajinovac_1.jpg'

headers = {
    'User-Agent': 'ManastiriSrbijeBot/1.0 (https://manastirisrbije.org; info@manastirisrbije.org) Python-urllib'
}

req = urllib.request.Request(test_url, headers=headers)
try:
    with urllib.request.urlopen(req, timeout=10) as resp:
        data = resp.read()
        print(f"Success! Downloaded {len(data)} bytes")
except Exception as e:
    print(f"Error: {e}")
