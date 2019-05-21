<?
################################################################################
# copyright 2019 by Markus Olderdissen
# free for private use or inspiration.
# public use need written permission.
################################################################################

################################################################################
# _pdf_begin_document ( array $pdf ) : void
################################################################################

function _pdf_begin_document(& $pdf)
	{
	$catalog = _pdf_add_catalog($pdf);

	$outlines = _pdf_add_outlines($pdf, $catalog);

	$pages = _pdf_add_pages($pdf, $catalog);

	$pdf["outlines"] = $outlines;
	$pdf["pages"] = $pages;
	}

################################################################################
# _pdf_begin_page ( array $pdf , int $width , int $height, string $parent ) : void
################################################################################

function _pdf_begin_page(& $pdf, $width, $height)
	{
	$pdf["width"] = $width;
	$pdf["height"] = $height;
	$pdf["stream"] = array();
	}

################################################################################
# _pdf_close ( array $pdf ) : array
################################################################################

function _pdf_close(& $pdf)
	{
	if(strlen($pdf["filename"]))
		file_put_contents($pdf["filename"], $pdf["stream"]);
	}

################################################################################
# _pdf_concat ( array $pdf , $int $a , int $b , int $c , int $d , int $e , int $f ) : array
################################################################################

function _pdf_concat(& $pdf, $a, $b, $c, $d, $e, $f)
	{
	$pdf["stream"][] = sprintf("%f %f %f %f %f %f cm", $a, $b, $c, $d, $e, $f);
	}

################################################################################
# _pdf_new ( void ) : array
################################################################################

function _pdf_new()
	{
	$pdf = array("objects" => array(array("dictionary" => array("/Size" => 0))));

	return($pdf);
	}

################################################################################
# _pdf_end_document ( array $pdf ) : void
################################################################################

function _pdf_end_document(& $pdf)
	{
	$pdf["objects"][0]["dictionary"]["/Size"] = count($pdf["objects"]);
	}

################################################################################
# _pdf_end_page ( array $pdf ) : string
################################################################################

function _pdf_end_page(& $pdf)
	{
	if(isset($pdf["loaded-resources"]["/ProcSet"]))
		foreach($pdf["loaded-resources"]["/ProcSet"] as $object)
			$resources["/ProcSet"][] = $object;

	if(isset($pdf["loaded-resources"]["/Font"]))
		foreach($pdf["loaded-resources"]["/Font"] as $id => $object)
			$resources["/Font"]["/F" . $id] = $object;

	if(isset($pdf["loaded-resources"]["/XObject"]))
		foreach($pdf["loaded-resources"]["/XObject"] as $id => $object)
			$resources["/XObject"]["/X" . $id] = $object;

	if(isset($resources["/ProcSet"]))
		$resources["/ProcSet"] = sprintf("[%s]", _pdf_glue_array($$resources["/ProcSet"]));

	if(isset($resources["/Font"]))
		$resources["/Font"] = sprintf("<< %s >>", _pdf_glue_dictionary($resources["/Font"]));

	if(isset($resources["/XObject"]))
		$resources["/XObject"] = sprintf("<< %s >>", _pdf_glue_dictionary($resources["/XObject"]));

	$parent = $pdf["pages"];

	$mediabox = sprintf("[%d %d %d %d]", 0, 0 , $pdf["width"], $pdf["height"]);
	$resources = sprintf("<< %s >>", _pdf_glue_dictionary($resources));

	$contents = implode(" ", $pdf["stream"]);

	$contents = _pdf_add_stream($pdf, $contents);

	$retval = _pdf_add_page($pdf, $parent, $resources, $mediabox, $contents);

	return($retval);
	}

################################################################################
# _pdf_get_buffer ( array $pdf ) : string
################################################################################

function _pdf_get_buffer(& $pdf)
	{
	return($pdf["stream"]);
	}

################################################################################
# _pdf_load_font ( array $pdf , string $fontname ) : string
################################################################################

function _pdf_load_font(& $pdf, $fontname)
	{
	$a = _pdf_add_font($pdf, $fontname);

	$b = _pdf_get_free_font_id($pdf);

	$pdf["loaded-resources"]["/Font"][$b] = sprintf("%d 0 R", $a);

	return("/F" . $b);
	}

################################################################################
# _pdf_open ( string $filename ) : void
################################################################################

function _pdf_open_file(& $pdf, $filename)
	{
	$pdf["filename"] = $filename;

	if(strlen($pdf["filename"]))
		$pdf["stream"] = file_get_contents($pdf["filename"]);
	}

################################################################################
# _pdf_estore ( array $pdf ) void
################################################################################

function _pdf_restore(& $pdf)
	{
	$pdf["stream"][] = "Q";
	}

################################################################################
# _pdf_rotate ( array $pdf , int $phi ) void
################################################################################

function _pdf_rotate(& $pdf, $phi)
	{
	$sin = sin($phi * M_PI / 180);
	$cos = cos($phi * M_PI / 180);

	_pdf_concat($pdf, 0 + $cos, 0 + $sin, 0 - $sin, 0 + $cos, 0, 0);
	}

################################################################################
# _pdf_save ( array $pdf ) void
################################################################################

function _pdf_save(& $pdf)
	{
	$pdf["stream"][] = "q";
	}

################################################################################
# _pdf_scale ( array $pdf , int $sx , in $sy ) void
################################################################################

function _pdf_scale(& $pdf, $sx, $sy)
	{
	_pdf_concat($pdf, $sx, 0, 0, $sy, 0, 0);
	}

################################################################################
# _pdf_set_font ( array $pdf , string $font , int $size ) void
################################################################################

function _pdf_set_font(& $pdf, $font, $size)
	{
	$pdf["stream"][] = sprintf("%s %d Tf", $font, $size);
	}

################################################################################
# _pdf_set_font ( array $pdf , int $leading ) void
################################################################################

function _pdf_set_leading(& $pdf, $leading)
	{
	$pdf["stream"][] = sprintf("%d TL", $leading);
	}

################################################################################
# _pdf_set_text ( array $pdf , string $text ) void
################################################################################

function _pdf_set_text(& $pdf, $text)
	{
	$pdf["stream"][] = sprintf("(%s) Tj", $text);
	}

################################################################################
# _pdf_set_xy ( array $pdf , int $x , int $y ) void
################################################################################

function _pdf_set_xy(& $pdf, $x, $y)
	{
	$pdf["stream"][] = sprintf("%s %d Td", $x, $y);
	}

################################################################################
# _pdf_translate ( array $pdf , int $tx , int $ty ) void
################################################################################

function _pdf_translate(& $pdf, $tx, $ty)
	{
	_pdf_concat($pdf, 1, 0, 0, 1, $tx, $ty);
	}
?>
