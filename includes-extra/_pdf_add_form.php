<?
################################################################################
# _pdf_add_form ( array $pdf , string $bbox , string $resources , string $stream ) : string
################################################################################

function _pdf_add_form(& $pdf, $resources, $bbox, $stream)
	{
	# check resources for beeing dictionary or pointer to such

	if(sscanf($bbox, "[%f %f %f %f]", $x, $ly, $w, $h) != 4)
		die("_pdf_add_form: invalid bbox:" . $bbox);

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/XObject",
			"/Subtype" => "/Form",
			"/FormType" => 1,
			"/Resources" => $resources,
			"/BBox" => $bbox,
			"/Length" => strlen($stream)
			),
		"stream" => $stream
		);

	$id = _pdf_get_free_xobject_id($pdf); # pdf-api-lib.php

	$pdf["resources"]["/XObject"][$id] = sprintf("%d 0 R", $this_id);

	return(sprintf("%d 0 R", $this_id));
	}
?>
