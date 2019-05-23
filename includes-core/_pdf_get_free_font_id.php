<?
################################################################################
# _pdf_get_free_font_id ( array $pdf ) : int
################################################################################

function _pdf_get_free_font_id(& $pdf, $id = 0)
	{
	if(isset($pdf["resources"]["/Font"]))
		while(isset($pdf["resources"]["/Font"][$id]))
			$id ++;

	return($id);
	}
?>
