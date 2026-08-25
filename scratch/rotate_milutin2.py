from PIL import Image

im = Image.open('public/images/ktitors/kralj-milutin-2.jpg')
# looking at the image, the church domes are pointing to the left, so rotating 90 degrees counter-clockwise (270 clockwise) will make it upright
im_rotated = im.rotate(90, expand=True)
im_rotated.save('public/images/ktitors/kralj-milutin-2.jpg', quality=95)
print("Rotated kralj-milutin-2.jpg successfully")
