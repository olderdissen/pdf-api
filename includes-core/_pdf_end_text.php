<?
################################################################################
# _pdf_end_text ( array $pdf ) : array
################################################################################

function _pdf_end_text(& $pdf)
	{
	$pdf["stream"][] = "ET";
	}
?>
