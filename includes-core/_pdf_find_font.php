<?
################################################################################
# _pdf_find_font ( array $pdf , string $fontname ) : string
################################################################################

function _pdf_find_font(& $pdf, $fontname)
	{
	$a = 0; # resource
	$b = 0; # name

	foreach($pdf["objects"] as $index => $object)
		{
		if($index == 0)
			continue;

		if(isset($object["dictionary"]["/Type"]) === false)
			continue;

		if(isset($object["dictionary"]["/Subtype"]) === false)
			continue;

		if(isset($object["dictionary"]["/BaseFont"]) === false)
			continue;

		if($object["dictionary"]["/Type"] != "/Font")
			continue;

		if($object["dictionary"]["/BaseFont"] != "/" . $fontname)
			continue;

		if($object["dictionary"]["/Subtype"] == "/Type1")
			$a = $index;

		if($object["dictionary"]["/Subtype"] == "/TrueType")
			$a = $index;
		}

	if($a == 0)
		die("_pdf_find_font: fontname not loaded.");

	foreach($pdf["resources"]["/Font"] as $name => $resource)
		if($a == $resource)
			$b = $name;

	if($b == 0)
		$b = _pdf_get_free_font_id($pdf);

	$pdf["resources"]["/Font"][$b] = sprintf("%d 0 R", $a);

	return("/F" . $b);
	}
?>
