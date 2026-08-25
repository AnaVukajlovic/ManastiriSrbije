from PIL import Image
im = Image.open("public/images/ktitors/_2757791.gif")
im.convert("RGB").save("public/images/ktitors/_2757791_preview.jpg")
print("Converted gif to jpg preview")
