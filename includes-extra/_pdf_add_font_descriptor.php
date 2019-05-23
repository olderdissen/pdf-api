<?
################################################################################
# _pdf_add_font_descriptor ( array $pdf , string $fontname , string $fontfile ) : string
################################################################################

function _pdf_add_font_descriptor(& $pdf, $fontname, $fontfile) # make fontfile an optlist
	{
	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/FontDescriptor",
			"/FontName" => "/" . $fontname,
			"/Flags" => FONTDESCRIPTOR_FLAG_SERIF | FONTDESCRIPTOR_FLAG_SCRIPT,
			"/FontBBox" => "[0 -240 1440 1000]",
			"/ItalicAngle" => 0,
			"/Ascent" => 720,
			"/Descent" => 0 - 250,
			"/CapHeight" => 720,
			"/StemV" => 90
			)
		);

	# apply location of fontfile
	if($fontfile)
		$pdf["objects"][$this_id]["dictionary"]["/FontFile2"] = $fontfile;

	return(sprintf("%d 0 R", $this_id));
	}
?>
