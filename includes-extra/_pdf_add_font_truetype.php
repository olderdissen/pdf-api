<?
################################################################################
# _pdf_add_font_truetype ( array $pdf , string $filename , string $encoding ) : string
################################################################################

function _pdf_add_font_truetype(& $pdf, $filename, $encoding = "builtin")
	{
	if(in_array($encoding, array("builtin", "winansi", "macroman", "macexpert")) === false)
		die("_pdf_add_font_truetype: invalid encoding: " . $encoding);

	$fontname = basename($filename, ".ttf");

	$f = _pdf_add_font_file($pdf, $filename);

	$d = _pdf_add_font_descriptor($pdf, $fontname, $f);

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Font",
			"/Subtype" => "/TrueType",
			"/BaseFont" => "/" . $fontname,
			"/FirstChar" => 32,
			"/LastChar" => 255,
			"/Widths" => "[]",
			"/FontDescriptor" => $d
			)
		);

	# apply widths
	$widths = array();

	foreach(range(0x20, 0xFF) as $char)
		$widths[chr($char)] = (($image_ttf_bbox = imagettfbbox(720, 0, $filename, chr($char))) ? $image_ttf_bbox[2] : 1000);

	$pdf["objects"][$this_id]["dictionary"]["/Widths"] = sprintf("[%s]", _pdf_glue_array($widths)); # pdf-api-glue.php

	# valid encodings
	$encodings = array("winansi" => "/WinAnsiEncoding", "macroman" => "/MacRomanEncoding", "macexpert" => "/MacExpertEncoding");

	# apply encoding
	if($encoding != "builtin")
		if(isset($encodings[$encoding]))
			$pdf["objects"][$this_id]["dictionary"]["/Encoding"] = $encodings[$encoding];
		else
			$pdf["objects"][$this_id]["dictionary"]["/Encoding"] = $encoding;

	return(sprintf("%d 0 R", $this_id));
	}
?>
