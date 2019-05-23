<?
################################################################################
# _pdf_load_font ( array $pdf , string $fontname ) : string
################################################################################

function _pdf_load_font(& $pdf, $fontname, $encoding = "builtin")
	{
	$a = _pdf_add_font($pdf, $fontname, $encoding); # pdf-api-extra.php

	$b = _pdf_get_free_font_id($pdf);

	$pdf["resources"]["/Font"][$b] = sprintf("%d 0 R", $a);

	return("/F" . $b);
	}
?>
