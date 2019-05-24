<?
################################################################################
# copyright 2019 by Markus Olderdissen
# free for private use or inspiration. 
# public use need written permission.
################################################################################

define("FONTDESCRIPTOR_FLAG_FIXEDPITCH", 1 << 1);
define("FONTDESCRIPTOR_FLAG_SERIF", 1 << 2);
define("FONTDESCRIPTOR_FLAG_SYMBOLIC", 1 << 3);
define("FONTDESCRIPTOR_FLAG_SCRIPT", 1 << 4);
define("FONTDESCRIPTOR_FLAG_NONSYMBOLIC", 1 << 6);
define("FONTDESCRIPTOR_FLAG_ITALIC", 1 << 7);
define("FONTDESCRIPTOR_FLAG_ALLCAP", 1 << 17);
define("FONTDESCRIPTOR_FLAG_SMALLCAP", 1 << 18);
define("FONTDESCRIPTOR_FLAG_FORCEBOLD", 1 << 19);

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

################################################################################
# _pdf_add_action ( array $pdf ) : string
################################################################################

function _pdf_add_action(& $pdf, $type, $optlist)
	{
	if(in_array($type, array("goto", "gotor", "launch", "uri")) === false)
		die("_pdf_add_action: invalid type: " . $type);

	$this_id = _pdf_get_free_object_id($pdf);

	if($type == "goto")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Action",
				"/S" => "/GoTo",
				"/D" => sprintf("[%s /Fit]", $optlist["dest"])
				)
			);
		}

	if($type == "gotor")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Action",
				"/S" => "/GoToR",
				"/F" => sprintf("(%s)", $optlist["filename"]),
				"/D" => sprintf("[%s /Fit]", $optlist["dest"])
				)
			);
		}

	if($type == "launch")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Action",
				"/S" => "/Launch",
				"/F" => sprintf("(%s)", $optlist["filename"])
				)
			);
		}

	if($type == "uri")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Action",
				"/S" => "/URI",
				"/URI" => sprintf("(%s)", $optlist["uri"])
				)
			);
		}

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_annotation ( array $pdf , string $parent , string $rect , string $uri ) : string
################################################################################

function _pdf_add_annotation(& $pdf, $parent, $rect, $type, $optlist)
	{
	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_annotation: invalid parent: " . $parent);

	if(sscanf($rect, "[%f %f %f %f]", $x, $ly, $w, $h) != 4)
		die("_pdf_add_annotation: invalid rect:" . $rect);

	if(in_array($type, array("attachment", "link", "text", "widget")) === false)
		die("_pdf_add_annotation: invalid type: " . $type);

	$this_id = _pdf_get_free_object_id($pdf);

	if($type == "attachment")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Annot",
				"/Rect" => $rect
				)
			);
		}

	if($type == "link")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Annot",
				"/Rect" => $rect
				)
			);
		}

	if($type == "text")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Annot",
				"/Rect" => $rect
				)
			);
		}

	# apply ...
	if(isset($pdf["objects"][$parent_id]["dictionary"]["/Annots"]))
		$data = $pdf["objects"][$parent_id]["dictionary"]["/Annots"];
	else
		$data = "[]";

	$data = substr($data, 1);
	list($annots, $data) = _pdf_parse_array($data);
	$data = substr($data, 1);

	$annots[] = sprintf("%d 0 R", $this_id);

	$pdf["objects"][$parent_id]["dictionary"]["/Annots"] = sprintf("[%s]", _pdf_glue_array($annots));

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_catalog ( array $pdf ) : string
################################################################################

function _pdf_add_catalog(& $pdf)
	{
	if(isset($pdf["objects"][0]["dictionary"]["/Root"]))
		die("_pdf_add_catalog: catalog already exist.");

	$this_id = _pdf_get_free_object_id($pdf);

	$pdf["objects"][] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Catalog",
			"/PageLayout" => "/SinglePage",
			"/PageMode" => "/UseOutlines"
			)
		);

	# apply location of catalog
	$pdf["objects"][0]["dictionary"]["/Root"] = sprintf("%d 0 R", $this_id);

	return(sprintf("%d 0 R", $this_id));
	}

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

################################################################################
# _pdf_add_font_core ( array $pdf , string $fontname , string $encoding ) : string
################################################################################

function _pdf_add_font_core(& $pdf, $fontname, $encoding = "builtin")
	{
	if(in_array($encoding, array("builtin", "winansi", "macroman", "macexpert")) === false)
		die("_pdf_add_font: invalid encoding: " . $encoding);

	$this_id = _pdf_get_free_object_id($pdf);

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Font",
			"/Subtype" => "/Type1",
			"/BaseFont" => "/" . $fontname
			)
		);

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

################################################################################
# _pdf_add_font_definiton ( array $pdf , ... ) : string
# pending
################################################################################

function _pdf_add_font_definition(& $pdf)
	{
	$e = _pdf_add_font_encoding($pdf);

	$s = _pdf_add_page_stream($pdf, "");

	$d = _pdf_add_font_descriptor($pdf, "test", "");

	$this_id = _pdf_get_free_object_id($pdf);

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Font",
			"/Subtype" => "/Type3",
			"/FontBBox" => "[0 0 1000 1000]",
			"/FontMatrix" => "[1 0 0 -1 0 0]",
			"/CharProcs" => sprintf("<< /C %s /B %s /A %s >>", $s, $s, $s),
			"/Encoding" => $e,
			"/FirstChar" => 65,
			"/LastChar" => 67,
			"/Widths" => "[8 8 8]",
			"/FontDescriptor" => $d
			)
		);

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_font_descriptor ( array $pdf , string $fontname , string $fontfile ) : string
################################################################################

function _pdf_add_font_descriptor(& $pdf, $fontname, $fontfile) # make fontfile an optlist
	{
	$this_id = _pdf_get_free_object_id($pdf);

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

################################################################################
# _pdf_add_font_encoding ( array $pdf , string $differences ) : string
################################################################################

function _pdf_add_font_encoding(& $pdf, $encoding = "builtin", $differences = "") # make differences an optlist
	{
	if(in_array($encoding, array("builtin", "winansi", "macroman", "macexpert")) === false)
		die("_pdf_add_font_encoding: invalid encoding: " . $encoding);

	$this_id = _pdf_get_free_object_id($pdf);

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Encoding"
			)
		);

	# apply differences
	if($differences)
		$pdf["objects"][$this_id]["dictionary"]["/Differences"] = $differences;

	# valid encodings
	$encodings = array("winansi" => "/WinAnsiEncoding", "macroman" => "/MacRomanEncoding", "macexpert" => "/MacExpertEncoding");

	# apply encoding
	if($encoding != "builtin")
		if(isset($encodings[$encoding]))
			$pdf["objects"][$this_id]["dictionary"]["/BaseEncoding"] = $encodings[$encoding];
		else
			$pdf["objects"][$this_id]["dictionary"]["/BaseEncoding"] = $encoding;

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_font_file ( array $pdf , string $filename ) : string
################################################################################

function _pdf_add_font_file(& $pdf, $filename)
	{
	if(file_exists($filename) === false)
		die("_pdf_add_font_truetype: invalid file: " . $filename);

	$this_id = _pdf_get_free_object_id($pdf);

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

	$this_id = _pdf_get_free_object_id($pdf);

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

	$pdf["objects"][$this_id]["dictionary"]["/Widths"] = sprintf("[%s]", _pdf_glue_array($widths));

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

################################################################################
# _pdf_add_form ( array $pdf , string $bbox , string $resources , string $stream ) : string
################################################################################

function _pdf_add_form(& $pdf, $resources, $bbox, $stream)
	{
	# check resources for beeing dictionary or pointer to such

	if(sscanf($bbox, "[%f %f %f %f]", $x, $ly, $w, $h) != 4)
		die("_pdf_add_form: invalid bbox:" . $bbox);

	$this_id = _pdf_get_free_object_id($pdf);

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/XObject",
			"/Subtype" => "/Form",
			"/FormType" => 1,
			"/Resources" => $resources,
			"/BBox" => $bbox,
			"/Length" => strlen($stream)
			),
		"stream" => $stream
		);

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_image_gif ( array $pdf , string $filename ) : string
################################################################################

function _pdf_add_image_gif(& $pdf, $filename)
	{
	if(function_exists("imagecreatefromgif") === false)
		die("_pdf_add_image_jpg: no gif support.");

	if(($image_create_from_gif = imagecreatefromgif($filename)) === false)
		die("_pdf_add_image_jpg: invalid file: " . $filename);

	imageinterlace($image_create_from_gif, 0);

	if(function_exists("imagepng") === false)
		die("_pdf_add_image_jpg: no png support.");

	if(($temp_filename = tempnam(".", "gif")) === false)
		die("_pdf_add_image_jpg: unable to create a temporary file.");

	if(imagepng($image_create_from_gif, $temp_filename) === false)
		die("_pdf_add_image_jpg: error while saving to temporary file.");

	imagedestroy($image_create_from_gif);

	$retval = _pdf_add_image_png($pdf, $temp_filename);

	unlink($temp_filename);

	return($retval);
	}

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

	$this_id = _pdf_get_free_object_id($pdf);

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
		if(in_array("/ImageB", $pdf["loaded-resources"]["/ProcSet"]) === false)
			$pdf["loaded-resources"]["/ProcSet"][] = "/ImageB";
		elseif(in_array("/ImageB", $pdf["loaded-resources"]["/ProcSet"]) === false)
			$pdf["loaded-resources"]["/ProcSet"][] = "/ImageB";

	if($bits_per_component != 1)
		if(isset($pdf["loaded-resources"]["/ProcSet"]) === false)
			$pdf["loaded-resources"]["/ProcSet"][] = "/ImageC";
		elseif(in_array("/ImageC", $pdf["loaded-resources"]["/ProcSet"]) === false)
			$pdf["loaded-resources"]["/ProcSet"][] = "/ImageC";

	if($color_space == "/Indexed")
		if(isset($pdf["loaded-resources"]["/ProcSet"]) === false)
			$pdf["loaded-resources"]["/ProcSet"][] = "/ImageI";
		elseif(in_array("/ImageI", $pdf["loaded-resources"]["/ProcSet"]) === false)
			$pdf["loaded-resources"]["/ProcSet"][] = "/ImageI";

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_image ( array $pdf , string $filename ) : string
################################################################################

function _pdf_add_image(& $pdf, $filename)
	{
	$imagetype = pathinfo($filename, PATHINFO_EXTENSION);

	if($imagetype == "jpg")
		$retval = _pdf_add_image_jpg($pdf, $filename);
	elseif($imagetype == "gif")
		$retval = _pdf_add_image_gif($pdf, $filename);
	elseif($imagetype == "png")
		$retval = _pdf_add_image_png($pdf, $filename);
	else
		{
		system("convert " . $filename . " -quality 15 lolo.jpg");

		$retval = _pdf_add_image($pdf, "lolo.jpg");

		# don't change it!
		# keep this name!
		# don't make fun here!

		unlink("lolo.jpg");
		}

	return($retval);
	}

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
		$this_id = _pdf_get_free_object_id($pdf);

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

		$this_id = _pdf_get_free_object_id($pdf);

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

	$this_id = _pdf_get_free_object_id($pdf);

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

		$pdf["objects"][$this_id]["dictionary"]["/Mask"] = sprintf("[%s]", _pdf_glue_array($mask));
		}


	if($bits_per_component == 1)
		if(in_array("/ImageB", $pdf["loaded-resources"]["/ProcSet"]) === false)
			$pdf["loaded-resources"]["/ProcSet"][] = "/ImageB";
		elseif(in_array("/ImageB", $pdf["loaded-resources"]["/ProcSet"]) === false)
			$pdf["loaded-resources"]["/ProcSet"][] = "/ImageB";

	if($bits_per_component != 1)
		if(isset($pdf["loaded-resources"]["/ProcSet"]) === false)
			$pdf["loaded-resources"]["/ProcSet"][] = "/ImageC";
		elseif(in_array("/ImageC", $pdf["loaded-resources"]["/ProcSet"]) === false)
			$pdf["loaded-resources"]["/ProcSet"][] = "/ImageC";

	if($color_space == "/Indexed")
		if(isset($pdf["loaded-resources"]["/ProcSet"]) === false)
			$pdf["loaded-resources"]["/ProcSet"][] = "/ImageI";
		elseif(in_array("/ImageI", $pdf["loaded-resources"]["/ProcSet"]) === false)
			$pdf["loaded-resources"]["/ProcSet"][] = "/ImageI";

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_info ( array $pdf , array $optlist ) : string
################################################################################

function _pdf_add_info(& $pdf, $optlist)
	{
	if(isset($pdf["objects"][0]["dictionary"]["/Info"]))
		die("_pdf_add_info: info already exist.");

	$this_id = _pdf_get_free_object_id($pdf);

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/CreationDate" => sprintf("(D:%sZ)", date("YmdHis")),
			"/ModDate" => sprintf("(D:%sZ)", date("YmdHis")),
			"/Producer" => sprintf("(%s)", basename(__FILE__))
			)
		);

	# apply additional settings to created object
	foreach($optlist as $key => $value)
		$pdf["objects"][$this_id]["dictionary"][$key] = $value;

	# apply location of info
	$pdf["objects"][0]["dictionary"]["/Info"] = sprintf("%d 0 R", $this_id);

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_metadata ( array $pdf , string $parent , string $stream ) : string
################################################################################

function _pdf_add_metadata(& $pdf, $parent, $stream = '<?xpacket?><x:xmpmeta xmlns:x="adobe:ns:meta/"><r:RDF xmlns:r="http://www.w3.org/1999/02/22-rdf-syntax-ns#"><r:Description xmlns:p="http://www.aiim.org/pdfa/ns/id/"><p:part>1</p:part><p:conformance>A</p:conformance></r:Description></r:RDF></x:xmpmeta><?xpacket?>')
	{
	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_metadata: invalid parent: " . $parent);

	if(isset($pdf["objects"][$parent_id]["dictionary"]["/Metadata"]))
		die("_pdf_add_metadata: metadata already exist.");

	$this_id = _pdf_get_free_object_id($pdf);

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Metadata",
			"/Subtype" => "/XML",
			"/Length" => strlen($stream)
			),
		"stream" => $stream
		);

	# apply location of metadata
	$pdf["objects"][$parent_id]["dictionary"]["/Metadata"] = sprintf("%d 0 R", $this_id);

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_outline ( array $pdf , string $parent , string $open , string $title ) : string
################################################################################

function _pdf_add_outline(& $pdf, $parent, $open, $title)
	{
	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_outline: invalid parent: " . $parent);

	if(sscanf($open, "%d %d R", $open_id, $open_version) != 2)
		die("_pdf_add_outline: invalid open: " . $open);

	$this_id = _pdf_get_free_object_id($pdf);

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Title" => sprintf("(%s)", _pdf_glue_string($title)),
			"/Parent" => $parent,
			"/Dest" => sprintf("[%s /Fit]", $open)
			)
		);

	# apply info about this object to parent
	if($pdf["objects"][$parent_id]["dictionary"]["/Type"] == "/Outlines")
		{
		if(isset($pdf["objects"][$parent_id]["dictionary"]["/First"]) === false)
			$pdf["objects"][$parent_id]["dictionary"]["/First"] = sprintf("%d 0 R", $this_id);

		if(isset($pdf["objects"][$parent_id]["dictionary"]["/Last"]))
			{
			$last = $pdf["objects"][$parent_id]["dictionary"]["/Last"];

			if(sscanf($last, "%d %d R", $last_id, $last_version) == 2)
				$pdf["objects"][$last_id]["dictionary"]["/Next"] = sprintf("%d 0 R", $this_id);

			$pdf["objects"][$this_id]["dictionary"]["/Prev"] = $last;
			}

		$pdf["objects"][$parent_id]["dictionary"]["/Last"] = sprintf("%d 0 R", $this_id);

		# increase or decrease counter, depending on plus or minus sign
		if(isset($pdf["objects"][$parent_id]["dictionary"]["/Count"]))
			$count = $pdf["objects"][$parent_id]["dictionary"]["/Count"];
		else
			$count = 0;

		$pdf["objects"][$parent_id]["dictionary"]["/Count"] = ($count < 0 ? $count - 1 : $count + 1);
		}
	else
		die("_pdf_add_outline: invalid type of parent.");

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_outlines ( array $pdf , string $parent ) : string
################################################################################

function _pdf_add_outlines(& $pdf, $parent)
	{
	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_outlines: invalid parent:" . $parent);

	$this_id = _pdf_get_free_object_id($pdf);

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Outlines",
			"/Count" => 0
			)
		);

	# apply info about this object to parent
	if($pdf["objects"][$parent_id]["dictionary"]["/Type"] == "/Outlines")
		$pdf["objects"][$this_id]["dictionary"]["/Parent"] = $parent;
	elseif($pdf["objects"][$parent_id]["dictionary"]["/Type"] != "/Catalog")
		die("_pdf_add_outlines: invalid type of parent.");

	# apply location of outlines
	$pdf["objects"][$parent_id]["dictionary"]["/Outlines"] = sprintf("%d 0 R", $this_id);

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_page ( array $pdf , string $parent , string $resources , string $mediabox , string $contents ) : string
################################################################################

function _pdf_add_page(& $pdf, $parent, $resources, $mediabox, $contents)
	{
	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_pages: invalid parent: " . $parent);

	# check resources for beeing dictionary or pointer to such

	if(sscanf($mediabox, "[%f %f %f %f]", $x, $y, $w, $h) != 4)
		die("_pdf_add_pages: invalid mediabox:" . $mediabox);

	# check contents for beeing pointer or array of such

	$this_id = _pdf_get_free_object_id($pdf);

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Page",
			"/Parent" => $parent,
			"/Resources" => $resources,
			"/MediaBox" => $mediabox,
			"/Contents" => $contents
			)
		);

	# apply group
	if($pdf["minor"] > 3)
		$pdf["objects"][$this_id]["dictionary"]["/Group"] = "<< /Type /Group /S /Transparency /CS /DeviceRGB >>";

	# apply info about this object to parent
	if($pdf["objects"][$parent_id]["dictionary"]["/Type"] == "/Pages")
		{
		# apply page to kids
		if(isset($pdf["objects"][$parent_id]["dictionary"]["/Kids"]))
			$data = $pdf["objects"][$parent_id]["dictionary"]["/Kids"];
		else
			$data = "[]";

		$data = substr($data, 1);
		list($kids, $data) = _pdf_parse_array($data);
		$data = substr($data, 1);

		$kids[] = sprintf("%d 0 R", $this_id);
	
		$pdf["objects"][$parent_id]["dictionary"]["/Kids"] = sprintf("[%s]", _pdf_glue_array($kids));

		# increase or decrease counter, depending on plus or minus sign
		if(isset($pdf["objects"][$parent_id]["dictionary"]["/Count"]))
			$count = $pdf["objects"][$parent_id]["dictionary"]["/Count"];
		else
			$count = 0;

		$pdf["objects"][$parent_id]["dictionary"]["/Count"] = ($count < 0 ? $count - 1 : $count + 1);
		}
	else
		die("_pdf_add_pages: invalid type of parent.");

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_pages ( array $pdf , string $parent ) : string
################################################################################

function _pdf_add_pages(& $pdf, $parent)
	{
	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_page: invalid parent: " . $parent);

	$this_id = _pdf_get_free_object_id($pdf);

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Pages",
			"/Kids" => "[]",
			"/Count" => 0
			)
		);

	# apply info about this object to parent
	if($pdf["objects"][$parent_id]["dictionary"]["/Type"] == "/Pages")
		{
		# apply pages to kids
		$pdf["objects"][$this_id]["dictionary"]["/Parent"] = $parent;

		if(isset($pdf["objects"][$parent_id]["dictionary"]["/Kids"]))
			$data = $pdf["objects"][$parent_id]["dictionary"]["/Kids"];
		else
			$data = "[]";

		$data = substr($data, 1);
		list($kids, $data) = _pdf_parse_array($data);
		$data = substr($data, 1);

		$kids[] = sprintf("%d 0 R", $this_id);

		$pdf["objects"][$parent_id]["dictionary"]["/Kids"] = sprintf("[%s]", _pdf_glue_array($kids));

		# increase or decrease counter, depending on plus or minus sign
		if(isset($pdf["objects"][$parent_id]["dictionary"]["/Count"]))
			$count = $pdf["objects"][$parent_id]["dictionary"]["/Count"];
		else
			$count = 0;

		$pdf["objects"][$parent_id]["dictionary"]["/Count"] = ($count < 0 ? $count - 1 : $count + 1);
		}
	elseif($pdf["objects"][$parent_id]["dictionary"]["/Type"] == "/Catalog")
		$pdf["objects"][$parent_id]["dictionary"]["/Pages"] = sprintf("%d 0 R", $this_id);
	else
		die("_pdf_add_pages: invalid type of parent.");

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_stream ( array $pdf , string $stream , array $optlist ) : string
################################################################################

function _pdf_add_stream(& $pdf, $stream, $optlist = array())
	{
	$this_id = _pdf_get_free_object_id($pdf);

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Length" => strlen($stream)
			),
		"stream" => $stream
		);

	# apply additional settings to created object
	foreach($optlist as $key => $value)
		$pdf["objects"][$this_id]["dictionary"][$key] = $value;

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_core_fonts ( void ) : array
################################################################################

function _pdf_core_fonts()
	{
	$retval = array
		(
		# widths are needed for stringwidth
		array
			(
			"/BaseFont" => "/Courier",
			"/Widths" => array_fill(0, 256, 707)
			),
		array
			(
			"/BaseFont" => "/Courier-Bold",
			"/Widths" => array_fill(0, 256, 707)
			),
		array
			(
			"/BaseFont" => "/Courier-BoldOblique",
			"/Widths" => array_fill(0, 256, 707)
			),
		array
			(
			"/BaseFont" => "/Courier-Oblique",
			"/Widths" => array_fill(0, 256, 707)
			),
		array
			(
			"/BaseFont" => "/Helvetica",
			"/Widths" => array
				(
				278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278,
				278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278,
				278, 278, 355, 556, 556, 889, 667, 191, 333, 333, 389, 584, 278, 333, 278, 278,
				556, 556, 556, 556, 556, 556, 556, 556, 556, 556, 278, 278, 584, 584, 584, 556,
				1015, 667, 667, 722, 722, 667, 611, 778, 722, 278, 500, 667, 556, 833, 722, 778,
				667, 778, 722, 667, 611, 722, 667, 944, 667, 667, 611, 278, 278, 278, 469, 556,
				333, 556, 556, 500, 556, 556, 278, 556, 556, 222, 222, 500, 222, 833, 556, 556,
				556, 556, 333, 500, 278, 556, 500, 722, 500, 500, 500, 334, 260, 334, 584, 350,
				556, 350, 222, 556, 333, 1000, 556, 556, 333, 1000, 667, 333, 1000, 350, 611, 350,
				350, 222, 222, 333, 333, 350, 556, 1000, 333, 1000, 500, 333, 944, 350, 500, 667,
				278, 333, 556, 556, 556, 556, 260, 556, 333, 737, 370, 556, 584, 333, 737, 333,
				400, 584, 333, 333, 333, 556, 537, 278, 333, 333, 365, 556, 834, 834, 834, 611,
				667, 667, 667, 667, 667, 667, 1000, 722, 667, 667, 667, 667, 278, 278, 278, 278,
				722, 722, 778, 778, 778, 778, 778, 584, 778, 722, 722, 722, 722, 667, 667, 611,
				556, 556, 556, 556, 556, 556, 889, 500, 556, 556, 556, 556, 278, 278, 278, 278,
				556, 556, 556, 556, 556, 556, 556, 584, 611, 556, 556, 556, 556, 500, 556, 500
				)
			),
		array
			(
			"/BaseFont" => "/Helvetica-Bold",
			"/Widths" => array
				(
				 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278,
				 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278,
				 278, 333, 474, 556, 556, 889, 722, 238, 333, 333, 389, 584, 278, 333, 278, 278,
				 556, 556, 556, 556, 556, 556, 556, 556, 556, 556, 333, 333, 584, 584, 584, 611,
				 975, 722, 722, 722, 722, 667, 611, 778, 722, 278, 556, 722, 611, 833, 722, 778,
				 667, 778, 722, 667, 611, 722, 667, 944, 667, 667, 611, 333, 278, 333, 584, 556,
				 333, 556, 611, 556, 611, 556, 333, 611, 611, 278, 278, 556, 278, 889, 611, 611,
				 611, 611, 389, 556, 333, 611, 556, 778, 556, 556, 500, 389, 280, 389, 584, 350,
				 556, 350, 278, 556, 500, 1000, 556, 556, 333, 1000, 667, 333, 1000, 350, 611, 350,
				 350, 278, 278, 500, 500, 350, 556, 1000, 333, 1000, 556, 333, 944, 350, 500, 667,
				 278, 333, 556, 556, 556, 556, 280, 556, 333, 737, 370, 556, 584, 333, 737, 333,
				 400, 584, 333, 333, 333, 611, 556, 278, 333, 333, 365, 556, 834, 834, 834, 611,
				 722, 722, 722, 722, 722, 722, 1000, 722, 667, 667, 667, 667, 278, 278, 278, 278,
				 722, 722, 778, 778, 778, 778, 778, 584, 778, 722, 722, 722, 722, 667, 667, 611,
				 556, 556, 556, 556, 556, 556, 889, 556, 556, 556, 556, 556, 278, 278, 278, 278,
				 611, 611, 611, 611, 611, 611, 611, 584, 611, 611, 611, 611, 611, 556, 611, 556
				)
			),
		array
			(
			"/BaseFont" => "/Helvetica-BoldOblique",
			"/Widths" => array
				(
				 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278,
				 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278,
				 278, 333, 474, 556, 556, 889, 722, 238, 333, 333, 389, 584, 278, 333, 278, 278,
				 556, 556, 556, 556, 556, 556, 556, 556, 556, 556, 333, 333, 584, 584, 584, 611,
				 975, 722, 722, 722, 722, 667, 611, 778, 722, 278, 556, 722, 611, 833, 722, 778,
				 667, 778, 722, 667, 611, 722, 667, 944, 667, 667, 611, 333, 278, 333, 584, 556,
				 333, 556, 611, 556, 611, 556, 333, 611, 611, 278, 278, 556, 278, 889, 611, 611,
				 611, 611, 389, 556, 333, 611, 556, 778, 556, 556, 500, 389, 280, 389, 584, 350,
				 556, 350, 278, 556, 500, 1000, 556, 556, 333, 1000, 667, 333, 1000, 350, 611, 350,
				 350, 278, 278, 500, 500, 350, 556, 1000, 333, 1000, 556, 333, 944, 350, 500, 667,
				 278, 333, 556, 556, 556, 556, 280, 556, 333, 737, 370, 556, 584, 333, 737, 333,
				 400, 584, 333, 333, 333, 611, 556, 278, 333, 333, 365, 556, 834, 834, 834, 611,
				 722, 722, 722, 722, 722, 722, 1000, 722, 667, 667, 667, 667, 278, 278, 278, 278,
				 722, 722, 778, 778, 778, 778, 778, 584, 778, 722, 722, 722, 722, 667, 667, 611,
				 556, 556, 556, 556, 556, 556, 889, 556, 556, 556, 556, 556, 278, 278, 278, 278,
				 611, 611, 611, 611, 611, 611, 611, 584, 611, 611, 611, 611, 611, 556, 611, 556
				)
			),
		array
			(
			"/BaseFont" => "/Helvetica-Oblique",
			"/Widths" => array
				(
				 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278,
				 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278, 278,
				 278, 278, 355, 556, 556, 889, 667, 191, 333, 333, 389, 584, 278, 333, 278, 278,
				 556, 556, 556, 556, 556, 556, 556, 556, 556, 556, 278, 278, 584, 584, 584, 556,
				 1015, 667, 667, 722, 722, 667, 611, 778, 722, 278, 500, 667, 556, 833, 722, 778,
				 667, 778, 722, 667, 611, 722, 667, 944, 667, 667, 611, 278, 278, 278, 469, 556,
				 333, 556, 556, 500, 556, 556, 278, 556, 556, 222, 222, 500, 222, 833, 556, 556,
				 556, 556, 333, 500, 278, 556, 500, 722, 500, 500, 500, 334, 260, 334, 584, 350,
				 556, 350, 222, 556, 333, 1000, 556, 556, 333, 1000, 667, 333, 1000, 350, 611, 350,
				 350, 222, 222, 333, 333, 350, 556, 1000, 333, 1000, 500, 333, 944, 350, 500, 667,
				 278, 333, 556, 556, 556, 556, 260, 556, 333, 737, 370, 556, 584, 333, 737, 333,
				 400, 584, 333, 333, 333, 556, 537, 278, 333, 333, 365, 556, 834, 834, 834, 611,
				 667, 667, 667, 667, 667, 667, 1000, 722, 667, 667, 667, 667, 278, 278, 278, 278,
				 722, 722, 778, 778, 778, 778, 778, 584, 778, 722, 722, 722, 722, 667, 667, 611,
				 556, 556, 556, 556, 556, 556, 889, 500, 556, 556, 556, 556, 278, 278, 278, 278,
				 556, 556, 556, 556, 556, 556, 556, 584, 611, 556, 556, 556, 556, 500, 556, 500
				)
			),
		array
			(
			"/BaseFont" => "/Symbol",
			"/Widths" => array
				(
				 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250,
				 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250,
				 250, 333, 713, 500, 549, 833, 778, 439, 333, 333, 500, 549, 250, 549, 250, 278,
				 500, 500, 500, 500, 500, 500, 500, 500, 500, 500, 278, 278, 549, 549, 549, 444,
				 549, 722, 667, 722, 612, 611, 763, 603, 722, 333, 631, 722, 686, 889, 722, 722,
				 768, 741, 556, 592, 611, 690, 439, 768, 645, 795, 611, 333, 863, 333, 658, 500,
				 500, 631, 549, 549, 494, 439, 521, 411, 603, 329, 603, 549, 549, 576, 521, 549,
				 549, 521, 549, 603, 439, 576, 713, 686, 493, 686, 494, 480, 200, 480, 549, 0,
				 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
				 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
				 750, 620, 247, 549, 167, 713, 500, 753, 753, 753, 753, 1042, 987, 603, 987, 603,
				 400, 549, 411, 549, 549, 713, 494, 460, 549, 549, 549, 549, 1000, 603, 1000, 658,
				 823, 686, 795, 987, 768, 768, 823, 768, 768, 713, 713, 713, 713, 713, 713, 713,
				 768, 713, 790, 790, 890, 823, 549, 250, 713, 603, 603, 1042, 987, 603, 987, 603,
				 494, 329, 790, 790, 786, 713, 384, 384, 384, 384, 384, 384, 494, 494, 494, 494,
				 0, 329, 274, 686, 686, 686, 384, 384, 384, 384, 384, 384, 494, 494, 494, 0
				 )
			),
		array
			(
			"/BaseFont" => "/Times-Roman",
			"/Widths" => array
				(
				 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250,
				 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250,
				 250, 333, 408, 500, 500, 833, 778, 180, 333, 333, 500, 564, 250, 333, 250, 278,
				 500, 500, 500, 500, 500, 500, 500, 500, 500, 500, 278, 278, 564, 564, 564, 444,
				 921, 722, 667, 667, 722, 611, 556, 722, 722, 333, 389, 722, 611, 889, 722, 722,
				 556, 722, 667, 556, 611, 722, 722, 944, 722, 722, 611, 333, 278, 333, 469, 500,
				 333, 444, 500, 444, 500, 444, 333, 500, 500, 278, 278, 500, 278, 778, 500, 500,
				 500, 500, 333, 389, 278, 500, 500, 722, 500, 500, 444, 480, 200, 480, 541, 350,
				 500, 350, 333, 500, 444, 1000, 500, 500, 333, 1000, 556, 333, 889, 350, 611, 350,
				 350, 333, 333, 444, 444, 350, 500, 1000, 333, 980, 389, 333, 722, 350, 444, 722,
				 250, 333, 500, 500, 500, 500, 200, 500, 333, 760, 276, 500, 564, 333, 760, 333,
				 400, 564, 300, 300, 333, 500, 453, 250, 333, 300, 310, 500, 750, 750, 750, 444,
				 722, 722, 722, 722, 722, 722, 889, 667, 611, 611, 611, 611, 333, 333, 333, 333,
				 722, 722, 722, 722, 722, 722, 722, 564, 722, 722, 722, 722, 722, 722, 556, 500,
				 444, 444, 444, 444, 444, 444, 667, 444, 444, 444, 444, 444, 278, 278, 278, 278,
				 500, 500, 500, 500, 500, 500, 500, 564, 500, 500, 500, 500, 500, 500, 500, 500
				)
			),
		array
			(
			"/BaseFont" => "/Times-Bold",
			"/Widths" => array
				(
				 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250,
				 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250,
				 250, 333, 555, 500, 500, 1000, 833, 278, 333, 333, 500, 570, 250, 333, 250, 278,
				 500, 500, 500, 500, 500, 500, 500, 500, 500, 500, 333, 333, 570, 570, 570, 500,
				 930, 722, 667, 722, 722, 667, 611, 778, 778, 389, 500, 778, 667, 944, 722, 778,
				 611, 778, 722, 556, 667, 722, 722, 1000, 722, 722, 667, 333, 278, 333, 581, 500,
				 333, 500, 556, 444, 556, 444, 333, 500, 556, 278, 333, 556, 278, 833, 556, 500,
				 556, 556, 444, 389, 333, 556, 500, 722, 500, 500, 444, 394, 220, 394, 520, 350,
				 500, 350, 333, 500, 500, 1000, 500, 500, 333, 1000, 556, 333, 1000, 350, 667, 350,
				 350, 333, 333, 500, 500, 350, 500, 1000, 333, 1000, 389, 333, 722, 350, 444, 722,
				 250, 333, 500, 500, 500, 500, 220, 500, 333, 747, 300, 500, 570, 333, 747, 333,
				 400, 570, 300, 300, 333, 556, 540, 250, 333, 300, 330, 500, 750, 750, 750, 500,
				 722, 722, 722, 722, 722, 722, 1000, 722, 667, 667, 667, 667, 389, 389, 389, 389,
				 722, 722, 778, 778, 778, 778, 778, 570, 778, 722, 722, 722, 722, 722, 611, 556,
				 500, 500, 500, 500, 500, 500, 722, 444, 444, 444, 444, 444, 278, 278, 278, 278,
				 500, 556, 500, 500, 500, 500, 500, 570, 500, 556, 556, 556, 556, 500, 556, 500
				)
			),
		array
			(
			"/BaseFont" => "/Times-BoldOblique",
			"/Widths" => array
				(
				 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250,
				 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250,
				 250, 389, 555, 500, 500, 833, 778, 278, 333, 333, 500, 570, 250, 333, 250, 278,
				 500, 500, 500, 500, 500, 500, 500, 500, 500, 500, 333, 333, 570, 570, 570, 500,
				 832, 667, 667, 667, 722, 667, 667, 722, 778, 389, 500, 667, 611, 889, 722, 722,
				 611, 722, 667, 556, 611, 722, 667, 889, 667, 611, 611, 333, 278, 333, 570, 500,
				 333, 500, 500, 444, 500, 444, 333, 500, 556, 278, 278, 500, 278, 778, 556, 500,
				 500, 500, 389, 389, 278, 556, 444, 667, 500, 444, 389, 348, 220, 348, 570, 350,
				 500, 350, 333, 500, 500, 1000, 500, 500, 333, 1000, 556, 333, 944, 350, 611, 350,
				 350, 333, 333, 500, 500, 350, 500, 1000, 333, 1000, 389, 333, 722, 350, 389, 611,
				 250, 389, 500, 500, 500, 500, 220, 500, 333, 747, 266, 500, 606, 333, 747, 333,
				 400, 570, 300, 300, 333, 576, 500, 250, 333, 300, 300, 500, 750, 750, 750, 500,
				 667, 667, 667, 667, 667, 667, 944, 667, 667, 667, 667, 667, 389, 389, 389, 389,
				 722, 722, 722, 722, 722, 722, 722, 570, 722, 722, 722, 722, 722, 611, 611, 500,
				 500, 500, 500, 500, 500, 500, 722, 444, 444, 444, 444, 444, 278, 278, 278, 278,
				 500, 556, 500, 500, 500, 500, 500, 570, 500, 556, 556, 556, 556, 444, 500, 444
				)
			),
		array
			(
			"/BaseFont" => "/Times-Oblique",
			"/Widths" => array
				(
				 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250,
				 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250, 250,
				 250, 333, 420, 500, 500, 833, 778, 214, 333, 333, 500, 675, 250, 333, 250, 278,
				 500, 500, 500, 500, 500, 500, 500, 500, 500, 500, 333, 333, 675, 675, 675, 500,
				 920, 611, 611, 667, 722, 611, 611, 722, 722, 333, 444, 667, 556, 833, 667, 722,
				 611, 722, 611, 500, 556, 722, 611, 833, 611, 556, 556, 389, 278, 389, 422, 500,
				 333, 500, 500, 444, 500, 444, 278, 500, 500, 278, 278, 444, 278, 722, 500, 500,
				 500, 500, 389, 389, 278, 500, 444, 667, 444, 444, 389, 400, 275, 400, 541, 350,
				 500, 350, 333, 500, 556, 889, 500, 500, 333, 1000, 500, 333, 944, 350, 556, 350,
				 350, 333, 333, 556, 556, 350, 500, 889, 333, 980, 389, 333, 667, 350, 389, 556,
				 250, 389, 500, 500, 500, 500, 275, 500, 333, 760, 276, 500, 675, 333, 760, 333,
				 400, 675, 300, 300, 333, 500, 523, 250, 333, 300, 310, 500, 750, 750, 750, 500,
				 611, 611, 611, 611, 611, 611, 889, 667, 611, 611, 611, 611, 333, 333, 333, 333,
				 722, 667, 722, 722, 722, 722, 722, 675, 722, 722, 722, 722, 722, 556, 611, 500,
				 500, 500, 500, 500, 500, 500, 667, 444, 444, 444, 444, 444, 278, 278, 278, 278,
				 500, 500, 500, 500, 500, 500, 500, 675, 500, 500, 500, 500, 500, 444, 500, 444
				)
			),
		array
			(
			"/BaseFont" => "/ZapfDingbats",
			"/Widths" => array
				(
				 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
				 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
				 278, 974, 961, 974, 980, 719, 789, 790, 791, 690, 960, 939, 549, 855, 911, 933,
				 911, 945, 974, 755, 846, 762, 761, 571, 677, 763, 760, 759, 754, 494, 552, 537,
				 577, 692, 786, 788, 788, 790, 793, 794, 816, 823, 789, 841, 823, 833, 816, 831,
				 923, 744, 723, 749, 790, 792, 695, 776, 768, 792, 759, 707, 708, 682, 701, 826,
				 815, 789, 789, 707, 687, 696, 689, 786, 787, 713, 791, 785, 791, 873, 761, 762,
				 762, 759, 759, 892, 892, 788, 784, 438, 138, 277, 415, 392, 392, 668, 668, 0,
				 390, 390, 317, 317, 276, 276, 509, 509, 410, 410, 234, 234, 334, 334, 0, 0,
				 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
				 0, 732, 544, 544, 910, 667, 760, 760, 776, 595, 694, 626, 788, 788, 788, 788,
				 788, 788, 788, 788, 788, 788, 788, 788, 788, 788, 788, 788, 788, 788, 788, 788,
				 788, 788, 788, 788, 788, 788, 788, 788, 788, 788, 788, 788, 788, 788, 788, 788,
				 788, 788, 788, 788, 894, 838, 1016, 458, 748, 924, 748, 918, 927, 928, 928, 834,
				 873, 828, 924, 924, 917, 930, 931, 463, 883, 836, 836, 867, 867, 696, 696, 874,
				 0, 874, 760, 946, 771, 865, 771, 888, 967, 888, 831, 873, 927, 970, 918, 0
				)
			)
		);

	return($retval);
	}

################################################################################
# _pdf_filter_ascii85_decode ( string $value ) : string
################################################################################

function _pdf_filter_ascii85_decode($value)
	{
	$return = "";

	$base = array();

	foreach(range(0, 4) as $i)
		$base[$i] = pow(85, $i);

	foreach(str_split($value, 5) as $tuple)
		{
		if($tuple === "zzzzz")
			{
			$return .= str_repeat(chr(0), 4);

			continue;
			}

		$bin_tuple = "0";

		$len = strlen($tuple);

		$tuple .= str_repeat("u", 5 - $len);

		foreach(range(0, 4) as $i)
			$bin_tuple += ((ord($tuple[$i]) - 33) * $base[4 - $i]);

		$i = 4;

		$tuple = "";

		$len -= 1;

		while($len --)
			$tuple .= chr((bindec(sprintf("%032b", $bin_tuple)) >> (-- $i * 8)) & 0xFF);

		$return .= $tuple;
		}

	return($return);
	}

################################################################################
# _pdf_filter_ascii85_encode ( string $value ) : string
################################################################################

function _pdf_filter_ascii85_encode($string)
	{
	$return = "";

	foreach(str_split($string, 4) as $tuple)
		{
		$binary = 0;

		for($i = 0; $i < strlen($tuple); $i ++)
			$binary |= (ord($tuple[$i]) << ((3 - $i) * 8));

		$tuple = "";

		foreach(range(0, 4) as $i)
			{
			$tuple = chr($binary % 85 + 33) . $tuple;

			$binary /= 85;
			}

		$return .= substr($tuple, 0, strlen($tuple) + 1);;
		}

	return($return);
	}

################################################################################
# _pdf_filter_asciihex_decode ( string $value ) : string
################################################################################

function _pdf_filter_asciihex_decode($data)
	{
	return(hex2bin($data));
	}

################################################################################
# _pdf_filter_asciihex_encode ( string $value ) : string
################################################################################

function _pdf_filter_asciihex_encode($data)
	{
	return(bin2hex($data));
	}

################################################################################
# _pdf_filter_change ( array $pdf , string $filter ) : array
################################################################################

function _pdf_filter_change(& $pdf, $filter = "")
	{
	foreach($pdf["objects"] as $index => $object)
		{
		if($index == 0) # trailer
			continue;

		if(isset($object["stream"]) === false)
			continue;

		if(isset($object["dictionary"]["/Filter"]))
			list($filter_old, $null) = _pdf_filter_parse($object["dictionary"]["/Filter"]);
		else
			$filter_old = array();

		$data = $object["stream"];

		while(1)
			{
			if(count($filter_old) == 0)
				break;

			if($filter_old[0] == "/ASCII85Decode")
				$data = _pdf_filter_ascii85_decode($data);

			if($filter_old[0] == "/ASCIIHexDecode")
				$data = _pdf_filter_asciihex_decode($data);

			if($filter_old[0] == "/DCTDecode")
				break; # image

			if($filter_old[0] == "/FlateDecode")
				$data = _pdf_filter_flate_decode($data);

			if($filter_old[0] == "/LZWDecode")
				$data = _pdf_filter_lzw_decode($data);

			$filter_old = array_slice($filter_old, 1);
			}

		$pdf["objects"][$index]["stream"] = $data;
		$pdf["objects"][$index]["dictionary"]["/Length"] = strlen($data);

		if(count($filter_old) == 0)
			unset($pdf["objects"][$index]["dictionary"]["/Filter"]);
		elseif(count($filter_old) == 1)
			$pdf["objects"][$index]["dictionary"]["/Filter"] = sprintf("%s", _pdf_glue_array($filter_old));
		else
			$pdf["objects"][$index]["dictionary"]["/Filter"] = sprintf("[%s]", _pdf_glue_array($filter_old));
		}
	
	################################################################################

	foreach($pdf["objects"] as $index => $object)
		{
		if($index == 0) # trailer
			continue;

		if(isset($object["stream"]) === false)
			continue;

		if(isset($object["dictionary"]["/Filter"]))
			list($filter_old, $null) = _pdf_filter_parse($object["dictionary"]["/Filter"]);
		else
			$filter_old = array();

		list($filter_new, $null) = _pdf_filter_parse($filter);

		$filter_new = array_reverse($filter_new);

		$data = $object["stream"];

		while(1)
			{
			if(count($filter_new) == 0)
				break;

			if($filter_new[0] == "/ASCII85Decode")
				$data = _pdf_filter_ascii85_encode($pdf["objects"][$index]["stream"]);

			if($filter_new[0] == "/ASCIIHexDecode")
				$data = _pdf_filter_asciihex_encode($data);

			if($filter_new[0] == "/FlateDecode")
				$data = _pdf_filter_flate_encode($data);

			if($filter_new[0] == "/LZWDecode")
				$data = _pdf_filter_lzw_encode($data);

			$filter_old = array_merge(array($filter_new[0]), $filter_old);

			$filter_new = array_slice($filter_new, 1);
			}

		$pdf["objects"][$index]["stream"] = $data;
		$pdf["objects"][$index]["dictionary"]["/Length"] = strlen($data);

		if(count($filter_old) == 0)
			unset($pdf["objects"][$index]["dictionary"]["/Filter"]);
		elseif(count($filter_old) == 1)
			$pdf["objects"][$index]["dictionary"]["/Filter"] = sprintf("%s", _pdf_glue_array($filter_old));
		else
			$pdf["objects"][$index]["dictionary"]["/Filter"] = sprintf("[%s]", _pdf_glue_array($filter_old));
		}

	return(true);
	}

################################################################################
# _pdf_filter_flate_encode ( string $value ) : string
################################################################################

function _pdf_filter_flate_encode($data)
	{
	return(gzcompress($data, 9));
	}

################################################################################
# _pdf_filter_flate_decode ( string $value ) : string
################################################################################

function _pdf_filter_flate_decode($data)
	{
	return(gzuncompress($data));
	}

################################################################################
# _pdf_filter_lzw_decode ( string $value ) : string
################################################################################

function _pdf_filter_lzw_decode($binary)
	{
	$dictionary_count = 256;
	$bits = 8;
	$codes = array();
	$rest = 0;
	$rest_length = 0;

	for($i = 0; $i < strlen($binary); $i ++)
		{
		$rest = ($rest << 8) + ord($binary[$i]);
		$rest_length += 8;

		if($rest_length >= $bits)
			{
			$rest_length -= $bits;
			$codes[] = $rest >> $rest_length;
			$rest = $rest & ((1 << $rest_length) - 1);
			$dictionary_count += 1;

			if($dictionary_count >> $bits)
				$bits += 1;
			}
		}

	$dictionary = range("\x00", "\xFF");
	$return = "";

	foreach($codes as $i => $code)
		{
		if(isset($dictionary[$code]) === false)
			$element = $word . $word[0];
		else
			$element = $dictionary[$code];

		$return = $return . $element;

		if($i > 0)
			$dictionary[] = $word . $element[0];

		$word = $element;
		}

	return($return);
	}

################################################################################
# _pdf_filter_lzw_encode ( string $value ) : string
################################################################################

function _pdf_filter_lzw_encode($string)
	{
	$dictionary = array_flip(range("\x00", "\xFF"));
	$word = "";
	$codes = array();

	for($i = 0; $i <= strlen($string); $i = $i +1)
		{
		$x = substr($string, $i, 1);

		if(strlen($x) > 0 && isset($dictionary[$word . $x]) === true)
			$word = $word . $x;
		elseif($i > 0)
			{
			$codes[] = $dictionary[$word];
			$dictionary[$word . $x] = count($dictionary);
			$word = $x;
			}
		}

	$dictionary_count = 256;
	$bits = 8;
	$return = "";
	$rest = 0;
	$rest_length = 0;

	foreach($codes as $code)
		{
		$rest = ($rest << $bits) + $code;
		$rest_length += $bits;
		$dictionary_count += 1;

		if($dictionary_count >> $bits)
			$bits += 1;

		while($rest_length > 7)
			{
			$rest_length -= 8;
			$return .= chr($rest >> $rest_length);
			$rest &= ((1 << $rest_length) - 1);
			}
		}

	return($return . ($rest_length > 0 ? chr($rest << (8 - $rest_length)) : ""));
	}

################################################################################
# _pdf_filter_parse ( string $data ) : array
# this function is needed because user can setup /Filter for final writing
################################################################################

function _pdf_filter_parse($data = "")
	{
	$retval = array();

	while(1)
		{
		if(strlen($data) == 0)
			break;
		elseif($data[0] == " ")
			$data = substr($data, 1);
		elseif($data[0] == "[")
			{
			$data = substr($data, 1);

			list($retval, $data) = _pdf_parse_array($data);

			$data = substr($data, 1);
			}
		elseif($data[0] == "]")
			break;
		elseif($data[0] == "/")
			{
			$data = substr($data, 1);

			list($name, $data) = _pdf_parse_name($data);

			$retval[] = sprintf("/%s", $name);
			}
		else
			die("_pdf_filter_parse: you should never be here: data follows: " . $data);
		}

	return(array($retval, $data));
	}

################################################################################
# _pdf_filter_rle_decode ( string $value ) : string
################################################################################

function _pdf_filter_rle_decode($data)
	{
	return(preg_replace_callback('/(\d+)(\D)/', function($match) { return(str_repeat($match[2], $match[1])); }, $data));
	}

################################################################################
# _pdf_filter_rle_encode ( string $value ) : string
################################################################################

function _pdf_filter_rle_encode($data)
	{
	return(preg_replace_callback('/(.)\1*/', function($match) { return (strlen($match[0]) . $match[1]); }, $data));
	}

################################################################################
# _pdf_get_free_font_id ( array $pdf ) : int
################################################################################

function _pdf_get_free_font_id(& $pdf, $id = 1)
	{
	if(isset($pdf["loaded-resources"]["/Font"]))
		while(isset($pdf["loaded-resources"]["/Font"][$id]))
			$id ++;

	return($id);
	}

################################################################################
# _pdf_get_free_object_id ( array $pdf ) : int
################################################################################

function _pdf_get_free_object_id(& $pdf, $id = 1)
	{
	if(isset($pdf["objects"]))
		while(isset($pdf["objects"][$id]))
			$id ++;

	return($id);
	}

################################################################################
# _pdf_get_free_xobject_id ( array $pdf ) : int
################################################################################

function _pdf_get_free_xobject_id(& $pdf, $id = 1)
	{
	if(isset($pdf["loaded-resources"]["/XObject"]))
		while(isset($pdf["loaded-resources"]["/XObject"][$id]))
			$id ++;

	return($id);
	}

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

################################################################################
# _pdf_glue_array ( array $array ) : string
# returns $array as string.
################################################################################

function _pdf_glue_array($array, $optional = true)
	{
	$retval = array();

	foreach($array as $value)
		{
		if($optional)
			if(is_array($value))
				$value = sprintf("[%s]", _pdf_glue_array($value));

		$retval[] = sprintf("%s", $value);
		}

	return(implode(" ", $retval));
	}

################################################################################
# _pdf_glue_dictionary ( array $dictionary ) : string
# returns $dictionary as string.
################################################################################

function _pdf_glue_dictionary($dictionary, $optional = true)
	{
	$retval = array();

	foreach($dictionary as $key => $value)
		{
		if($optional)
			if(is_array($value))
				$value = sprintf("<< %s >>", _pdf_glue_dictionary($value));

		$retval[] = sprintf("%s %s", $key, $value);
		}

	return(implode(" ", $retval));
	}

################################################################################
# _pdf_glue_document ( array $objects ) : string
# returns $objects as string (pdf-format).
################################################################################

function _pdf_glue_document($objects, $optional = true)
	{
	################################################################################
	# fix count
	################################################################################

	$objects[0]["dictionary"]["/Size"] = count($objects); # inclusive null-object

	################################################################################
	# header
	################################################################################

	$retval = array("%PDF-1.5");

	################################################################################
	# body
	################################################################################

	$offsets = array();

	foreach($objects as $index => $object)
		{
		if($index == 0) # trailer
			continue;

		$offsets[$index] = strlen(implode("\n", $retval)) + 1; # +EOL

		$retval[] = _pdf_glue_object($object);
		}

	################################################################################
	# cross-reference table
	################################################################################

	$startxref = strlen(implode("\n", $retval)) + 1; # +EOL
	$trailer = $objects[0]["dictionary"];

	if($optional)
		ksort($objects);

	$count = 0;
	$start = 0;

	$retval[] = sprintf("xref");

	foreach($objects as $index => $object)
		{
		if($count == 0)
			$start = $index;

		$count++;

		if(isset($objects[$index + 1]))
			continue;

		$retval[] = sprintf("%d %d", $start, $count);

		foreach(range($start, $start + $count - 1) as $id)
			if($id == 0)
				$retval[] = sprintf("%010d %05d %s", 0, 65535, "f");
			else
				$retval[] = sprintf("%010d %05d %s", $offsets[$id], $objects[$id]["version"], "n");

		$count = 0;
		}

	################################################################################
	# trailer
	################################################################################

	$retval[] = "trailer";
	$retval[] = sprintf("<< %s >>", _pdf_glue_dictionary($trailer));

	$retval[] = "startxref";
	$retval[] = $startxref;

	$retval[] = "%%EOF";

	################################################################################
	# final pdf file
	################################################################################

	return(implode("\n", $retval));
	}

################################################################################
# _pdf_glue_object ( array $object ) : string
# returns $object as string (obj-format).
################################################################################

function _pdf_glue_object($object)
	{
	$retval = array();

	$retval[] = sprintf("%d %d obj", $object["id"], $object["version"]);

		if(isset($object["dictionary"]))
			$retval[] = sprintf("<< %s >>", _pdf_glue_dictionary($object["dictionary"]));

		if(isset($object["stream"]))
			$retval[] = sprintf("stream\n%s\nendstream", $object["stream"]);

		if(isset($object["value"]))
			$retval[] = $object["value"];

	$retval[] = "endobj";

	return(implode("\n", $retval));
	}

################################################################################
# _pdf_glue_string ( string $value ) : string
# returns $value as string (escaped-format).
################################################################################

function _pdf_glue_string($value)
	{
	$value = utf8_decode($value);

	$value = str_replace(array("\\", "(", ")"), array("\\\\", "\(", "\)"), $value);

	return($value);
	}

################################################################################
# _pdf_load_includes ( string $path ) : bool
################################################################################

function _pdf_load_includes($path)
	{
	foreach(glob($path . "/*.php") as $file)
		include_once($file);

	return(true);
	}

################################################################################
# _pdf_parse_array ( string $data ) : array
# returns array of found element as array and unparsed data as string.
################################################################################

function _pdf_parse_array($data)
	{
	$retval = array();

	while(1)
		{
		if(strlen($data) == 0)
			die("_pdf_parse_array: process runs out of data.");
		elseif(in_array($data[0], array("\t", "\n", "\r", " ")))
			$data = substr($data, 1);
		elseif($data[0] == "(")
			{
			$data = substr($data, 1);

			list($value, $data) = _pdf_parse_string($data);

			$data = substr($data, 1);

			$retval[] = sprintf("(%s)", $value);
			}
		elseif($data[0] == "/")
			{
			$data = substr($data, 1);

			list($value, $data) = _pdf_parse_name($data);

			$retval[] = sprintf("/%s", $value);
			}
		elseif(substr($data, 0, 2) == "<<")
			{
			$data = substr($data, 2);

			list($value, $data) = _pdf_parse_dictionary($data);

			$data = substr($data, 2);

			$retval[] = sprintf("<< %s >>", _pdf_glue_dictionary($value));
			}
		elseif($data[0] == "<")
			{
			$data = substr($data, 1);

			list($value, $data) = _pdf_parse_hex($data);

			$data = substr($data, 1);

			$retval[] = sprintf("<%s>", $value);
			}
		elseif($data[0] == "[")
			{
			$data = substr($data, 1);

			list($value, $data) = _pdf_parse_array($data);

			$data = substr($data, 1);

			$retval[] = sprintf("[%s]", _pdf_glue_array($value));
			}
		elseif($data[0] == "]")
			break;
		elseif(substr($data, 0, 5) == "false")
			{
			$data = substr($data, 5);

			list($value, $data) = array("false", $data);

			$retval[] = $value;
			}
		elseif(substr($data, 0, 4) == "true")
			{
			$data = substr($data, 4);

			list($value, $data) = array("true", $data);

			$retval[] = $value;
			}
		elseif(preg_match("/^(\d+ \d+ R)(.*)/is", $data, $matches) == 1)
			{
			list($null, $value, $data) = $matches;

			$retval[] = $value;
			}
		else
			{
			list($value, $data) = _pdf_parse_numeric($data);

			$retval[] = $value;
			}
		}

	return(array($retval, $data));
	}

################################################################################
# _pdf_parse_comment ( string $data ) : array
# returns array of found element as string and unparsed data as string.
################################################################################

function _pdf_parse_comment($data)
	{
	$retval = "";

	while(1)
		{
		if(strlen($data) == 0)
			break;
		elseif(in_array($data[0], array("\n", "\r")))
			break;
		elseif($data[0] == "\\")
			{
			$retval .= $data[0];

			$data = substr($data, 1);
			}

		$retval .= $data[0];

		$data = substr($data, 1);
		}

	return(array($retval, $data));
	}

################################################################################
# _pdf_parse_dictionary ( string $data ) : array
# returns array of found element as array and unparsed data as string.
################################################################################

function _pdf_parse_dictionary($data)
	{
	$retval = array();

	$loop = 0;

	while(1)
		{
		if(strlen($data) == 0)
			break;
		elseif(in_array($data[0], array("\t", "\n", "\r", " ")))
			$data = substr($data, 1);
		elseif(substr($data, 0, 2) == ">>")
			break;
		else
			{
			$key = "";

			while(1)
				{
				if(strlen($data) == 0)
					die("_pdf_parse_dictionary: process runs out of data (key).");
				elseif(in_array($data[0], array("\t", "\n", "\r", " ")))
					$data = substr($data, 1);
				elseif(in_array($data[0], array("(", "<", "[", "f", "t")))
					die("_pdf_parse_dictionary: no other char than / allowed for key. data follows: " . $data);
				elseif($data[0] == "/")
					{
					$data = substr($data, 1);

					list($value, $data) = _pdf_parse_name($data);

					$key = sprintf("/%s", $value);

					break;
					}
				else
					die("_pdf_parse_dictionary: no other char than / allowed for key. data follows: " . $data);
				}

			$value = "";

			while(1)
				{
				if(strlen($data) == 0)
					die("_pdf_parse_dictionary: process runs out of data (value).");
				elseif(in_array($data[0], array("\t", "\n", "\r", " ")))
					$data = substr($data, 1);
				elseif($data[0] == "(")
					{
					$data = substr($data, 1);

					list($value, $data) = _pdf_parse_string($data);

					$data = substr($data, 1);

					$value = sprintf("(%s)", $value);

					break;
					}
				elseif($data[0] == "/")
					{
					$data = substr($data, 1);

					list($value, $data) = _pdf_parse_name($data);

					$value = sprintf("/%s", $value);

					break;
					}
				elseif(substr($data, 0, 2) == "<<")
					{
					$data = substr($data, 2);

					list($value, $data) = _pdf_parse_dictionary($data);

					$data = substr($data, 2);

					$value = sprintf("<< %s >>", _pdf_glue_dictionary($value));

					break;
					}
				elseif($data[0] == "<")
					{
					$data = substr($data, 1);

					list($value, $data) = _pdf_parse_hex($data);

					$data = substr($data, 1);

					$value = sprintf("<%s>", $value);

					break;
					}
				elseif($data[0] == "[")
					{
					$data = substr($data, 1);

					list($value, $data) = _pdf_parse_array($data);

					$data = substr($data, 1);

					$value = sprintf("[%s]", _pdf_glue_array($value));

					break;
					}
				elseif(substr($data, 0, 5) == "false")
					{
					$data = substr($data, 5);

					list($value, $data) = array("false", $data);

					break;
					}
				elseif(substr($data, 0, 4) == "true")
					{
					$data = substr($data, 4);

					list($value, $data) = array("true", $data);

					break;
					}
				elseif(preg_match("/^(\d+ \d+ R)(.*)/is", $data, $matches) == 1)
					{
					list($null, $value, $data) = $matches;

					break;
					}
				else
					{
					list($value, $data) = _pdf_parse_numeric($data);

					break;
					}
				}

			$retval[$key] = $value;
			}

		$loop ++;

		if($loop > 1024)
			die("_pdf_parse_dictionary: process stuck on data " . $data);
		}

	return(array($retval, $data));
	}

################################################################################
# _pdf_parse_document ( string $data ) : array
# returns array of found element as array and unparsed data as warning.
################################################################################

function _pdf_parse_document($data)
	{
	$retval = array();

	if(preg_match("/^%PDF-(\d+)\.(\d+)[\s|\n]+(.*)[\s|\n]+startxref[\s|\n]+(\d+)[\s|\n]+%%EOF(.*)/is", $data, $matches) == 0)
		die("_pdf_parse_document: something is seriously wrong (invalid structure).");

	list($null, $major, $minor, $body, $startxref, $null) = $matches;

	################################################################################
	# pdf_parse_xref ( string $data ) : array
	# returns offsets from $data as string.
	# pdf got differentformats of xref
	################################################################################

	$offsets = array();

	$table = substr($data, $startxref);

	while(1)
		{
		if(strlen($table) == 0)
			break;
		elseif(in_array($table[0], array("\t", "\n", "\r", " ")))
			$table = substr($table, 1);
		elseif(substr($table, 0, 4) == "xref")
			{
			$table = substr($table, 4);

			# start
			while(1)
				{
				if(strlen($table) == 0)
					break;
				elseif(in_array($table[0], array("\t", "\n", "\r", " ")))
					$table = substr($table, 1);
				else
					{
					list($first, $table) = _pdf_parse_numeric($table);

					break;
					}
				}

			# count
			while(1)
				{
				if(strlen($table) == 0)
					break;
				elseif(in_array($table[0], array("\t", "\n", "\r", " ")))
					$table = substr($table, 1);
				else
					{
					list($count, $table) = _pdf_parse_numeric($table);

					break;
					}
				}

			foreach(range($first, $first + $count - 1) as $index)
				{
				# offset
				while(1)
					{
					if(strlen($table) == 0)
						break;
					elseif(in_array($table[0], array("\t", "\n", "\r", " ")))
						$table = substr($table, 1);
					else
						{
						list($offset, $table) = _pdf_parse_numeric($table);

						break;
						}
					}

				# generation
				while(1)
					{
					if(strlen($table) == 0)
						break;
					elseif(in_array($table[0], array("\t", "\n", "\r", " ")))
						$table = substr($table, 1);
					else
						{
						list($generation, $table) = _pdf_parse_numeric($table);

						break;
						}
					}

				# used
				while(1)
					{
					if(strlen($table) == 0)
						break;
					elseif(in_array($table[0], array("\t", "\n", "\r", " ")))
						$table = substr($table, 1);
					else
						{
						list($used, $table) = _pdf_parse_name($table);

						break;
						}
					}

				if($used == "n")
					$offsets[$index] = $offset; # _pdf_parse_object($data)
				}
			}
		elseif(substr($table, 0, 7) == "trailer")
			{			
			$table = substr($table, 7);

			while(1)
				{
				if(strlen($table) == 0)
					break;
				elseif(in_array($table[0], array("\t", "\n", "\r", " ")))
					$table = substr($table, 1);
				elseif(substr($table, 0, 2) == "<<")
					{
					$table = substr($table, 2);

					list($trailer, $table) = _pdf_parse_dictionary($table);

					$table = substr($table, 2);

					break;
					}
				}

			if(isset($retval[0]["dictionary"]) === false)
				$retval[0]["dictionary"] = $trailer;
			}
		elseif(substr($table, 0, 9) == "startxref")
			{
			if(isset($retval[0]["dictionary"]["/Prev"]))
				{
				$startxref = $retval[0]["dictionary"]["/Prev"];

				unset($retval[0]["dictionary"]["/Prev"]);

				$table = substr($data, $startxref);
				}
			}
		elseif(substr($table, 0, 5) == "%%EOF")
			break;
		else
			{
			$pattern = array
				(
				"\d+[\s|\n]+\d+[\s|\n]+obj[\s|\n]+.*?[\s|\n]+endobj",
				"xref[\s|\n]+.*",
				"trailer[\s|\n]+.*",
				"startxref[\s|\n]+\d+[\s|\n]+"
				);

			if(preg_match_all("/(" . implode("|", $pattern) . ")/is", $data, $matches) == 0)
				die("_pdf_parse_document: ...");

			foreach($matches[0] as $object)
				{
				if(substr($object, 0, 7) == "trailer")
					{
					$object = substr($object, 7);

					while(1)
						{
						if(strlen($object) == 0)
							break;
						elseif(in_array($object[0], array("\t", "\n", "\r", " ")))
							$object = substr($object, 1);
						elseif(substr($object, 0, 2) == "<<")
							{
							$object = substr($object, 2);

							list($retval[0]["dictionary"], $object) = _pdf_parse_dictionary($object);

							$object = substr($object, 2);

							break;
							}
						}

					continue;
					}

				if(substr($object, 0, 9) == "startxref")
					continue;

				list($k, $null) = _pdf_parse_object($object);

				$id = $k["id"];

				$retval[$id] = $k;
				}

			ksort($retval);

			return($retval);
			}
		}

#	print_r($retval);exit;

	################################################################################
	# get objects by offset
	################################################################################

	foreach($offsets as $index => $offset_start)
		{
		$offset_stop = $startxref;

		foreach($offsets as $offset_test)
			{
			if($offset_test >= $offset_stop)
				continue;

			if($offset_test <= $offset_start)
				continue;

			$offset_stop = $offset_test;
			}

		$help = substr($data, $offset_start, $offset_stop - $offset_start - 1);

		list($value, $null) = _pdf_parse_object($help);

		if($value["id"] != $index)
			die("_pdf_parse_document: something is seriously wrong (invalid id).");

		$retval[$index]= $value;
		}

	################################################################################

	return($retval);
	}

################################################################################
# _pdf_parse_hex ( string $data ) : array
# returns array of found element as string and unparsed data as string.
################################################################################

function _pdf_parse_hex($data)
	{
	$retval = "";

	while(1)
		{
		if(strlen($data) == 0)
			die("_pdf_parse_hex: process runs out of data.");
		elseif($data[0] == ">")
			break;

		$retval .= $data[0];

		$data = substr($data, 1);
		}

	return(array($retval, $data));
	}

################################################################################
# _pdf_parse_name ( string $data ) : array
# returns array of found element as string and unparsed data as string.
################################################################################

function _pdf_parse_name($data)
	{
	$retval = "";

	while(1)
		{
		if(strlen($data) == 0)
			break;
		elseif(in_array($data[0], array("\t", "\n", "\r", " ", "(", "/", "<", ">", "[", "]")))
			break;

		$retval .= $data[0];

		$data = substr($data, 1);
		}

	return(array($retval, $data));
	}

################################################################################
# _pdf_parse_numeric ( string $data ) : array
# returns array of found element as string and unparsed data as string.
################################################################################

function _pdf_parse_numeric($data)
	{
	$retval = "";

	while(1)
		{
		if(strlen($data) == 0)
			die("_pdf_parse_numeric: process runs out of data.");
		elseif(in_array($data[0], array("\t", "\n", "\r", " ", "(", "/", "<", ">", "[", "]", "f", "t")))
			break;

		$retval .= $data[0];

		$data = substr($data, 1);
		}

	return(array($retval, $data));
	}

################################################################################
# _pdf_parse_object ( string $data ) : array
# returns array of found element as array and unparsed data as string.
################################################################################

function _pdf_parse_object($data)
	{
	$retval = array();

	if(preg_match("/^(\d+)[\s|\n]+(\d+)[\s|\n]+obj[\s|\n]*(.+)[\s|\n]*endobj.*/is", $data, $matches) == 0)
		die("_pdf_parse_object: something is seriously wrong");

	list($null, $id, $version, $data) = $matches;

	$retval["id"] = $id;
	$retval["version"] = $version;

	$data = ltrim($data); # try to overcome this

 	if(substr($data, 0, 2) == "<<")
		{		
		$data = substr($data, 2);

		list($dictionary, $data) = _pdf_parse_dictionary($data);

		$data = substr($data, 2);

		$retval["dictionary"] = $dictionary;

		$data = ltrim($data); # try to overcome this

		if(preg_match("/^stream[\s|\n]+(.+)[\s|\n]+endstream.*/is", $data, $matches) == 1) # !!! fails on hex streams sometimes
			{
			list($null, $stream) = $matches; # data for value

			$retval["stream"] = $stream;
			}
		}
	elseif(preg_match("/^stream[\s|\n]+(.+)[\s|\n]+endstream.*/is", $data, $matches) == 1) # !!! fails on hex streams sometimes
		{
		list($null, $stream) = $matches; # data for value

		$retval["stream"] = $stream;
		}
	else
		$retval["value"] = $data;

	return(array($retval, ""));
	}

################################################################################
# _pdf_parse_string ( string $data ) : array
# returns array of found element as string and unparsed data as string.
################################################################################

function _pdf_parse_string($data)
	{
	$retval = "";

	while(1)
		{
		if(strlen($data) == 0)
			die("_pdf_parse_string: process runs out of data.");
		elseif($data[0] == ")")
			break;
		elseif($data[0] == "\\")
			{
			$retval .= $data[0];

			$data = substr($data, 1);
			}

		$retval .= $data[0];

		$data = substr($data, 1);
		}

	return(array($retval, $data));
	}
?>
