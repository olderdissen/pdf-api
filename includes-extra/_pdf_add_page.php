<?
################################################################################
# _pdf_add_page ( array $pdf , string $parent , string $resources , string $mediabox , string $contents ) : string
################################################################################

function _pdf_add_page(& $pdf, $parent, $resources, $mediabox, $contents)
	{
	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_pages: invalid parent: " . $parent);

	# check resources for beeing dictionary or pointer to such

	if(sscanf($mediabox, "[%f %f %f %f]", $x, $y, $w, $h) != 4)
		die("_pdf_add_pages: invalid mediabox:" . $mediabox);

	# check contents for beeing pointer or array of such

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Page",
			"/Parent" => $parent,
			"/Resources" => $resources,
			"/MediaBox" => $mediabox,
			"/Contents" => $contents
			)
		);

	# apply group
	if($pdf["minor"] > 3)
		$pdf["objects"][$this_id]["dictionary"]["/Group"] = "<< /Type /Group /S /Transparency /CS /DeviceRGB >>";

	# apply info about this object to parent
	if($pdf["objects"][$parent_id]["dictionary"]["/Type"] == "/Pages")
		{
		# apply page to kids
		if(isset($pdf["objects"][$parent_id]["dictionary"]["/Kids"]))
			$data = $pdf["objects"][$parent_id]["dictionary"]["/Kids"];
		else
			$data = "[]";

		$data = substr($data, 1);
		list($kids, $data) = _pdf_parse_array($data); # pdf-api-parse.php
		$data = substr($data, 1);

		$kids[] = sprintf("%d 0 R", $this_id);
	
		$pdf["objects"][$parent_id]["dictionary"]["/Kids"] = sprintf("[%s]", _pdf_glue_array($kids)); # pdf-api-glue.php

		# increase or decrease counter, depending on plus or minus sign
		if(isset($pdf["objects"][$parent_id]["dictionary"]["/Count"]))
			$count = $pdf["objects"][$parent_id]["dictionary"]["/Count"];
		else
			$count = 0;

		$pdf["objects"][$parent_id]["dictionary"]["/Count"] = ($count < 0 ? $count - 1 : $count + 1);
		}
	else
		die("_pdf_add_pages: invalid type of parent.");

	return(sprintf("%d 0 R", $this_id));
	}
?>
