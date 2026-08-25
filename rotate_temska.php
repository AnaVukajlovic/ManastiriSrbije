<?php
$file = 'public/images/monasteries/temska_gal_2.jpg';
$img = imagecreatefromjpeg($file);
$rot = imagerotate($img, 270, 0);
imagejpeg($rot, $file, 95);
imagedestroy($img);
imagedestroy($rot);
echo "Rotated successfully\n";
