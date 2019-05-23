<?
################################################################################
# _pdf_begin_page ( array $pdf , int $width , int $height, string $parent ) : void
################################################################################

function _pdf_begin_page(& $pdf, $width, $height)
	{
	$pdf["width"] = $width;
	$pdf["height"] = $height;
	$pdf["stream"] = array();
	}
?>
