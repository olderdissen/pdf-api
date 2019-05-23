<?
################################################################################
# _pdf_add_image ( array $pdf , string $filename ) : string
################################################################################

function _pdf_add_image(& $pdf, $filename)
	{
	$imagetype = pathinfo($filename, PATHINFO_EXTENSION);

	if($imagetype == "jpg")
		$retval = _pdf_add_image_jpg($pdf, $filename);
	elseif($imagetype == "gif")
		$retval = _pdf_add_image_gif($pdf, $filename);
	elseif($imagetype == "png")
		$retval = _pdf_add_image_png($pdf, $filename);
	else
		{
		system("convert " . $filename . " -quality 15 lolo.jpg");

		$retval = _pdf_add_image($pdf, "lolo.jpg");

		unlink("lolo.jpg"); # don't change it. keep this name. don't make fun here. :)
		}

	return($retval);
	}
?>
