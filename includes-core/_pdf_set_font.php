<?
################################################################################
# _pdf_set_font ( array $pdf , string $font , int $size ) void
################################################################################

function _pdf_set_font(& $pdf, $font, $size)
	{
	$pdf["stream"][] = sprintf("%s %d Tf", $font, $size);
	}
?>
