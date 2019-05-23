<?
################################################################################
# _pdf_add_annotation ( array $pdf , string $parent , string $rect , string $uri ) : string
################################################################################

function _pdf_add_annotation(& $pdf, $parent, $rect, $type, $optlist)
	{
	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_annotation: invalid parent: " . $parent);

	if(sscanf($rect, "[%f %f %f %f]", $x, $ly, $w, $h) != 4)
		die("_pdf_add_annotation: invalid rect:" . $rect);

	if(in_array($type, array("attachment", "link", "text", "widget")) === false)
		die("_pdf_add_annotation: invalid type: " . $type);

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	if($type == "attachment")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Annot",
				"/Rect" => $rect
				)
			);
		}

	if($type == "link")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Annot",
				"/Rect" => $rect
				)
			);
		}

	if($type == "text")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Annot",
				"/Rect" => $rect
				)
			);
		}

	if($type == "widget")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Annot",
				"/Subtype" => "/Widget",
				"/Rect" => $rect,
				"/FT" => "/Tx",
				"/V" => "(edit)",
				"/T" => "(javascript_name_a)",
				"/AP" => sprintf("<< /N %s >>", $optlist["form"])
				)
			);
		}

	# apply ...
	if(isset($pdf["objects"][$parent_id]["dictionary"]["/Annots"]))
		$data = $pdf["objects"][$parent_id]["dictionary"]["/Annots"];
	else
		$data = "[]";

	$data = substr($data, 1);
	list($annots, $data) = _pdf_parse_array($data); # pdf-api-parse.php
	$data = substr($data, 1);

	$annots[] = sprintf("%d 0 R", $this_id);

	$pdf["objects"][$parent_id]["dictionary"]["/Annots"] = sprintf("[%s]", _pdf_glue_array($annots)); # pdf-api-glue.php

	return(sprintf("%d 0 R", $this_id));
	}

?>
