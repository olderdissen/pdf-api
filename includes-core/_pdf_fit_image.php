<?
################################################################################
# _pdf_fit_image ( array $pdf , string $image , int $x , $y , array $optlist) : int
################################################################################

function _pdf_fit_image(& $pdf, $image, $x, $y, $optlist)
	{
	pdf_save($pdf);
	$pdf["stream"][] = sprintf("%d 0 0 %d %d %d cm", $optlist["width"] * $optlist["scale"], $optlist["height"] * $optlist["scale"], $x, $y);
	$pdf["stream"][] = sprintf("%s Do", $image); # Invoke named XObject
	pdf_restore($pdf);
	}
?>
