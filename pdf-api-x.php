<?
################################################################################
# written 2019 by markus olderdissen
################################################################################

function pdf_checkbox(& $pdf, $x, $y, $checked, $text, $space = 0.50)
	{
	if($text)
		{
		x_pdf_show_xy($pdf, $text, $x + 0.30 + $space, $y + 0.00);
		}

	pdf_setlinewidth($pdf, 0.25);

	x_pdf_rect($pdf, $x, $x, 0.3, 0.3);

	if($checked)
		{
		x_pdf_moveto($pdf, $x + 0.05, $y + 0.05);
		x_pdf_lineto($pdf, $x + 0.25, $y + 0.25);

		x_pdf_moveto($pdf, $x + 0.25, $y + 0.05);
		x_pdf_lineto($pdf, $x + 0.05, $y + 0.25);
		}

	pdf_stroke($pdf);
	}

function x_pdf_circle(& $Object, $X, $Y, $Z)
	{
	$X = cm_to_pt($X);
	$Y = cm_to_pt($Y);
	$Z = cm_to_pt($Z);

	pdf_circle($Object, $X, $Y, $Z);
	}

function x_pdf_lineto(& $Object, $X, $Y)
	{
	$X = cm_to_pt($X);
	$Y = cm_to_pt($Y);

	pdf_lineto($Object, $X, $Y);
	}

function x_pdf_moveto(& $Object, $X, $Y)
	{
	$X = cm_to_pt($X);
	$Y = cm_to_pt($Y);

	pdf_moveto($Object, $X, $Y);
	}

function x_pdf_place_image(& $Object, $Image, $X, $Y, $Z)
	{
	$X = cm_to_pt($X);
	$Y = cm_to_pt($Y);

	pdf_place_image($Object, $Image, $X, $Y, $Z);
	}

function x_pdf_rect(& $Object, $X, $Y, $W, $H)
	{
	$X = cm_to_pt($X);
	$Y = cm_to_pt($Y);
	$W = cm_to_pt($W);
	$H = cm_to_pt($H);

	pdf_rect($Object, $X, $Y, $W, $H);
	}

function x_pdf_show_boxed(& $Object, $Text, $X, $Y, $W, $H, $A)
	{
	$X = cm_to_pt($X);
	$Y = cm_to_pt($Y);
	$W = cm_to_pt($W);
	$H = cm_to_pt($H);

	return pdf_show_boxed($Object, $Text, $X, $Y, $W, $H, $A);
	}

function x_pdf_show_xy(& $Object, $Text, $X, $Y)
	{
	$X = cm_to_pt($X);
	$Y = cm_to_pt($Y);

	pdf_show_xy($Object, $Text, $X, $Y);
	}
?>
