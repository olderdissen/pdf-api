<?
################################################################################
# _pdf_end_document ( array $pdf ) : void
################################################################################

function _pdf_end_document(& $pdf)
	{
	$pdf["stream"] = _pdf_glue_document($pdf["objects"]); # pdf-api-glue.php

	if($pdf["filename"])
		file_put_contents($pdf["filename"], $pdf["stream"]);
	}
?>
