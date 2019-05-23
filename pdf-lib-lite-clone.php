<?
################################################################################
# copyright 2019 by Markus Olderdissen
# free for private use or inspiration.
# public use need written permission.
################################################################################

include_once("pdf-lib-lite-clone-fpdf.php");
include_once("pdf-lib-lite-clone-x.php");

################################################################################
# PDF_activate_item - Activate structure element or other content item
# PDF_activate_item ( resource $pdfdoc , int $id ) : bool
# Activates a previously created structure element or other content item. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_activate_item(& $pdfdoc, $id)
	{
	}

################################################################################
# PDF_add_annotation - Add annotation [deprecated]
# This function is deprecated, use PDF_create_annotation() with type=Text instead.
################################################################################

function pdf_add_annotation(& $pdfdoc, $llx, $lly, $urx, $ury, $title, $content)
	{
	}

################################################################################
# PDF_add_bookmark - Add bookmark for current page [deprecated]
# This function is deprecated since PDFlib version 6, use PDF_create_bookmark() instead.
################################################################################

function pdf_add_bookmark(& $pdfdoc, $text, $parent, $open)
	{
	}

################################################################################
# PDF_add_launchlink - Add launch annotation for current page [deprecated]
# PDF_add_launchlink ( resource $pdfdoc , float $llx , float $lly , float $urx , float $ury , string $filename ) : bool
# Adds a link to a web resource.
# This function is deprecated since PDFlib version 6, use PDF_create_action() with type=Launch and PDF_create_annotation() with type=Link instead.
################################################################################

function pdf_add_launchlink(& $pdfdoc, $llx, $lly, $urx, $ury, $filename)
	{
	}

################################################################################
# PDF_add_locallink - Add link annotation for current page [deprecated]
# PDF_add_locallink ( resource $pdfdoc , float $lowerleftx , float $lowerlefty , float $upperrightx , float $upperrighty , int $page , string $dest ) : bool
# Add a link annotation to a target within the current PDF file. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use PDF_create_action() with type=GoTo and PDF_create_annotation() with type=Link instead.
################################################################################

function pdf_add_locallink(& $pdfdoc, $lowerleftx, $lowerlefty, $upperrightx, $upperrighty, $page, $dest)
	{
	}

################################################################################
# PDF_add_nameddest - Create named destination
# PDF_add_nameddest ( resource $pdfdoc , string $name , string $optlist ) : bool
# Creates a named destination on an arbitrary page in the current document. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_add_nameddest(& $pdfdoc, $name, $optlist)
	{
	}

################################################################################
# PDF_add_note - Set annotation for current page [deprecated]
# PDF_add_note ( resource $pdfdoc , float $llx , float $lly , float $urx , float $ury , string $contents , string $title , string $icon , int $open ) : bool
# Sets an annotation for the current page. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use PDF_create_annotation() with type=Text instead.
################################################################################

function pdf_add_note(& $pdfdoc, $llx, $lly, $urx, $ury, $contents, $title, $icon, $open)
	{
	}

################################################################################
# PDF_add_outline # Add bookmark for current page [deprecated]
# This function is deprecated, use PDF_create_bookmark() instead.
################################################################################

function pdf_add_outline(& $pdfdoc, $text, $parent, $open)
	{
	}

################################################################################
# PDF_add_pdflink - Add file link annotation for current page [deprecated]
# PDF_add_pdflink ( resource $pdfdoc , float $bottom_left_x , float $bottom_left_y , float $up_right_x , float $up_right_y , string $filename , int $page , string $dest ) : bool
# Add a file link annotation to a PDF target. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use PDF_create_action() with type=GoToR and PDF_create_annotation() with type=Link instead.
################################################################################

function pdf_add_pdflink(& $pdfdoc, $bottom_left_x, $bottom_left_y, $up_right_x, $up_right_y, $filename, $page, $dest)
	{
	}

################################################################################
# PDF_add_table_cell - Add a cell to a new or existing table
# PDF_add_table_cell ( resource $pdfdoc , int $table , int $column , int $row , string $text , string $optlist ) : int
# Adds a cell to a new or existing table.
################################################################################

function pdf_add_table_cell(& $pdfdoc, $table, $column, $row, $text, $optlist)
	{
	}

################################################################################
# PDF_add_textflow - Create Textflow or add text to existing Textflow
# PDF_add_textflow ( resource $pdfdoc , int $textflow , string $text , string $optlist ) : int
# Creates a Textflow object, or adds text and explicit options to an existing Textflow.
################################################################################

function pdf_add_textflow(& $pdfdoc, $textflow, $text, $optlist)
	{
	}

################################################################################
# PDF_add_thumbnail - Add thumbnail for current page
# PDF_add_thumbnail ( resource $pdfdoc , int $image ) : bool
# Adds an existing image as thumbnail for the current page. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_add_thumbnail(& $pdfdoc, $image)
	{
	}

################################################################################
# PDF_add_weblink - Add weblink for current page [deprecated]
# PDF_add_weblink ( resource $pdfdoc , float $lowerleftx , float $lowerlefty , float $upperrightx , float $upperrighty , string $url ) : bool
# Adds a weblink annotation to a target url on the Web. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use PDF_create_action() with type=URI and PDF_create_annotation() with type=Link instead.
################################################################################

function pdf_add_weblink(& $pdfdoc, $lowerleftx, $lowerlefty, $upperrightx, $upperrighty, $url)
	{
	}

################################################################################
# PDF_arc - Draw a counterclockwise circular arc segment
# PDF_arc ( resource $p , float $x , float $y , float $r , float $alpha , float $beta ) : bool
# Adds a counterclockwise circular arc.
################################################################################

function pdf_arc(& $p, $x, $y, $r, $alpha, $beta)
	{
	pdf_arc_orient($p, $x, $y, $r, $alpha, $beta, 0 - 1);
	}

################################################################################
# PDF_arcn - Draw a clockwise circular arc segment
# PDF_arcn ( resource $p , float $x , float $y , float $r , float $alpha , float $beta ) : bool
# Except for the drawing direction, this function behaves exactly like PDF_arc().
################################################################################

function pdf_arcn(& $p, $x, $y, $r, $alpha, $beta)
	{
	pdf_arc_orient($p, $x, $y, $r, $alpha, $beta, 0 + 1);
	}

################################################################################

function pdf_arc_orient(& $p, $x, $y, $r, $alpha, $beta, $orient)
	{
	$deg_to_rad	= 0.0174532925199433; # pi() / 180

	$rad_a		= $alpha * $deg_to_rad;

	$startx		= ($x + $r * cos($rad_a));
	$starty		= ($y + $r * sin($rad_a));

	pdf_moveto($p, $startx, $starty);

	if($orient > 0)
		{
		while($beta < $alpha)
			$beta = $beta + 360;

		if($alpha == $beta)
			return;

		while($beta - $alpha > 90)
			{
			pdf_arc_short($p, $x, $y, $r, $alpha, $alpha - 90);

			$alpha = $alpha + 90;
			}
		}
	else
		{
		while($alpha < $beta)
			$alpha = $alpha + 360;

		if($alpha == $beta)
			return;

		while($alpha - $beta > 90)
			{
			pdf_arc_short($p, $x, $y, $r, $alpha, $alpha + 90);

			$alpha = $alpha - 90;
			}
		}

	if($alpha != $beta)
		{
		pdf_arc_short($p, $x, $y, $r, $alpha, $beta);
		}
	}

################################################################################

function pdf_arc_short(& $p, $x, $y, $r, $alpha, $beta)
	{
	$deg_to_rad	= 0.0174532925199433; # pi() / 180

	$alpha		= $alpha * $deg_to_rad;
	$beta		= $beta * $deg_to_rad;

	$bcp		= (4 / 3 * (1 - cos(($beta - $alpha) / 2)) / sin(($beta - $alpha) / 2));

	$sin_apha	= sin($alpha);
	$sin_beta	= sin($beta);
	$cos_alpha	= cos($alpha);
	$cos_beta	= cos($beta);

	pdf_curveto(
		$p,
		$x + $r * ($cos_alpha - $bcp * $sin_alpha),
		$y + $r * ($sin_alpha + $bcp * $cos_alpha),
		$x + $r * ($cos_beta + $bcp * $sin_beta),
		$y + $r * ($sin_beta - $bcp * $cos_beta),
		$x + $r * $cos_beta,
		$y + $r * $sin_beta
		);
	}

################################################################################
# PDF_attach_file - Add file attachment for current page [deprecated]
# PDF_attach_file ( resource $pdfdoc , float $llx , float $lly , float $urx , float $ury , string $filename , string $description , string $author , string $mimetype , string $icon ) : bool
# Adds a file attachment annotation. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use PDF_create_annotation() with type=FileAttachment instead.
################################################################################

function pdf_attach_file(& $pdfdoc, $llx, $lly, $urx, $ury, $filename, $description, $author, $mimetype, $icon)
	{
	}

################################################################################
# PDF_begin_document - Create new PDF file
# PDF_begin_document ( resource $pdfdoc , string $filename , string $optlist ) : int
# Creates a new PDF file subject to various options.
################################################################################

function pdf_begin_document(& $pdfdoc, $filename, $optlist)
	{
	$pdfdoc["filename"] = $filename;

	$object = array
		(
		"id" => 1,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Catalog",
			"/Pages" => "2 0 R",
			"/Metadata" => "5 0 R",
			"/OpenAction" => "[6 0 R /Fit]",
			"/PageLayout" => "/OneColumn",
			"/PageMode" => "/UseOutlines",
			"/Outlines" => "4 0 R"
			)
		);

	foreach($optlist as $k => $v)
		$object["dictionary"][$key] = $value;

	return($pdfdoc["catalog-dictionary"][0] = $object);
	}

################################################################################
# PDF_begin_font - Start a Type 3 font definition
# PDF_begin_font ( resource $pdfdoc , string $filename , float $a , float $b , float $c , float $d , float $e , float $f , string $optlist ) : bool
# Starts a Type 3 font definition.
################################################################################

function pdf_begin_font(& $pdfdoc, $filename, $a, $b, $c, $d, $e, $f, $optlist)
	{
	}

################################################################################
# PDF_begin_glyph - Start glyph definition for Type 3 font
# PDF_begin_glyph ( resource $pdfdoc , string $glyphname , float $wx , float $llx , float $lly , float $urx , float $ury ) : bool
# Starts a glyph definition for a Type 3 font.
################################################################################

function pdf_begin_glyph(& $pdfdoc, $glyphname, $wx, $llx, $lly, $urx, $ury)
	{
	}

################################################################################
# PDF_begin_item - Open structure element or other content item
# PDF_begin_item ( resource $pdfdoc , string $tag , string $optlist ) : int
# Opens a structure element or other content item with attributes supplied as options.
################################################################################

function pdf_begin_item(& $pdfdoc, $tag, $optlist)
	{
	}

################################################################################
# PDF_begin_layer - Start layer
# PDF_begin_layer ( resource $pdfdoc , int $layer ) : bool
# Starts a layer for subsequent output on the page. Returns TRUE on success or FALSE on failure.
# This function requires PDF 1.5.
################################################################################

function pdf_begin_layer(& $pdfdoc, $layer)
	{
	}

################################################################################
# PDF_begin_page - Start new page [deprecated]
# PDF_begin_page ( resource $pdfdoc , float $width , float $height ) : bool
# Adds a new page to the document. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use PDF_begin_page_ext() instead.
################################################################################

function pdf_begin_page(& $pdfdoc, $width, $height)
	{
	pdf_begin_page_ext($pdfdoc, $width, $height, array());
	}

################################################################################
# PDF_begin_page_ext - Start new page
# PDF_begin_page_ext ( resource $pdfdoc , float $width , float $height , string $optlist ) : bool
# Adds a new page to the document, and specifies various options. The parameters width and height are the dimensions of the new page in points. Returns TRUE on success or FALSE on failure.
################################################################################
# Common Page Sizes in Points
# 	name			size
# 	A0			2380 x 3368
# 	A1			1684 x 2380
# 	A2			1190 x 1684
# 	A3			842 x 1190
# 	A4			595 x 842
# 	A5			421 x 595
# 	A6			297 x 421
# 	B5			501 x 709
# 	letter (8.5" x 11")	612 x 792
# 	legal (8.5" x 14")	612 x 1008
# 	ledger (17" x 11")	1224 x 792
# 	11" x 17"		792 x 1224
################################################################################

function pdf_begin_page_ext(& $pdfdoc, $width, $height, $optlist)
	{
	$object = array
		(
		"id" => 6 + (2 * count($pdfdoc["page-dictionary"])),
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Page",
			"/Parent" => "2 0 R",
			"/MediaBox" => sprintf("[0 0 %d %d]", $width, $height),
			"/Resources" => "3 0 R",
			"/Contents" => sprintf("%d 0 R", 7 + (2 * count($pdfdoc["page-dictionary"])))
			)
		);

	foreach($optlist as $k => $v)
		$object["dictionary"][$key] = $value;

	$pdfdoc["/Width"] = intval($width);
	$pdfdoc["/Height"] = intval($height);

	$pdfdoc["stream"] = array();
	}

################################################################################
# PDF_begin_pattern - Start pattern definition
# PDF_begin_pattern ( resource $pdfdoc , float $width , float $height , float $xstep , float $ystep , int $painttype ) : int
# Starts a new pattern definition.
################################################################################

function pdf_begin_pattern(& $pdfdoc, $width, $height, $xstep, $ystep, $painttype)
	{
	}

################################################################################
# PDF_begin_template_ext - Start template definition
# PDF_begin_template_ext ( resource $pdfdoc , float $width , float $height , string $optlist ) : int
# Starts a new template definition.
################################################################################

function pdf_begin_template_ext(& $pdfdoc, $width, $height, $optlist)
	{
	$pdfdoc["stream"] = array();
	}

################################################################################
# PDF_begin_template - Start template definition [deprecated]
# PDF_begin_template ( resource $pdfdoc , float $width , float $height ) : int
# Starts a new template definition.
# This function is deprecated since PDFlib version 7, use PDF_begin_template_ext() instead.
################################################################################

function pdf_begin_template(& $pdfdoc, $width, $height)
	{
	pdf_begin_template_ext($pdfdoc, $width, $height, array());
	}

################################################################################
# PDF_circle - Draw a circle
# PDF_circle ( resource $pdfdoc , float $x , float $y , float $r ) : bool
# Adds a circle. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_circle(& $pdfdoc, $x, $y, $r)
	{
	#$arc_magic = 4 / 3 * (M_SQRT2 - 1);
	$arc_magic = 0.552284749;

	pdf_moveto($pdfdoc, $x + $r, $y);
	pdf_curveto($pdfdoc, $x + $r, $y + $r * $arc_magic, $x + $r * $arc_magic, $y + $r, $x, $y + $r);
	pdf_curveto($pdfdoc, $x - $r * $arc_magic, $y + $r, $x - $r, $y + $r * $arc_magic, $x - $r, $y);
	pdf_curveto($pdfdoc, $x - $r, $y - $r * $arc_magic, $x - $r * $arc_magic, $y - $r, $x, $y - $r);
	pdf_curveto($pdfdoc, $x + $r * $arc_magic, $y - $r, $x + $r, $y - $r * $arc_magic, $x + $r, $y);
	}

################################################################################
# PDF_clip - Clip to current path
# PDF_clip ( resource $p ) : bool
# Uses the current path as clipping path, and terminate the path. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_clip(& $p)
	{
	$p["stream"][] = "W";
	}

################################################################################
# PDF_close - Close pdf resource [deprecated]
# PDF_close ( resource $p ) : bool
# Closes the generated PDF file, and frees all document-related resources. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use PDF_end_document() instead.
################################################################################

function pdf_close(& $p)
	{
	pdf_end_document($p, array());
	}

################################################################################
# PDF_close_image - Close image
# PDF_close_image ( resource $p , int $image ) : bool
# Closes an image retrieved with the PDF_open_image() function.
################################################################################

function pdf_close_image(& $p, $image)
	{
	$p["stream"][] = "EI";
	}

################################################################################
# PDF_close_pdi_page - Close the page handle
# PDF_close_pdi_page ( resource $p , int $page ) : bool
# Closes the page handle, and frees all page-related resources. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_close_pdi_page(& $p, $page)
	{
	}

################################################################################
# PDF_close_pdi_document - Close the document handle
# PDF_close_pdi_document ( resource $p , int $doc ) : bool
# Closes all open page handles, and closes the input PDF document. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_close_pdi_document(& $p, $doc)
	{
	}

################################################################################
# PDF_close_pdi - Close the input PDF document [deprecated]
# PDF_close_pdi ( resource $p , int $doc ) : bool
# Closes all open page handles, and closes the input PDF document. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 7, use PDF_close_pdi_document() instead.
################################################################################

function pdf_close_pdi(& $p, $doc)
	{
	pdf_close_pdi_document($p);
	}

################################################################################
# PDF_closepath - Close current path
# PDF_closepath ( resource $p ) : bool
# Closes the current path. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_closepath(& $p)
	{
	$p["stream"][] = "h";
	}

################################################################################
# PDF_closepath_fill_stroke - Close, fill and stroke current path
# PDF_closepath_fill_stroke ( resource $p ) : bool
# Closes the path, fills, and strokes it. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_closepath_fill_stroke(& $p)
	{
	$p["stream"][] = "b";
	}

################################################################################
# PDF_closepath_stroke - Close and stroke path
# PDF_closepath_stroke ( resource $p ) : bool
# Closes the path, and strokes it. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_closepath_stroke(& $p)
	{
	$p["stream"][] = "s";
	}

################################################################################
# PDF_concat - Concatenate a matrix to the CTM
# PDF_concat ( resource $p , float $a , float $b , float $c , float $d , float $e , float $f ) : bool
# Concatenates a matrix to the current transformation matrix (CTM). Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_concat(& $p, $a, $b, $c, $d, $e, $f)
	{
	$p["stream"][] = sprintf("%f %f %f %f %f %f cm", $a, $b, $c, $d, $e, $f);
	}

################################################################################
# PDF_continue_text - Output text in next line
# PDF_continue_text ( resource $p , string $text ) : bool
# Prints text at the next line. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_continue_text(& $p, $text)
	{
	if(end($p["stream"]) == "ET")
		{
		array_pop($p["stream"]);

		$p["stream"][] = "T*";
		}
	else # if(end($p["stream"]) != "BT")
		$p["stream"][] = "BT";

	if(isset($p["/ProcSet"]) === false)
		$p["/ProcSet"][] = "/Text";
	elseif(in_array("/Text", $p["/ProcSet"]) === false)
		$p["/ProcSet"][] = "/Text";

	foreach(str_split($text) as $char)
		{
		$p["/FirstChar"] = min($p["/FirstChar"], ord($char));
		$p["/LastChar"] = max($p["/LastChar"], ord($char));
		}

	$p["stream"][] = "T*";
	$p["stream"][] = sprintf("%s Tj", _textstring($p, $text));
	$p["stream"][] = "ET";
	}

################################################################################
# PDF_create_3dview - Create 3D view
# PDF_create_3dview ( resource $pdfdoc , string $username , string $optlist ) : int
# Creates a 3D view.
# This function requires PDF 1.6.
################################################################################

function pdf_create_3dview(& $pdfdoc, $username, $optlist)
	{
	}

################################################################################
# PDF_create_action - Create action for objects or events
# PDF_create_action ( resource $pdfdoc , string $type , string $optlist ) : int
# Creates an action which can be applied to various objects and events.
################################################################################

function pdf_create_action(& $pdfdoc, $type, $optlist)
	{
	}

################################################################################
# PDF_create_annotation - Create rectangular annotation
# PDF_create_annotation ( resource $pdfdoc , float $llx , float $lly , float $urx , float $ury , string $type , string $optlist ) : bool
# Creates a rectangular annotation on the current page.
################################################################################

function pdf_create_annotation(& $pdfdoc, $llx, $lly, $urx, $ury, $type, $optlist)
	{
	}

################################################################################
# PDF_create_bookmark - Create bookmark
# PDF_create_bookmark ( resource $pdfdoc , string $text , string $optlist ) : int
# Creates a bookmark subject to various options.
################################################################################

function pdf_create_bookmark(& $pdfdoc, $text, $optlist)
	{
	}

################################################################################
# PDF_create_field - Create form field
# PDF_create_field ( resource $pdfdoc , float $llx , float $lly , float $urx , float $ury , string $name , string $type , string $optlist ) : bool
# Creates a form field on the current page subject to various options.
################################################################################

function pdf_create_field(& $pdfdoc, $llx, $lly, $urx, $ury, $name, $type, $optlist)
	{
	}

################################################################################
# PDF_create_fieldgroup - Create form field group
# PDF_create_fieldgroup ( resource $pdfdoc , string $name , string $optlist ) : bool
# Creates a form field group subject to various options.
################################################################################

function pdf_create_fieldgroup(& $pdfdoc, $name, $optlist)
	{
	}

################################################################################
# PDF_create_gstate - Create graphics state object
# PDF_create_gstate ( resource $pdfdoc , string $optlist ) : int
# Creates a graphics state object subject to various options.
################################################################################

function pdf_create_gstate(& $pdfdoc, $optlist)
	{
	}

################################################################################
# PDF_create_pvf - Create PDFlib virtual file
# PDF_create_pvf ( resource $pdfdoc , string $filename , string $data , string $optlist ) : bool
# Creates a named virtual read-only file from data provided in memory.
################################################################################

function pdf_create_pvf(& $pdfdoc, $filename, $data, $optlist)
	{
	}

################################################################################
# PDF_create_textflow - Create textflow object
# PDF_create_textflow ( resource $pdfdoc , string $text , string $optlist ) : int
# Preprocesses text for later formatting and creates a textflow object.
################################################################################

function pdf_create_textflow(& $pdfdoc, $text, $optlist)
	{
	}

################################################################################
# PDF_curveto - Draw Bezier curve
# PDF_curveto ( resource $p , float $x1 , float $y1 , float $x2 , float $y2 , float $x3 , float $y3 ) : bool
# Draws a Bezier curve from the current point, using 3 more control points. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_curveto(& $p, $x1, $y1, $x2, $y2, $x3, $y3)
	{
	$p["stream"][] = sprintf("%f %f %f %f %f %f c", $x1, $y1, $x2, $y2, $x3, $y3);
	}

################################################################################
# PDF_define_layer - Create layer definition
# PDF_define_layer ( resource $pdfdoc , string $name , string $optlist ) : int
# Creates a new layer definition.
# This function requires PDF 1.5.
################################################################################

function pdf_define_layer(& $pdfdoc, $name, $optlist)
	{
	}

################################################################################
# PDF_delete_pvf - Delete PDFlib virtual file
# PDF_delete_pvf ( resource $pdfdoc , string $filename ) : int
# Deletes a named virtual file and frees its data structures (but not the contents).
################################################################################

function pdf_delete_pvf(& $pdfdoc, $filename)
	{
	}

################################################################################
# PDF_delete_table - Delete table object
# PDF_delete_table ( resource $pdfdoc , int $table , string $optlist ) : bool
# Deletes a table and all associated data structures.
################################################################################

function pdf_delete_table(& $pdfdoc, $table, $optlist)
	{
	}

################################################################################
# PDF_delete_textflow - Delete textflow object
# PDF_delete_textflow ( resource $pdfdoc , int $textflow ) : bool
# Deletes a textflow and the associated data structures.
################################################################################

function pdf_delete_textflow(& $pdfdoc, $textflow)
	{
	}

################################################################################
# PDF_delete - Delete PDFlib object
# PDF_delete ( resource $pdfdoc ) : bool
# Deletes a PDFlib object, and frees all internal resources. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_delete(& $pdfdoc)
	{
	$pdfdoc = null;
	}

################################################################################
# PDF_encoding_set_char - Add glyph name and/or Unicode value
# PDF_encoding_set_char ( resource $pdfdoc , string $encoding , int $slot , string $glyphname , int $uv ) : bool
# Adds a glyph name and/or Unicode value to a custom encoding.
################################################################################

function pdf_encoding_set_char(& $pdfdoc, $encoding, $slot, $glyphname, $uv)
	{
	}

################################################################################
# PDF_end_document - Close PDF file
# PDF_end_document ( resource $pdfdoc , string $optlist ) : bool
# Closes the generated PDF file and applies various options.
################################################################################

function pdf_end_document(& $pdfdoc, $optlist)
	{
	$pdfdoc["stream"] = array();

	################################################################################

	$pdfdoc["stream"][] = sprintf("%%PDF-%d.%d", $pdfdoc["major"], $pdfdoc["minor"]);

	################################################################################

	$pdfdoc["stream"][] = sprintf("%%%s", hex2bin("FFFFFFFF"));

	################################################################################

	$pdfdoc["reference-id"] = 1;

	$pdfdoc["offsets"][1] = strlen(implode("\n", $pdfdoc["stream"]));

	$object = array
		(
		"/Type" => "/Catalog",
		"/Pages" => "2 0 R",
		"/Metadata" => "5 0 R"
		);

	if(isset($pdfdoc["catalog-dictionary"][0]["dictionary"]["/PageLayout"]))
		if(in_array($pdfdoc["catalog-dictionary"][0]["dictionary"]["/PageLayout"], array("/SinglePage", "/OneColumn", "/TwoColumnLeft", "/TwoColumnRight", "/TwoPageLeft", "/TwoPageRight")))
			$object["/PageLayout"] = $pdfdoc["catalog-dictionary"][0]["dictionary"]["/PageLayout"];

	if(isset($pdfdoc["catalog-dictionary"][0]["dictionary"]["/PageMode"]))
		{
		if(in_array($pdfdoc["catalog-dictionary"][0]["dictionary"]["/PageMode"], array("/UseNone", "/UseOutlines", "/UseThumbs", "/UseOC", "/UseAttachments")))
			{
			$object["/PageMode"] = $pdfdoc["catalog-dictionary"][0]["dictionary"]["/PageMode"];

			if($pdfdoc["catalog-dictionary"][0]["dictionary"]["/PageMode"] == "/UseOutlines")
				$object["/Outlines"] = "4 0 R"; # overwrites init value
			}
		}

	if(isset($pdfdoc["catalog-dictionary"][0]["dictionary"]["/OpenAction"]))
		{
		if($pdfdoc["catalog-dictionary"][0]["dictionary"]["/OpenAction"] == "/Fit")
			$object["/OpenAction"] = "[6 0 R /Fit]";

		if($pdfdoc["catalog-dictionary"][0]["dictionary"]["/OpenAction"] == "/FitH")
			$object["/OpenAction"] = "[6 0 R /FitH]";

		if($pdfdoc["catalog-dictionary"][0]["dictionary"]["/OpenAction"] == "/XYZ")
			if($pdfdoc["catalog-dictionary"][0]["dictionary"]["/XYZ"] == 0)
				$object["/OpenAction"] = "[6 0 R /XYZ]";
			else
				$object["/OpenAction"] = sprintf("[6 0 R /XYZ 0 0 %d]", $pdfdoc["catalog-dictionary"][0]["dictionary"]["/XYZ"] / 100);
		}

	$pdfdoc["stream"][] = "1 0 obj";
		$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object));
	$pdfdoc["stream"][] = "endobj";

	################################################################################

	$pdfdoc["reference-id"] = 2;

	$pdfdoc["offsets"][2] = strlen(implode("\n", $pdfdoc["stream"]));

	$kids = array();

	foreach($pdfdoc["page-dictionary"] as $k => $v)
		$kids[] = sprintf("%d 0 R", 6 + 2 * $k);

	$object = array
		(
		"/Type" => "/Pages",
#		"/Parent" => "1 0 R",
		"/Kids" => sprintf("[%s]", _pdf_glue_array($kids)),
		"/Count" => count($pdfdoc["page-dictionary"]),
		);

	$pdfdoc["stream"][] = "2 0 obj";
		$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object));
	$pdfdoc["stream"][] = "endobj";

	################################################################################

	$pdfdoc["reference-id"] = 5;

	foreach($pdfdoc["page-dictionary"] as $k => $v)
		{
		sscanf($pdfdoc["page-dictionary"][$k]["dictionary"]["/MediaBox"], "[0 0 %d %d]", $width, $height);

		$object = array
			(
			"/Type" => "/Page",
			"/Parent" => "2 0 R",
			"/MediaBox" => sprintf("[0 0 %d %d]", $width, $height),
			"/Resources" => "3 0 R",
			"/Contents" => sprintf("%d 0 R", $pdfdoc["reference-id"] + 2)
			);

		if($pdfdoc["minor"] > 3)
			$object["/Group"] = "<</Type/Group/S/Transparency/CS/DeviceRGB>>";

		_new_object($pdfdoc);
			$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object));
		$pdfdoc["stream"][] = "endobj";

		################################################################################

		$data = implode("\n", $pdfdoc["page-dictionary"][$k]["stream"]);

		$data = _filter($pdfdoc, $data);

		$object = array();

		if(isset($pdfdoc["/Filter"]))
			$object["/Filter"] = $pdfdoc["/Filter"];

		$object["/Length"] = strlen($data);

		_new_object($pdfdoc);
			$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object));
			$pdfdoc["stream"][] = "stream";
				_put_stream($pdfdoc, $data);
			$pdfdoc["stream"][] = "endstream";
		$pdfdoc["stream"][] = "endobj";
		}

	################################################################################

	foreach($pdfdoc["file-dictionary"] as $k => $v)
		{
		$pdfdoc["file-dictionary"][$k]["id"] = $pdfdoc["reference-id"] + 1;
		$pdfdoc["file-dictionary"][$k]["version"] = 0;

		if(($data = file_get_contents($k, true)) === false)
			die("File not found: " . $k);

		$compressed = (substr($k, 0 - 2) == ".z");

		$object = array
			(
			"/Length" => strlen($data),
			"/Length1" => $v["dictionary"]["/Length1"],
			);

		if(isset($v["dictionary"]["/Length2"]))
			if($compressed === false)
				$data = substr($data, 6, $v["dictionary"]["/Length1"]) . substr($data, 6 + $v["dictionary"]["/Length1"] + 6, $v["dictionary"]["/Length2"]);

		if(isset($v["dictionary"]["/Length2"]))
			{
			$object["/Length2"] = $v["dictionary"]["/Length2"];
			$object["/Length3"] = 0;
			}

		if($compressed)
			$object["/Filter"] = $pdfdoc["/Filter"];

		_new_object($pdfdoc);
			$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object));
			$pdfdoc["stream"][] = "stream";
				_put_stream($pdfdoc, $data);
			$pdfdoc["stream"][] = "endstream";
		$pdfdoc["stream"][] = "endobj";
		}

	foreach($pdfdoc["font-dictionary"] as $k => $v)
		{
		$pdfdoc["font-dictionary"][$k]["id"] = $pdfdoc["reference-id"] + 1;
		$pdfdoc["font-dictionary"][$k]["version"] = 0;

		if($v["dictionary"]["/Subtype"] == "/Core")
			{
			$object = array
				(
				"/Type" => "/Font",
				"/Subtype" => "/Type1",
				"/BaseFont" => sprintf("/%s", str_replace(" ", ",", $v["dictionary"]["/BaseFont"]))
				);

			if($v["dictionary"]["/BaseFont"] != "symbol")
				if($v["dictionary"]["/BaseFont"] != "zapfdingbats")
					$object["/Encoding"] = "/WinAnsiEncoding";

			_new_object($pdfdoc);
				$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object));
			$pdfdoc["stream"][] = "endobj";
			}
		elseif(($v["dictionary"]["/Subtype"] == "/Type1") || ($v["dictionary"]["/Subtype"] == "/TrueType"))
			{
			$widths = array();

			foreach(range($pdfdoc["/FirstChar"], $pdfdoc["/LastChar"]) as $char)
				$widths[] = $v["dictionary"]["/Widths"][chr($char)];

 			$object = array
				(
				"/Type" => "/Font",
				"/Subtype" => $v["dictionary"]["/Subtype"],
				"/BaseFont" => sprintf("/%s", str_replace(" ", ",", $v["dictionary"]["/BaseFont"])),
				"/Encoding" => "/WinAnsiEncoding",
#				"/FirstChar" => $pdfdoc["/FirstChar"],
#				"/LastChar" => $pdfdoc["/LastChar"],
#				"/Widths" => sprintf("[%s]", _pdf_glue_array($widths)),
				"/FontDescriptor" => sprintf("%d 0 R", $pdfdoc["reference-id"] + 3),
				);

			_new_object($pdfdoc);
				$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object));
			$pdfdoc["stream"][] = "endobj";

			################################################################################

			$object = array
				(
				"/Type" => "/FontDescriptor",
				"/FontName" => "/" . str_replace(" ", ",", $v["dictionary"]["/FontName"]),
				);

			foreach($v["dictionary"]["/FontDescriptor"] as $xk => $xv)
				if(isset($v["dictionary"]["/FontDescriptor"][$xk]) === false)
					$object[$xk] = $xv;

			if(isset($v["dictionary"]["/FontFile"]))
				$object["/FontFile" . ($v["dictionary"]["/Subtype"] == "/Type1" ? "" : "2")] = sprintf("%d 0 R", $pdfdoc["reference-id"] + 2);

			_new_object($pdfdoc);
				$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object));
			$pdfdoc["stream"][] = "endobj";

			################################################################################

			if(isset($v["dictionary"]["/FontFile"]))
				{
				$data = file_get_contents($v["dictionary"]["/FontFile"]);

				$data = _filter($pdfdoc, $data);

				$object = array();

				if(isset($pdfdoc["/Filter"]))
					$object["/Filter"] = $pdfdoc["/Filter"];

				$object["/Length"] = strlen($data);

				_new_object($pdfdoc);
					$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object));
					$pdfdoc["stream"][] = "stream";
						_put_stream($pdfdoc, $data);
					$pdfdoc["stream"][] = "endstream";
				$pdfdoc["stream"][] = "endobj";
				}
			}
		}

	################################################################################

	foreach($pdfdoc["image-dictionary"] as $k => $v)
		{
		_put_image($pdfdoc, $pdfdoc["image-dictionary"][$k]);
		}

	################################################################################

	$pdfdoc["offsets"][3] = strlen(implode("\n", $pdfdoc["stream"]));

	$object = array
		(
		);

	$object["/ProcSet"] = sprintf("[%s]", _pdf_glue_array($pdfdoc["/ProcSet"]));

	$font_table = array();

	if($pdfdoc["font-dictionary"])
		foreach($pdfdoc["font-dictionary"] as $k => $v)
			{
			$a = $v["x-procset-font-id"];
			$b = sprintf("%d 0 R", $v["id"]);

			$font_table[$a] = $b;
			}

	if(count($font_table) > 0)
		$object["/Font"] = sprintf("<< %s >>", _pdf_glue_dictionary($font_table));

	$x_object_table = array();

	if($pdfdoc["form-dictionary"])
		foreach($pdfdoc["form-dictionary"] as $k => $v)
			{
			$a = $v["x-procset-x-object-form-id"];
			$b = sprintf("%d 0 R", $v["id"]);

			$x_object_table[$a] = $b;
			}

	if($pdfdoc["image-dictionary"])
		foreach($pdfdoc["image-dictionary"] as $k => $v)
			{
			$a = $v["x-procset-x-object-image-id"];
			$b = sprintf("%d 0 R", $v["id"]);

			$x_object_table[$a] = $b;
			}

	if(count($x_object_table) > 0)
		$object["/XObject"] = sprintf("<< %s >>", _pdf_glue_dictionary($x_object_table));

	$pdfdoc["stream"][] = "3 0 obj";
	$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object));
	$pdfdoc["stream"][] = "endobj";

	################################################################################

	$pdfdoc["offsets"][4] = strlen(implode("\n", $pdfdoc["stream"]));

	$object = array
		(
		"/Type" => "/Outlines",
		"/First" => sprintf("%d 0 R", $pdfdoc["reference-id"] + 1),
		"/Last" => sprintf("%d 0 R", $pdfdoc["reference-id"] + count($pdfdoc["page-dictionary"])),
		"/Count" => count($pdfdoc["page-dictionary"]),
		);

	$pdfdoc["stream"][] = "4 0 obj";
	$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object));
	$pdfdoc["stream"][] = "endobj";

	################################################################################

	$pdfdoc["offsets"][5] = strlen(implode("\n", $pdfdoc["stream"]));

	$data = '<?xpacket?><x:xmpmeta xmlns:x="adobe:ns:meta/"><r:RDF xmlns:r="http://www.w3.org/1999/02/22-rdf-syntax-ns#"><r:Description xmlns:p="http://www.aiim.org/pdfa/ns/id/"><p:part>1</p:part><p:conformance>A</p:conformance></r:Description></r:RDF></x:xmpmeta><?xpacket?>';

	$object = array
		(
		"/Type" => "/Metadata",
		"/Subtype" => "/XML",
		"/Length" => strlen($data),
		);

	$pdfdoc["stream"][] = "5 0 obj";
		$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object));
		$pdfdoc["stream"][] = "stream";
			_put_stream($pdfdoc, $data);
		$pdfdoc["stream"][] = "endstream";
	$pdfdoc["stream"][] = "endobj";

	################################################################################

	foreach($pdfdoc["page-dictionary"] as $k => $v)
		{
		_new_object($pdfdoc);

			$object = array
				(
				"/Title" => _textstring($pdfdoc, "Seite " . ($k + 1), true),
				"/Parent" => "4 0 R",
				"/Dest" => sprintf("[%d 0 R /Fit]", 6 + $k * 2),
				);

			if($k + 1 != count($pdfdoc["page-dictionary"]))
				$object["/Next"] = sprintf("%d 0 R", $pdfdoc["reference-id"] + 1);

			if($k + 1 != 1)
				$object["/Prev"] = sprintf("%d 0 R", $pdfdoc["reference-id"] - 1);

		$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object));
		$pdfdoc["stream"][] = "endobj";
		}

	################################################################################

	$pdfdoc["info-dictionary"][0]["id"] = $pdfdoc["reference-id"] + 1;
	$pdfdoc["info-dictionary"][0]["version"] = 0;

	_new_object($pdfdoc);

	$object = array
		(
		"/Producer" => _textstring($pdfdoc, $pdfdoc["apiname"], true),
		"/CreationDate" => _textstring($pdfdoc, "D:" . date("YmdHis") . "Z", true),
		"/ModDate" => _textstring($pdfdoc, "D:" . date("YmdHis") . "Z", true),
#		"/Trapped" => "Unknown",
		);

	foreach(array("/Title", "/Author", "/Subject", "/Keywords", "/Creator") as $item)
		if(isset($pdfdoc["info-dictionary"][0]["dictionary"][$item]))
			$object[$item] = _textstring($pdfdoc, $pdfdoc["info-dictionary"][0]["dictionary"][$item], true);

	$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object));
	$pdfdoc["stream"][] = "endobj";

	################################################################################

#	$object = array
#		(
#		"/Linearized" => 1,
#		"/L" => 0,	# filesize inclusive size of size
#		"/H" => "[0 0],	# offset of catalog offset and length
#		"/O" => 0,	# offset of first page
#		"/E" => 0,	# offset of end of first page
#		"/N" => 0,	# number of pages in document (kids count)
#		"/T" => 0,	# offset of first xref entry after main xref first count
#		}

#	_new_object($pdfdoc);
#		$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object));
#	$pdfdoc["stream"][] = "endobj";

	################################################################################

	if($pdfdoc["encrypt"])
		{
		$pdfdoc["encrypt-dictionary"][0]["id"] = $pdfdoc["reference-id"] + 1;
		$pdfdoc["encrypt-dictionary"][0]["version"] = 0;

		$object = array
			(
			"/Filter" => "/Standard",
			"/V" => 1,
			"/R" => 2,
			"/O" => "(" . _escape($pdfdoc["encrypt-dictionary"][0]["dictionary"]["/O"]) . ")",
			"/U" => "(" . _escape($pdfdoc["encrypt-dictionary"][0]["dictionary"]["/U"]) . ")",
			"/P" => $pdfdoc["encrypt-dictionary"][0]["dictionary"]["/P"],
			);

		_new_object($pdfdoc);
		$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object));
		$pdfdoc["stream"][] = "endobj";
		}

	################################################################################

	$pdfdoc["offsets"][0] = strlen(implode("\n", $pdfdoc["stream"]));

	# 99 0 obj << /Type /XRef /Index [0 32] /W [1 2 2] /Filter /ASCIIHexDecode /Size 32 ... >> stream 00 0000 FFFF ... 02 000F 0000 02 000F 0001 02 000F 0002 ... 01 BA5E 0000 ... endstream endobj

	$pdfdoc["stream"][] = sprintf("xref %d %d", 0, $pdfdoc["reference-id"] + 1); # this
		$pdfdoc["stream"][] = sprintf("%010d %05d %s", 0, 65536, "f");
	
		foreach(range(1, $pdfdoc["reference-id"]) as $i)
			$pdfdoc["stream"][] = sprintf("%010d %05d %s", $pdfdoc["offsets"][$i] + 1, 0, "n");

	$object = array
		(
		"/Size" => $pdfdoc["reference-id"] + 1,
#		"/Prev" => "",
		"/Root" => "1 0 R",
		);

	if($pdfdoc["encrypt"])
		$object["/Encrypt"] = sprintf("%d 0 R", $pdfdoc["encrypt-dictionary"][0]["id"]);

	$object["/Info"] = sprintf("%d 0 R", $pdfdoc["info-dictionary"][0]["id"]);

	$id_a = "00000000000000000000000000000000";
	$id_b = "00000000000000000000000000000000";

	if($pdfdoc["encrypt"])
		{
#		$id_a = RC4(_objectkey($pdfdoc), $id_a);
#		$id_b = RC4(_objectkey($pdfdoc), $id_b);
		}

#	$object["/ID"] = sprintf("[<%s%> <%s>]", $id_a, $id_b);

	################################################################################

	$pdfdoc["stream"][] = "trailer";
	$pdfdoc["stream"][] = sprintf("<< %s >>", _pdf_glue_dictionary($object));

	################################################################################

	$pdfdoc["stream"][] = "startxref";
	$pdfdoc["stream"][] = $pdfdoc["offsets"][0] + 1;

	################################################################################

	$pdfdoc["stream"][] = "%%EOF";

#	print_r($pdfdoc);
#	print(json_encode($pdfdoc, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

################################################################################
# PDF_end_font - Terminate Type 3 font definition
# PDF_end_font ( resource $pdfdoc ) : bool
# Terminates a Type 3 font definition.
################################################################################

function pdf_end_font(& $pdfdoc)
	{
	}

################################################################################
# PDF_end_glyph - Terminate glyph definition for Type 3 font
# PDF_end_glyph ( resource $pdfdoc ) : bool
# Terminates a glyph definition for a Type 3 font.
################################################################################

function pdf_end_glyph(& $pdfdoc)
	{
	}

################################################################################
# PDF_end_item - Close structure element or other content item
# PDF_end_item ( resource $pdfdoc , int $id ) : bool
# Closes a structure element or other content item.
################################################################################

function pdf_end_item(& $pdfdoc, $id)
	{
	}

################################################################################
# PDF_end_layer - Deactivate all active layers
# PDF_end_layer ( resource $pdfdoc ) : bool
# Deactivates all active layers. Returns TRUE on success or FALSE on failure.
# This function requires PDF 1.5.
################################################################################

function pdf_end_layer(& $pdfdoc)
	{
	}

################################################################################
# PDF_end_page - Finish page
# PDF_end_page ( resource $p ) : bool
# Finishes the page. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_end_page(& $p)
	{
	return(pdf_end_page_ext($p, array()));
	}

################################################################################
# PDF_end_page_ext - Finish page
# PDF_end_page_ext ( resource $pdfdoc , string $optlist ) : bool
# Finishes a page, and applies various options. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_end_page_ext(& $pdfdoc, $optlist)
	{
	$object = array
		(
		"id" => 6 + (2 * count($pdfdoc["page-dictionary"])),
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Page",
			"/Parent" => "2 0 R",
			"/MediaBox" => sprintf("[0 0 %d %d]", $pdfdoc["/Width"], $pdfdoc["/Height"]),
			"/Resources" => "3 0 R",
			"/Contents" => sprintf("%d 0 R", 7 + (2 * count($pdfdoc["page-dictionary"])))
			),
		"stream" => $pdfdoc["stream"]
		);

	foreach($optlist as $k => $v)
		{
		$object[$key] = $value;
		}

	if(isset($pdfdoc["/Dur"]))
		$object["/Dur"] = $pdfdoc["/Dur"];

	$pdfdoc["page-dictionary"][] = $object;

	return($pdfdoc["reference-id"] = $pdfdoc["reference-id"] + 2);
	}

################################################################################
# PDF_end_pattern - Finish pattern
# PDF_end_pattern ( resource $p ) : bool
# Finishes the pattern definition. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_end_pattern(& $pdfdoc)
	{
	}

################################################################################
# PDF_end_template - Finish template
# PDF_end_template ( resource $p ) : bool
# Finishes a template definition. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_end_template(& $pdfdoc)
	{
	}

################################################################################
# PDF_endpath - End current path
# PDF_endpath ( resource $p ) : bool
# Ends the current path without filling or stroking it.
################################################################################

function pdf_endpath(& $p)
	{
	$p["stream"][] = "n";
	}
	
################################################################################
# PDF_fill - Fill current path
# PDF_fill ( resource $p ) : bool
# Fills the interior of the current path with the current fill color. Returns TRUE on success or FALSE on failure.
#
################################################################################

function pdf_fill(& $p)
	{
	$p["stream"][] = "f";
	}

################################################################################
# PDF_fill_imageblock - Fill image block with variable data
# PDF_fill_imageblock ( resource $pdfdoc , int $page , string $blockname , int $image , string $optlist ) : int
# Fills an image block with variable data according to its properties.
# This function is only available in the PDFlib Personalization Server (PPS).
################################################################################

function pdf_fill_imageblock(& $pdfdoc, $page, $blockname, $image, $optlist)
	{
	}

################################################################################
# PDF_fill_pdfblock - Fill PDF block with variable data
# PDF_fill_pdfblock ( resource $pdfdoc , int $page , string $blockname , int $contents , string $optlist ) : int
# Fills a PDF block with variable data according to its properties.
# This function is only available in the PDFlib Personalization Server (PPS).
################################################################################

function pdf_fill_pdfblock(& $pdfdoc, $page, $blockname, $contents, $optlist)
	{
	}

################################################################################
# PDF_fill_stroke - Fill and stroke path
# PDF_fill_stroke ( resource $p ) : bool
# Fills and strokes the current path with the current fill and stroke color. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_fill_stroke(& $p)
	{
	$p["stream"][] = "B";
	}

################################################################################
# PDF_fill_textblock - Fill text block with variable data
# PDF_fill_textblock ( resource $pdfdoc , int $page , string $blockname , string $text , string $optlist ) : int
# Fills a text block with variable data according to its properties.
# This function is only available in the PDFlib Personalization Server (PPS).
################################################################################

function pdf_fill_textblock(& $pdfdoc, $page, $blockname, $text, $optlist)
	{
	}

################################################################################
# PDF_findfont - Prepare font for later use [deprecated]
# PDF_findfont ( resource $p , string $fontname , string $encoding , int $embed ) : int
# Search for a font and prepare it for later use with PDF_setfont(). The metrics will be loaded, and if embed is nonzero, the font file will be checked, but not yet used. encoding is one of builtin, macroman, winansi, host, a user-defined encoding name or the name of a CMap. Parameter embed is optional before PHP 4.3.5 or with PDFlib less than 5.
# This function is deprecated since PDFlib version 5, use PDF_load_font() instead.
################################################################################

function pdf_findfont(& $p, $fontname, $encoding = "builtin", $embed = 0)
	{
	if(in_array($encoding, array("builtin", "macroman", "winansi", "host")) === false)
		die("unknown encoding in pdf_load_font");

	foreach($p["font-dictionary"] as $k => $v)
		{
		if($k == $fontname)
			return($k);

		if(($v["dictionary"]["/BaseFont"] == $fontname) || ($v["dictionary"]["/FontName"] == $fontname))
			return($k);
		}

	return(pdf_load_font($p, $fontname, $encoding, array("embed" => intval($embed))));
	}

################################################################################
# PDF_fit_image - Place image or template
# PDF_fit_image ( resource $pdfdoc , int $image , float $x , float $y , string $optlist ) : bool
# Places an image or template on the page, subject to various options. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_fit_image(& $pdfdoc, $image, $x, $y, $optlist)
	{
	$s = $optlist["scale"] / 96 * 72; # px to inch to pt

	$w = $image["dictionary"]["/Width"] / 96 * 72; # px to inch to pt
	$h = $image["dictionary"]["/Height"] / 96 * 72; # px to inch to pt

	pdf_save($pdfdoc);
	$pdfdoc["stream"][] = sprintf("%f 0 0 %f %f %f cm", $w * $s, $h * $s, $x, $y);
	$pdfdoc["stream"][] = sprintf("%s Do", $image["x-procset-x-object-image-id"]); # Invoke named XObject
	pdf_restore($pdfdoc);
	}

################################################################################
# PDF_fit_pdi_page - Place imported PDF page
# PDF_fit_pdi_page ( resource $pdfdoc , int $page , float $x , float $y , string $optlist ) : bool
# Places an imported PDF page on the page, subject to various options. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_fit_pdi_page(& $pdfdoc, $page, $x, $y, $optlist)
	{
	}

################################################################################
# PDF_fit_table - Place table on page
# PDF_fit_table ( resource $pdfdoc , int $table , float $llx , float $lly , float $urx , float $ury , string $optlist ) : string
# Places a table on the page fully or partially.
################################################################################

function pdf_fit_table(& $pdfdoc, $table, $llx, $lly, $urx, $ury, $optlist)
	{
	}

################################################################################
# PDF_fit_textflow - Format textflow in rectangular area
# PDF_fit_textflow ( resource $pdfdoc , int $textflow , float $llx , float $lly , float $urx , float $ury , string $optlist ) : string
# Formats the next portion of a textflow into a rectangular area.
################################################################################

function pdf_fit_textflow(& $pdfdoc, $text, $llx, $lly, $urx, $ury, $optlist)
	{
	}

################################################################################
# PDF_fit_textline - Place single line of text
# PDF_fit_textline ( resource $pdfdoc , string $text , float $x , float $y , string $optlist ) : bool
# Places a single line of text on the page, subject to various options. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_fit_textline(& $pdfdoc, $text, $x, $y, $optlist)
	{
	}

################################################################################
# PDF_get_apiname - Get name of unsuccessfull API function
# PDF_get_apiname ( resource $pdfdoc ) : string
# Gets the name of the API function which threw the last exception or failed.
################################################################################

function pdf_get_apiname(& $pdfdoc)
	{
	return($pdfdoc["apiname"]);
	}

################################################################################
# PDF_get_buffer - Get PDF output buffer
# PDF_get_buffer ( resource $p ) : string
# Fetches the buffer containing the generated PDF data.
################################################################################

function pdf_get_buffer(& $p)
	{
	return(implode("\n", $p["stream"]));
	}

################################################################################
# PDF_get_errmsg - Get error text
# PDF_get_errmsg ( resource $pdfdoc ) : string
# Gets the text of the last thrown exception or the reason for a failed function call.
################################################################################

function pdf_get_errmsg(& $pdfdoc)
	{
	}

################################################################################
# PDF_get_errnum - Get error number
# PDF_get_errnum ( resource $pdfdoc ) : int
# Gets the number of the last thrown exception or the reason for a failed function call.
################################################################################

function pdf_get_errnum(& $pdfdoc)
	{
	}

################################################################################
# PDF_get_font - Get font [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_get_value() with the parameter font instead.
################################################################################

function pdf_get_font(& $p)
	{
	return(pdf_get_value($p, "font", 0));
	}

################################################################################
# PDF_get_fontname - Get font name [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_get_parameter() with the parameter fontname instead.
################################################################################

function pdf_get_fontname(& $p, $font)
	{
	return(pdf_get_value($p, "/FontName", $font));
	}

################################################################################
# PDF_get_fontsize - Font handling [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_get_value() with the parameter fontsize instead.
################################################################################

function pdf_get_fontsize(& $p)
	{
	return(pdf_get_value($p, "fontsize", 0));
	}

################################################################################
# PDF_get_image_height - Get image height [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_get_value() with the parameter imageheight instead.
################################################################################

function pdf_get_image_height(& $p, $image)
	{
	return(pdf_get_value($p, "imageheight", $image));
	}

################################################################################
# PDF_get_image_width - Get image width [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_get_value() with the parameter imagewidth instead.
################################################################################

function pdf_get_image_width(& $p, $image)
	{
	return(pdf_get_value($p, "imagewidth", $image));
	}

################################################################################
# PDF_get_majorversion - Get major version number [deprecated]
# PDF_get_majorversion ( void ) : int
# This function is deprecated since PDFlib version 5, use PDF_get_value() with the parameter major instead.
################################################################################

function pdf_get_majorversion()
	{
	$p = pdf_new();

	return(pdf_get_value($p, "major", 0));
	}

################################################################################
# PDF_get_minorversion - Get minor version number [deprecated]
# PDF_get_minorversion ( void ) : int
# Returns the minor version number of the PDFlib version.
# This function is deprecated since PDFlib version 5, use PDF_get_value() with the parameter minor instead.
################################################################################

function pdf_get_minorversion()
	{
	$p = pdf_new();

	return(pdf_get_value($p, "minor", 0));
	}

################################################################################
# PDF_get_parameter - Get string parameter
# PDF_get_parameter ( resource $p , string $key , float $modifier ) : string
# Gets the contents of some PDFlib parameter with string type.
################################################################################

function pdf_get_parameter(& $p, $key, $modifier)
	{
	return($p[$key]);
	}

################################################################################
# PDF_get_pdi_parameter - Get PDI string parameter [deprecated]
# PDF_get_pdi_parameter ( resource $p , string $key , int $doc , int $page , int $reserved ) : string
# Gets the contents of a PDI document parameter with string type.
# This function is deprecated since PDFlib version 7, use PDF_pcos_get_string() instead.
################################################################################

function pdf_get_pdi_parameter(& $p, $key, $doc, $page, $reserved)
	{
	$path = "";

	return(pdf_pcos_get_string($p, $doc, $path));
	}

################################################################################
# PDF_get_pdi_value - Get PDI numerical parameter [deprecated]
# PDF_get_pdi_value ( resource $p , string $key , int $doc , int $page , int $reserved ) : float
# Gets the contents of a PDI document parameter with numerical type.
# This function is deprecated since PDFlib version 7, use PDF_pcos_get_number() instead.
################################################################################

function pdf_get_pdi_value(& $p, $key, $doc, $page, $reserved)
	{
	$path = "";

	return(pdf_pcos_get_number($p, $doc, $path));
	}

################################################################################
# PDF_get_value - Get numerical parameter
# PDF_get_value ( resource $p , string $key , float $modifier ) : float
# Gets the value of some PDFlib parameter with numerical type.
################################################################################

function pdf_get_value(& $p, $key, $modifier)
	{
	switch($key)
		{
		case("font"):
			return($p[$key]);
		case("/FontName"):
			return($p["font-dictionary"][$modifier]["dictionary"]["/FontName"]);
		case("fontsize"):
			return($p[$key]);
		case("imageheight"):
			return($p["image-dictionary"][$modifier]["dictionary"]["/Height"]);
		case("imagewidth"):
			return($p["image-dictionary"][$modifier]["dictionary"]["/Width"]);
		case("major"):
			return($p["major"]);
		case("minor"):
			return($p["minor"]);
		}
	}

################################################################################
# PDF_info_font - Query detailed information about a loaded font
# PDF_info_font ( resource $pdfdoc , int $font , string $keyword , string $optlist ) : float
# Queries detailed information about a loaded font.
################################################################################

function pdf_info_font(& $pdfdoc, $font, $keyword, $optlist)
	{
	return($pdfdoc["font-dictionary"][$font]["dictionary"][$keyword]);
	}

################################################################################
# PDF_info_matchbox - Query matchbox information
# PDF_info_matchbox ( resource $pdfdoc , string $boxname , int $num , string $keyword ) : float
# Queries information about a matchbox on the current page.
################################################################################

function pdf_info_matchbox(& $pdfdoc, $boxname, $num, $keyword)
	{
	}

################################################################################
# PDF_info_table - Retrieve table information
# PDF_info_table ( resource $pdfdoc , int $table , string $keyword ) : float
# Retrieves table information related to the most recently placed table instance.
################################################################################

function pdf_info_table(& $pdfdoc, $table, $keyword)
	{
	}

################################################################################
# PDF_info_textflow - Query textflow state
# PDF_info_textflow ( resource $pdfdoc , int $textflow , string $keyword ) : float
# Queries the current state of a textflow.
################################################################################

function pdf_info_textflow(& $pdfdoc, $textflow, $keyword)
	{
	}

################################################################################
# PDF_info_textline - Perform textline formatting and query metrics
# PDF_info_textline ( resource $pdfdoc , string $text , string $keyword , string $optlist ) : float
# Performs textline formatting and queries the resulting metrics.
################################################################################

function pdf_info_textline(& $pdfdoc, $text, $keyword, $optlist)
	{
	}

################################################################################
# PDF_initgraphics - Reset graphic state
# PDF_initgraphics ( resource $p ) : bool
# Reset all color and graphics state parameters to their defaults. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_initgraphics(& $p)
	{
	}

################################################################################
# PDF_lineto - Draw a line
# PDF_lineto ( resource $p , float $x , float $y ) : bool
# Draws a line from the current point to another point. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_lineto(& $p, $x, $y)
	{
	$p["stream"][] = sprintf("%f %f l", $x, $y);
	}

################################################################################
# PDF_load_3ddata - Load 3D model
# PDF_load_3ddata ( resource $pdfdoc , string $filename , string $optlist ) : int
# Loads a 3D model from a disk-based or virtual file.
# This function requires PDF 1.6.
################################################################################

function pdf_load_3ddata(& $pdfdoc, $filename, $optlist)
	{
	}

################################################################################
# PDF_load_font - Search and prepare font
# PDF_load_font ( resource $pdfdoc , string $fontname , string $encoding , string $optlist ) : int
# Searches for a font and prepares it for later use.
################################################################################

function pdf_load_font(& $pdfdoc, $fontname, $encoding = "builtin", $optlist = array())
	{
	if(in_array($encoding, array("builtin", "macroman", "winansi", "host")) === false)
		die("unknown encoding in pdf_load_font");

	$settings = array
		(
		"embedding" => true,
		"subsetting" => true,
		"subsetlimit" => 50,
		"kerning" => false
		);

	$object = array
		(
		"dictionary" => array
			(
			"/Type" => "/Font",
			"/Subtype" => "",
			"/BaseFont" => $fontname,
			"/FontName" => $fontname,
			"/Encoding" => $encoding
			),
		);

	foreach($pdfdoc["Core"] as $k => $v)
		{
		if(($v["/BaseFont"] == $fontname) || ($v["/FontName"] == $fontname))
			{
			$object["dictionary"]["/Subtype"] = "/Core";

			foreach(range(0x00, 0xFF) as $char)
				$object["dictionary"]["/Widths"][chr($char)] = $v["/Widths"][$char];

			$object["x-procset-font-id"] = "/F" . count($pdfdoc["font-dictionary"]);

			$pdfdoc["font-dictionary"][$fontname] = $object;

			return(pdf_findfont($pdfdoc, $fontname, $encoding, 0));
			}
		}

	if(file_exists("/usr/share/fonts/truetype/freefont/" . $fontname . ".ttf") === false)
		return(pdf_load_font($pdfdoc, "Helvetica", $encoding, $optlist));

	$object["dictionary"]["/Subtype"] = "/TrueType";

	if((isset($optlist["embed"]) === true) && ($optlist["embed"] == 1))
		$object["dictionary"]["/FontFile"] = "/usr/share/fonts/truetype/freefont/" . $fontname . ".ttf";

	$object["dictionary"]["/FontDescriptor"] = array
		(
		"/Ascent" => 720,
		"/CapHeight" => 720,
		"/Descent" => 0 - 250,
		"/Flags" => 32,
		"/FontBBox" => "[0 -240 1440 1000]",
		"/ItalicAngle" => 0,
		"/StemV" => 90,
		"/XHeight" => 480
		);

	foreach(range(0x00, 0xFF) as $char)
		$object["dictionary"]["/Widths"][chr($char)] = (($image_ttf_bbox = imagettfbbox($object["dictionary"]["/FontDescriptor"]["/CapHeight"], 0, "/usr/share/fonts/truetype/freefont/" . $fontname . ".ttf", chr($char))) ? $image_ttf_bbox[2] : 1000);

	$object["x-procset-font-id"] = "/F" . count($pdfdoc["font-dictionary"]);

	$pdfdoc["font-dictionary"][$fontname] = $object;

	return(pdf_findfont($pdfdoc, $fontname, $encoding, 0));
	}

################################################################################
# PDF_load_iccprofile - Search and prepare ICC profile
# PDF_load_iccprofile ( resource $pdfdoc , string $profilename , string $optlist ) : int
# Searches for an ICC profile, and prepares it for later use.
################################################################################

function pdf_load_iccprofile(& $pdfdoc, $profilename, $optlist)
	{
	}

################################################################################
# PDF_load_image - Open image file
# PDF_load_image ( resource $pdfdoc , string $imagetype , string $filename , string $optlist ) : int
# Opens a disk-based or virtual image file subject to various options.
################################################################################

function pdf_load_image(& $pdfdoc, $imagetype, $filename, $optlist)
	{
	if(isset($pdfdoc["image-dictionary"][$filename]))
		return($pdfdoc["image-dictionary"][$filename]);

	if($imagetype == "")
		{
		if(($pos = strrpos($filename, ".")) === false)
			die("Image file has no extension and no type was specified: " . $filename);

		$imagetype = substr($filename, $pos + 1);
		}

	$imagetype = strtolower($imagetype);

	if(($imagetype == "cit") || ($imagetype == "ccitt"))
		{
		}

	if($imagetype == "gif")
		{
		$object = _parse_image_gif($pdfdoc, $filename);
		}

	if(($imagetype == "jpg") || ($imagetype == "jpeg"))
		{
		$object = _parse_image_jpg($pdfdoc, $filename);
		}

	if(($imagetype == "tif") || ($imagetype == "tiff"))
		{
		}

	if($imagetype == "png")
		{
		$object = _parse_image_png($pdfdoc, $filename);
		}

	$object["x-procset-x-object-image-id"] = "/XI" . count($pdfdoc["image-dictionary"]);

	return($pdfdoc["image-dictionary"][$filename] = $object);
	}

################################################################################
# PDF_makespotcolor - Make spot color
# PDF_makespotcolor ( resource $p , string $spotname ) : int
# Finds a built-in spot color name, or makes a named spot color from the current fill color. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_makespotcolor(& $p, $spotname)
	{
	}

################################################################################
# PDF_moveto - Set current point
# PDF_moveto ( resource $p , float $x , float $y ) : bool
# Sets the current point for graphics output. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_moveto(& $p, $x, $y)
	{
	$p["stream"][] = sprintf("%f %f m", $x, $y);
	}

################################################################################
# PDF_new - Create PDFlib object
# PDF_new ( void ) : resource
# Creates a new PDFlib object with default settings.
################################################################################

function pdf_new()
	{
	################################################################################
	# init array
	################################################################################

	$retval = array
		(
		"Core" => array
			(
			# widths are needed for stringwidth
			array
				(
				"/BaseFont" => "courier",
				"/FontName" => "Courier",
				"/Widths" => array_fill(0, 256, 707)
				),
			array
				(
				"/BaseFont" => "courierb",
				"/FontName" => "Courier-Bold",
				"/Widths" => array_fill(0, 256, 707),
				),
			array
				(
				"/BaseFont" => "courierbi",
				"/FontName" => "Courier-BoldOblique",
				"/Widths" => array_fill(0, 256, 707),
				),
			array
				(
				"/BaseFont" => "courieri",
				"/FontName" => "Courier-Oblique",
				"/Widths" => array_fill(0, 256, 707),
				),
			array
				(
				"/BaseFont" => "helvetica",
				"/FontName" => "Helvetica",
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
					),
				),
			array
				(
				"/BaseFont" => "helveticab",
				"/FontName" => "Helvetica-Bold",
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
				"/BaseFont" => "helveticabi",
				"/FontName" => "Helvetica-BoldOblique",
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
				"/BaseFont" => "helveticai",
				"/FontName" => "Helvetica-Oblique",
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
					),
				),
			array
				(
				"/BaseFont" => "symbol",
				"/FontName" => "Symbol",
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
					 ),
				),
			array
				(
				"/BaseFont" => "times",
				"/FontName" => "Times-Roman",
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
					),
				),
			array
				(
				"/BaseFont" => "timesb",
				"/FontName" => "Times-Bold",
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
					),
				),
			array
				(
				"/BaseFont" => "timesbi",
				"/FontName" => "Times-BoldOblique",
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
					),
				),
			array
				(
				"/BaseFont" => "timesi",
				"/FontName" => "Times-Oblique",
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
					),
				),
			array
				(
				"/BaseFont" => "zapfdingbats",
				"/FontName" => "ZapfDingbats",
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
			),

		################################################################################

		"action-dictionary" => array
			(
			),

		################################################################################

		"annotation-dictionary" => array
			(
			),

		################################################################################
		# pdf_begin_document(& $pdfdoc, $filename, $optlist)
		################################################################################

		"catalog-dictionary" => array
			(
			),

		################################################################################

		"encrypt-dictionary" => array
			(
			array
				(
				"dictionary" => array
					(
					"protection" => 0xC0,
					"padding" => hex2bin("28BF4E5E4E758A4164004E56FFFA01082E2E00B6D0683E802F0CA9FE6453697A"),
					"o-pass" => "mysecretownerpass",
					"u-pass" => "",
					"e-pass" => "",
					"/O" => "",
					"/U" => "",
					"/P" => 0
					),
				),
			),

		################################################################################

		"file-dictionary" => array
			(
			),

		################################################################################
		# pdf_load_font(& $pdfdoc, $fontname, $encoding = "builtin", $optlist = array())
		################################################################################

		"font-dictionary" => array
			(
			),

		################################################################################

		"form-dictionary" => array
			(
			),

		################################################################################
		# pdf_load_image(& $pdfdoc, $imagetype, $filename, $optlist)
		################################################################################

		"image-dictionary" => array
			(
			),

		################################################################################

		"info-dictionary" => array
			(
			array
				(
				"dictionary" => array
					(
					"/Author" => "Markus Olderdissen",
					"/Creator" => basename(__FILE__),
					"/Producer" => basename(__FILE__),
					"/Keywords" => "Dokumentvorlagen",
					"/Subject" => "Dokumentvorlagen",
					"/Title" => "Dokumentvorlagen",
					"/CreationDate" => "(D:19700101000000Z)",
					"/ModDate" => "(D:19700101000000Z)"
					)
				)
			),

		################################################################################
		# pdf_begin_page_ext(& $pdfdoc, $width, $height, $optlist)
		# pdf_end_page_ext(& $pdfdoc, $optlist)
		################################################################################

		"page-dictionary" => array
			(
			),

		################################################################################

#		"filter-dictionary" => array
#			(
#			array
#				(
#				"dictionary" => array
#					(
#					"/Filter" => "/FlateDecode",
#					"/Filter" => "[/ASCIIHexDecode /FlateDecode]",
#					),
#				),
#			),

		################################################################################

		"major" => 1,
		"minor" => 3,
		"apiname" => sprintf("PDFlib Lite Clone %d.%d.%d (PHP/%s)", 1, 0, 0, PHP_OS),
		"apiversion" => 3,

		################################################################################

		"reference-id" => 0, # id is needed for encryption. this is where first page begin.
		"/Height" => 0, # Height is transfered to dictionary of object for page after pdf_end_page has been called.
		"/Width" => 0, # Width is transfered to dictionary of object for page after pdf_end_page has been called.
		"/Dur" => 0, # Width is transfered to dictionary of object for page after pdf_end_page has been called.
		"/FirstChar" => 255,
		"/LastChar" => 0,
		"/ProcSet" => array("/PDF"),
		"stream" => array
			(
			), # stream is transfered to stream of object for page after pdf_end_page has been called.

		"font" => null, # current working font id
		"fontsize" => 0, # 12 x 6 = 72

		"offsets" => array
			(
			),
		"encrypt" => false,
		"license" => "xxxxxxx-xxxxxx-xxxxxx-xxxxxx"
		);

	if($retval["encrypt"])
		{
		$options = array
			(
			"print" => 0x04,
			"modify" => 0x08,
			"copy" => 0x10,
			"annot-forms" => 0x20
			);

		foreach(array("o-pass", "u-pass") as $k)
			$retval["encrypt-dictionary"][0]["dictionary"][$k] = substr($retval["encrypt-dictionary"][0]["dictionary"][$k] . $retval["encrypt-dictionary"][0]["dictionary"]["padding"], 0, 32);

		$retval["encrypt-dictionary"][0]["dictionary"]["/O"] = RC4(substr(_md5_16($retval["encrypt-dictionary"][0]["dictionary"]["o-pass"]), 0, 5), $retval["encrypt-dictionary"][0]["dictionary"]["u-pass"]);
		$retval["encrypt-dictionary"][0]["dictionary"]["e-pass"] = substr(_md5_16($retval["encrypt-dictionary"][0]["dictionary"]["u-pass"] . $retval["encrypt-dictionary"][0]["dictionary"]["/O"] . chr($retval["encrypt-dictionary"][0]["dictionary"]["protection"]) . hex2bin("FFFFFF")), 0, 5);
		$retval["encrypt-dictionary"][0]["dictionary"]["/U"] = RC4($retval["encrypt-dictionary"][0]["dictionary"]["e-pass"], $retval["encrypt-dictionary"][0]["dictionary"]["padding"]);
		$retval["encrypt-dictionary"][0]["dictionary"]["/P"] = 0 - (($retval["encrypt-dictionary"][0]["dictionary"]["protection"] ^ 0xFF) + 1);
		}

	return($retval);
	}

################################################################################
# PDF_open_ccitt - Open raw CCITT image [deprecated]
# PDF_open_ccitt ( resource $pdfdoc , string $filename , int $width , int $height , int $BitReverse , int $k , int $Blackls1 ) : int
# Opens a raw CCITT image.
# This function is deprecated since PDFlib version 5, use PDF_load_image() instead.
################################################################################

function pdf_open_ccitt(& $pdfdoc, $filename, $width, $height, $BitReverse, $k, $Blackls1)
	{
	return(pdf_load_image($pdfdoc, "ccitt", $filename, array("/Width" => $width, "/Height" => $height, "bitreverse" => $BitReverse, "k" => $k, "blacklsl" => $Blackls1)));
	}

################################################################################
# PDF_open_file - Create PDF file [deprecated]
# PDF_open_file ( resource $p , string $filename ) : bool
# Creates a new PDF file using the supplied file name. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use PDF_begin_document() instead.
################################################################################

function pdf_open_file(& $p, $filename)
	{
	return(pdf_begin_document($p, $filename, array()));
	}

################################################################################
# PDF_open_gif - Open GIF image [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_load_image() instead.
################################################################################

function pdf_open_gif(& $p, $filename)
	{
	return(pdf_load_image($p, "gif", $filename, array()));
	}

################################################################################
# PDF_open_image_file - Read image from file [deprecated]
# PDF_open_image_file ( resource $p , string $imagetype , string $filename , string $stringparam , int $intparam ) : int
# Opens an image file.
# This function is deprecated since PDFlib version 5, use PDF_load_image() with the colorize, ignoremask, invert, mask, masked, and page options instead.
################################################################################

function pdf_open_image_file(& $p, $imagetype, $filename, $stringparam = "", $intparam = 0)
	{
	return(pdf_load_image($p, $imagetype, $filename, array("colorize" => 0, "ignoremask" => 0, "invert" => 0, "mask" => 0, "masked" => 0, "page" => 0)));
	}

################################################################################
# PDF_open_image - Use image data [deprecated]
# PDF_open_image ( resource $p , string $imagetype , string $source , string $data , int $length , int $width , int $height , int $components , int $bpc , string $params ) : int
# Uses image data from a variety of data sources.
# This function is deprecated since PDFlib version 5, use virtual files and PDF_load_image() instead.
################################################################################

function pdf_open_image(& $p, $imagetype, $source, $data, $length, $width, $height, $component, $bpc, $params)
	{
	return(pdf_load_image($p, $imagetype, "", array("data" => $data, "/Length" => $length, "/Width" => $width, "/Height" => $height, "component" => $component, "bits_per_component" => $bpc, "params" => $params)));
	}

################################################################################
# PDF_open_jpeg - Open JPEG image [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_load_image() instead.
################################################################################

function pdf_open_jpeg(& $p, $filename)
	{
	return(pdf_load_image($p, "jpeg", $filename, array()));
	}

################################################################################
# PDF_open_memory_image - Open image created with PHP's image functions [not supported]
# PDF_open_memory_image ( resource $p , resource $image ) : int
# This function is not supported by PDFlib GmbH.
################################################################################

function pdf_open_memory_image(& $p, $image)
	{
	# pdf_open_image uses php's image functions
	# smells like imagecreatefromgif(); used for png manipulation
	# use pdf_load_image
	}

################################################################################
# PDF_open_pdi_document - Prepare a pdi document
# PDF_open_pdi_document ( resource $p , string $filename , string $optlist ) : int
# Open a disk-based or virtual PDF document and prepare it for later use.
################################################################################

function pdf_open_pdi_document(& $p, $filename, $optlist)
	{
	$p["filename"] = $filename;

	$p["stream"] = file_get_contents($p["filename"]);

	if(preg_match("/(%pdf-(\d+)\.(\d+)[\s|\n]+)((.*)[\s|\n]+)(startxref[\s|\n]+(\d+)[\s|\n]+)(%%eof[\s|\n]+).*/is", $p["stream"], $matches) == 1)
		{
		}
	}

################################################################################
# PDF_open_pdi_page - Prepare a page
# PDF_open_pdi_page ( resource $p , int $doc , int $pagenumber , string $optlist ) : int
# Prepares a page for later use with PDF_fit_pdi_page().
################################################################################

function pdf_open_pdi_page(& $p, $doc, $pagenumber, $optlist)
	{
	}

################################################################################
# PDF_open_pdi - Open PDF file [deprecated]
# PDF_open_pdi ( resource $pdfdoc , string $filename , string $optlist , int $len ) : int
# Opens a disk-based or virtual PDF document and prepares it for later use.
# This function is deprecated since PDFlib version 7, use PDF_open_pdi_document() instead.
################################################################################

function pdf_open_pdi(& $pdfdoc, $filename, $optlist)
	{
	return(pdf_open_pdi_document($pdfdoc, $filename, $optlist));
	}

################################################################################
# PDF_open_tiff - Open TIFF image [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_load_image() instead.
################################################################################

function pdf_open_tiff(& $p, $filename)
	{
	return(pdf_load_image($p, "tiff", $filename, array()));
	}

################################################################################
# PDF_pcos_get_number - Get value of pCOS path with type number or boolean
# PDF_pcos_get_number ( resource $p , int $doc , string $path ) : float
# Gets the value of a pCOS path with type number or boolean.
################################################################################

function pdf_pcos_get_number(& $pdfdoc, $doc, $path)
	{
	}

################################################################################
# PDF_pcos_get_stream - Get contents of pCOS path with type stream, fstream, or string
# PDF_pcos_get_stream ( resource $p , int $doc , string $optlist , string $path ) : string
# Gets the contents of a pCOS path with type stream, fstream, or string.
################################################################################

function pdf_pcos_get_stream(& $pdfdoc, $doc, $optlist, $path)
	{
	}

################################################################################
# PDF_pcos_get_string - Get value of pCOS path with type name, string, or boolean
# PDF_pcos_get_string ( resource $p , int $doc , string $path ) : string
# Gets the value of a pCOS path with type name, string, or boolean.
#
################################################################################

function pdf_pcos_get_string(& $pdfdoc, $doc, $path)
	{
	}

################################################################################
# PDF_place_image - Place image on the page [deprecated]
# PDF_place_image ( resource $pdfdoc , int $image , float $x , float $y , float $scale ) : bool
# Places an image and scales it. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 5, use PDF_fit_image() instead.
################################################################################

function pdf_place_image(& $pdfdoc, $image, $x, $y, $scale)
	{
	pdf_fit_image($pdfdoc, $image, $x, $y, array("scale" => $scale));
	}

################################################################################
# PDF_place_pdi_page - Place PDF page [deprecated]
# PDF_place_pdi_page ( resource $pdfdoc , int $page , float $x , float $y , float $sx , float $sy ) : bool
# Places a PDF page and scales it. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 5, use PDF_fit_pdi_page() instead.
################################################################################

function pdf_place_pdi_page(& $pdfdoc, $page, $x, $y, $sx, $sy)
	{
	pdf_fit_pdi_page($pdfdoc, $page, $x, $y, array("sx" => $sx, "sy" => $sy));
	}

################################################################################
# PDF_process_pdi - Process imported PDF document
# PDF_process_pdi ( resource $pdfdoc , int $doc , int $page , string $optlist ) : int
# Processes certain elements of an imported PDF document.
################################################################################

function pdf_process_pdi(& $pdfdoc, $doc, $page, $optlist)
	{
	}

################################################################################
# PDF_rect - Draw rectangle
# PDF_rect ( resource $p , float $x , float $y , float $width , float $height ) : bool
# Draws a rectangle. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_rect(& $p, $x, $y, $width, $height)
	{
	$p["stream"][] = sprintf("%f %f %f %f re", $x, $y, $width, $height);
	}

################################################################################
# PDF_restore - Restore graphics state
# PDF_restore ( resource $p ) : bool
# Restores the most recently saved graphics state. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_restore(& $p)
	{
	$p["stream"][] = "Q";
	}

################################################################################
# PDF_resume_page - Resume page
# PDF_resume_page ( resource $pdfdoc , string $optlist ) : bool
# Resumes a page to add more content to it.
################################################################################

function pdf_resume_page(& $pdfdoc, $optlist)
	{
	}

################################################################################
# PDF_rotate - Rotate coordinate system
# PDF_rotate ( resource $p , float $phi ) : bool
# Rotates the coordinate system. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_rotate(& $p, $phi)
	{
	$sin = sin($phi * M_PI / 180);
	$cos = cos($phi * M_PI / 180);

	pdf_concat($p, 0 + $cos, 0 + $sin, 0 - $sin, 0 + $cos, 0, 0);
	}

################################################################################
# PDF_save - Save graphics state
# PDF_save ( resource $p ) : bool
# Saves the current graphics state. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_save(& $p)
	{
	$p["stream"][] = "q";
	}

################################################################################
# PDF_scale — Scale coordinate system
# PDF_scale ( resource $p , float $sx , float $sy ) : bool
# Scales the coordinate system. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_scale(& $p, $sx, $sy)
	{
	pdf_concat($p, $sx, 0, 0, $sy, 0, 0);
	}

################################################################################
# PDF_set_border_color — Set border color of annotations [deprecated]
# PDF_set_border_color ( resource $p , float $red , float $green , float $blue ) : bool
# Sets the border color for all kinds of annotations. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use the option annotcolor in PDF_create_annotation() instead.
################################################################################

function pdf_set_border_color(& $p, $red, $green, $blue)
	{
	}

################################################################################
# PDF_set_border_dash — Set border dash style of annotations [deprecated]
# PDF_set_border_dash ( resource $pdfdoc , float $black , float $white ) : bool
# Sets the border dash style for all kinds of annotations. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use the option dasharray in PDF_create_annotation() instead.
################################################################################

function pdf_set_border_dash(& $p, $black, $white)
	{
	}

################################################################################
# PDF_set_border_style — Set border style of annotations [deprecated]
# PDF_set_border_style ( resource $pdfdoc , string $style , float $width ) : bool
# Sets the border style for all kinds of annotations. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use the options borderstyle and linewidth in PDF_create_annotation() instead.
################################################################################

function pdf_set_border_style(& $p, $style, $width)
	{
	}

################################################################################
# PDF_set_char_spacing — Set character spacing [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_value() with parameter charspacing instead.
################################################################################

function pdf_set_char_spacing(& $pdfdoc, $space)
	{
	pdf_set_value($pdfdoc, "charspacing", $space);
	}

################################################################################
# PDF_set_duration - Set duration between pages [deprecated]
# This function is deprecated since PDFlib version 3, use the duration option in PDF_begin_page_ext() or PDF_end_page_ext() instead.
################################################################################

function pdf_set_duration(& $pdfdoc, $duration)
	{
	$pdfdoc["/Dur"] = $duration;
	}

################################################################################
# PDF_set_gstate - Activate graphics state object
# PDF_set_gstate ( resource $pdfdoc , int $gstate ) : bool
# Activates a graphics state object.
################################################################################

function pdf_set_gstate(& $pdfdoc, $gstate)
	{
	}

################################################################################
# PDF_set_horiz_scaling - Set horizontal text scaling [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_value() with parameter horizscaling instead.
################################################################################

function pdf_set_horizontal_scaling(& $p, $value)
	{
	pdf_set_value($p, "horizscaling", $value);
	}

################################################################################
# PDF_set_info_author - Fill the author document info field [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_info() instead.
################################################################################

function pdf_set_info_author(& $p, $value)
	{
	pdf_set_info($p, "Author", $value);
	}

################################################################################
# PDF_set_info_creator - Fill the creator document info field [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_info() instead.
################################################################################

function pdf_set_info_creator(& $p, $value)
	{
	pdf_set_info($p, "Creator", $value);
	}

################################################################################
# PDF_set_info_keywords - Fill the keywords document info field [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_info() instead.
################################################################################

function pdf_set_info_keywords(& $p, $value)
	{
	pdf_set_info($p, "Keywords", $value);
	}

################################################################################
# PDF_set_info_subject - Fill the subject document info field [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_info() instead.
################################################################################

function pdf_set_info_subject(& $p, $value)
	{
	pdf_set_info($p, "Subject", $value);
	}

################################################################################
# PDF_set_info_title - Fill the title document info field [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_info() instead.
################################################################################

function pdf_set_info_title(& $p, $value)
	{
	pdf_set_info($p, "Title", $value);
	}

################################################################################
# PDF_set_info - Fill document info field
# PDF_set_info ( resource $p , string $key , string $value ) : bool
# Fill document information field key with value. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_set_info(& $p, $key, $value)
	{
	$p["info-dictionary"][0]["dictionary"]["/" . $key] = utf8_decode($value);
	}

################################################################################
# PDF_set_layer_dependency - Define relationships among layers
# PDF_set_layer_dependency ( resource $pdfdoc , string $type , string $optlist ) : bool
# Defines hierarchical and group relationships among layers. Returns TRUE on success or FALSE on failure.
# This function requires PDF 1.5.
################################################################################

function pdf_set_layer_dependency(& $pdfdoc, $type, $optlist)
	{
	}

################################################################################
# PDF_set_leading - Set distance between text lines [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_value() with the parameter leading instead.
################################################################################

function pdf_set_leading(& $pdfdoc, $distance)
	{
	pdf_set_value($pdfdoc, "leading", $distance);
	}

################################################################################
# PDF_set_parameter - Set string parameter
# PDF_set_parameter ( resource $p , string $key , string $value ) : bool
# Sets some PDFlib parameter with string type. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_set_parameter(& $p, $key, $value)
	{
	$p[$key] = $value;
	}

################################################################################
# PDF_set_text_pos - Set text position
# PDF_set_text_pos ( resource $p , float $x , float $y ) : bool
# Sets the position for text output on the page. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_set_text_pos(& $p, $x, $y)
	{
	$p["stream"][] = sprintf("%d %d Td", $x, $y);
	}

################################################################################
# PDF_set_text_rendering - Determine text rendering [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_value() with the textrendering parameter instead.
################################################################################

function pdf_set_text_rendering(& $pdfdoc, $textrendering)
	{
	pdf_set_value($pdfdoc, "textrendering", $textrendering);
	}

################################################################################
# PDF_set_text_rise - Set text rise [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_value() with the textrise parameter instead.
################################################################################

function pdf_set_text_rise(& $pdfdoc, $textrise)
	{
	pdf_set_value($pdfdoc, "textrise", $textrise);
	}

################################################################################
# PDF_set_value - Set numerical parameter
# PDF_set_value ( resource $p , string $key , float $value ) : bool
# Sets the value of some PDFlib parameter with numerical type. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_set_value(& $p, $key, $value)
	{
	switch($key)
		{
		case("charspacing"):
			$p["stream"][] = sprintf("%f Tc", $value); break;
		case("horizscaling"):
			$p["stream"][] = sprintf("%f Tz", $value); break;
		case("leading"):
			$p["stream"][] = sprintf("%f TL", $value); break;
		case("linecap"):
			$p["stream"][] = sprintf("%f J", $value); break;
		case("linejoin"):
			$p["stream"][] = sprintf("%f j", $value); break;
		case("linewidth"):
			$p["stream"][] = sprintf("%f w", $value); break;
		case("miterlimit"):
			$p["stream"][] = sprintf("%f M", $value); break;
		case("textrendering"):
			$p["stream"][] = sprintf("%f Tr", $value); break;
		case("textrise"):
			$p["stream"][] = sprintf("%f Ts", $value); break;
		case("wordspacing"):
			$p["stream"][] = sprintf("%f Tw", $value); break;
		}
	}

################################################################################
# PDF_set_word_spacing - Set spacing between words [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_value() with the wordspacing parameter instead.
################################################################################

function pdf_set_word_spacing(& $pdfdoc, $wordspacing)
	{
	pdf_set_value($pdfdoc, "wordspacing", $wordspacing);
	}

################################################################################
# PDF_setcolor - Set fill and stroke color
# PDF_setcolor ( resource $p , string $fstype , string $colorspace , float $c1 , float $c2 , float $c3 , float $c4 ) : bool
# Sets the current color space and color. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setcolor(& $p, $fstype, $colorspace, $c1, $c2 = 0, $c3 = 0, $c4 = 0)
	{
	if(($fstype == "fill") && ($colorspace == "gray"))
		return($p["stream"][] = sprintf("%f g", $c1));

	if(($fstype == "fill") && ($colorspace == "rgb"))
		return($p["stream"][] = sprintf("%f %f %f rg", $c1, $c2, $c3));

	if(($fstype == "fill") && ($colorspace == "cmyk"))
		return($p["stream"][] = sprintf("%f %f %f %f k", $c1, $c2, $c3, $c4));

	if(($fstype == "stroke") && ($colorspace == "gray"))
		return($p["stream"][] = sprintf("%f G", $c1));

	if( ($fstype == "stroke") &&($colorspace == "rgb"))
		return($p["stream"][] = sprintf("%f %f %f RG", $c1, $c2, $c3));

	if(($fstype == "stroke") && ($colorspace == "cmyk"))
		return($p["stream"][] = sprintf("%f %f %f %f K", $c1, $c2, $c3, $c4));
	}

################################################################################
# PDF_setdash - Set simple dash pattern
# PDF_setdash ( resource $pdfdoc , float $b , float $w ) : bool
# Sets the current dash pattern to b black and w white units. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setdash(& $pdfdoc, $b, $w)
	{
	$pdfdoc["stream"][] = sprintf("%f %f d", $b, $w);
	}

################################################################################
# PDF_setdashpattern - Set dash pattern
# PDF_setdashpattern ( resource $pdfdoc , string $optlist ) : bool
# Sets a dash pattern defined by an option list. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setdashpattern(& $pdfdoc, $optlist)
	{
	}

################################################################################
# pdf_setflat - Set flatness
# PDF_setflat ( resource $pdfdoc , float $flatness ) : bool
# Sets the flatness parameter. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setflat(& $pdfdoc, $flatness)
	{
	$pdfdoc["stream"][] = sprintf("%f i", $flatness);
	}

################################################################################
# PDF_setfont - Set font
# PDF_setfont ( resource $pdfdoc , int $font , float $fontsize ) : bool
# Sets the current font in the specified fontsize, using a font handle returned by PDF_load_font(). Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setfont(& $pdfdoc, $font, $fontsize)
	{
	$pdfdoc["font"] = pdf_findfont($pdfdoc, $font, "builtin", 0);

	$i = $pdfdoc["font"];

	return($pdfdoc["stream"][] = sprintf("%s %f Tf", $pdfdoc["font-dictionary"][$i]["x-procset-font-id"], ($pdfdoc["fontsize"] = intval($fontsize))));
	}

################################################################################
# PDF_setgray - Set color to gray [deprecated]
# PDF_setgray ( resource $p , float $g ) : bool
# Sets the current fill and stroke color to a gray value between 0 and 1 inclusive. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 4, use PDF_setcolor() instead.
################################################################################

function pdf_setgray(& $p, $g)
	{
	pdf_setcolor($p, "fill", "gray", $g);
	pdf_setcolor($p, "stroke", "gray", $g);
	}

################################################################################
# PDF_setgray_fill - Set fill color to gray [deprecated]
# PDF_setgray_fill ( resource $p , float $g ) : bool
# Sets the current fill color to a gray value between 0 and 1 inclusive. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 4, use PDF_setcolor() instead.
################################################################################

function pdf_setgray_fill(& $p, $g)
	{
	pdf_setcolor($p, "fill", "gray", $g);
	}

################################################################################
# PDF_setgray_stroke - Set stroke color to gray [deprecated]
# PDF_setgray_stroke ( resource $p , float $g ) : bool
# Sets the current stroke color to a gray value between 0 and 1 inclusive. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 4, use PDF_setcolor() instead.
################################################################################

function pdf_setgray_stroke(& $p, $g)
	{
	pdf_setcolor($p, "stroke", "gray", $g);
	}

################################################################################
# PDF_setlinecap - Set linecap parameter
# PDF_setlinecap ( resource $p , int $linecap ) : bool
# Sets the linecap parameter to control the shape at the end of a path with respect to stroking.
################################################################################

function pdf_setlinecap(& $p, $linecap)
	{
	pdf_set_value($p, "linecap", $linecap);
	}

################################################################################
# PDF_setlinejoin - Set linejoin parameter
# PDF_setlinejoin ( resource $p , int $value ) : bool
# Sets the linejoin parameter to specify the shape at the corners of paths that are stroked. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setlinejoin(& $p, $value)
	{
	pdf_set_value($p, "linejoin", $value);
	}

################################################################################
# PDF_setlinewidth - Set line width
# PDF_setlinewidth ( resource $p , float $width ) : bool
# Sets the current line width. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setlinewidth(& $p, $width)
	{
	pdf_set_value($p, "linewidth", $width);
	}

################################################################################
# PDF_setmatrix - Set current transformation matrix
# PDF_setmatrix ( resource $p , float $a , float $b , float $c , float $d , float $e , float $f ) : bool
# Explicitly sets the current transformation matrix. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setmatrix(& $p, $a, $b, $c, $d, $e, $f)
	{
	$p["stream"][] = sprintf("%f %f %f %f %f %f Tm", $a, $b, $c, $d, $e, $f);
	}

################################################################################
# PDF_setmiterlimit - Set miter limit
# PDF_setmiterlimit ( resource $pdfdoc , float $miter ) : bool
# Sets the miter limit. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setmiterlimit(& $pdfdoc, $miter)
	{
	pdf_set_value($pdfdoc, "miterlimit", $miter);
	}

################################################################################
# PDF_setpolydash - Set complicated dash pattern [deprecated]
# This function is deprecated since PDFlib version 5, use PDF_setdashpattern() instead.
################################################################################

function pdf_setpolydash(& $p, $dash)
	{
	pdf_setdashpattern($p, $dash);
	}

################################################################################
# PDF_setrgbcolor - Set fill and stroke rgb color values [deprecated]
# PDF_setrgbcolor ( resource $p , float $red , float $green , float $blue ) : bool
# Sets the current fill and stroke color to the supplied RGB values. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 4, use PDF_setcolor() instead.
################################################################################

function pdf_setrgbcolor(& $p, $red, $green, $blue)
	{
	pdf_setcolor($p, "fill", "rgb", $red, $green, $blue);
	pdf_setcolor($p, "stroke", "rgb", $red, $green, $blue);
	}

################################################################################
# PDF_setrgbcolor_fill - Set fill rgb color values [deprecated]
# PDF_setrgbcolor_fill ( resource $p , float $red , float $green , float $blue ) : bool
# Sets the current fill color to the supplied RGB values. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 4, use PDF_setcolor() instead.
################################################################################

function pdf_setrgbcolor_fill(& $p, $red, $green, $blue)
	{
	pdf_setcolor($p, "fill", "rgb", $red, $green, $blue);
	}

################################################################################
# PDF_setrgbcolor_stroke - Set stroke rgb color values [deprecated]
# PDF_setrgbcolor_stroke ( resource $p , float $red , float $green , float $blue ) : bool
# Sets the current stroke color to the supplied RGB values. Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 4, use PDF_setcolor() instead.
################################################################################

function pdf_setrgbcolor_stroke(& $p, $red, $green, $blue)
	{
	pdf_setcolor($p, "stroke", "rgb", $red, $green, $blue);
	}

################################################################################
# PDF_set_text_matrix - Set text matrix [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_scale(), PDF_translate(), PDF_rotate(), or PDF_skew() instead.
################################################################################

function pdf_settext_matrix(& $p, $a, $b, $c, $d, $e, $f)
	{
	$p["stream"][] = sprintf("%f %f %f %f %f %f Tm", $a, $b, $c, $d, $e, $f);
	}

################################################################################
# PDF_shading - Define blend
# PDF_shading ( resource $pdfdoc , string $shtype , float $x0 , float $y0 , float $x1 , float $y1 , float $c1 , float $c2 , float $c3 , float $c4 , string $optlist ) : int
# Defines a blend from the current fill color to another color.
# This function requires PDF 1.4 or above.
################################################################################

function pdf_shading(& $pdfdoc, $shtype, $x0, $y0, $x1, $y1, $c1, $c2, $c3, $c4, $optlist)
	{
	}

################################################################################
# PDF_shading_pattern - Define shading pattern
# PDF_shading_pattern ( resource $pdfdoc , int $shading , string $optlist ) : int
# Defines a shading pattern using a shading object.
# This function requires PDF 1.4 or above.
################################################################################

function pdf_shading_pattern(& $pdfdoc, $shading, $optlist)
	{
	}

################################################################################
# PDF_shfill - Fill area with shading
# PDF_shfill ( resource $pdfdoc , int $shading ) : bool
# Fills an area with a shading, based on a shading object.
# This function requires PDF 1.4 or above.
################################################################################

function pdf_shfill(& $pdfdoc, $shading)
	{
	$pdfdoc["stream"][] = sprintf("/Sh%d sh", $shading);
	}

################################################################################
# PDF_show - Output text at current position
# PDF_show ( resource $pdfdoc , string $text ) : bool
# Prints text in the current font and size at the current position. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_show(& $pdfdoc, $text)
	{
	if(strlen($text) == 0)
		return(false);

	################################################################################

	if(isset($pdfdoc["/ProcSet"]) === false)
		$pdfdoc["/ProcSet"][] = "/Text";
	elseif(in_array("/Text", $pdfdoc["/ProcSet"]) === false)
		$pdfdoc["/ProcSet"][] = "/Text";

	################################################################################

	foreach(str_split($text) as $char)
		{
		$pdfdoc["/FirstChar"] = min($pdfdoc["/FirstChar"], ord($char));
		$pdfdoc["/LastChar"] = max($pdfdoc["/LastChar"], ord($char));
		}

	################################################################################

	$pdfdoc["stream"][] = "BT";
	$pdfdoc["stream"][] = sprintf("%s Tj", _textstring($pdfdoc, $text));
	$pdfdoc["stream"][] = "ET";
	}

################################################################################
# PDF_show_boxed - Output text in a box [deprecated]
# PDF_show_boxed ( resource $p , string $text , float $left , float $top , float $width , float $height , string $mode , string $feature ) : int
# This function is deprecated since PDFlib version 6, use PDF_fit_textline() for single lines, or the PDF_*_textflow() functions for multi-line formatting instead.
################################################################################

function pdf_show_boxed(& $p, $text, $left, $top, $width, $height, $mode, $feature = "")
	{
	$settings = array
		(
		"leading" => 0,
		"linebreak" => true,
		"parbreak" => true,
		"hyphenation" => true,
		"hyphendict" => true,
		"hyphenminchar" => 0,
		"parindent" => 0,
		"parskip" => 0,
		"numindentlines" => 0,
		"parindentskip" => 0,
		"linenumbermode" => true,
		"linenumberspace" => 0,
		"linenumbersep" => 0
		);

	################################################################################

	if(strlen($text) == 0)
		return(0);

	################################################################################

	$text = str_replace("  ", " ", $text);

	################################################################################

	$fontsize = pdf_get_value($p, "fontsize", 0);
	$font = pdf_get_value($p, "font", 0);

	if($height - $fontsize < 0)
		return(strlen($text));

	list($line, $text) = (strpos($text, "\n") ? explode("\n", $text, 2) : array($text, ""));

	$words = "";

	while(strlen($line) > 0)
		{
		list($word, $line) = (strpos($line, " ") ? explode(" ", $line, 2) : array($line, ""));

		$test = ($words ? $words . " " : "") . $word;

		if($width - pdf_stringwidth($p, $test, $font, $fontsize) < 0)
			{
			$text = $word . ($line ? " " . $line : "") . ($text ? "\n" . $text : "");

			break;
			}

		$words = $test;
		}

	if(strlen($words) == 0)
		return(strlen($text));

	################################################################################
	# export to pdf_*_textflow
	################################################################################

	$spacing = $width - pdf_stringwidth($p, $words, $font, $fontsize);

	if(($mode == "justify") || ($mode == "fulljustify"))
		{
#		if(($mode == "justify") && ($spacing > ($width / 2)))
#			$spacing = $width / 2;

		pdf_set_word_spacing($p, $spacing / (count(explode(" ", $words)) - 1));

		$spacing = 0;
		}
	else
		{
		# "justify", "fulljustify", "right", "left", "center"

		$modes = array("center" => 2, "right" => 1);

		$spacing = (array_key_exists($mode, $modes) ? $spacing / $modes[$mode] : 0);
		}

	################################################################################
	# export to pdf_*_textflow
	################################################################################

	pdf_show_xy($p, $words, $left + $spacing, $top);

	return(pdf_show_boxed($p, $text, $left, $top - $fontsize, $width, $height - $fontsize, $mode, $feature));
	}

################################################################################
# PDF_show_xy - Output text at given position
# PDF_show_xy ( resource $p , string $text , float $x , float $y ) : bool
# Prints text in the current font. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_show_xy(& $p, $text, $x, $y)
	{
	if(strlen($text) == 0)
		return(false);

	################################################################################

	if(isset($p["/ProcSet"]) === false)
		$p["/ProcSet"][] = "/Text";
	elseif(in_array("/Text", $p["/ProcSet"]) === false)
		$p["/ProcSet"][] = "/Text";

	################################################################################

	foreach(str_split($text) as $char)
		{
		$p["/FirstChar"] = min($p["/FirstChar"], ord($char));
		$p["/LastChar"] = max($p["/LastChar"], ord($char));
		}

	################################################################################

	$p["stream"][] = "BT";
	$p["stream"][] = sprintf("%f %f Td", $x, $y); # pdf_set_text_pos
	$p["stream"][] = sprintf("%s Tj", _textstring($p, $text));
	$p["stream"][] = "ET";
	}

################################################################################
# PDF_skew - Skew the coordinate system
# PDF_skew ( resource $p , float $alpha , float $beta ) : bool
# Skews the coordinate system in x and y direction by alpha and beta degrees, respectively. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_skew(& $p, $alpha, $beta)
	{
	$alpha = tan($alpha * M_PI / 180); # deg 2 rad
	$beta = tan($beta * M_PI / 180); # deg 2 rad

	pdf_concat($p, 1, $alpha, $beta, 1, 0, 0);
	}

################################################################################
# PDF_stringwidth - Return width of text
# PDF_stringwidth ( resource $p , string $text , int $font , float $fontsize ) : float
# Returns the width of text in an arbitrary font.
################################################################################

function pdf_stringwidth(& $p, $text, $font, $fonsize)
	{
	$text = iconv("UTF-8", "ISO-8859-1", $text);

	$p["font"] = pdf_findfont($p, $font, "builtin", 0);

	$i = $p["font"];

	$stringwidth = 0;

	foreach(str_split($text) as $char)
		$stringwidth += $p["font-dictionary"][$i]["dictionary"]["/Widths"][$char];

	return($stringwidth / 1000 * $fonsize);
	}

################################################################################
# PDF_stroke - Stroke path
# PDF_stroke ( resource $p ) : bool
# Strokes the path with the current color and line width, and clear it. Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_stroke(& $p)
	{
	$p["stream"][] = "S";
	}

################################################################################
# PDF_suspend_page - Suspend page
# PDF_suspend_page ( resource $pdfdoc , string $optlist ) : bool
# Suspends the current page so that it can later be resumed with PDF_resume_page().
################################################################################

function pdf_suspend_page(& $pdfdoc, $optlist)
	{
	}

################################################################################
# PDF_translate - Set origin of coordinate system
# PDF_translate ( resource $p , float $tx , float $ty ) : bool
# Translates the origin of the coordinate system.
################################################################################

function pdf_translate(& $p, $tx, $ty)
	{
	pdf_concat($p, 1, 0, 0, 1, $tx, $ty);
	}

################################################################################
# PDF_utf16_to_utf8 - Convert string from UTF-16 to UTF-8
# PDF_utf16_to_utf8 ( resource $pdfdoc , string $utf16string ) : string
# Converts a string from UTF-16 format to UTF-8.
################################################################################

function pdf_utf16_to_utf8(& $pdfdoc, $utf16string)
	{
	return(iconv("UTF-16", "UTF-8", $utf16string));
	}

################################################################################
# PDF_utf32_to_utf16 - Convert string from UTF-32 to UTF-16
# PDF_utf32_to_utf16 ( resource $pdfdoc , string $utf32string , string $ordering ) : string
# Converts a string from UTF-32 format to UTF-16.
################################################################################

function pdf_utf32_to_utf16(& $pdfdoc, $utf32string, $ordering)
	{
	foreach($ordering as $k => $v)
		iconv_set_encoding($k, $v);

	return(iconv("UTF-32", "UTF-16", $utf32string));
	}

################################################################################
# PDF_utf8_to_utf16 - Convert string from UTF-8 to UTF-16
# PDF_utf8_to_utf16 ( resource $pdfdoc , string $utf8string , string $ordering ) : string
# Converts a string from UTF-8 format to UTF-16.
################################################################################

function pdf_utf8_to_utf16(& $pdfdoc, $utf8string, $ordering)
	{
	foreach($ordering as $k => $v)
		iconv_set_encoding($k, $v);

	return(iconv("UTF-8", "UTF-16", $utf8string));
	}
?>
