<?
################################################################################
# _pdf_add_outline ( array $pdf , string $parent , string $open , string $title ) : string
################################################################################

function _pdf_add_outline(& $pdf, $parent, $open, $title)
	{
	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_outline: invalid parent: " . $parent);

	if(sscanf($open, "%d %d R", $open_id, $open_version) != 2)
		die("_pdf_add_outline: invalid open: " . $open);

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Title" => sprintf("(%s)", _pdf_glue_string($title)), # pdf-api-glue.php
			"/Parent" => $parent,
			"/Dest" => sprintf("[%s /Fit]", $open)
			)
		);

	# apply info about this object to parent
	if($pdf["objects"][$parent_id]["dictionary"]["/Type"] == "/Outlines")
		{
		if(isset($pdf["objects"][$parent_id]["dictionary"]["/First"]) === false)
			$pdf["objects"][$parent_id]["dictionary"]["/First"] = sprintf("%d 0 R", $this_id);

		if(isset($pdf["objects"][$parent_id]["dictionary"]["/Last"]))
			{
			$last = $pdf["objects"][$parent_id]["dictionary"]["/Last"];

			if(sscanf($last, "%d %d R", $last_id, $last_version) == 2)
				$pdf["objects"][$last_id]["dictionary"]["/Next"] = sprintf("%d 0 R", $this_id);

			$pdf["objects"][$this_id]["dictionary"]["/Prev"] = $last;
			}

		$pdf["objects"][$parent_id]["dictionary"]["/Last"] = sprintf("%d 0 R", $this_id);

		# increase or decrease counter, depending on plus or minus sign
		if(isset($pdf["objects"][$parent_id]["dictionary"]["/Count"]))
			$count = $pdf["objects"][$parent_id]["dictionary"]["/Count"];
		else
			$count = 0;

		$pdf["objects"][$parent_id]["dictionary"]["/Count"] = ($count < 0 ? $count - 1 : $count + 1);
		}
	else
		die("_pdf_add_outline: invalid type of parent.");

	return(sprintf("%d 0 R", $this_id));
	}
?>
