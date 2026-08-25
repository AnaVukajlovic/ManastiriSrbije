from PIL import Image

im = Image.open('public/images/ktitors/kralj-milutin-2.jpg')
im_rotated = im.rotate(180, expand=True)
im_rotated.save('public/images/ktitors/kralj-milutin-2.jpg', quality=95)
print("Rotated 180 successfully")
