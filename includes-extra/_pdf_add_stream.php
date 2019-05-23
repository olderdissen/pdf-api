<?
################################################################################
# _pdf_add_stream ( array $pdf , string $stream , array $optlist ) : string
################################################################################

function _pdf_add_stream(& $pdf, $stream, $optlist = array())
	{
	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Length" => strlen($stream)
			),
		"stream" => $stream
		);

	# apply additional settings to created object
	foreach($optlist as $key => $value)
		$pdf["objects"][$this_id]["dictionary"][$key] = $value;

	return(sprintf("%d 0 R", $this_id));
	}
?>
