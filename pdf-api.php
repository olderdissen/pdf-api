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

	$pdf["objects"][$this_id] = array
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

	foreach($pdf["fonts"] as $index => $object)
		{
		if($object["/BaseFont"] != "/" . $fontname)
			continue;

		$retval = _pdf_add_font_core($pdf, $fontname, $encoding);

		if(sscanf($retval, "%d %d R", $retval_id, $retval_version) != 2)
			die("_pdf_add_font: invalid font.");
			
		# apply widths
		$pdf["objects"][$retval_id]["dictionary"]["/Widths"] = sprintf("[%s]", _pdf_glue_array($object["/Widths"]));

		return($retval);
		}

	################################################################################

#	$filename = "/home/nomatrix/externe_platte/daten/ttf/" . strtolower($fontname[0]) . "/" . $fontname . ".ttf";
	$filename = "/usr/share/fonts/truetype/freefont/" . $fontname . ".ttf";

	if(file_exists($filename) === false)
		return(_pdf_add_font($pdf["objects"], "Courier", $encoding));

	################################################################################

	$retval = _pdf_add_font_truetype($pdf, $filename, $encoding);

	if(sscanf($retval, "%d %d R", $retval_id, $retval_version) != 2)
		die("_pdf_add_font: invalid font.");

	$widths = array();

	foreach(range(0x20, 0xFF) as $char)
		$widths[chr($char)] = (($info = imagettfbbox(720, 0, $filename, chr($char))) ? $info[2] : 1000);

	# apply widths
	$pdf["objects"][$retval_id]["dictionary"]["/FirstChar"] = 0x20;
	$pdf["objects"][$retval_id]["dictionary"]["/LastChar"] = 0xFF;
	$pdf["objects"][$retval_id]["dictionary"]["/Widths"] = sprintf("[%s]", _pdf_glue_array($widths));

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
		$p = _pdf_add_stream($pdf, $plte_stream);

	################################################################################
	# smask
	################################################################################

	if($color_type >= 4)
		{
		$data_stream = gzuncompress($data_stream);

		$color_stream = "";
		$alpha_stream = "";

		if($color_type == 4)
			list($width_index, $width_char) = array(2 * $width, 1);
		else
			list($width_index, $width_char) = array(4 * $width, 3);

		for($height_index = 0; $height_index < $height; $height_index = $height_index + 1)
			{
			$temp_index = $height_index * ($width_index + 1);

			$alpha_stream .= $data_stream[$temp_index];
			$alpha_stream .= preg_replace("/.{" . $width_char . "}(.)/s", "$1", substr($data_stream, $temp_index + 1, $width_index));

			$color_stream .= $data_stream[$temp_index];
			$color_stream .= preg_replace("/(.{" . $width_char . "})./s", "$1", substr($data_stream, $temp_index + 1, $width_index));
			}

		$alpha_stream = gzcompress($alpha_stream);
		$data_stream = gzcompress($color_stream);

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

	$type = ($bits_per_component == 1 ? "/ImageB" : "/ImageC");

	if(isset($pdf["loaded-resources"]["/ProcSet"]) === false)
		$pdf["loaded-resources"]["/ProcSet"][] = $type;
	elseif(in_array($type, $pdf["loaded-resources"]["/ProcSet"]) === false)
		$pdf["loaded-resources"]["/ProcSet"][] = $type;

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
#	if($pdf["objects"][$parent_id]["dictionary"]["/Type"] == "/Outlines")
#		{
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
#		}
#	else
#		die("_pdf_add_outline: invalid type of parent.");

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
