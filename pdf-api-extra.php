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
# _pdf_add_acroform ( array $pdf , string $parent ) : string
################################################################################

function _pdf_add_acroform(& $pdf, $parent, $resources)
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
# _pdf_add_action ( array $pdf  ) : string
################################################################################

function _pdf_add_action(& $pdf, $optlist)
	{
	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Action",
			"/S" => "/URI",
			"/URI" => sprintf("(%s)", _pdf_glue_string($optlist["uri"])) # pdf-api-glue.php
			)
		);

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_annotation ( array $pdf , string $parent , string $rect , string $uri ) : string
################################################################################

function _pdf_add_annotation(& $pdf, $parent, $rect, $type, $optlist)
	{
	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_annots: invalid parent: " . $parent);

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	if($type == "link")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Annot",
				"/Subtype" => "/Link",
				"/Rect" => $rect,

				"/Dest" => sprintf("[%s /Fit]", $optlist["dest"])
				)
			);
		}

	################################################################################

	if($type == "uri")
		{
		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Annot",
				"/Subtype" => "/Link",
				"/Rect" => $rect,

				"/A" => $optlist["action"]
				)
			);
		}

	################################################################################

	if($type == "widget")
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
				"/AP" => array
					(
					"/N" => $optlist["form"]
					)
				)
			);
		}

	################################################################################

	if(isset($pdf["objects"][$parent_id]["dictionary"]["/Annots"]))
		$data = $pdf["objects"][$parent_id]["dictionary"]["/Annots"];
	else
		$data = "[]";

	################################################################################

	$data = substr($data, 1);

	list($annots, $data) = _pdf_parse_array($data); # pdf-api-parse.php

	$data = substr($data, 1);

	################################################################################

	$annots[] = sprintf("%d 0 R", $this_id);

	################################################################################

	$pdf["objects"][$parent_id]["dictionary"]["/Annots"] = sprintf("[%s]", _pdf_glue_array($annots)); # pdf-api-glue.php

	################################################################################

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_page ( array $df ) : string
################################################################################

function _pdf_add_catalog(& $pdf)
	{
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

	################################################################################

	if(isset($pdf["objects"][$parent_id]["dictionary"]["/Fields"]))
		$data = $pdf["objects"][$parent_id]["dictionary"]["/Fields"];
	else
		$data = "[]";

	################################################################################

	$data = substr($data, 1);

	list($fields, $data) = _pdf_parse_array($data); # pdf-api-parse.php

	$data = substr($data, 1);
	
	################################################################################

	$fields[] = $field;
	
	################################################################################

	$pdf["objects"][$parent_id]["dictionary"]["/Fields"] = sprintf("[%s]", _pdf_glue_array($fields)); # pdf-api-glue.php
	}

################################################################################
# _pdf_add_font ( array $pdf , string $fontname , string $encoding ) : string
################################################################################

function _pdf_add_font(& $pdf, $fontname, $encoding = "/WinAnsiEncoding")
	{
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
			return(sprintf("%d 0 R", $index));

		if($object["dictionary"]["/Subtype"] == "/TrueType")
			return(sprintf("%d 0 R", $index));
		}

	################################################################################

	$core_fonts = _pdf_core_fonts();

	foreach($core_fonts as $k => $v)
		{
		if($v["/BaseFont"] != "/" . $fontname)
			continue;

		$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Font",
				"/Subtype" => "/Type1",
				"/BaseFont" => "/" . $fontname,
				"/Encoding" => $encoding
				)
			);

		return(sprintf("%d 0 R", $this_id));
		}

	################################################################################

#	$filename = "/home/nomatrix/externe_platte/daten/ttf/" . strtolower($fontname[0]) . "/" . $fontname . ".ttf";
	$filename = "/usr/share/fonts/truetype/freefont/" . $fontname . ".ttf";

	if(file_exists($filename) === false)
		return(_pdf_add_font($pdf["objects"], "/Courier", $encoding));

	################################################################################

	$widths = array();

	foreach(range(0x20, 0xFF) as $char)
		$widths[chr($char)] = (($image_ttf_bbox = imagettfbbox(720, 0, $filename, chr($char))) ? $image_ttf_bbox[2] : 1000);

	################################################################################

	$a = _pdf_add_font_file($pdf, $filename);

	$b = _pdf_add_font_descriptor($pdf, $fontname, $a);

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
			"/Encoding" => $encoding,
			"/FirstChar" => 32,
			"/LastChar" => 255,
			"/Widths" => sprintf("[%s]", _pdf_glue_array($widths)), # pdf-api-glue.php
			"/FontDescriptor" => $b
			)
		);

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_font_encoding ( array $pdf , string $differences ) : string
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
#			"/Flags" => FONTDESCRIPTOR_FLAG_SERIF | FONTDESCRIPTOR_FLAG_SCRIPT,
#			"/StemV" => 90,
#			"/CapHeight" => 720,
#			"/XHeight" => 480,
#			"/Ascent" => 720,
#			"/Descent" => 0 - 250,
#			"/ItalicAngle" => 0,
#			"/FontBBox" => "[0 -240 1440 1000]",
			"/FontName" => "/" . $fontname,
			"/FontFile2" => $fontfile
			)
		);

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_font_encoding ( array $pdf , string $differences ) : string
################################################################################

function _pdf_add_font_encoding(& $pdf, $encoding = "/WinAnsiEncoding", $differences = "[65 /A /B /C]")
	{
	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Encoding",
			"/BaseEncoding" => $encoding,
			"/Differences" => $differences
			)
		);

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# pending
################################################################################

function _pdf_add_font_definition(& $pdf)
	{
	$a = _pdf_add_font_encoding($pdf);

	$b = _pdf_add_page_content($pdf, "");

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Font",
			"/Subtype" => "/Type3",
			"/Encoding" => $a,
			"/CharProcs" => sprintf("<< /C %s /B %s /A %s >>", $b, $b, $b),
			"/FontMatrix" => "[1 0 0 -1 0 0]",
			"/FontBBox" => "[0 0 1000 1000]",
			"/FirstChar" => 65,
			"/LastChar" => 67,
			"/Widths" => "[8 8 8]"
			)
		);

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

	$pdf["loaded-resources"]["/XObject"][$id] = sprintf("%d 0 R", $this_id);

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_image ( array $pdf , string $imagetype , string $filename ) : string
################################################################################

function _pdf_add_image(& $pdf, $imagetype, $filename)
	{
	if($imagetype == "jpg")
		{
		$retval = _pdf_add_image_jpg($pdf, $filename);
		}
	else
		{
		system("convert " . $filename . " -quality 15 lolo.jpg");

		$retval = _pdf_add_image($pdf, "jpg", "lolo.jpg");

		unlink("lolo.jpg"); # don't change it. keep this name. don't make fun here. :)
		}

	return($retval);
	}

################################################################################
# _pdf_add_image_jpeg ( array $pdf , string $filename ) : string
################################################################################

function _pdf_add_image_jpg(& $pdf, $filename)
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
		$pdf["objects"][$this_id]["dictionary"][$key] = sprintf("(%s)", _pdf_glue_string($value)); # pdf-api-glue.php

	$pdf["objects"][0]["dictionary"]["/Info"] = sprintf("%d 0 R", $this_id);

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_linearized ( array $pdf ) : string
################################################################################

function _pdf_add_linearized(& $pdf)
	{
	$hint = _pdf_add_linearized_hints($pdf);

	if(sscanf($hint, "%d %d R", $hint_id, $hint_version) != 2)
		die("_pdf_add_linearized: invalid hint stream offset.");

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	foreach($pdf["objects"] as $index => $object)
		if(isset($object["dictionary"]["/Linearized"]))
			$this_id = $index;

#	$pdf["objects"][$this_id] = array
#		(
#		"id" => $this_id,
#		"version" => 0,
#		"dictionary" => array
#			(
#			"/Linearized" => 1,
#			"/E" => 0, # Offset of end of first page
#			"/H" => "[0 0]", # Primary hint stream offset and length  int.: offset is equal to number in xref table
#			"/L" => 0, # File length int.: startxret + 10 + length of lineraized things
#			"/N" => 0, # Number of pages in document
#			"/O" => 0, # Object number of first page’s page object
#			"/T" => 0 # Offset of first entry in main cross-reference table int.: startxret + 10
#			)
#		);

	################################################################################

	$root = $pdf["objects"][0]["dictionary"]["/Root"];

	if(sscanf($root, "%d %d R", $root_id, $root_version) != 2)
		die("_pdf_add_linearized: invalid root: " . $root);

	if(isset($pdf["objects"][$root_id]["dictionary"]["/Pages"]) === false)
		die("_pdf_add_linearized: pages not found.");

	$pages = $pdf["objects"][$root_id]["dictionary"]["/Pages"];

	if(sscanf($pages, "%d %d R", $pages_id, $pages_version) != 2)
		die("_pdf_add_linearized: invalid pages: " . $pages);

	if(isset($pdf["objects"][$pages_id]["dictionary"]["/Count"]) === false)
		die("_pdf_add_linearized: invalid count.");

	if(isset($pdf["objects"][$pages_id]["dictionary"]["/Count"]))
		$count = abs($pdf["objects"][$pages_id]["dictionary"]["/Count"]);
	else
		$count = 0;

	################################################################################

	if(isset($pdf["objects"][$pages_id]["dictionary"]["/Kids"]))
		$data = $pdf["objects"][$pages_id]["dictionary"]["/Kids"];
	else
		$data = "[]";

	################################################################################

	$data = substr($data, 1);

	list($kids, $data) = _pdf_parse_array($data);

	$data = substr($data, 1);

	################################################################################

	$page = $kids[0];

	if(sscanf($page, "%d %d R", $page_id, $page_version) != 2)
		die("_pdf_add_linearized: invalid kids.");

	################################################################################

	$retval = array("%PDF-1.0");

	$offsets = array();

	foreach($pdf["objects"] as $index => $object)
		{
		if($index == 0) # trailer
			continue;

		if(isset($object["dictionary"]["/Linearized"]))
			$this_id = $index;

		$offsets[$index] = strlen(implode("\n", $retval)) + 1;

		if($index == $hint_id)
			$hint_offset = strlen(implode("\n", $retval)) + 1;

		$retval[] = _pdf_glue_object($object); # pdf-api-glue.php

		if($index == $hint_id)
			$hint_length = strlen(implode("\n", $retval)) - $hint_offset + 1;

		if($index == $page_id)
			$e = strlen(implode("\n", $retval)) + 1;
		}

	$startxref = strlen(implode("\n", $retval)) + 1;

	################################################################################

	return(sprintf("%d 0 R", $this_id));
	}

function _pdf_add_linearized_hints(& $pdf)
	{
	$stream = array();

	# the least number of objects in first page
	$stream[] = sprintf("%08x", 0);

	# the location of the first pages page object
	$stream[] = sprintf("%08x", 0);

	# the number of bits needed to represent the difference between the greatest and least number of objects in a page.
	$stream[] = sprintf("%04x", 0);

	# The least length of a page in bytes. 
	# This shall be the least length from the beginning of a page object to the last byte of the last object used by that page.
	$stream[] = sprintf("%08x", 0);

	# The number of bits needed to represent the difference between the greatest and least length of a page, in bytes.
	$stream[] = sprintf("%04x", 0);

	# The least offset of the start of any content stream, relative to the beginning of its page.
	$stream[] = sprintf("%08x", 0);
	
	# The number of bits needed to represent the difference between the greatest and least offset to the start of the content stream.
	$stream[] = sprintf("%04x", 0);

	# The least content stream length.
	$stream[] = sprintf("%08x", 0);

	# The number of bits needed to represent the difference between the greatest and least content stream length.
	$stream[] = sprintf("%04x", 0);

	# The number of bits needed to represent the greatest number of shared object references.
	$stream[] = sprintf("%04x", 0);

	# The number of bits needed to represent the numerically greatest shared object identifier used by the pages (discussed further in Table F.4, item 4).	
	$stream[] = sprintf("%04x", 0);

	# The number of bits needed to represent the numerator of the fractional position for each shared object reference.
	# For each shared object referenced from a page, there shall be an indication of where in the page’s content stream the object is first referenced.
	# That position shall be given as the numerator of a fraction, whose denominator is specified once for the entire document (in the next item in this table).
	# The fraction is explained in more detail in Table F.4, item 5.
	$stream[] = sprintf("%04x", 0);

	# The denominator of the fractional position for each shared object reference.
	$stream[] = sprintf("%04x", 0);

	$stream = implode("", $stream);

	$stream = hex2bin($stream);
	$stream = gzcompress($stream);
	$stream = bin2hex($stream);

	$this_id = _pdf_get_free_object_id($pdf); # pdf-api-lib.php

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/S" => 0, # Position of shared object hint table
			"/Filter" => "[/ASCIIHexDecode /FlateDecode]",
			"/Length" => strlen($stream)
			),
		"stream" => $stream # Page offset hint table, Shared object hint table, Possibly other hint tables
		);

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
			"/Contents" => $contents,
#			"/Group" => "<< /Type /Group /S /Transparency /CS /DeviceRGB >>"
			)
		);

	################################################################################

	if(isset($pdf["objects"][$parent_id]["dictionary"]["/Kids"]))
		$data = $pdf["objects"][$parent_id]["dictionary"]["/Kids"];
	else
		$data = "[]";

	################################################################################

	$data = substr($data, 1);

	list($kids, $data) = _pdf_parse_array($data); # pdf-api-parse.php

	$data = substr($data, 1);

	################################################################################

	$kids[] = sprintf("%d 0 R", $this_id);
	
	################################################################################

	$pdf["objects"][$parent_id]["dictionary"]["/Kids"] = sprintf("[%s]", _pdf_glue_array($kids)); # pdf-api-glue.php
	$pdf["objects"][$parent_id]["dictionary"]["/Count"] = count($kids);

	################################################################################

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


	if($pdf["objects"][$parent_id]["dictionary"]["/Type"] == "/Page")
		$pdf["objects"][$this_id]["dictionary"]["/Parent"] = $parent;

	$pdf["objects"][$parent_id]["dictionary"]["/Pages"] = sprintf("%d 0 R", $this_id);

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_stream ( array $pdf , string $stream ) : string
################################################################################

function _pdf_add_stream(& $pdf, $stream)
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

	return(sprintf("%d 0 R", $this_id));
	}
?>
