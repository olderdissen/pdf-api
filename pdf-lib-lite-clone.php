<?
################################################################################
# pdf_activate_item - Activate structure element or other content item
# pdf_activate_item ( array $pdf , int $id ) : bool
# Activates a previously created structure element or other content item.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_activate_item(& $pdf, $id)
	{
	}

################################################################################
# PDF_add_annotation - Add annotation [deprecated]
# This function is deprecated, use PDF_create_annotation() with type=Text instead.
################################################################################

function pdf_add_annotation(& $pdf, $llx, $lly, $urx, $ury, $title, $content = array())
	{
	}

################################################################################
# PDF_add_bookmark - Add bookmark for current page [deprecated]
# This function is deprecated since PDFlib version 6, use PDF_create_bookmark() instead.
################################################################################

function pdf_add_bookmark(& $pdf, $text, $parent, $open = array())
	{
	}

################################################################################
# PDF_add_launchlink - Add launch annotation for current page [deprecated]
# PDF_add_launchlink ( resource $pdfdoc , float $llx , float $lly , float $urx , float $ury , string $filename ) : bool
# Adds a link to a web resource.
# This function is deprecated since PDFlib version 6, use PDF_create_action() with type=Launch and PDF_create_annotation() with type=Link instead.
################################################################################

function pdf_add_launchlink(& $pdf, $llx, $lly, $urx, $ury, $filename)
	{
	}

################################################################################
# PDF_add_locallink - Add link annotation for current page [deprecated]
# PDF_add_locallink ( resource $pdfdoc , float $lowerleftx , float $lowerlefty , float $upperrightx , float $upperrighty , int $page , string $dest ) : bool
# Add a link annotation to a target within the current PDF file.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use PDF_create_action() with type=GoTo and PDF_create_annotation() with type=Link instead.
################################################################################

function pdf_add_locallink(& $pdf, $lowerleftx, $lowerlefty, $upperrightx, $upperrighty, $page, $dest)
	{
	}

################################################################################
# PDF_add_nameddest - Create named destination
# PDF_add_nameddest ( resource $pdfdoc , string $name , string $optlist ) : bool
# Creates a named destination on an arbitrary page in the current document.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_add_nameddest(& $pdf, $name, $optlist = array())
	{
	}

################################################################################
# PDF_add_note - Set annotation for current page [deprecated]
# PDF_add_note ( resource $pdfdoc , float $llx , float $lly , float $urx , float $ury , string $contents , string $title , string $icon , int $open ) : bool
# Sets an annotation for the current page.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use PDF_create_annotation() with type=Text instead.
################################################################################

function pdf_add_note(& $pdf, $llx, $lly, $urx, $ury, $contents, $title, $icon, $open = array())
	{
	}

################################################################################
# PDF_add_outline # Add bookmark for current page [deprecated]
# This function is deprecated, use PDF_create_bookmark() instead.
################################################################################

function pdf_add_outline(& $pdf, $text, $parent, $open)
	{
	}

################################################################################
# PDF_add_pdflink - Add file link annotation for current page [deprecated]
# PDF_add_pdflink ( resource $pdfdoc , float $bottom_left_x , float $bottom_left_y , float $up_right_x , float $up_right_y , string $filename , int $page , string $dest ) : bool
# Add a file link annotation to a PDF target.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use PDF_create_action() with type=GoToR and PDF_create_annotation() with type=Link instead.
################################################################################

function pdf_add_pdflink(& $pdf, $bottom_left_x, $bottom_left_y, $up_right_x, $up_right_y, $filename, $page, $dest)
	{
	}

################################################################################
# PDF_add_table_cell - Add a cell to a new or existing table
# PDF_add_table_cell ( resource $pdfdoc , int $table , int $column , int $row , string $text , string $optlist ) : int
# Adds a cell to a new or existing table.
################################################################################

function pdf_add_table_cell(& $pdf, $table, $column, $row, $text, $optlist = array())
	{
	}

################################################################################
# PDF_add_textflow - Create Textflow or add text to existing Textflow
# PDF_add_textflow ( resource $pdfdoc , int $textflow , string $text , string $optlist ) : int
# Creates a Textflow object, or adds text and explicit options to an existing Textflow.
################################################################################

function pdf_add_textflow(& $pdf, $textflow, $text, $optlist = array())
	{
	}

################################################################################
# PDF_add_thumbnail - Add thumbnail for current page
# PDF_add_thumbnail ( resource $pdfdoc , int $image ) : bool
# Adds an existing image as thumbnail for the current page.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_add_thumbnail(& $pdf, $image)
	{
	}

################################################################################
# PDF_add_weblink - Add weblink for current page [deprecated]
# PDF_add_weblink ( resource $pdfdoc , float $lowerleftx , float $lowerlefty , float $upperrightx , float $upperrighty , string $url ) : bool
# Adds a weblink annotation to a target url on the Web.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use PDF_create_action() with type=URI and PDF_create_annotation() with type=Link instead.
################################################################################

function pdf_add_weblink(& $pdf, $lowerleftx, $lowerlefty, $upperrightx, $upperrighty, $url)
	{
	}

################################################################################
# PDF_arc - Draw a counterclockwise circular arc segment
# PDF_arc ( resource $p , float $x , float $y , float $r , float $alpha , float $beta ) : bool
# Adds a counterclockwise circular arc.
################################################################################

function pdf_arc(& $pdf, $x, $y, $r, $alpha, $beta)
	{
	pdf_arc_orient($pdf, $x, $y, $r, $alpha, $beta, 0 - 1);
	}

################################################################################
# PDF_arcn - Draw a clockwise circular arc segment
# PDF_arcn ( resource $p , float $x , float $y , float $r , float $alpha , float $beta ) : bool
# Except for the drawing direction, this function behaves exactly like PDF_arc().
################################################################################

function pdf_arcn(& $pdf, $x, $y, $r, $alpha, $beta)
	{
	pdf_arc_orient($pdf, $x, $y, $r, $alpha, $beta, 0 + 1);
	}

################################################################################

function pdf_arc_orient(& $pdf, $x, $y, $r, $alpha, $beta, $orient)
	{
	$deg_to_rad	= 0.0174532925199433; # pi() / 180

	$rad_a		= $alpha * $deg_to_rad;

	$startx		= ($x + $r * cos($rad_a));
	$starty		= ($y + $r * sin($rad_a));

	pdf_moveto($pdf, $startx, $starty);

	if($orient > 0)
		{
		while($beta < $alpha)
			$beta = $beta + 360;

		if($alpha == $beta)
			return;

		while($beta - $alpha > 90)
			{
			pdf_arc_short($pdf, $x, $y, $r, $alpha, $alpha - 90);

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
			pdf_arc_short($pdf, $x, $y, $r, $alpha, $alpha + 90);

			$alpha = $alpha - 90;
			}
		}

	if($alpha != $beta)
		{
		pdf_arc_short($pdf, $x, $y, $r, $alpha, $beta);
		}
	}

################################################################################

function pdf_arc_short(& $pdf, $x, $y, $r, $alpha, $beta)
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
# Adds a file attachment annotation.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use PDF_create_annotation() with type=FileAttachment instead.
################################################################################

function pdf_attach_file(& $pdf, $llx, $lly, $urx, $ury, $filename, $description, $author, $mimetype, $icon)
	{
	}

################################################################################
# PDF_begin_document - Create new PDF file
# PDF_begin_document ( resource $pdfdoc , string $filename , string $optlist ) : int
# Creates a new PDF file subject to various options.
################################################################################

function pdf_begin_document(& $pdf, $filename, $optlist = array())
	{
	$catalog = _pdf_add_catalog($pdf);

	$outlines = _pdf_add_outlines($pdf, $catalog);

	$pages = _pdf_add_pages($pdf, $catalog);

	$pdf["catalog"] = $catalog;
	$pdf["outlines"] = $outlines;
	$pdf["pages"] = $pages;
	$pdf["filename"] = $filename;

	$pdf["loaded-resources"] = array();
	}

################################################################################
# PDF_begin_font - Start a Type 3 font definition
# PDF_begin_font ( resource $pdfdoc , string $filename , float $a , float $b , float $c , float $d , float $e , float $f , string $optlist ) : bool
# Starts a Type 3 font definition.
################################################################################

function pdf_begin_font(& $pdf, $filename, $a, $b, $c, $d, $e, $f, $optlist = array())
	{
	}

################################################################################
# PDF_begin_glyph - Start glyph definition for Type 3 font
# PDF_begin_glyph ( resource $pdfdoc , string $glyphname , float $wx , float $llx , float $lly , float $urx , float $ury ) : bool
# Starts a glyph definition for a Type 3 font.
################################################################################

function pdf_begin_glyph(& $pdf, $glyphname, $wx, $llx, $lly, $urx, $ury)
	{
	}

################################################################################
# PDF_begin_item - Open structure element or other content item
# PDF_begin_item ( resource $pdfdoc , string $tag , string $optlist ) : int
# Opens a structure element or other content item with attributes supplied as options.
################################################################################

function pdf_begin_item(& $pdf, $tag, $optlist = array())
	{
	}

################################################################################
# PDF_begin_layer - Start layer
# PDF_begin_layer ( resource $pdfdoc , int $layer ) : bool
# Starts a layer for subsequent output on the page.
# Returns TRUE on success or FALSE on failure.
# This function requires PDF 1.5.
################################################################################

function pdf_begin_layer(& $pdf, $layer)
	{
	}

################################################################################
# PDF_begin_page - Start new page [deprecated]
# PDF_begin_page ( resource $pdfdoc , float $width , float $height ) : bool
# Adds a new page to the document.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use PDF_begin_page_ext() instead.
################################################################################

function pdf_begin_page(& $pdf, $width, $height)
	{
	pdf_begin_page_ext($pdf, $width, $height);
	}

################################################################################
# PDF_begin_page_ext - Start new page
# PDF_begin_page_ext ( resource $pdfdoc , float $width , float $height , string $optlist ) : bool
# Adds a new page to the document, and specifies various options.
# The parameters width and height are the dimensions of the new page in points.
# Returns TRUE on success or FALSE on failure.
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

function pdf_begin_page_ext(& $pdf, $width, $height, $optlist = array())
	{
	$pdf["width"] = $width;
	$pdf["height"] = $height;
	$pdf["stream"] = array();

	$pdf["used-resources"] = array();
	}

################################################################################
# PDF_begin_pattern - Start pattern definition
# PDF_begin_pattern ( resource $pdfdoc , float $width , float $height , float $xstep , float $ystep , int $painttype ) : int
# Starts a new pattern definition.
################################################################################

function pdf_begin_pattern(& $pdf, $width, $height, $xstep, $ystep, $painttype)
	{
	}

################################################################################
# PDF_begin_template_ext - Start template definition
# PDF_begin_template_ext ( resource $pdfdoc , float $width , float $height , string $optlist ) : int
# Starts a new template definition.
################################################################################

function pdf_begin_template_ext(& $pdf, $width, $height, $optlist = array())
	{
	$pdf["stream"] = array();
	}

################################################################################
# PDF_begin_template - Start template definition [deprecated]
# PDF_begin_template ( resource $pdfdoc , float $width , float $height ) : int
# Starts a new template definition.
# This function is deprecated since PDFlib version 7, use PDF_begin_template_ext() instead.
################################################################################

function pdf_begin_template(& $pdf, $width, $height)
	{
	pdf_begin_template_ext($pdf, $width, $height);
	}

################################################################################
# PDF_circle - Draw a circle
# PDF_circle ( resource $pdfdoc , float $x , float $y , float $r ) : bool
# Adds a circle.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_circle(& $pdf, $x, $y, $r)
	{
	#$arc_magic = 4 / 3 * (M_SQRT2 - 1);
	$arc_magic = 0.552284749;

	pdf_moveto($pdf, $x + $r, $y);
	pdf_curveto($pdf, $x + $r, $y + $r * $arc_magic, $x + $r * $arc_magic, $y + $r, $x, $y + $r);
	pdf_curveto($pdf, $x - $r * $arc_magic, $y + $r, $x - $r, $y + $r * $arc_magic, $x - $r, $y);
	pdf_curveto($pdf, $x - $r, $y - $r * $arc_magic, $x - $r * $arc_magic, $y - $r, $x, $y - $r);
	pdf_curveto($pdf, $x + $r * $arc_magic, $y - $r, $x + $r, $y - $r * $arc_magic, $x + $r, $y);
	}

################################################################################
# PDF_clip - Clip to current path
# PDF_clip ( resource $p ) : bool
# Uses the current path as clipping path, and terminate the path.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_clip(& $pdf)
	{
	$pdf["stream"][] = "W";
	}

################################################################################
# PDF_close - Close pdf resource [deprecated]
# PDF_close ( resource $p ) : bool
# Closes the generated PDF file, and frees all document-related resources.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use PDF_end_document() instead.
################################################################################

function pdf_close(& $pdf)
	{
	pdf_end_document($pdf);
	}

################################################################################
# PDF_close_image - Close image
# PDF_close_image ( resource $p , int $image ) : bool
# Closes an image retrieved with the PDF_open_image() function.
################################################################################

function pdf_close_image(& $pdf, $image)
	{
	$pdf["stream"][] = "EI";
	}

################################################################################
# PDF_close_pdi_page - Close the page handle
# PDF_close_pdi_page ( resource $p , int $page ) : bool
# Closes the page handle, and frees all page-related resources.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_close_pdi_page(& $pdf, $page)
	{
	}

################################################################################
# PDF_close_pdi_document - Close the document handle
# PDF_close_pdi_document ( resource $p , int $doc ) : bool
# Closes all open page handles, and closes the input PDF document.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_close_pdi_document(& $pdf, $doc)
	{
	}

################################################################################
# PDF_close_pdi - Close the input PDF document [deprecated]
# PDF_close_pdi ( resource $p , int $doc ) : bool
# Closes all open page handles, and closes the input PDF document.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 7, use PDF_close_pdi_document() instead.
################################################################################

function pdf_close_pdi(& $pdf, $doc)
	{
	pdf_close_pdi_document($pdf);
	}

################################################################################
# PDF_closepath - Close current path
# PDF_closepath ( resource $p ) : bool
# Closes the current path.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_closepath(& $pdf)
	{
	$pdf["stream"][] = "h";
	}

################################################################################
# PDF_closepath_fill_stroke - Close, fill and stroke current path
# PDF_closepath_fill_stroke ( resource $p ) : bool
# Closes the path, fills, and strokes it.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_closepath_fill_stroke(& $pdf)
	{
	$pdf["stream"][] = "b";
	}

################################################################################
# PDF_closepath_stroke - Close and stroke path
# PDF_closepath_stroke ( resource $p ) : bool
# Closes the path, and strokes it.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_closepath_stroke(& $pdf)
	{
	$pdf["stream"][] = "s";
	}

################################################################################
# PDF_concat - Concatenate a matrix to the CTM
# PDF_concat ( resource $p , float $a , float $b , float $c , float $d , float $e , float $f ) : bool
# Concatenates a matrix to the current transformation matrix (CTM).
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_concat(& $pdf, $a, $b, $c, $d, $e, $f)
	{
	$pdf["stream"][] = sprintf("%.1f %.1f %.1f %.1f %.1f %.1f cm", $a, $b, $c, $d, $e, $f);
	}

################################################################################
# PDF_continue_text - Output text in next line
# PDF_continue_text ( resource $p , string $text ) : bool
# Prints text at the next line.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_continue_text(& $pdf, $text)
	{
	if(! $text)
		return;

	$text = str_replace("\r", "", $text);
	$text = utf8_decode($text);
	$text = str_replace(array("\\", "(", ")"), array("\\\\", "\\(", "\\)"), $text);

	$pdf["stream"][] = "T*";
	$pdf["stream"][] = sprintf("(%s) Tj", $text);
	}

################################################################################
# PDF_create_3dview - Create 3D view
# PDF_create_3dview ( resource $pdfdoc , string $username , string $optlist ) : int
# Creates a 3D view.
# This function requires PDF 1.6.
################################################################################

function pdf_create_3dview(& $pdf, $username, $optlist = array())
	{
	}

################################################################################
# PDF_create_action - Create action for objects or events
# PDF_create_action ( resource $pdfdoc , string $type , string $optlist ) : int
# Creates an action which can be applied to various objects and events.
################################################################################

function pdf_create_action(& $pdf, $type, $optlist = array())
	{
	}

################################################################################
# PDF_create_annotation - Create rectangular annotation
# PDF_create_annotation ( resource $pdfdoc , float $llx , float $lly , float $urx , float $ury , string $type , string $optlist ) : bool
# Creates a rectangular annotation on the current page.
################################################################################

function pdf_create_annotation(& $pdf, $llx, $lly, $urx, $ury, $type, $optlist = array())
	{
	}

################################################################################
# PDF_create_bookmark - Create bookmark
# PDF_create_bookmark ( resource $pdfdoc , string $text , string $optlist ) : int
# Creates a bookmark subject to various options.
################################################################################

function pdf_create_bookmark(& $pdf, $text, $optlist = array())
	{
	}

################################################################################
# PDF_create_field - Create form field
# PDF_create_field ( resource $pdfdoc , float $llx , float $lly , float $urx , float $ury , string $name , string $type , string $optlist ) : bool
# Creates a form field on the current page subject to various options.
################################################################################

function pdf_create_field(& $pdf, $llx, $lly, $urx, $ury, $name, $type, $optlist = array())
	{
	}

################################################################################
# PDF_create_fieldgroup - Create form field group
# PDF_create_fieldgroup ( resource $pdfdoc , string $name , string $optlist ) : bool
# Creates a form field group subject to various options.
################################################################################

function pdf_create_fieldgroup(& $pdf, $name, $optlist = array())
	{
	}

################################################################################
# PDF_create_gstate - Create graphics state object
# PDF_create_gstate ( resource $pdfdoc , string $optlist ) : int
# Creates a graphics state object subject to various options.
################################################################################

function pdf_create_gstate(& $pdf, $optlist = array())
	{
	}

################################################################################
# PDF_create_pvf - Create PDFlib virtual file
# PDF_create_pvf ( resource $pdfdoc , string $filename , string $data , string $optlist ) : bool
# Creates a named virtual read-only file from data provided in memory.
################################################################################

function pdf_create_pvf(& $pdf, $filename, $data, $optlist = array())
	{
	}

################################################################################
# PDF_create_textflow - Create textflow object
# PDF_create_textflow ( resource $pdfdoc , string $text , string $optlist ) : int
# Preprocesses text for later formatting and creates a textflow object.
################################################################################

function pdf_create_textflow(& $pdf, $text, $optlist = array())
	{
	}

################################################################################
# PDF_curveto - Draw Bezier curve
# PDF_curveto ( resource $p , float $x1 , float $y1 , float $x2 , float $y2 , float $x3 , float $y3 ) : bool
# Draws a Bezier curve from the current point, using 3 more control points.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_curveto(& $pdf, $x1, $y1, $x2, $y2, $x3, $y3)
	{
	$pdf["stream"][] = sprintf("%.1f %.1f %.1f %.1f %.1f %.1f c", $x1, $y1, $x2, $y2, $x3, $y3);
	}

################################################################################
# PDF_define_layer - Create layer definition
# PDF_define_layer ( resource $pdfdoc , string $name , string $optlist ) : int
# Creates a new layer definition.
# This function requires PDF 1.5.
################################################################################

function pdf_define_layer(& $pdf, $name, $optlist)
	{
	}

################################################################################
# PDF_delete_pvf - Delete PDFlib virtual file
# PDF_delete_pvf ( resource $pdfdoc , string $filename ) : int
# Deletes a named virtual file and frees its data structures (but not the contents).
################################################################################

function pdf_delete_pvf(& $pdf, $filename)
	{
	}

################################################################################
# PDF_delete_table - Delete table object
# PDF_delete_table ( resource $pdfdoc , int $table , string $optlist ) : bool
# Deletes a table and all associated data structures.
################################################################################

function pdf_delete_table(& $pdf, $table, $optlist = array())
	{
	}

################################################################################
# PDF_delete_textflow - Delete textflow object
# PDF_delete_textflow ( resource $pdfdoc , int $textflow ) : bool
# Deletes a textflow and the associated data structures.
################################################################################

function pdf_delete_textflow(& $pdf, $textflow)
	{
	}

################################################################################
# PDF_delete - Delete PDFlib object
# PDF_delete ( resource $pdfdoc ) : bool
# Deletes a PDFlib object, and frees all internal resources.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_delete(& $pdfdoc)
	{
	$pdf = null;
	}

################################################################################
# PDF_encoding_set_char - Add glyph name and/or Unicode value
# PDF_encoding_set_char ( resource $pdfdoc , string $encoding , int $slot , string $glyphname , int $uv ) : bool
# Adds a glyph name and/or Unicode value to a custom encoding.
################################################################################

function pdf_encoding_set_char(& $pdf, $encoding, $slot, $glyphname, $uv)
	{
	}

################################################################################
# PDF_end_document - Close PDF file
# PDF_end_document ( resource $pdfdoc , string $optlist ) : bool
# Closes the generated PDF file and applies various options.
################################################################################

function pdf_end_document(& $pdf, $optlist = array())
	{
	_pdf_filter_change($pdf, "/FlateDecode");

	$pdf["stream"] = _pdf_glue_document($pdf["objects"]);

	if($pdf["filename"])
		file_put_contents($pdf["filename"], $pdf["stream"]);
	}

################################################################################
# PDF_end_font - Terminate Type 3 font definition
# PDF_end_font ( resource $pdfdoc ) : bool
# Terminates a Type 3 font definition.
################################################################################

function pdf_end_font(& $pdf)
	{
	}

################################################################################
# PDF_end_glyph - Terminate glyph definition for Type 3 font
# PDF_end_glyph ( resource $pdfdoc ) : bool
# Terminates a glyph definition for a Type 3 font.
################################################################################

function pdf_end_glyph(& $pdf)
	{
	}

################################################################################
# PDF_end_item - Close structure element or other content item
# PDF_end_item ( resource $pdfdoc , int $id ) : bool
# Closes a structure element or other content item.
################################################################################

function pdf_end_item(& $pdf, $id)
	{
	}

################################################################################
# PDF_end_layer - Deactivate all active layers
# PDF_end_layer ( resource $pdfdoc ) : bool
# Deactivates all active layers.
# Returns TRUE on success or FALSE on failure.
# This function requires PDF 1.5.
################################################################################

function pdf_end_layer(& $pdf)
	{
	}

################################################################################
# PDF_end_page - Finish page
# PDF_end_page ( resource $p ) : bool
# Finishes the page.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_end_page(& $pdf)
	{
	return(pdf_end_page_ext($pdf));
	}

################################################################################
# PDF_end_page_ext - Finish page
# PDF_end_page_ext ( resource $pdfdoc , string $optlist ) : bool
# Finishes a page, and applies various options.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_end_page_ext(& $pdf, $optlist = array())
	{
	# this is default if something went wrong
	$resources = array("/ProcSet" => array("/PDF", "/Text", "/ImageB", "/ImageC", "/ImageI"));

	# translate used data
	if(isset($pdf["used-resources"]["/ProcSet"]))
		foreach($pdf["used-resources"]["/ProcSet"] as $object)
			$resources["/ProcSet"][] = $object;

	foreach(array("/Font" => "/F", "/XObject" => "/X") as $type => $prefix)
		if(isset($pdf["used-resources"][$type]))
			foreach($pdf["used-resources"][$type] as $index => $object)
				$resources[$type][$prefix . $index] = $object;

	# glue resources
	if(isset($resources["/ProcSet"]))
		$resources["/ProcSet"] = sprintf("[%s]", _pdf_glue_array($resources["/ProcSet"]));

	foreach(array("/Font", "/XObject") as $type)
		if(isset($resources[$type]))
			$resources[$type] = sprintf("<< %s >>", _pdf_glue_dictionary($resources[$type]));

	# prepare data
	$parent = $pdf["pages"];
	$mediabox = sprintf("[%d %d %d %d]", 0, 0 , $pdf["width"], $pdf["height"]);
	$resources = sprintf("<< %s >>", _pdf_glue_dictionary($resources));
	$contents = implode(" ", $pdf["stream"]);

	$contents = _pdf_add_stream($pdf, $contents);

	$retval = _pdf_add_page($pdf, $parent, $resources, $mediabox, $contents);

	return($retval);
	}

################################################################################
# PDF_end_pattern - Finish pattern
# PDF_end_pattern ( resource $p ) : bool
# Finishes the pattern definition.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_end_pattern(& $pdf)
	{
	}

################################################################################
# PDF_end_template - Finish template
# PDF_end_template ( resource $p ) : bool
# Finishes a template definition.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_end_template(& $pdf)
	{
	}

################################################################################
# PDF_endpath - End current path
# PDF_endpath ( resource $p ) : bool
# Ends the current path without filling or stroking it.
################################################################################

function pdf_endpath(& $pdf)
	{
	$pdf["stream"][] = "n";
	}
	
################################################################################
# PDF_fill - Fill current path
# PDF_fill ( resource $p ) : bool
# Fills the interior of the current path with the current fill color.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_fill(& $pdf)
	{
	$pdf["stream"][] = "f";
	}

################################################################################
# PDF_fill_imageblock - Fill image block with variable data
# PDF_fill_imageblock ( resource $pdfdoc , int $page , string $blockname , int $image , string $optlist ) : int
# Fills an image block with variable data according to its properties.
# This function is only available in the PDFlib Personalization Server (PPS).
################################################################################

function pdf_fill_imageblock(& $pdf, $page, $blockname, $image, $optlist = array())
	{
	}

################################################################################
# PDF_fill_pdfblock - Fill PDF block with variable data
# PDF_fill_pdfblock ( resource $pdfdoc , int $page , string $blockname , int $contents , string $optlist ) : int
# Fills a PDF block with variable data according to its properties.
# This function is only available in the PDFlib Personalization Server (PPS).
################################################################################

function pdf_fill_pdfblock(& $pdf, $page, $blockname, $contents, $optlist = array())
	{
	}

################################################################################
# PDF_fill_stroke - Fill and stroke path
# PDF_fill_stroke ( resource $p ) : bool
# Fills and strokes the current path with the current fill and stroke color.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_fill_stroke(& $pdf)
	{
	$pdf["stream"][] = "B";
	}

################################################################################
# PDF_fill_textblock - Fill text block with variable data
# PDF_fill_textblock ( resource $pdfdoc , int $page , string $blockname , string $text , string $optlist ) : int
# Fills a text block with variable data according to its properties.
# This function is only available in the PDFlib Personalization Server (PPS).
################################################################################

function pdf_fill_textblock(& $pdf, $page, $blockname, $text, $optlist = array())
	{
	}

################################################################################
# PDF_findfont - Prepare font for later use [deprecated]
# PDF_findfont ( resource $p , string $fontname , string $encoding , int $embed ) : int
# Search for a font and prepare it for later use with PDF_setfont().
# The metrics will be loaded, and if embed is nonzero, the font file will be checked, but not yet used.
# encoding is one of builtin, macroman, winansi, host, a user-defined encoding name or the name of a CMap.
# Parameter embed is optional before PHP 4.3.5 or with PDFlib less than 5.
# This function is deprecated since PDFlib version 5, use PDF_load_font() instead.
################################################################################

function pdf_findfont(& $pdf, $fontname, $encoding = "builtin", $embed = 0)
	{
	if(in_array($encoding, array("builtin", "winansi", "macroman", "macexpert")) === false)
		die("_pdf_add_font: invalid encoding: " . $encoding);

	$a = 0; # found in objects
	$b = 0;

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
		{
		$c = pdf_load_font($pdf, $fontname, $encoding);

		return($c);
		}

	if(isset($pdf["loaded-resources"]["/Font"]) === false)
		die("pdf_findfont: fonts not loaded.");

	foreach($pdf["loaded-resources"]["/Font"] as $index => $resource)
		if($a == $resource)
			$b = $index;

	if($b == 0)
		die("pdf_findfont: font not found.");

	return("/F" . $b);
	}

################################################################################
# PDF_fit_image - Place image or template
# PDF_fit_image ( resource $pdfdoc , int $image , float $x , float $y , string $optlist ) : bool
# Places an image or template on the page, subject to various options.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_fit_image(& $pdf, $image, $x, $y, $optlist = array())
	{
	if(sscanf($image, "/X%d", $image_id) != 1)
		die("pdf_fit_image: invalid image.");

	if(isset($pdf["loaded-resources"]["/XObject"]) === false)
		die("pdf_fit_image: images not loaded.");

	$a = $pdf["loaded-resources"]["/XObject"][$image_id];

	if(sscanf($a, "%d %d R", $a_id, $a_version) != 2)
		die("pdf_fit_image: invalid image.");

	$pdf["used-resources"]["/XObject"][$image_id] = $pdf["loaded-resources"]["/XObject"][$image_id];

	$w = $pdf["objects"][$a_id]["dictionary"]["/Width"];
	$h = $pdf["objects"][$a_id]["dictionary"]["/Height"];

	pdf_save($pdf);
	$pdf["stream"][] = sprintf("%d 0 0 %d %d %d cm", $w * $optlist["scale"], $h * $optlist["scale"], $x, $y);
	$pdf["stream"][] = sprintf("%s Do", $image); # Invoke named XObject
	pdf_restore($pdf);
	}

################################################################################
# PDF_fit_pdi_page - Place imported PDF page
# PDF_fit_pdi_page ( resource $pdfdoc , int $page , float $x , float $y , string $optlist ) : bool
# Places an imported PDF page on the page, subject to various options.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_fit_pdi_page(& $pdf, $page, $x, $y, $optlist = array())
	{
	}

################################################################################
# PDF_fit_table - Place table on page
# PDF_fit_table ( resource $pdfdoc , int $table , float $llx , float $lly , float $urx , float $ury , string $optlist ) : string
# Places a table on the page fully or partially.
################################################################################

function pdf_fit_table(& $pdf, $table, $llx, $lly, $urx, $ury, $optlist = array())
	{
	}

################################################################################
# PDF_fit_textflow - Format textflow in rectangular area
# PDF_fit_textflow ( resource $pdfdoc , int $textflow , float $llx , float $lly , float $urx , float $ury , string $optlist ) : string
# Formats the next portion of a textflow into a rectangular area.
################################################################################

function pdf_fit_textflow(& $pdf, $text, $llx, $lly, $urx, $ury, $optlist)
	{
	}

################################################################################
# PDF_fit_textline - Place single line of text
# PDF_fit_textline ( resource $pdfdoc , string $text , float $x , float $y , string $optlist ) : bool
# Places a single line of text on the page, subject to various options.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_fit_textline(& $pdf, $text, $x, $y, $optlist = array())
	{
	}

################################################################################
# PDF_get_apiname - Get name of unsuccessfull API function
# PDF_get_apiname ( resource $pdfdoc ) : string
# Gets the name of the API function which threw the last exception or failed.
################################################################################

function pdf_get_apiname(& $pdf)
	{
	return($pdf["apiname"]);
	}

################################################################################
# PDF_get_buffer - Get PDF output buffer
# PDF_get_buffer ( resource $p ) : string
# Fetches the buffer containing the generated PDF data.
################################################################################

function pdf_get_buffer(& $pdf)
	{
	return($pdf["stream"]);
	}

################################################################################
# PDF_get_errmsg - Get error text
# PDF_get_errmsg ( resource $pdfdoc ) : string
# Gets the text of the last thrown exception or the reason for a failed function call.
################################################################################

function pdf_get_errmsg(& $pdf)
	{
	}

################################################################################
# PDF_get_errnum - Get error number
# PDF_get_errnum ( resource $pdfdoc ) : int
# Gets the number of the last thrown exception or the reason for a failed function call.
################################################################################

function pdf_get_errnum(& $pdf)
	{
	}

################################################################################
# PDF_get_font - Get font [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_get_value() with the parameter font instead.
################################################################################

function pdf_get_font(& $pdf)
	{
	return(pdf_get_value($pdf, "font", 0));
	}

################################################################################
# PDF_get_fontname - Get font name [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_get_parameter() with the parameter fontname instead.
################################################################################

function pdf_get_fontname(& $pdf, $font)
	{
	return(pdf_get_value($pdf, "/FontName", $font));
	}

################################################################################
# PDF_get_fontsize - Font handling [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_get_value() with the parameter fontsize instead.
################################################################################

function pdf_get_fontsize(& $pdf)
	{
	return(pdf_get_value($pdf, "fontsize", 0));
	}

################################################################################
# PDF_get_image_height - Get image height [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_get_value() with the parameter imageheight instead.
################################################################################

function pdf_get_image_height(& $pdf, $image)
	{
	return(pdf_get_value($pdf, "imageheight", $image));
	}

################################################################################
# PDF_get_image_width - Get image width [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_get_value() with the parameter imagewidth instead.
################################################################################

function pdf_get_image_width(& $pdf, $image)
	{
	return(pdf_get_value($pdf, "imagewidth", $image));
	}

################################################################################
# PDF_get_majorversion - Get major version number [deprecated]
# PDF_get_majorversion ( void ) : int
# This function is deprecated since PDFlib version 5, use PDF_get_value() with the parameter major instead.
################################################################################

function pdf_get_majorversion()
	{
	$pdf = pdf_new();

	return(pdf_get_value($pdf, "major", 0));
	}

################################################################################
# PDF_get_minorversion - Get minor version number [deprecated]
# PDF_get_minorversion ( void ) : int
# Returns the minor version number of the PDFlib version.
# This function is deprecated since PDFlib version 5, use PDF_get_value() with the parameter minor instead.
################################################################################

function pdf_get_minorversion()
	{
	$pdf = pdf_new();

	return(pdf_get_value($pdf, "minor", 0));
	}

################################################################################
# PDF_get_parameter - Get string parameter
# PDF_get_parameter ( resource $p , string $key , float $modifier ) : string
# Gets the contents of some PDFlib parameter with string type.
################################################################################

function pdf_get_parameter(& $pdf, $key, $modifier)
	{
	return($pdf[$key]);
	}

################################################################################
# PDF_get_pdi_parameter - Get PDI string parameter [deprecated]
# PDF_get_pdi_parameter ( resource $p , string $key , int $doc , int $page , int $reserved ) : string
# Gets the contents of a PDI document parameter with string type.
# This function is deprecated since PDFlib version 7, use PDF_pcos_get_string() instead.
################################################################################

function pdf_get_pdi_parameter(& $pdf, $key, $doc, $page, $reserved)
	{
	$path = "";

	return(pdf_pcos_get_string($pdf, $doc, $path));
	}

################################################################################
# PDF_get_pdi_value - Get PDI numerical parameter [deprecated]
# PDF_get_pdi_value ( resource $p , string $key , int $doc , int $page , int $reserved ) : float
# Gets the contents of a PDI document parameter with numerical type.
# This function is deprecated since PDFlib version 7, use PDF_pcos_get_number() instead.
################################################################################

function pdf_get_pdi_value(& $pdf, $key, $doc, $page, $reserved)
	{
	$path = "";

	return(pdf_pcos_get_number($pdf, $doc, $path));
	}

################################################################################
# PDF_get_value - Get numerical parameter
# PDF_get_value ( resource $p , string $key , float $modifier ) : float
# Gets the value of some PDFlib parameter with numerical type.
################################################################################

function pdf_get_value(& $pdf, $key, $modifier)
	{
	switch($key)
		{
		case("font"):
			return($pdf[$key]);
		case("/FontName"):
			return($pdf["font-dictionary"][$modifier]["dictionary"]["/FontName"]);
		case("fontsize"):
			return($pdf[$key]);
		case("imageheight"):
			return($pdf["image-dictionary"][$modifier]["dictionary"]["/Height"]);
		case("imagewidth"):
			return($pdf["image-dictionary"][$modifier]["dictionary"]["/Width"]);
		case("major"):
			return($pdf["major"]);
		case("minor"):
			return($pdf["minor"]);
		}
	}

################################################################################
# PDF_info_font - Query detailed information about a loaded font
# PDF_info_font ( resource $pdfdoc , int $font , string $keyword , string $optlist ) : float
# Queries detailed information about a loaded font.
################################################################################

function pdf_info_font(& $pdf, $font, $keyword, $optlist = array())
	{
	}

################################################################################
# PDF_info_matchbox - Query matchbox information
# PDF_info_matchbox ( resource $pdfdoc , string $boxname , int $num , string $keyword ) : float
# Queries information about a matchbox on the current page.
################################################################################

function pdf_info_matchbox(& $pdf, $boxname, $num, $keyword)
	{
	}

################################################################################
# PDF_info_table - Retrieve table information
# PDF_info_table ( resource $pdfdoc , int $table , string $keyword ) : float
# Retrieves table information related to the most recently placed table instance.
################################################################################

function pdf_info_table(& $pdf, $table, $keyword)
	{
	}

################################################################################
# PDF_info_textflow - Query textflow state
# PDF_info_textflow ( resource $pdfdoc , int $textflow , string $keyword ) : float
# Queries the current state of a textflow.
################################################################################

function pdf_info_textflow(& $pdf, $textflow, $keyword)
	{
	}

################################################################################
# PDF_info_textline - Perform textline formatting and query metrics
# PDF_info_textline ( resource $pdfdoc , string $text , string $keyword , string $optlist ) : float
# Performs textline formatting and queries the resulting metrics.
################################################################################

function pdf_info_textline(& $pdf, $text, $keyword, $optlist = array())
	{
	}

################################################################################
# PDF_initgraphics - Reset graphic state
# PDF_initgraphics ( resource $p ) : bool
# Reset all color and graphics state parameters to their defaults.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_initgraphics(& $pdf)
	{
	}

################################################################################
# PDF_lineto - Draw a line
# PDF_lineto ( resource $p , float $x , float $y ) : bool
# Draws a line from the current point to another point.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_lineto(& $pdf, $x, $y)
	{
	$pdf["stream"][] = sprintf("%d %d l", $x, $y);
	}

################################################################################
# PDF_load_3ddata - Load 3D model
# PDF_load_3ddata ( resource $pdfdoc , string $filename , string $optlist ) : int
# Loads a 3D model from a disk-based or virtual file.
# This function requires PDF 1.6.
################################################################################

function pdf_load_3ddata(& $pdf, $filename, $optlist = array())
	{
	}

################################################################################
# PDF_load_font - Search and prepare font
# PDF_load_font ( resource $pdfdoc , string $fontname , string $encoding , string $optlist ) : int
# Searches for a font and prepares it for later use.
################################################################################

function pdf_load_font(& $pdf, $fontname, $encoding = "builtin", $optlist = array())
	{
	$a = _pdf_add_font($pdf, $fontname, $encoding);

	$b = _pdf_get_free_font_id($pdf);

	$pdf["loaded-resources"]["/Font"][$b] = $a;

	return("/F" . $b);
	}

################################################################################
# PDF_load_iccprofile - Search and prepare ICC profile
# PDF_load_iccprofile ( resource $pdfdoc , string $profilename , string $optlist ) : int
# Searches for an ICC profile, and prepares it for later use.
################################################################################

function pdf_load_iccprofile(& $pdf, $profilename, $optlist = array())
	{
	}

################################################################################
# PDF_load_image - Open image file
# PDF_load_image ( resource $pdfdoc , string $imagetype , string $filename , string $optlist ) : int
# Opens a disk-based or virtual image file subject to various options.
################################################################################

function pdf_load_image(& $pdf, $imagetype, $filename, $optlist = array())
	{
	$a = _pdf_add_image($pdf, $filename);

	$b = _pdf_get_free_xobject_id($pdf);

	$pdf["loaded-resources"]["/XObject"][$b] = $a;

	return("/X" . $b);
	}

################################################################################
# PDF_makespotcolor - Make spot color
# PDF_makespotcolor ( resource $p , string $spotname ) : int
# Finds a built-in spot color name, or makes a named spot color from the current fill color.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_makespotcolor(& $pdf, $spotname)
	{
	}

################################################################################
# PDF_moveto - Set current point
# PDF_moveto ( resource $p , float $x , float $y ) : bool
# Sets the current point for graphics output.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_moveto(& $pdf, $x, $y)
	{
	$pdf["stream"][] = sprintf("%d %d m", $x, $y);
	}

################################################################################
# PDF_new - Create PDFlib object
# PDF_new ( void ) : resource
# Creates a new PDFlib object with default settings.
################################################################################

function pdf_new()
	{
	$retval = array
		(
		"apiname" => sprintf("%s %d.%d.%d (PHP/%s)", basename(__FILE__), 1, 0, 0, PHP_OS),
		"catalog" => "0 0 R",
		"filename" => "",
		"height" => 0,
		"info" => array(),
		"loaded-resources" => array(), # global
		"major" => 1,
		"minor" => 3,
		"objects" => array(0 => array("dictionary" => array("/Size" => 0))),
		"outlines" => "0 0 R",
		"pages" => "0 0 R",
		"stream" => array(),
		"used-resources" => array(), # page
		"width" => 0
		);

	return($retval);
	}

################################################################################
# PDF_open_ccitt - Open raw CCITT image [deprecated]
# PDF_open_ccitt ( resource $pdfdoc , string $filename , int $width , int $height , int $BitReverse , int $k , int $Blackls1 ) : int
# Opens a raw CCITT image.
# This function is deprecated since PDFlib version 5, use PDF_load_image() instead.
################################################################################

function pdf_open_ccitt(& $pdf, $filename, $width, $height, $BitReverse, $k, $Blackls1)
	{
	return(pdf_load_image($pdf, "ccitt", $filename, array("/Width" => $width, "/Height" => $height, "bitreverse" => $BitReverse, "k" => $k, "blacklsl" => $Blackls1)));
	}

################################################################################
# PDF_open_file - Create PDF file [deprecated]
# PDF_open_file ( resource $p , string $filename ) : bool
# Creates a new PDF file using the supplied file name.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use PDF_begin_document() instead.
################################################################################

function pdf_open_file(& $pdf, $filename)
	{
	return(pdf_begin_document($pdf, $filename));
	}

################################################################################
# PDF_open_gif - Open GIF image [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_load_image() instead.
################################################################################

function pdf_open_gif(& $pdf, $filename)
	{
	return(pdf_load_image($pdf, "gif", $filename));
	}

################################################################################
# PDF_open_image_file - Read image from file [deprecated]
# PDF_open_image_file ( resource $p , string $imagetype , string $filename , string $stringparam , int $intparam ) : int
# Opens an image file.
# This function is deprecated since PDFlib version 5, use PDF_load_image() with the colorize, ignoremask, invert, mask, masked, and page options instead.
################################################################################

function pdf_open_image_file(& $pdf, $imagetype, $filename, $stringparam = "", $intparam = 0)
	{
	return(pdf_load_image($pdf, $imagetype, $filename, array("colorize" => 0, "ignoremask" => 0, "invert" => 0, "mask" => 0, "masked" => 0, "page" => 0)));
	}

################################################################################
# PDF_open_image - Use image data [deprecated]
# PDF_open_image ( resource $p , string $imagetype , string $source , string $data , int $length , int $width , int $height , int $components , int $bpc , string $params ) : int
# Uses image data from a variety of data sources.
# This function is deprecated since PDFlib version 5, use virtual files and PDF_load_image() instead.
################################################################################

function pdf_open_image(& $pdf, $imagetype, $source, $data, $length, $width, $height, $component, $bpc, $params)
	{
	return(pdf_load_image($pdf, $imagetype, "", array("data" => $data, "length" => $length, "width" => $width, "height" => $height, "component" => $component, "bits_per_component" => $bpc, "params" => $params)));
	}

################################################################################
# PDF_open_jpeg - Open JPEG image [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_load_image() instead.
################################################################################

function pdf_open_jpeg(& $pdf, $filename)
	{
	return(pdf_load_image($pdf, "jpeg", $filename));
	}

################################################################################
# PDF_open_memory_image - Open image created with PHP's image functions [not supported]
# PDF_open_memory_image ( resource $p , resource $image ) : int
# This function is not supported by PDFlib GmbH.
################################################################################

function pdf_open_memory_image(& $pdf, $image)
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

function pdf_open_pdi_document(& $pdf, $filename, $optlist = array())
	{
	$pdf["filename"] = $filename;

	$pdf["stream"] = file_get_contents($pdf["filename"]);

	if(preg_match("/(%pdf-(\d+)\.(\d+)[\s|\n]+)((.*)[\s|\n]+)(startxref[\s|\n]+(\d+)[\s|\n]+)(%%eof[\s|\n]+).*/is", $pdf["stream"], $matches) == 1)
		{
		}
	}

################################################################################
# PDF_open_pdi_page - Prepare a page
# PDF_open_pdi_page ( resource $p , int $doc , int $pagenumber , string $optlist ) : int
# Prepares a page for later use with PDF_fit_pdi_page().
################################################################################

function pdf_open_pdi_page(& $pdf, $doc, $pagenumber, $optlist = array())
	{
	}

################################################################################
# PDF_open_pdi - Open PDF file [deprecated]
# PDF_open_pdi ( resource $pdfdoc , string $filename , string $optlist , int $len ) : int
# Opens a disk-based or virtual PDF document and prepares it for later use.
# This function is deprecated since PDFlib version 7, use PDF_open_pdi_document() instead.
################################################################################

function pdf_open_pdi(& $pdf, $filename, $optlist = array())
	{
	return(pdf_open_pdi_document($pdf, $filename, $optlist));
	}

################################################################################
# PDF_open_tiff - Open TIFF image [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_load_image() instead.
################################################################################

function pdf_open_tiff(& $pdf, $filename)
	{
	return(pdf_load_image($pdf, "tiff", $filename));
	}

################################################################################
# PDF_pcos_get_number - Get value of pCOS path with type number or boolean
# PDF_pcos_get_number ( resource $p , int $doc , string $path ) : float
# Gets the value of a pCOS path with type number or boolean.
################################################################################

function pdf_pcos_get_number(& $pdf, $doc, $path)
	{
	}

################################################################################
# PDF_pcos_get_stream - Get contents of pCOS path with type stream, fstream, or string
# PDF_pcos_get_stream ( resource $p , int $doc , string $optlist , string $path ) : string
# Gets the contents of a pCOS path with type stream, fstream, or string.
################################################################################

function pdf_pcos_get_stream(& $pdf, $doc, $optlist, $path)
	{
	}

################################################################################
# PDF_pcos_get_string - Get value of pCOS path with type name, string, or boolean
# PDF_pcos_get_string ( resource $p , int $doc , string $path ) : string
# Gets the value of a pCOS path with type name, string, or boolean.
################################################################################

function pdf_pcos_get_string(& $pdf, $doc, $path)
	{
	}

################################################################################
# PDF_place_image - Place image on the page [deprecated]
# PDF_place_image ( resource $pdfdoc , int $image , float $x , float $y , float $scale ) : bool
# Places an image and scales it.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 5, use PDF_fit_image() instead.
################################################################################

function pdf_place_image(& $pdf, $image, $x, $y, $scale)
	{
	pdf_fit_image($pdf, $image, $x, $y, array("scale" => $scale));
	}

################################################################################
# PDF_place_pdi_page - Place PDF page [deprecated]
# PDF_place_pdi_page ( resource $pdfdoc , int $page , float $x , float $y , float $sx , float $sy ) : bool
# Places a PDF page and scales it.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 5, use PDF_fit_pdi_page() instead.
################################################################################

function pdf_place_pdi_page(& $pdf, $page, $x, $y, $sx, $sy)
	{
	pdf_fit_pdi_page($pdf, $page, $x, $y, array("sx" => $sx, "sy" => $sy));
	}

################################################################################
# PDF_process_pdi - Process imported PDF document
# PDF_process_pdi ( resource $pdfdoc , int $doc , int $page , string $optlist ) : int
# Processes certain elements of an imported PDF document.
################################################################################

function pdf_process_pdi(& $pdf, $doc, $page, $optlist = array())
	{
	}

################################################################################
# PDF_rect - Draw rectangle
# PDF_rect ( resource $p , float $x , float $y , float $width , float $height ) : bool
# Draws a rectangle.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_rect(& $pdf, $x, $y, $width, $height)
	{
	$pdf["stream"][] = sprintf("%d %d %d %d re", $x, $y, $width, $height);
	}

################################################################################
# PDF_restore - Restore graphics state
# PDF_restore ( resource $p ) : bool
# Restores the most recently saved graphics state.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_restore(& $pdf)
	{
	$pdf["stream"][] = "Q";
	}

################################################################################
# PDF_resume_page - Resume page
# PDF_resume_page ( resource $pdfdoc , string $optlist ) : bool
# Resumes a page to add more content to it.
################################################################################

function pdf_resume_page(& $pdf, $optlist = array())
	{
	}

################################################################################
# PDF_rotate - Rotate coordinate system
# PDF_rotate ( resource $p , float $phi ) : bool
# Rotates the coordinate system.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_rotate(& $pdf, $phi)
	{
	$sin = sin($phi * M_PI / 180);
	$cos = cos($phi * M_PI / 180);

	pdf_concat($pdf, 0 + $cos, 0 + $sin, 0 - $sin, 0 + $cos, 0, 0);
	}

################################################################################
# PDF_save - Save graphics state
# PDF_save ( resource $p ) : bool
# Saves the current graphics state.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_save(& $pdf)
	{
	$pdf["stream"][] = "q";
	}

################################################################################
# PDF_scale — Scale coordinate system
# PDF_scale ( resource $p , float $sx , float $sy ) : bool
# Scales the coordinate system.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_scale(& $pdf, $sx, $sy)
	{
	pdf_concat($pdf, $sx, 0, 0, $sy, 0, 0);
	}

################################################################################
# PDF_set_border_color — Set border color of annotations [deprecated]
# PDF_set_border_color ( resource $p , float $red , float $green , float $blue ) : bool
# Sets the border color for all kinds of annotations.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use the option annotcolor in PDF_create_annotation() instead.
################################################################################

function pdf_set_border_color(& $pdf, $red, $green, $blue)
	{
	}

################################################################################
# PDF_set_border_dash — Set border dash style of annotations [deprecated]
# PDF_set_border_dash ( resource $pdfdoc , float $black , float $white ) : bool
# Sets the border dash style for all kinds of annotations.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use the option dasharray in PDF_create_annotation() instead.
################################################################################

function pdf_set_border_dash(& $pdf, $black, $white)
	{
	}

################################################################################
# PDF_set_border_style — Set border style of annotations [deprecated]
# PDF_set_border_style ( resource $pdfdoc , string $style , float $width ) : bool
# Sets the border style for all kinds of annotations.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 6, use the options borderstyle and linewidth in PDF_create_annotation() instead.
################################################################################

function pdf_set_border_style(& $pdf, $style, $width)
	{
	}

################################################################################
# PDF_set_char_spacing — Set character spacing [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_value() with parameter charspacing instead.
################################################################################

function pdf_set_char_spacing(& $pdf, $space)
	{
	pdf_set_value($pdf, "charspacing", $space);
	}

################################################################################
# PDF_set_duration - Set duration between pages [deprecated]
# This function is deprecated since PDFlib version 3, use the duration option in PDF_begin_page_ext() or PDF_end_page_ext() instead.
################################################################################

function pdf_set_duration(& $pdf, $duration)
	{
	$pdf["duration"] = $duration; # not used yet
	}

################################################################################
# PDF_set_gstate - Activate graphics state object
# PDF_set_gstate ( resource $pdfdoc , int $gstate ) : bool
# Activates a graphics state object.
################################################################################

function pdf_set_gstate(& $pdf, $gstate)
	{
	}

################################################################################
# PDF_set_horiz_scaling - Set horizontal text scaling [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_value() with parameter horizscaling instead.
################################################################################

function pdf_set_horizontal_scaling(& $pdf, $value)
	{
	pdf_set_value($pdf, "horizscaling", $value);
	}

################################################################################
# PDF_set_info_author - Fill the author document info field [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_info() instead.
################################################################################

function pdf_set_info_author(& $pdf, $value)
	{
	pdf_set_info($pdf, "Author", $value);
	}

################################################################################
# PDF_set_info_creator - Fill the creator document info field [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_info() instead.
################################################################################

function pdf_set_info_creator(& $pdf, $value)
	{
	pdf_set_info($pdf, "Creator", $value);
	}

################################################################################
# PDF_set_info_keywords - Fill the keywords document info field [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_info() instead.
################################################################################

function pdf_set_info_keywords(& $pdf, $value)
	{
	pdf_set_info($pdf, "Keywords", $value);
	}

################################################################################
# PDF_set_info_subject - Fill the subject document info field [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_info() instead.
################################################################################

function pdf_set_info_subject(& $pdf, $value)
	{
	pdf_set_info($pdf, "Subject", $value);
	}

################################################################################
# PDF_set_info_title - Fill the title document info field [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_info() instead.
################################################################################

function pdf_set_info_title(& $pdf, $value)
	{
	pdf_set_info($pdf, "Title", $value);
	}

################################################################################
# PDF_set_info - Fill document info field
# PDF_set_info ( resource $p , string $key , string $value ) : bool
# Fill document information field key with value.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_set_info(& $pdf, $key, $value)
	{
	$pdf["info"]["/" . $key] = utf8_decode($value);
	}

################################################################################
# PDF_set_layer_dependency - Define relationships among layers
# PDF_set_layer_dependency ( resource $pdfdoc , string $type , string $optlist ) : bool
# Defines hierarchical and group relationships among layers.
# Returns TRUE on success or FALSE on failure.
# This function requires PDF 1.5.
################################################################################

function pdf_set_layer_dependency(& $pdf, $type, $optlist = array())
	{
	}

################################################################################
# PDF_set_leading - Set distance between text lines [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_value() with the parameter leading instead.
################################################################################

function pdf_set_leading(& $pdf, $distance)
	{
	pdf_set_value($pdf, "leading", $distance);
	}

################################################################################
# PDF_set_parameter - Set string parameter
# PDF_set_parameter ( resource $p , string $key , string $value ) : bool
# Sets some PDFlib parameter with string type.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_set_parameter(& $pdf, $key, $value)
	{
	$pdf[$key] = $value;
	}

################################################################################
# PDF_set_text_pos - Set text position
# PDF_set_text_pos ( resource $p , float $x , float $y ) : bool
# Sets the position for text output on the page.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_set_text_pos(& $pdf, $x, $y)
	{
	$pdf["stream"][] = sprintf("%d %d Td", $x, $y);
	}

################################################################################
# PDF_set_text_rendering - Determine text rendering [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_value() with the textrendering parameter instead.
################################################################################

function pdf_set_text_rendering(& $pdf, $textrendering)
	{
	pdf_set_value($pdf, "textrendering", $textrendering);
	}

################################################################################
# PDF_set_text_rise - Set text rise [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_value() with the textrise parameter instead.
################################################################################

function pdf_set_text_rise(& $pdf, $textrise)
	{
	pdf_set_value($pdf, "textrise", $textrise);
	}

################################################################################
# PDF_set_value - Set numerical parameter
# PDF_set_value ( resource $p , string $key , float $value ) : bool
# Sets the value of some PDFlib parameter with numerical type.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_set_value(& $pdf, $key, $value)
	{
	switch($key)
		{
		case("charspacing"):
			$pdf["stream"][] = sprintf("%d Tc", $value); break;
		case("horizscaling"):
			$pdf["stream"][] = sprintf("%d Tz", $value); break;
		case("leading"):
			$pdf["stream"][] = sprintf("%d TL", $value); break;
		case("linecap"):
			$pdf["stream"][] = sprintf("%d J", $value); break;
		case("linejoin"):
			$pdf["stream"][] = sprintf("%d j", $value); break;
		case("linewidth"):
			$pdf["stream"][] = sprintf("%d w", $value); break;
		case("miterlimit"):
			$pdf["stream"][] = sprintf("%d M", $value); break;
		case("textrendering"):
			$pdf["stream"][] = sprintf("%d Tr", $value); break;
		case("textrise"):
			$pdf["stream"][] = sprintf("%d Ts", $value); break;
		case("wordspacing"):
			$pdf["stream"][] = sprintf("%d Tw", $value); break;
		}
	}

################################################################################
# PDF_set_word_spacing - Set spacing between words [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_set_value() with the wordspacing parameter instead.
################################################################################

function pdf_set_word_spacing(& $pdf, $wordspacing)
	{
	pdf_set_value($pdf, "wordspacing", $wordspacing);
	}

################################################################################
# PDF_setcolor - Set fill and stroke color
# PDF_setcolor ( resource $p , string $fstype , string $colorspace , float $c1 , float $c2 , float $c3 , float $c4 ) : bool
# Sets the current color space and color.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setcolor(& $pdf, $fstype, $colorspace, $c1, $c2 = 0, $c3 = 0, $c4 = 0)
	{
	if(($fstype == "fill") && ($colorspace == "gray"))
		return($pdf["stream"][] = sprintf("%.1f g", $c1));

	if(($fstype == "fill") && ($colorspace == "rgb"))
		return($pdf["stream"][] = sprintf("%.1f %.1f %.1f rg", $c1, $c2, $c3));

	if(($fstype == "fill") && ($colorspace == "cmyk"))
		return($pdf["stream"][] = sprintf("%.1f %.1f %.1f %.1f k", $c1, $c2, $c3, $c4));

	if(($fstype == "stroke") && ($colorspace == "gray"))
		return($pdf["stream"][] = sprintf("%.1f G", $c1));

	if( ($fstype == "stroke") &&($colorspace == "rgb"))
		return($pdf["stream"][] = sprintf("%.1f %.1f %.1f RG", $c1, $c2, $c3));

	if(($fstype == "stroke") && ($colorspace == "cmyk"))
		return($pdf["stream"][] = sprintf("%.1f %.1f %.1f %.1f K", $c1, $c2, $c3, $c4));
	}

################################################################################
# PDF_setdash - Set simple dash pattern
# PDF_setdash ( resource $pdfdoc , float $b , float $w ) : bool
# Sets the current dash pattern to b black and w white units.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setdash(& $pdf, $b, $w)
	{
	$pdf["stream"][] = sprintf("%.1f %.1f d", $b, $w);
	}

################################################################################
# PDF_setdashpattern - Set dash pattern
# PDF_setdashpattern ( resource $pdfdoc , string $optlist ) : bool
# Sets a dash pattern defined by an option list.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setdashpattern(& $pdf, $optlist = array())
	{
	}

################################################################################
# pdf_setflat - Set flatness
# PDF_setflat ( resource $pdfdoc , float $flatness ) : bool
# Sets the flatness parameter.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setflat(& $pdf, $flatness)
	{
	$pdf["stream"][] = sprintf("%.1f i", $flatness);
	}

################################################################################
# PDF_setfont - Set font
# PDF_setfont ( resource $pdfdoc , int $font , float $fontsize ) : bool
# Sets the current font in the specified fontsize, using a font handle returned by PDF_load_font().
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setfont(& $pdf, $font, $fontsize)
	{
	if(sscanf($font, "/F%d", $font_id) != 1)
		$font = pdf_findfont($pdf, $font, "winansi");

	if(sscanf($font, "/F%d", $font_id) != 1)
		die("pdf_setfont: invalid font.");

	$pdf["used-resources"]["/Font"][$font_id] = $pdf["loaded-resources"]["/Font"][$font_id];
	
	$pdf["font"] = $font;
	$pdf["fontsize"] = $fontsize;
	$pdf["stream"][] = sprintf("%s %d Tf", $font, $fontsize);
	}

################################################################################
# PDF_setgray - Set color to gray [deprecated]
# PDF_setgray ( resource $p , float $g ) : bool
# Sets the current fill and stroke color to a gray value between 0 and 1 inclusive.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 4, use PDF_setcolor() instead.
################################################################################

function pdf_setgray(& $pdf, $g)
	{
	pdf_setcolor($pdf, "fill", "gray", $g);
	pdf_setcolor($pdf, "stroke", "gray", $g);
	}

################################################################################
# PDF_setgray_fill - Set fill color to gray [deprecated]
# PDF_setgray_fill ( resource $p , float $g ) : bool
# Sets the current fill color to a gray value between 0 and 1 inclusive.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 4, use PDF_setcolor() instead.
################################################################################

function pdf_setgray_fill(& $pdf, $g)
	{
	pdf_setcolor($pdf, "fill", "gray", $g);
	}

################################################################################
# PDF_setgray_stroke - Set stroke color to gray [deprecated]
# PDF_setgray_stroke ( resource $p , float $g ) : bool
# Sets the current stroke color to a gray value between 0 and 1 inclusive.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 4, use PDF_setcolor() instead.
################################################################################

function pdf_setgray_stroke(& $pdf, $g)
	{
	pdf_setcolor($pdf, "stroke", "gray", $g);
	}

################################################################################
# PDF_setlinecap - Set linecap parameter
# PDF_setlinecap ( resource $p , int $linecap ) : bool
# Sets the linecap parameter to control the shape at the end of a path with respect to stroking.
################################################################################

function pdf_setlinecap(& $pdf, $linecap)
	{
	pdf_set_value($pdf, "linecap", $linecap);
	}

################################################################################
# PDF_setlinejoin - Set linejoin parameter
# PDF_setlinejoin ( resource $p , int $value ) : bool
# Sets the linejoin parameter to specify the shape at the corners of paths that are stroked.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setlinejoin(& $pdf, $value)
	{
	pdf_set_value($pdf, "linejoin", $value);
	}

################################################################################
# PDF_setlinewidth - Set line width
# PDF_setlinewidth ( resource $p , float $width ) : bool
# Sets the current line width.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setlinewidth(& $pdf, $width)
	{
	pdf_set_value($pdf, "linewidth", $width);
	}

################################################################################
# PDF_setmatrix - Set current transformation matrix
# PDF_setmatrix ( resource $p , float $a , float $b , float $c , float $d , float $e , float $f ) : bool
# Explicitly sets the current transformation matrix.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setmatrix(& $pdf, $a, $b, $c, $d, $e, $f)
	{
	$pdf["stream"][] = sprintf("%.1f %.1f %.1f %.1f %.1f %.1f Tm", $a, $b, $c, $d, $e, $f);
	}

################################################################################
# PDF_setmiterlimit - Set miter limit
# PDF_setmiterlimit ( resource $pdfdoc , float $miter ) : bool
# Sets the miter limit.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_setmiterlimit(& $pdf, $miter)
	{
	pdf_set_value($pdf, "miterlimit", $miter);
	}

################################################################################
# PDF_setpolydash - Set complicated dash pattern [deprecated]
# This function is deprecated since PDFlib version 5, use PDF_setdashpattern() instead.
################################################################################

function pdf_setpolydash(& $pdf, $dash)
	{
	pdf_setdashpattern($pdf, $dash);
	}

################################################################################
# PDF_setrgbcolor - Set fill and stroke rgb color values [deprecated]
# PDF_setrgbcolor ( resource $p , float $red , float $green , float $blue ) : bool
# Sets the current fill and stroke color to the supplied RGB values.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 4, use PDF_setcolor() instead.
################################################################################

function pdf_setrgbcolor(& $pdf, $red, $green, $blue)
	{
	pdf_setcolor($pdf, "fill", "rgb", $red, $green, $blue);
	pdf_setcolor($pdf, "stroke", "rgb", $red, $green, $blue);
	}

################################################################################
# PDF_setrgbcolor_fill - Set fill rgb color values [deprecated]
# PDF_setrgbcolor_fill ( resource $p , float $red , float $green , float $blue ) : bool
# Sets the current fill color to the supplied RGB values.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 4, use PDF_setcolor() instead.
################################################################################

function pdf_setrgbcolor_fill(& $pdf, $red, $green, $blue)
	{
	pdf_setcolor($pdf, "fill", "rgb", $red, $green, $blue);
	}

################################################################################
# PDF_setrgbcolor_stroke - Set stroke rgb color values [deprecated]
# PDF_setrgbcolor_stroke ( resource $p , float $red , float $green , float $blue ) : bool
# Sets the current stroke color to the supplied RGB values.
# Returns TRUE on success or FALSE on failure.
# This function is deprecated since PDFlib version 4, use PDF_setcolor() instead.
################################################################################

function pdf_setrgbcolor_stroke(& $pdf, $red, $green, $blue)
	{
	pdf_setcolor($pdf, "stroke", "rgb", $red, $green, $blue);
	}

################################################################################
# pdf_set_text_matrix - Set text matrix [deprecated]
# This function is deprecated since PDFlib version 3, use PDF_scale(), PDF_translate(), PDF_rotate(), or PDF_skew() instead.
################################################################################

function pdf_settext_matrix(& $pdf, $a, $b, $c, $d, $e, $f)
	{
	$pdf["stream"][] = sprintf("%.1f %.1f %.1f %.1f %.1f %.1f Tm", $a, $b, $c, $d, $e, $f);
	}

################################################################################
# pdf_shading - Define blend
# pdf_shading ( resource $pdf , string $shtype , float $x0 , float $y0 , float $x1 , float $y1 , float $c1 , float $c2 , float $c3 , float $c4 , string $optlist ) : int
# Defines a blend from the current fill color to another color.
# This function requires PDF 1.4 or above.
################################################################################

function pdf_shading(& $pdf, $shtype, $x0, $y0, $x1, $y1, $c1, $c2, $c3, $c4, $optlist = array())
	{
	}

################################################################################
# pdf_shading_pattern - Define shading pattern
# pdf_shading_pattern ( resource $pdf , int $shading , string $optlist ) : int
# Defines a shading pattern using a shading object.
# This function requires PDF 1.4 or above.
################################################################################

function pdf_shading_pattern(& $pdf, $shading, $optlist = array())
	{
	}

################################################################################
# pdf_shfill - Fill area with shading
# pdf_shfill ( resource $pdfdoc , int $shading ) : bool
# Fills an area with a shading, based on a shading object.
# This function requires PDF 1.4 or above.
################################################################################

function pdf_shfill(& $pdf, $shading)
	{
	$pdf["stream"][] = sprintf("/Sh%d sh", $shading);
	}

################################################################################
# pdf_show - Output text at current position
# pdf_show ( resource $pdfdoc , string $text ) : bool
# Prints text in the current font and size at the current position.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_show(& $pdf, $text)
	{
	if(! $text)
		return;

	$text = str_replace("\r", "", $text);
	$text = utf8_decode($text);
	$text = str_replace(array("\\", "(", ")"), array("\\\\", "\\(", "\\)"), $text);

	$pdf["stream"][] = sprintf("(%s) Tj", $text);
	}

################################################################################
# pdf_show_boxed - Output text in a box [deprecated]
# pdf_show_boxed ( resource $p , string $text , float $left , float $top , float $width , float $height , string $mode , string $feature ) : int
# This function is deprecated since PDFlib version 6, use PDF_fit_textline() for single lines, or the PDF_*_textflow() functions for multi-line formatting instead.
################################################################################

function pdf_show_boxed(& $pdf, $text, $left, $top, $width, $height, $mode, $feature = array())
	{
	if(! $text)
		return(0);

	################################################################################

	$text = str_replace("  ", " ", $text);

	################################################################################

	$fontsize = pdf_get_value($pdf, "fontsize", 0);
	$font = pdf_get_value($pdf, "font", 0);

	if($height - $fontsize < 0)
		return(strlen($text));

	list($line, $text) = (strpos($text, "\n") ? explode("\n", $text, 2) : array($text, ""));

	$words = "";

	while(strlen($line) > 0)
		{
		list($word, $line) = (strpos($line, " ") ? explode(" ", $line, 2) : array($line, ""));

		$test = ($words ? $words . " " : "") . $word;

		if($width - pdf_stringwidth($pdf, $test, $font, $fontsize) < 0)
			{
			$text = $word . ($line ? " " . $line : "") . ($text ? "\n" . $text : "");

			break;
			}

		$words = $test;
		}

	if(! $words)
		return(strlen($text));

	################################################################################
	# export to pdf_*_textflow
	################################################################################

	$spacing = $width - pdf_stringwidth($pdf, $words, $font, $fontsize);

	if(($mode == "justify") || ($mode == "fulljustify"))
		{
#		if(($mode == "justify") && ($spacing > ($width / 2)))
#			$spacing = $width / 2;

		pdf_set_word_spacing($pdf, $spacing / (count(explode(" ", $words)) - 1));

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

	pdf_show_xy($pdf, $words, $left + $spacing, $top);

	return(pdf_show_boxed($pdf, $text, $left, $top - $fontsize, $width, $height - $fontsize, $mode, $feature));
	}

################################################################################
# pdf_show_xy - Output text at given position
# pdf_show_xy ( resource $pdf , string $text , float $x , float $y ) : bool
# Prints text in the current font.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_show_xy(& $pdf, $text, $x, $y)
	{
	if(! $text)
		return;

	$text = str_replace("\r", "", $text);
	$text = utf8_decode($text);
	$text = str_replace(array("\\", "(", ")"), array("\\\\", "\\(", "\\)"), $text);

	$pdf["stream"][] = "BT";
	$pdf["stream"][] = sprintf("%d %d Td", $x, $y); # pdf_set_text_pos
	$pdf["stream"][] = sprintf("(%s) Tj", $text);
	$pdf["stream"][] = "ET";
	}

################################################################################
# pdf_skew - Skew the coordinate system
# pdf_skew ( resource $p , float $alpha , float $beta ) : bool
# Skews the coordinate system in x and y direction by alpha and beta degrees, respectively.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_skew(& $pdf, $alpha, $beta)
	{
	$alpha = tan($alpha * M_PI / 180); # deg 2 rad
	$beta = tan($beta * M_PI / 180); # deg 2 rad

	pdf_concat($pdf, 1, $alpha, $beta, 1, 0, 0);
	}

################################################################################
# pdf_stringwidth - Return width of text
# pdf_stringwidth ( resource $pdf , string $text , int $font , float $fontsize ) : float
# Returns the width of text in an arbitrary font.
################################################################################

function pdf_stringwidth(& $pdf, $text, $font, $fontsize)
	{
	if(! $text)
		return(0);

	if(sscanf($font, "/F%d", $font_id) != 1)
		die("pdf_setfont: invalid font.");

	if($fontsize == 0)
		die("pdf_setfont: invalid fontsize.");

	$text = utf8_decode($text);

	if(sscanf($pdf["loaded-resources"]["/Font"][$font_id], "%d %d R", $id, $version) != 2)
		die("pdf_setfont: invalid font.");

	$a = $pdf["objects"][$id]["dictionary"]["/Widths"];

	$a = substr($a, 1);
	list($b, $a) = _pdf_parse_array($a);
	$a = substr($a, 1);

	$width = 0;

	foreach(str_split($text) as $char)
		$width += $b[ord($char)];

	return($width / 1000 * $fontsize);
	}

################################################################################
# pdf_stroke - Stroke path
# pdf_stroke ( resource $pdf ) : bool
# Strokes the path with the current color and line width, and clear it.
# Returns TRUE on success or FALSE on failure.
################################################################################

function pdf_stroke(& $pdf)
	{
	$pdf["stream"][] = "S";
	}

################################################################################
# pdf_suspend_page - Suspend page
# pdf_suspend_page ( resource $pdf , string $optlist ) : bool
# Suspends the current page so that it can later be resumed with PDF_resume_page().
################################################################################

function pdf_suspend_page(& $pdf, $optlist = array())
	{
	}

################################################################################
# pdf_translate - Set origin of coordinate system
# pdf_translate ( resource $pdf , float $tx , float $ty ) : bool
# Translates the origin of the coordinate system.
################################################################################

function pdf_translate(& $pdf, $tx, $ty)
	{
	pdf_concat($pdf, 1, 0, 0, 1, $tx, $ty);
	}

################################################################################
# pdf_utf8_to_utf16 - Convert string from UTF-8 to UTF-16
# pdf_utf8_to_utf16 ( resource $pdf , string $utf8string , string $ordering ) : string
# Converts a string from UTF-8 format to UTF-16.
################################################################################

function pdf_utf8_to_utf16(& $pdf, $utf8string, $ordering)
	{
	foreach($ordering as $k => $v)
		iconv_set_encoding($k, $v);

	return(iconv("UTF-8", "UTF-16", $utf8string));
	}

################################################################################
# pdf_utf16_to_utf8 - Convert string from UTF-16 to UTF-8
# pdf_utf16_to_utf8 ( array $pdf , string $utf16string ) : string
# Converts a string from UTF-16 format to UTF-8.
################################################################################

function pdf_utf16_to_utf8(& $pdf, $utf16string)
	{
	return(iconv("UTF-16", "UTF-8", $utf16string));
	}

################################################################################
# pdf_utf32_to_utf16 - Convert string from UTF-32 to UTF-16
# pdf_utf32_to_utf16 ( array $pdf , string $utf32string , string $ordering ) : string
# Converts a string from UTF-32 format to UTF-16.
################################################################################

function pdf_utf32_to_utf16(& $pdf, $utf32string, $ordering)
	{
	foreach($ordering as $k => $v)
		iconv_set_encoding($k, $v);

	return(iconv("UTF-32", "UTF-16", $utf32string));
	}
?>
