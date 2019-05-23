<?
################################################################################
# _pdf_add_font_definiton ( array $pdf , ... ) : string
# pending
################################################################################

function _pdf_add_font_definition(& $pdf)
	{
	$e = _pdf_add_font_encoding($pdf);

	$s = _pdf_add_page_stream($pdf, "");

	$d = _pdf_add_font_descriptor($pdf, "test", "");

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Font",
			"/Subtype" => "/Type3",
			"/FontBBox" => "[0 0 1000 1000]",
			"/FontMatrix" => "[1 0 0 -1 0 0]",
			"/CharProcs" => sprintf("<< /C %s /B %s /A %s >>", $s, $s, $s),
			"/Encoding" => $e,
			"/FirstChar" => 65,
			"/LastChar" => 67,
			"/Widths" => "[8 8 8]",
			"/FontDescriptor" => $d
			)
		);

	return(sprintf("%d 0 R", $this_id));
	}
?>
