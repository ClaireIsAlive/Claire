<?php
$phrase = 'invalid';
if (isset($_GET['key'])) $phrase = substr(md5($_GET['key']),0,4);
$im = @imagecreate(37, 18);
imagesavealpha($im, true);
imagefill($im, 255, 255, imagecolorallocatealpha($im, 0,0,0,127) );
imagestring($im, 4, 4, 0, $phrase, imagecolorallocate($im, 255, 255, 255)); 
header('Content-type: image/png'); 
imagepng($im);
imagedestroy($im);