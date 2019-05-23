<?
################################################################################
# _pdf_begin_document ( array $pdf , string $filename) : void
################################################################################

function _pdf_begin_document(& $pdf, $filename)
	{
	$catalog = _pdf_add_catalog($pdf); # pdf-api-extra.php

	$outlines = _pdf_add_outlines($pdf, $catalog); # pdf-api-extra.php

	$pages = _pdf_add_pages($pdf, $catalog); # pdf-api-extra.php

	$pdf["catalog"] = $catalog;
	$pdf["outlines"] = $outlines;
	$pdf["pages"] = $pages;
	$pdf["filename"] = $filename;
	}
?>
