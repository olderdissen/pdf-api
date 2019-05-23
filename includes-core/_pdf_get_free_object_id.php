<?
################################################################################
# _pdf_get_free_object_id ( array $pdf ) : int
################################################################################

function _pdf_get_free_object_id(& $pdf, $id = 1)
	{
	if(isset($pdf["objects"]))
		while(isset($pdf["objects"][$id]))
			$id ++;

	return($id);
	}
?>
