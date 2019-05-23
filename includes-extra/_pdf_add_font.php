<?
################################################################################
# _pdf_add_font ( array $pdf , string $fontname , string $encoding ) : string
################################################################################

function _pdf_add_font(& $pdf, $fontname, $encoding = "builtin")
	{
	if(in_array($encoding, array("builtin", "winansi", "macroman", "macexpert")) === false)
		die("_pdf_add_font: invalid encoding: " . $encoding);

	$core_fonts = _pdf_core_fonts();

	foreach($core_fonts as $k => $v)
		{
		if($v["/BaseFont"] != "/" . $fontname)
			continue;

		$retval = _pdf_add_font_core($pdf, $fontname, $encoding);

		return($retval);
		}

	################################################################################

#	$filename = "/home/nomatrix/externe_platte/daten/ttf/" . strtolower($fontname[0]) . "/" . $fontname . ".ttf";
	$filename = "/usr/share/fonts/truetype/freefont/" . $fontname . ".ttf";

	if(file_exists($filename) === false)
		return(_pdf_add_font($pdf["objects"], "Courier", $encoding));

	################################################################################

	$retval = _pdf_add_font_truetype($pdf, $filename, $encoding);

	return($retval);
	}
?>
