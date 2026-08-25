Add-Type -AssemblyName System.Drawing
$filePath = "d:\projekti\ManastiriSrbije\backend\public\images\monasteries\temska_gal_2.jpg"
$img = [System.Drawing.Image]::FromFile($filePath)
$img.RotateFlip([System.Drawing.RotateFlipType]::Rotate90FlipNone)
$tmpPath = "d:\projekti\ManastiriSrbije\backend\public\images\monasteries\temska_gal_2_rot.jpg"
$img.Save($tmpPath, [System.Drawing.Imaging.ImageFormat]::Jpeg)
$img.Dispose()
Move-Item -Force $tmpPath $filePath
Write-Host "Rotated temska_gal_2.jpg successfully"
