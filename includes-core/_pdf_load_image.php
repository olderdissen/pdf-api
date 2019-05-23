<?
################################################################################
# _pdf_load_image ( array $pdf , string $filename ) : string
################################################################################

function _pdf_load_image(& $pdf, $filename)
	{
	$a = _pdf_add_image($pdf, $filename); # pdf-api-extra.php

	$b = _pdf_get_free_xobject_id($pdf);

	$pdf["resources"]["/XObject"][$b] = sprintf("%d 0 R", $a);

	return("/X" . $b);
	}
?>
