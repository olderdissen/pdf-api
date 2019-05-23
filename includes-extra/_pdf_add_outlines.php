<?
################################################################################
# _pdf_add_outlines ( array $pdf , string $parent ) : string
################################################################################

function _pdf_add_outlines(& $pdf, $parent)
	{
	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_outlines: invalid parent:" . $parent);

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Outlines",
			"/Count" => 0
			)
		);

	# apply info about this object to parent
	if($pdf["objects"][$parent_id]["dictionary"]["/Type"] == "/Outlines")
		$pdf["objects"][$this_id]["dictionary"]["/Parent"] = $parent;
	elseif($pdf["objects"][$parent_id]["dictionary"]["/Type"] != "/Catalog")
		die("_pdf_add_outlines: invalid type of parent.");

	# apply location of outlines
	$pdf["objects"][$parent_id]["dictionary"]["/Outlines"] = sprintf("%d 0 R", $this_id);

	return(sprintf("%d 0 R", $this_id));
	}
?>
