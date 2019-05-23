<?
################################################################################
# _pdf_add_image_png ( array $pdf , string $filename ) : string
################################################################################

function _pdf_add_image_png(& $pdf, $filename)
	{
	if(($f = fopen($filename, "rb")) === false)
		die("_pdf_add_image_png: invalid file: " . $filename);

	if(_readstream($f, 8) != "\x89PNG\x0D\x0A\x1A\x0A")
		die("_pdf_add_image_png: invalid file: " . $filename);

	_readstream($f, 4);

	if(_readstream($f, 4) != "IHDR")
		die("_pdf_add_image_png: invalid file: " . $filename);

	$width = _readint($f);

	$height = _readint($f);

	$bits_per_component = ord(_readstream($f, 1));

	if($bits_per_component > 8)
		die("_pdf_add_image_png: 16-bit depth not supported: " . $filename);

	$color_type = ord(_readstream($f, 1));

	if($color_type == 0)
		$color_space = "/DeviceGray";
	elseif($color_type == 2)
		$color_space = "/DeviceRGB";
	elseif($color_type == 3)
		$color_space = "/Indexed";
	elseif($color_type == 4)
		$color_space = "/DeviceGray";
	elseif($color_type == 6)
		$color_space = "/DeviceRGB";
	else
		die("_pdf_add_image_png: unknown color type: " . $filename);

	$compression_method = ord(_readstream($f, 1));

	if($compression_method != 0)
		die("_pdf_add_image_png: unknown compression method: " . $filename);

	$filter_method = ord(_readstream($f, 1));

	if($filter_method != 0)
		die("_pdf_add_image_png: unknown filter method: " . $filename);

	$interlacing = ord(_readstream($f, 1));

	if($interlacing != 0)
		die("_pdf_add_image_png: interlacing not supported: " . $filename);

	_readstream($f, 4);

	################################################################################

	$trns_stream = array();
	$plte_stream = "";
	$data_stream = "";

	do
		{
		$chunk_length = _readint($f);
		$chunk_type = _readstream($f, 4);
		$chunk_data = "";

		if($chunk_type == "PLTE")
			$plte_stream = _readstream($f, $chunk_length);
		elseif($chunk_type == "tRNS")
			{
			$chunk_data = _readstream($f, $chunk_length);

			if($color_type == 0)
				$trns_stream[] = array(ord($chunk_data[1]));
			elseif($color_type == 2)
				$trns_stream[] = array(ord($chunk_data[1]), ord($chunk_data[3]), ord($chunk_data[5]));
			elseif($pos = strpos($chunk_data, chr(0)))
				$trns_stream[] = array($pos);
			}
		elseif($chunk_type == "IDAT")
			$data_stream .= _readstream($f, $chunk_length);
		elseif($chunk_type == "IEND")
			break;
		else
			_readstream($f, $chunk_length);

		_readstream($f, 4);
		}
	while($chunk_length);

	################################################################################
	# palette
	################################################################################

	if($color_type == 3)
		{
		$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Filter" => "/FlateDecode",
				"/Length" => strlen($plte_stream)
				),
			"stream" => $plte_stream
			);

		$p = sprintf("%d 0 R", $this_id);
		}

	################################################################################
	# smask
	################################################################################

	if($color_type >= 4)
		{
		$data_stream = gzuncompress($data_stream);

		$color_stream = "";
		$alpha_stream = "";

		if($color_type == 4)
			{
			$width_index = 2 * $width;

			for($height_index = 0; $height_index < $height; $height_index = $height_index + 1)
				{
				$temp_index = (1 + $width_index) * $height_index;

				$alpha_stream .= $data_stream[$temp_index];
				$alpha_stream .= preg_replace("/.{1}(.)/s", "$1", substr($data_stream, $temp_index + 1, $width_index));

				$color_stream .= $data_stream[$temp_index];
				$color_stream .= preg_replace("/(.{1})./s", "$1", substr($data_stream, $temp_index + 1, $width_index));
				}
			}
		else
			{
			$width_index = 4 * $width;

			for($height_index = 0; $height_index < $height; $height_index = $height_index + 1)
				{
				$temp_index = (1 + $width_index) * $height_index;

				$alpha_stream .= $data_stream[$temp_index];
				$alpha_stream .= preg_replace("/.{3}(.)/s", "$1", substr($data_stream, $temp_index + 1, $width_index));

				$color_stream .= $data_stream[$temp_index];
				$color_stream .= preg_replace("/(.{3})./s", "$1", substr($data_stream, $temp_index + 1, $width_index));
				}
			}

		$alpha_stream = gzcompress($alpha_stream);
		$data_stream = gzcompress($color_stream);
		$plte_stream = gzcompress($plte_stream);

		################################################################################

		$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Width" => $width,
				"/Height" => $height,
				"/ColorSpace" => "/DeviceGray",
				"/BitsPerComponent" => 8,
				"/DecodeParms" => array
					(
					"/Predictor" => 15,
					"/Colors" => 1,
					"/BitsPerComponent" => 8,
					"/Columns" => $width
					),
				"/Filter" => "/FlateDecode",
				"/Length" => strlen($alpha_stream)
				),
			"stream" => $alpha_stream
			);

		$s = sprintf("%d 0 R", $this_id);
		}

	################################################################################
	# finally ...
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
			"/DecodeParms" => array
				(
				"/Predictor" => 15,
				"/Colors" => ($color_space == "/DeviceRGB" ? 3 : 1),
				"/BitsPerComponent" => $bits_per_component,
				"/Columns" => $width
				),
			"/Filter" => "/FlateDecode",
			"/Length" => strlen($data_stream)
			),
		"stream" => $data_stream
		);

	if($color_type == 3)
		$pdf["objects"][$this_id]["dictionary"]["/ColorSpace"] = sprintf("[/Indexed /DeviceRGB %d %s]", strlen($plte_stream) / 3 - 1, $p);

	if($color_type >= 4)
		$pdf["objects"][$this_id]["dictionary"]["/SMask"] = $s;

	if(count($trns_stream) > 0)
		{
		$mask = array();

		for($i = 0; $i < count($trns_stream); $i = $i + 1)
			$mask[] = implode(" ", array($trns_stream[$i], $trns_stream[$i]));

		$pdf["objects"][$this_id]["dictionary"]["/Mask"] = sprintf("[%s]", _pdf_glue_array($mask)); # pdf-api-glue.php
		}


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
