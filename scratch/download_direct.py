import os, sys, json, urllib.request, urllib.parse
from PIL import Image

Image.MAX_IMAGE_PIXELS = None
USER_AGENT = "ManastiriSrbijeResearch/1.0 (contact: ana.vukajlovic@gmail.com)"

def download_file(file_title, dest_path, width=1200):
    url = f"https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(file_title)}&prop=imageinfo&iiprop=url&iiurlwidth={width}&format=json"
    req = urllib.request.Request(url, headers={"User-Agent": USER_AGENT})
    with urllib.request.urlopen(req, timeout=15) as resp:
        data = json.loads(resp.read().decode("utf-8"))
    
    pages = data.get("query", {}).get("pages", {})
    img_url = None
    for pid, pdata in pages.items():
        if "imageinfo" in pdata and len(pdata["imageinfo"]) > 0:
            info = pdata["imageinfo"][0]
            img_url = info.get("thumburl") or info.get("url")
            break
    
    if not img_url:
        print(f"FAILED to find URL for {file_title}")
        return False
    
    req_img = urllib.request.Request(img_url, headers={"User-Agent": USER_AGENT})
    tmp_path = dest_path + ".tmp"
    with urllib.request.urlopen(req_img, timeout=30) as resp, open(tmp_path, "wb") as f:
        f.write(resp.read())
    
    im = Image.open(tmp_path)
    im = im.convert("RGB")
    if max(im.size) > 1600:
        im.thumbnail((1600, 1600), Image.Resampling.LANCZOS)
    im.save(dest_path, "JPEG", quality=88, optimize=True)
    if os.path.exists(tmp_path):
        os.remove(tmp_path)
    print(f"SAVED: {dest_path} ({os.path.getsize(dest_path)//1024} KB)")
    return True

if __name__ == "__main__":
    if len(sys.argv) >= 3:
        download_file(sys.argv[1], sys.argv[2])
