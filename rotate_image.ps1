Add-Type -AssemblyName System.Drawing
$filePath = "d:\projekti\ManastiriSrbije\backend\public\images\monasteries\oraovica-niska.jpg"
$tempPath = "d:\projekti\ManastiriSrbije\backend\public\images\monasteries\oraovica-niska-temp.jpg"

$image = [System.Drawing.Image]::FromFile($filePath)
$image.RotateFlip([System.Drawing.RotateFlipType]::Rotate90FlipNone)
$image.Save($tempPath, [System.Drawing.Imaging.ImageFormat]::Jpeg)
$image.Dispose()

Remove-Item -Force $filePath
Move-Item -Force $tempPath $filePath
Write-Host "Image successfully rotated 90 degrees clockwise."
