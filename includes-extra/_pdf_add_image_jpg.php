<?
################################################################################
# _pdf_add_image_jpeg ( array $pdf , string $filename ) : string
################################################################################

function _pdf_add_image_jpg(& $pdf, $filename)
	{
	if(($get_image_size = getimagesize($filename)) === false)
		die("_pdf_add_image_jpg: invalid file: " . $filename);

	$width = $get_image_size[0];
	$height = $get_image_size[1];

	if($get_image_size[2] != 2)
		die("_pdf_add_image_jpg: invalid file: " . $filename);

	if(isset($get_image_size["channels"]) === false)
		$color_space = "/DeviceRGB";
	elseif($get_image_size["channels"] == 3)
		$color_space = "/DeviceRGB";
	elseif($get_image_size["channels"] == 4)
		$color_space = "/DeviceCMYK";
	else
		$color_space = "/DeviceGray";

	if(isset($get_image_size["bits"]))
		$bits_per_component = $get_image_size["bits"];
	else
		$bits_per_component = 8;

	################################################################################

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/XObject",
			"/Subtype" => "/Image",
			"/Width" => $width,
			"/Height" => $height,
			"/ColorSpace" => $color_space,
			"/BitsPerComponent" => $bits_per_component,
			"/Filter" => "/DCTDecode",
			"/Length" => filesize($filename)
			),
		"stream" => file_get_contents($filename)
		);

	if($bits_per_component == 1)
		if(in_array("/ImageB", $pdf["resources"]["/ProcSet"]) === false)
			$pdf["resources"]["/ProcSet"][] = "/ImageB";
		elseif(in_array("/ImageB", $pdf["resources"]["/ProcSet"]) === false)
			$pdf["resources"]["/ProcSet"][] = "/ImageB";

	if($bits_per_component != 1)
		if(isset($pdf["resources"]["/ProcSet"]) === false)
			$pdf["resources"]["/ProcSet"][] = "/ImageC";
		elseif(in_array("/ImageC", $pdf["resources"]["/ProcSet"]) === false)
			$pdf["resources"]["/ProcSet"][] = "/ImageC";

	if($color_space == "/Indexed")
		if(isset($pdf["resources"]["/ProcSet"]) === false)
			$pdf["resources"]["/ProcSet"][] = "/ImageI";
		elseif(in_array("/ImageI", $pdf["resources"]["/ProcSet"]) === false)
			$pdf["resources"]["/ProcSet"][] = "/ImageI";

	return(sprintf("%d 0 R", $this_id));
	}
?>
