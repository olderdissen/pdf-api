<?
################################################################################
# _pdf_add_font_file ( array $pdf , string $filename ) : string
################################################################################

function _pdf_add_font_file(& $pdf, $filename)
	{
	if(file_exists($filename) === false)
		die("_pdf_add_font_truetype: invalid file: " . $filename);

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Length" => filesize($filename),
			"/Length1" => filesize($filename) # untouched during filter
			),
		"stream" => file_get_contents($filename)
		);

	return(sprintf("%d 0 R", $this_id));
	}
?>
