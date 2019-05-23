<?
################################################################################
# copyright 2019 by Markus Olderdissen
# free for private use or inspiration.
# public use need written permission.
################################################################################

# this content will replace some content of pdf-api.php once ...

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
# _pdf_add_acroform ( array $pdf , string $parent ) : string
################################################################################

function _pdf_add_acroform(& $pdf, $parent)
	{
	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_acroform: invalid parent: " . $parent);

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Fields" => "[]"
			)
		);

	$pdf["objects"][$parent_id]["dictionary"]["/AcroForm"] = sprintf("%d 0 R", $this_id);

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_action ( array $pdf ) : string
################################################################################

function _pdf_add_action(& $pdf, $type, $optlist)
	{
	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

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

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	if($type == "FileAttachment")
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

	if($type == "Link")
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

	if($type == "Text")
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

	if($type == "Widget")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Annot",
				"/Subtype" => "/Widget",
				"/Rect" => $rect,
				"/FT" => "/Tx",
				"/V" => "(edit)",
				"/T" => "(javascript_name_a)",
				"/AP" => sprintf("<< /N %s >>", $optlist["form"])
				)
			);
		}

	################################################################################

	if(isset($pdf["objects"][$parent_id]["dictionary"]["/Annots"]))
		$data = $pdf["objects"][$parent_id]["dictionary"]["/Annots"];
	else
		$data = "[]";

	$data = substr($data, 1);
	list($annots, $data) = _pdf_parse_array($data); # pdf-api-parse.php
	$data = substr($data, 1);

	$annots[] = sprintf("%d 0 R", $this_id);

	$pdf["objects"][$parent_id]["dictionary"]["/Annots"] = sprintf("[%s]", _pdf_glue_array($annots)); # pdf-api-glue.php

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_page ( array $df ) : string
################################################################################

function _pdf_add_catalog(& $pdf)
	{
	if(isset($pdf["objects"][0]["dictionary"]["/Root"]))
		die("_pdf_add_catalog: catalog already exist.");

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

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

	$pdf["objects"][0]["dictionary"]["/Root"] = sprintf("%d 0 R", $this_id);

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_field ( array $pdf , string $parent , string $field ) : string
################################################################################

function _pdf_add_field(& $pdf, $parent, $field)
	{
	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_field: invalid parent: " . $parent);

	if(sscanf($field, "%d %d R", $field_id, $field_version) != 2)
		die("_pdf_add_field: invalid field: " . $field);

	if(isset($pdf["objects"][$parent_id]["dictionary"]["/Fields"]))
		$data = $pdf["objects"][$parent_id]["dictionary"]["/Fields"];
	else
		$data = "[]";

	$data = substr($data, 1);
	list($fields, $data) = _pdf_parse_array($data); # pdf-api-parse.php
	$data = substr($data, 1);
	
	$fields[] = $field;
	
	$pdf["objects"][$parent_id]["dictionary"]["/Fields"] = sprintf("[%s]", _pdf_glue_array($fields)); # pdf-api-glue.php
	}

################################################################################
# _pdf_add_font ( array $pdf , string $fontname , string $encoding ) : string
################################################################################

function _pdf_add_font(& $pdf, $fontname, $encoding = "builtin")
	{
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
	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

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

	$encodings = array("winansi" => "/WinAnsiEncoding", "macroman" => "/MacRomanEncoding", "macexpert" => "/MacExpertEncoding");

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

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

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

function _pdf_add_font_descriptor(& $pdf, $fontname, $fontfile)
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

	if($fontfile)
		$pdf["objects"][$this_id]["dictionary"]["/FontFile2"] = $fontfile;

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_font_encoding ( array $pdf , string $differences ) : string
################################################################################

function _pdf_add_font_encoding(& $pdf, $encoding = "builtin", $differences = "")
	{
	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

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

	# apply encodings
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
	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

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
	$fontname = basename($filename, ".ttf");

	$f = _pdf_add_font_file($pdf, $filename);

	$d = _pdf_add_font_descriptor($pdf, $fontname, $f);

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

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

	################################################################################

	$widths = array();

	foreach(range(0x20, 0xFF) as $char)
		$widths[chr($char)] = (($image_ttf_bbox = imagettfbbox(720, 0, $filename, chr($char))) ? $image_ttf_bbox[2] : 1000);

	$pdf["objects"][$this_id]["dictionary"]["/Widths"] = sprintf("[%s]", _pdf_glue_array($widths)); # pdf-api-glue.php

	################################################################################

	$encodings = array("winansi" => "/WinAnsiEncoding", "macroman" => "/MacRomanEncoding", "macexpert" => "/MacExpertEncoding");

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
	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

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

	$id = _pdf_get_free_xobject_id($pdf); # pdf-api-lib.php

	$pdf["resources"]["/XObject"][$id] = sprintf("%d 0 R", $this_id);

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

		unlink("lolo.jpg"); # don't change it. keep this name. don't make fun here. :)
		}

	return($retval);
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

################################################################################
# _pdf_add_info ( array $pdf , array $optlist ) : string
################################################################################

function _pdf_add_info(& $pdf, $optlist)
	{
	if(isset($pdf["objects"][0]["dictionary"]["/Info"]))
		die("_pdf_add_info: info already exist.");

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

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

	foreach($optlist as $key => $value)
		$pdf["objects"][$this_id]["dictionary"][$key] = $value;

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

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

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

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Title" => sprintf("(%s)", _pdf_glue_string($title)), # pdf-api-glue.php
			"/Parent" => $parent,
			"/Dest" => sprintf("[%s /Fit]", $open)
			)
		);

	if(isset($pdf["objects"][$parent_id]["dictionary"]["/Count"]))
		$count = $pdf["objects"][$parent_id]["dictionary"]["/Count"];
	else
		$count = 0;

	$pdf["objects"][$parent_id]["dictionary"]["/Count"] = $count - 1;

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

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_outlines ( array $pdf , string $parent ) : string
################################################################################

function _pdf_add_outlines(& $pdf, $parent)
	{
	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_outlines: invalid parent:" . $parent);

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

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

	if($pdf["objects"][$parent_id]["dictionary"]["/Type"] == "/Pages")
		$pdf["objects"][$this_id]["dictionary"]["/Parent"] = $parent;

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

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

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

	if($pdf["minor"] > 3)
		$pdf["objects"][$this_id]["dictionary"]["/Group"] = "<< /Type /Group /S /Transparency /CS /DeviceRGB >>";

	if($pdf["objects"][$parent_id]["dictionary"]["/Type"] == "/Pages")
		{
		if(isset($pdf["objects"][$parent_id]["dictionary"]["/Kids"]))
			$data = $pdf["objects"][$parent_id]["dictionary"]["/Kids"];
		else
			$data = "[]";

		$data = substr($data, 1);
		list($kids, $data) = _pdf_parse_array($data); # pdf-api-parse.php
		$data = substr($data, 1);

		$kids[] = sprintf("%d 0 R", $this_id);
	
		$pdf["objects"][$parent_id]["dictionary"]["/Kids"] = sprintf("[%s]", _pdf_glue_array($kids)); # pdf-api-glue.php

		$data = $pdf["objects"][$parent_id]["dictionary"]["/Count"];
		$pdf["objects"][$parent_id]["dictionary"]["/Count"] = ($data < 0 ? $data - 1 : $data + 1);
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

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

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
		$pdf["objects"][$this_id]["dictionary"]["/Parent"] = $parent;

		if(isset($pdf["objects"][$parent_id]["dictionary"]["/Kids"]))
			$data = $pdf["objects"][$parent_id]["dictionary"]["/Kids"];
		else
			$data = "[]";

		$data = substr($data, 1);
		list($kids, $data) = _pdf_parse_array($data); # pdf-api-parse.php
		$data = substr($data, 1);

		$kids[] = sprintf("%d 0 R", $this_id);

		$pdf["objects"][$parent_id]["dictionary"]["/Kids"] = sprintf("[%s]", _pdf_glue_array($kids));

		# increase or decrease counter, depending on plus or minus sign
		$data = $pdf["objects"][$parent_id]["dictionary"]["/Count"];
		$pdf["objects"][$parent_id]["dictionary"]["/Count"] = ($data < 0 ? $data - 1 : $data + 1);
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
	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

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
?>
