import urllib.request
import xml.etree.ElementTree as ET

headers = {'User-Agent': 'Mozilla/5.0'}

def get_sitemap(url):
    req = urllib.request.Request(url, headers=headers)
    with urllib.request.urlopen(req, timeout=10) as resp:
        return resp.read()

try:
    content = get_sitemap("https://manastiri.rs/sitemap.xml")
    root = ET.fromstring(content)
    # Check if index or urlset
    namespaces = {'ns': 'http://www.sitemaps.org/schemas/sitemap/0.9'}
    sitemaps = [elem.text for elem in root.findall('.//ns:loc', namespaces)]
    print(f"Found {len(sitemaps)} locs in sitemap.xml:")
    for sm in sitemaps:
        print(" ", sm)
        if 'post-sitemap' in sm or 'page-sitemap' in sm or 'manastir' in sm:
            sub = get_sitemap(sm)
            sub_root = ET.fromstring(sub)
            sub_urls = [elem.text for elem in sub_root.findall('.//ns:loc', namespaces)]
            print(f"   --> Found {len(sub_urls)} URLs in {sm}")
            # print sample
            for u in sub_urls:
                if any(m in u for m in ['grliste', 'krepicevac', 'lapusnja', 'lozica', 'vratna', 'suvodol', 'lelic', 'bogovadja', 'dokmir', 'grabovac', 'ribnica', 'pluzac', 'jovanja']):
                    print("      MATCH:", u)
except Exception as e:
    print("Error:", e)
