<?
################################################################################
# _pdf_end_page ( array $pdf ) : string
################################################################################

function _pdf_end_page(& $pdf)
	{
	$resources = array("/ProcSet" => array("/PDF", "/Text"));

	################################################################################

	if(isset($pdf["resources"]["/ProcSet"]))
		foreach($pdf["resources"]["/ProcSet"] as $object)
			$resources["/ProcSet"][] = $object;

	if(isset($pdf["resources"]["/Font"]))
		foreach($pdf["resources"]["/Font"] as $id => $object)
			$resources["/Font"]["/F" . $id] = $object;

	if(isset($pdf["resources"]["/XObject"]))
		foreach($pdf["resources"]["/XObject"] as $id => $object)
			$resources["/XObject"]["/X" . $id] = $object;

	################################################################################

	if(isset($resources["/ProcSet"]))
		$resources["/ProcSet"] = sprintf("[%s]", _pdf_glue_array($resources["/ProcSet"])); # pdf-api-glue.php

	if(isset($resources["/Font"]))
		$resources["/Font"] = sprintf("<< %s >>", _pdf_glue_dictionary($resources["/Font"])); # pdf-api-glue.php

	if(isset($resources["/XObject"]))
		$resources["/XObject"] = sprintf("<< %s >>", _pdf_glue_dictionary($resources["/XObject"])); # pdf-api-glue.php

	################################################################################

	$parent = $pdf["pages"];
	$mediabox = sprintf("[%d %d %d %d]", 0, 0 , $pdf["width"], $pdf["height"]);
	$resources = sprintf("<< %s >>", _pdf_glue_dictionary($resources)); # pdf-api-glue.php
	$contents = implode(" ", $pdf["stream"]);

	$contents = _pdf_add_stream($pdf, $contents); # pdf-api-extra.php

	$retval = _pdf_add_page($pdf, $parent, $resources, $mediabox, $contents); # pdf-api-extra.php

	return($retval);
	}
?>
