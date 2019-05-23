<?
################################################################################
# _pdf_add_pages ( array $pdf , string $parent ) : string
################################################################################

function _pdf_add_pages(& $pdf, $parent)
	{
	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_page: invalid parent: " . $parent);

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Pages",
			"/Kids" => "[]",
			"/Count" => 0
			)
		);

	# apply info about this object to parent
	if($pdf["objects"][$parent_id]["dictionary"]["/Type"] == "/Pages")
		{
		# apply pages to kids
		$pdf["objects"][$this_id]["dictionary"]["/Parent"] = $parent;

		if(isset($pdf["objects"][$parent_id]["dictionary"]["/Kids"]))
			$data = $pdf["objects"][$parent_id]["dictionary"]["/Kids"];
		else
			$data = "[]";

		$data = substr($data, 1);
		list($kids, $data) = _pdf_parse_array($data); # pdf-api-parse.php
		$data = substr($data, 1);

		$kids[] = sprintf("%d 0 R", $this_id);

		$pdf["objects"][$parent_id]["dictionary"]["/Kids"] = sprintf("[%s]", _pdf_glue_array($kids));

		# increase or decrease counter, depending on plus or minus sign
		if(isset($pdf["objects"][$parent_id]["dictionary"]["/Count"]))
			$count = $pdf["objects"][$parent_id]["dictionary"]["/Count"];
		else
			$count = 0;

		$pdf["objects"][$parent_id]["dictionary"]["/Count"] = ($count < 0 ? $count - 1 : $count + 1);
		}
	elseif($pdf["objects"][$parent_id]["dictionary"]["/Type"] == "/Catalog")
		$pdf["objects"][$parent_id]["dictionary"]["/Pages"] = sprintf("%d 0 R", $this_id);
	else
		die("_pdf_add_pages: invalid type of parent.");

	return(sprintf("%d 0 R", $this_id));
	}
?>
