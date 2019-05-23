<?
################################################################################
# _pdf_add_info ( array $pdf , array $optlist ) : string
################################################################################

function _pdf_add_info(& $pdf, $optlist)
	{
	if(isset($pdf["objects"][0]["dictionary"]["/Info"]))
		die("_pdf_add_info: info already exist.");

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/CreationDate" => sprintf("(D:%sZ)", date("YmdHis")),
			"/ModDate" => sprintf("(D:%sZ)", date("YmdHis")),
			"/Producer" => sprintf("(%s)", basename(__FILE__))
			)
		);

	# apply additional settings to created object
	foreach($optlist as $key => $value)
		$pdf["objects"][$this_id]["dictionary"][$key] = $value;

	# apply location of info
	$pdf["objects"][0]["dictionary"]["/Info"] = sprintf("%d 0 R", $this_id);

	return(sprintf("%d 0 R", $this_id));
	}
?>
