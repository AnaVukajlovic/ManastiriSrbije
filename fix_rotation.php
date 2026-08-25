<?php
$img = imagecreatefromjpeg(__DIR__ . '/public/images/monasteries/oraovica-niska.jpg');
$rotated = imagerotate($img, 270, 0);
imagejpeg($rotated, __DIR__ . '/public/images/monasteries/oraovica-niska.jpg', 95);
echo "Rotated oraovica-niska.jpg successfully\n";
