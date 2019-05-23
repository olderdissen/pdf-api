<?
################################################################################
# _pdf_add_acroform ( array $pdf , string $parent ) : string
################################################################################

function _pdf_add_acroform(& $pdf, $parent)
	{
	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_acroform: invalid parent: " . $parent);

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Fields" => "[]"
			)
		);

	# apply location of acroform
	$pdf["objects"][$parent_id]["dictionary"]["/AcroForm"] = sprintf("%d 0 R", $this_id);

	return(sprintf("%d 0 R", $this_id));
	}
?>
