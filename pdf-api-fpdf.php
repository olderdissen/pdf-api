<?
################################################################################
# copyright 2019 by Markus Olderdissen
# free for private use or inspiration.
# public use need written permission.
#
# contains material from olivier plathey
################################################################################

date_default_timezone_set("UTC");

#ini_set("error_reporting", E_ALL);
#ini_set("max_execution_time", 5);

################################################################################
# ...
################################################################################

function RC4($key, $data)
	{
#	return(openssl_encrypt($data, "RC4-40", $key, OPENSSL_RAW_DATA));
	return(mcrypt_encrypt(MCRYPT_ARCFOUR, $key, $data, MCRYPT_MODE_STREAM, ""));
	}

################################################################################
# ...
################################################################################

function _filter(& $pdfdoc, $test)
	{
	if(isset($pdfdoc["/Filter"]))
		{
		$data = $pdfdoc["/Filter"];
		$retval = array();

		while(1)
			{
			if(strlen($data) == 0)
				break;
			elseif($data[0] == " ")
				$data = substr($data, 1);
			elseif($data[0] == "[")
				$data = substr($data, 1); # shall we???
			elseif($data[0] == "]")
				$data = substr($data, 1);
			elseif($data[0] == "/")
				{
				$data = substr($data, 1);

				list($name, $data) = _pdf_parse_name($data);

				$retval[] = sprintf("/%s", $name);
				}
			else
				die(sprintf("you should never be here: data follows: %s", $data));
			}

		foreach(array_reverse($retval) as $filter)
			{
			if($filter == "/ASCIIHexDecode")
				$test = _pdf_filter_asciihex_encode($test);

			if($filter == "/FlateDecode")
				$test = _pdf_filter_flate_encode($test, 9);
			}
		}

	return($test);
	}

################################################################################
# ...
################################################################################

function _new_object(& $pdfdoc)
	{
	$i = $pdfdoc["reference-id"] + 1;

	$pdfdoc["reference-id"] = $i;

	$pdfdoc["offsets"][$i] = strlen(implode("\n", $pdfdoc["stream"]));

	$pdfdoc["stream"][] = sprintf("%d 0 obj", $pdfdoc["reference-id"]);

	return($i);
	}

################################################################################
# ...
################################################################################

function _put_object(& $pdfdoc, $object)
	{
	_new_object($pdfdoc);

		$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object["dictionary"]));

		$pdfdoc["stream"][] = "stream";
			_put_stream($pdfdoc, $object["stream"]);
		$pdfdoc["stream"][] = "endstream";

	$pdfdoc["stream"][] = "endobj";
	}

function _put_image(& $pdfdoc, & $image)
	{
	$image["id"] = $pdfdoc["reference-id"] + 1;
	$image["version"] = 0;

	################################################################################

	$object = array
		(
		"dictionary" => array
			(
			"/Type" => "/XObject",
			"/Subtype" => "/Image",
			"/Width" => $image["dictionary"]["/Width"],
			"/Height" => $image["dictionary"]["/Height"],
			"/Length" => strlen($image["stream"])
			),

		"stream" => $image["stream"]
		);

	if(isset($image["dictionary"]["/ColorSpace"]))
		if($image["dictionary"]["/ColorSpace"] == "/Indexed")
			$object["dictionary"]["/ColorSpace"] = sprintf("[/Indexed /DeviceRGB %d %d 0 R]", strlen($image["dictionary"]["x-palette"]) / 3 - 1, $pdfdoc["reference-id"] + 2);
		else
			$object["dictionary"]["/ColorSpace"] = $image["dictionary"]["/ColorSpace"];

	if(isset($image["dictionary"]["/ColorSpace"]))
		if($image["dictionary"]["/ColorSpace"] == "/DeviceCMYK")
			$object["dictionary"]["/Decode"] = "[1 0 1 0 1 0 1 0]";

	$object["dictionary"]["/BitsPerComponent"] = $image["dictionary"]["/BitsPerComponent"];

	if(isset($image["dictionary"]["/Filter"]))
		$object["dictionary"]["/Filter"] = $image["dictionary"]["/Filter"];

	if(isset($image["dictionary"]["/DecodeParms"]))
		$object["dictionary"]["/DecodeParms"] = sprintf("<< %s >>", _pdf_glue_dictionary($image["dictionary"]["/DecodeParms"]));

	if(isset($image["dictionary"]["/Mask"]))
		{
		$mask = array();

		for($i = 0; $i < count($image["dictionary"]["/Mask"]); $i = $i + 1)
			$mask[] = implode(" ", array($image["dictionary"]["/Mask"][$i], $image["dictionary"]["/Mask"][$i]));

		$object["dictionary"]["/Mask"] = sprintf("[%s]", _pdf_glue_array($mask));
		}

	if(isset($image["dictionary"]["/SMask"]))
		$object["dictionary"]["/SMask"] = sprintf("%d 0 R", $pdfdoc["reference-id"] + 1);

	_put_object($pdfdoc, $object);

	################################################################################

	if(isset($image["dictionary"]["/SMask"]))
		{
		$object = array
			(
			"dictionary" => array
				(
				"/Width" => $image["dictionary"]["/Width"],
				"/Height" => $image["dictionary"]["/Height"],
				"/ColorSpace" => "/DeviceGray",
				"/BitsPerComponent" => 8,
				"/Filter" => $image["dictionary"]["/Filter"],
				"/Length" => strlen($image["dictionary"]["/SMask"]),
				"/DecodeParms" => array
					(
					"/Predictor" => 15,
					"/Colors" => 1,
					"/BitsPerComponent" => 8,
					"/Columns" => $image["dictionary"]["/Width"]
					),
				),

			"stream" => $image["dictionary"]["/SMask"],
			);

		_put_object($pdfdoc, $object);
		}

	if($image["dictionary"]["/ColorSpace"] == "/Indexed")
		{
		$data = $image["dictionary"]["x-palette"];

		$data = gzcompress($data);

		$object = array
			(
			"dictionary" => array
				(
				"/Filter" => "/FlateDecode",
				"/Length" => strlen($data)
				),

			"stream" => $data
			);

		_put_object($pdfdoc, $object);
		}

	################################################################################

	if($image["dictionary"]["/BitsPerComponent"] == 1)
		if(in_array("/ImageB", $pdfdoc["/ProcSet"]) === false)
			$pdfdoc["/ProcSet"][] = "/ImageB";
		elseif(in_array("/ImageB", $pdfdoc["/ProcSet"]) === false)
			$pdfdoc["/ProcSet"][] = "/ImageB";

	if($image["dictionary"]["/BitsPerComponent"] != 1)
		if(isset($pdfdoc["/ProcSet"]) === false)
			$pdfdoc["/ProcSet"][] = "/ImageC";
		elseif(in_array("/ImageC", $pdfdoc["/ProcSet"]) === false)
			$pdfdoc["/ProcSet"][] = "/ImageC";

	if($image["dictionary"]["/ColorSpace"] == "/Indexed")
		if(isset($pdfdoc["/ProcSet"]) === false)
			$pdfdoc["/ProcSet"][] = "/ImageI";
		elseif(in_array("/ImageI", $pdfdoc["/ProcSet"]) === false)
			$pdfdoc["/ProcSet"][] = "/ImageI";
	}

################################################################################
# ...
################################################################################

function _put_stream(& $pdfdoc, $data)
	{
	if(isset($pdfdoc["encrypt"]))
		if($pdfdoc["encrypt"])
			$data = RC4(_objectkey($pdfdoc), $data);

	$pdfdoc["stream"][] = $data;
	}

################################################################################
# ...
################################################################################

function _md5_16($string)
	{
#	return(hex2bin(md5($string)));
	return(pack("H*", md5($string)));
	}

################################################################################
# ...
################################################################################

function _objectkey(& $pdfdoc)
	{
#	return(substr(_md5_16($pdfdoc["encrypt-dictionary"][0]["dictionary"]["e-pass"] . chr($pdfdoc["reference-id"] % 256)), 0, 10));
	return(substr(_md5_16($pdfdoc["encrypt-dictionary"][0]["dictionary"]["e-pass"] . pack("VXxx", $pdfdoc["reference-id"])), 0, 10));
	}

################################################################################
# ...
################################################################################

function _textstring(& $pdfdoc, $string, $encrypt = false)
	{
	$string = utf8_decode(str_replace("\r", "", $string));

	if(isset($pdfdoc["encrypt"]) && $pdfdoc["encrypt"] && $encrypt)
		$string = RC4(_objectkey($pdfdoc), $string);

	return("(" . _escape($string) . ")");
	}

################################################################################
# ...
################################################################################

function _escape($s)
	{
	return(str_replace(array("\\", "(", ")"), array("\\\\", "\\(", "\\)"), $s));
	}

################################################################################
# ...
################################################################################

function _parse_image_gif($pdfdoc, $filename)
	{
	if(function_exists("imagecreatefromgif") === false)
		die("gd has no gif read support");

	if(($image_create_from_gif = imagecreatefromgif($filename)) === false)
		die("missing or incorrect image file: " . $filename);

	imageinterlace($image_create_from_gif, 0);

	if(function_exists("imagepng") === false)
		die("gd extension is required for gif support");

	if(($temp_filename = tempnam(".", "gif")) === false)
		die("unable to create a temporary file");

	if(imagepng($image_create_from_gif, $temp_filename) === false)
		die("error while saving to temporary file");

	imagedestroy($image_create_from_gif);

	$object = _parse_image_png($pdfdoc, $temp_filename);

	unlink($temp_filename);

	return($object);
	}

function _parse_image_jpg($pdfdoc, $filename)
	{
	if(($get_image_size = getimagesize($filename)) === false)
		die("missing or incorrect image file: " . $filename);

	$width = $get_image_size[0];
	$height = $get_image_size[1];

	if($get_image_size[2] != 2)
		die("not a jpeg file: " . $filename);

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

	$object = array
		(
		"dictionary" => array
			(
			"/Width" => $width,
			"/Height" => $height,
			"/ColorSpace" => $color_space,
			"/BitsPerComponent" => $bits_per_component,
			"/Filter" => "/DCTDecode"
			),

		"stream" => file_get_contents($filename)
		);

	return($object);
	}

function _parse_image_png($pdfdoc, $filename)
	{
	if(($f = fopen($filename, "rb")) === false)
		die("can not open image file: " . $filename);

	if(_readstream($f, 8) != "\x89PNG\x0D\x0A\x1A\x0A")
		die("not a png file: " . $filename);

	_readstream($f, 4);

	if(_readstream($f, 4) != "IHDR")
		die("incorrect png file: " . $filename);

	$width = _readint($f);

	$height = _readint($f);

	$bits_per_component = ord(_readstream($f, 1));

	if($bits_per_component > 8)
		die("16-bit depth not supported: " . $filename);

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
		die("unknown color type: " . $filename);

	$compression_method = ord(_readstream($f, 1));

	if($compression_method != 0)
		die("unknown compression method: " . $filename);

	$filter_method = ord(_readstream($f, 1));

	if($filter_method != 0)
		die("unknown filter method: " . $filename);

	$interlacing = ord(_readstream($f, 1));

	if($interlacing != 0)
		die("interlacing not supported: " . $filename);

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

#print($plte_stream);

	if($color_space == "/DeviceRGB")
		$colors = 3;
	else
		$colors = 1;

	if($color_space == "/Indexed")
		if(empty($plte_stream))
			die("Missing palette in " . $filename);

	################################################################################
	# glue dictionary
	################################################################################

	$object = array
		(
		"dictionary" => array
			(
			"/Width" => $width,
			"/Height" => $height,
			"/ColorSpace" => $color_space,
			"/BitsPerComponent" => $bits_per_component,
			"/Filter" => "/FlateDecode",
			"/DecodeParms" => array
				(
				"/Predictor" => 15,
				"/Colors" => $colors,
				"/BitsPerComponent" => $bits_per_component,
				"/Columns" => $width
				),

			"x-palette" => $plte_stream,
			),
		);

	if(count($trns_stream) > 0)
		$object["dictionary"]["/Mask"] = $trns_stream;

	################################################################################

	$temp_stream = _pdf_filter_flate_decode($data_stream);

	################################################################################

	if($color_type >= 4)
		{
		$color_stream = "";
		$alpha_stream = "";

		if($color_type == 4)
			{
			$width_index = 2 * $width;

			for($height_index = 0; $height_index < $height; $height_index = $height_index + 1)
				{
				$temp_index = (1 + $width_index) * $height_index;

				$alpha_stream .= $temp_stream[$temp_index];
				$alpha_stream .= preg_replace("/.(.)/s", "$1", substr($temp_stream, $temp_index + 1, $width_index));

				$color_stream .= $temp_stream[$temp_index];
				$color_stream .= preg_replace("/(.)./s", "$1", substr($temp_stream, $temp_index + 1, $width_index));
				}
			}
		else
			{
			$width_index = 4 * $width;

			for($height_index = 0; $height_index < $height; $height_index = $height_index + 1)
				{
				$temp_index = (1 + $width_index) * $height_index;

				$alpha_stream .= $temp_stream[$temp_index];
				$alpha_stream .= preg_replace("/.{3}(.)/s", "$1", substr($temp_stream, $temp_index + 1, $width_index));

				$color_stream .= $temp_stream[$temp_index];
				$color_stream .= preg_replace("/(.{3})./s", "$1", substr($temp_stream, $temp_index + 1, $width_index));
				}
			}

		$alpha_stream = gzcompress($alpha_stream);

		$object["dictionary"]["/SMask"] = $alpha_stream;

		$color_stream = gzcompress($color_stream);

		$object["stream"] = $color_stream;
		}
	else
		{
		$temp_stream = gzcompress($temp_stream);

		$object["stream"] = $temp_stream;
		}

	return($object);
	}

################################################################################
# ...
################################################################################

function _readint($f)
	{
	$a = unpack("Ni", _readstream($f, 4));

	return($a["i"]);
	}

function _readstream($f, $n)
	{
	$retval = "";

	while(($n > 0) && (feof($f) === false))
		{
		if(($chunk = fread($f, $n)) === false)
			die("Error while reading stream");

		$n = $n - strlen($chunk);

		$retval .= $chunk;
		}

	if($n)
		die("Unexpected end of stream");

	return($retval);
	}
?>
