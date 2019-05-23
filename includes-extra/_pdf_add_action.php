<?
################################################################################
# _pdf_add_action ( array $pdf ) : string
################################################################################

function _pdf_add_action(& $pdf, $type, $optlist)
	{
	if(in_array($type, array("goto", "gotor", "launch", "uri")) === false)
		die("_pdf_add_action: invalid type: " . $type);

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	if($type == "goto")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Action",
				"/S" => "/GoTo",
				"/D" => sprintf("[%s /Fit]", $optlist["dest"])
				)
			);
		}

	if($type == "gotor")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Action",
				"/S" => "/GoToR",
				"/F" => sprintf("(%s)", $optlist["filename"]),
				"/D" => sprintf("[%s /Fit]", $optlist["dest"])
				)
			);
		}

	if($type == "launch")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Action",
				"/S" => "/Launch",
				"/F" => sprintf("(%s)", $optlist["filename"])
				)
			);
		}

	if($type == "uri")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Action",
				"/S" => "/URI",
				"/URI" => sprintf("(%s)", $optlist["uri"])
				)
			);
		}

	return(sprintf("%d 0 R", $this_id));
	}
?>
