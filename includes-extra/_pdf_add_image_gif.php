<?
################################################################################
# _pdf_add_image_gif ( array $pdf , string $filename ) : string
################################################################################

function _pdf_add_image_gif(& $pdf, $filename)
	{
	if(function_exists("imagecreatefromgif") === false)
		die("_pdf_add_image_jpg: no gif support.");

	if(($image_create_from_gif = imagecreatefromgif($filename)) === false)
		die("_pdf_add_image_jpg: invalid file: " . $filename);

	imageinterlace($image_create_from_gif, 0);

	if(function_exists("imagepng") === false)
		die("_pdf_add_image_jpg: no png support.");

	if(($temp_filename = tempnam(".", "gif")) === false)
		die("_pdf_add_image_jpg: unable to create a temporary file.");

	if(imagepng($image_create_from_gif, $temp_filename) === false)
		die("_pdf_add_image_jpg: error while saving to temporary file.");

	imagedestroy($image_create_from_gif);

	$retval = _pdf_add_image_png($pdf, $temp_filename);

	unlink($temp_filename);

	return($retval);
	}
?>
