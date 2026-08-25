from PIL import Image
p = r"d:\projekti\ManastiriSrbije\backend\public\images\monasteries\drenova_gal_1.jpg"
im = Image.open(p)
# rotate 90 degrees clockwise (270 counterclockwise)
im_rot = im.rotate(270, expand=True)
im_rot.save(p, quality=95)
print("Rotated drenova_gal_1.jpg")
