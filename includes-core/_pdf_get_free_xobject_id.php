<?
################################################################################
# _pdf_get_free_xobject_id ( array $pdf ) : int
################################################################################

function _pdf_get_free_xobject_id(& $pdf, $id = 0)
	{
	if(isset($pdf["resources"]["/XObject"]))
		while(isset($pdf["resources"]["/XObject"][$id]))
			$id ++;

	return($id);
	}
?>
