<?
################################################################################
# _pdf_add_page ( array $pdf ) : string
################################################################################

function _pdf_add_catalog(& $pdf)
	{
	if(isset($pdf["objects"][0]["dictionary"]["/Root"]))
		die("_pdf_add_catalog: catalog already exist.");

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Catalog",
			"/PageLayout" => "/SinglePage",
			"/PageMode" => "/UseOutlines"
			)
		);

	# apply location of catalog
	$pdf["objects"][0]["dictionary"]["/Root"] = sprintf("%d 0 R", $this_id);

	return(sprintf("%d 0 R", $this_id));
	}
?>
