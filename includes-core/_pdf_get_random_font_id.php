<?
################################################################################
# _pdf_get_random_font_id ( array $pdf ) : string
################################################################################

function _pdf_get_random_font_id(& $pdf, $fontname)
	{
	if(sscanf($fontname, "/%s", $fontname) != 1)
		die("_pdf_get_random_font_id: invalid fontname.");

	$fontname = sprintf("/AAAAAA-%s", $fontname);

	foreach(range(1, 6) as $i)
		$fontname[$i] = chr(rand(65, 90));
	
	return($fontname);
	}
?>
