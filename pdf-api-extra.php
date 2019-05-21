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
# _pdf_add_acroform ( array $pdf , string $parent ) : string
################################################################################

function _pdf_add_acroform(& $pdf, $parent, $resources)
	{
	$this_id = _pdf_get_free_object_id($pdf);

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Fields" => "[]"
			)
		);

	################################################################################

	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_acroform: invalid parent: " . $parent);

	$pdf["objects"][$parent_id]["dictionary"]["/AcroForm"] = sprintf("%d 0 R", $this_id);

	################################################################################

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_annotation ( array $pdf , string $parent , string $rect , string $uri ) : string
################################################################################

function _pdf_add_annotation(& $pdf, $parent, $rect, $type, $optlist)
	{
	if($type == "link")
		{
		$this_id = _pdf_get_free_object_id($pdf);

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
		$this_id = _pdf_get_free_object_id($pdf);

		$pdf["objects"][$this_id] = array
			(
			"id" => $this_id,
			"version" => 0,
			"dictionary" => array
				(
				"/Type" => "/Annot",
				"/Subtype" => "/Link",
				"/Rect" => $rect,

				"/A" => array
					(
					"/Type" => "/Action",
					"/S" => "/URI",
					"/URI" => sprintf("(%s)", _pdf_glue_string($optlist["uri"]))
					)
				)
			);
		}

	################################################################################

	if($type == "widget")
		{
		$resources = array
			(
			"/ProcSet" => "[/PDF /Text]",
			"/Font" => "<< /F0 4 0 R >>"
			);

		$stream = "/Tx BMC q 1 1 98 8 re W n BT /F0 8 Tf 0 g 1 1 Td (static) Tj ET Q EMC";

		$a = _pdf_add_form($pdf, $resources, "[0 0 100 10]", $stream);

		################################################################################

		$this_id = _pdf_get_free_object_id($pdf);

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
					"/N" => $a
					)
				)
			);
		}

	################################################################################

	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_annots: invalid parent: " . $parent);

	################################################################################

	if(isset($pdf["objects"][$parent_id]["dictionary"]["/Annots"]))
		$data = $pdf["objects"][$parent_id]["dictionary"]["/Annots"];
	else
		$data = "[]";

	################################################################################

	$data = substr($data, 1);

	list($annots, $data) = _pdf_parse_array($data);

	$data = substr($data, 1);

	################################################################################

	$annots[] = sprintf("%d 0 R", $this_id);

	################################################################################

	$pdf["objects"][$parent_id]["dictionary"]["/Annots"] = sprintf("[%s]", _pdf_glue_array($annots));

	################################################################################

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_page ( array $df ) : string
################################################################################

function _pdf_add_catalog(& $pdf)
	{
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

	################################################################################

	$pdf["objects"][0]["dictionary"]["/Root"] = sprintf("%d 0 R", $this_id);

	################################################################################

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

	list($fields, $data) = _pdf_parse_array($data);

	$data = substr($data, 1);
	
	################################################################################

	$fields[] = $field;
	
	################################################################################

	$pdf["objects"][$parent_id]["dictionary"]["/Fields"] = sprintf("[%s]", _pdf_glue_array($fields));
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

		$this_id = _pdf_get_free_object_id($pdf);

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

	################################################################################

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
			"/Encoding" => $encoding,
			"/FirstChar" => 32,
			"/LastChar" => 255,
			"/Widths" => sprintf("[%s]", _pdf_glue_array($widths)),
			"/FontDescriptor" => array
				(
#				"/Type" => "/FontDescriptor",
#				"/Flags" => FONTDESCRIPTOR_FLAG_SERIF | FONTDESCRIPTOR_FLAG_SCRIPT,
#				"/StemV" => 90,
#				"/CapHeight" => 720,
#				"/XHeight" => 480,
#				"/Ascent" => 720,
#				"/Descent" => 0 - 250,
#				"/ItalicAngle" => 0,
#				"/FontBBox" => "[0 -240 1440 1000]",
				"/FontName" => "/" . $fontname,
				"/FontFile2" => $a
				)
			)
		);

	################################################################################

	$id = _pdf_get_free_font_id($pdf);

	$pdf["loaded-resources"]["/Font"][$id] = sprintf("%d 0 R", $this_id);

	################################################################################

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_font_encoding ( array $pdf , string $differences ) : string
################################################################################

function _pdf_add_font_encoding(& $pdf, $encoding = "/WinAnsiEncoding", $differences = "[65 /A /B /C]")
	{
	$this_id = _pdf_get_free_object_id($pdf);

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

	################################################################################

	$this_id = _pdf_get_free_object_id($pdf);

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

	################################################################################

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_fontfile ( array $pdf , string $filename ) : string
################################################################################

function _pdf_add_font_file(& $pdf, $filename)
	{
	$this_id = _pdf_get_free_object_id($pdf);

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Length" => filesize($filename),
			"/Length1" => filesize($filename)
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

	################################################################################

	$id = _pdf_get_free_xobject_id($pdf);

	$pdf["loaded-resources"]["/XObject"][$id] = sprintf("%d 0 R", $this_id);

	################################################################################

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

	################################################################################

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

	################################################################################

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

	################################################################################

	$id = _pdf_get_free_xobject_id($pdf);

	$pdf["loaded-resources"]["/XObject"][$id] = sprintf("%d 0 R", $this_id);

	################################################################################

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_info ( array $pdf , array $optlist ) : string
################################################################################

function _pdf_add_info(& $pdf, $optlist)
	{
	$this_id = _pdf_get_free_object_id($pdf);

	$objects[$this_id] = array
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

	################################################################################

	foreach($optlist as $key => $value)
		$objects[$this_id]["dictionary"][$key] = sprintf("(%s)", _pdf_glue_string($value));

	$pdf["objects"][0]["dictionary"]["/Info"] = sprintf("%d 0 R", $this_id);

	################################################################################

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_linearized ( array $pdf ) : string
################################################################################

function _pdf_add_linearized(& $pdf)
	{
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

	$hint = _pdf_add_linearized_hints($pdf, "");

	if(sscanf($hint, "%d %d R", $hint_id, $hint_version) != 2)
		die("_pdf_add_linearized: invalid hint stream offset.");

	################################################################################

	$this_id = _pdf_get_free_object_id($pdf);

	$retval = array("%PDF-1.0");

	$offsets = array();

	foreach($pdf["objects"] as $index => $object)
		{
		if($index == 0) # trailer
			continue;

		if(isset($object["dictionary"]["/Linearized"]))
			$this_id = $index;

		if($index == $page_id)
			$e = strlen(implode("\n", $retval)) + 1;

		if($index == $hint_id)
			$hint_offset = strlen(implode("\n", $retval)) + 1;

		$offsets[$index] = strlen(implode("\n", $retval)) + 1;

		$retval[] = _pdf_glue_object($object);
		}

	$startxref = strlen(implode("\n", $retval)) + 1;

	$l = 0;

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Linearized" => 1,

			# Offset of end of first page
			"/E" => $e,

			# Primary hint stream offset and length
			# int.: offset is equal to number in xref table
			"/H" => sprintf("[%d %d]", $hint_id, $hint_offset),

			# File length
			"/L" => $l,

			# Number of pages in document
			"/N" => $count,

			# Object number of first page’s page object
			"/O" => $page_id,

			# Offset of first entry in main cross-reference table
			# startxret + 10
			"/T" => $startxref + strlen($count) + strlen($startxref) + strlen($page_id) + 83
			)
		);

	################################################################################

	return(sprintf("%d 0 R", $this_id));
	}

function _pdf_add_linearized_hints(& $pdf, $stream = "")
	{
	if(strlen($stream) == 0)
		$stream = "\x00\x00\x00\x00";

	################################################################################

	$this_id = _pdf_get_free_object_id($pdf);

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/S" => 0, # Position of shared object hint table
			"/Length" => strlen($stream)
			),
		"stream" => $stream # Page offset hint table, Shared object hint table, Possibly other hint tables
		);

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_metadata ( array $pdf , string $parent , string $stream ) : string
################################################################################

function _pdf_add_metadata(& $pdf, $parent, $stream = "")
	{
	if(strlen($stream) == 0)
		$stream = '<?xpacket?><x:xmpmeta xmlns:x="adobe:ns:meta/"><r:RDF xmlns:r="http://www.w3.org/1999/02/22-rdf-syntax-ns#"><r:Description xmlns:p="http://www.aiim.org/pdfa/ns/id/"><p:part>1</p:part><p:conformance>A</p:conformance></r:Description></r:RDF></x:xmpmeta><?xpacket?>';

	################################################################################

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

	################################################################################

	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_metadata: invalid parent: " . $parent);

	$pdf["objects"][$parent_id]["dictionary"]["/Metadata"] = sprintf("%d 0 R", $this_id);

	################################################################################

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_outline ( array $pdf , string $parent , string $open , string $title ) : string
################################################################################

function _pdf_add_outline(& $pdf, $parent, $open, $title)
	{
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

	################################################################################

	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_outline: invalid parent: " . $parent);

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

	################################################################################

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_outlines ( array $pdf , string $parent ) : string
################################################################################

function _pdf_add_outlines(& $pdf, $parent)
	{
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

	################################################################################

	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_outlines: invalid parent:" . $parent);

	$pdf["objects"][$parent_id]["dictionary"]["/Outlines"] = sprintf("%d 0 R", $this_id);

	if($pdf["objects"][$parent_id]["dictionary"]["/Type"] == "/Pages")
		$pdf["objects"][$this_id]["dictionary"]["/Parent"] = $parent;

	################################################################################

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_page ( array $pdf , string $parent , string $resources , string $mediabox , string $contents ) : string
################################################################################

function _pdf_add_page(& $pdf, $parent, $resources, $mediabox, $contents)
	{
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
			"/Contents" => $contents,
#			"/Group" => "<< /Type /Group /S /Transparency /CS /DeviceRGB >>"
			)
		);

	################################################################################

	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_pages: invalid parent: " . $parent);

	################################################################################

	if(isset($pdf["objects"][$parent_id]["dictionary"]["/Kids"]))
		$data = $pdf["objects"][$parent_id]["dictionary"]["/Kids"];
	else
		$data = "[]";

	################################################################################

	$data = substr($data, 1);

	list($kids, $data) = _pdf_parse_array($data);

	$data = substr($data, 1);

	################################################################################

	$kids[] = sprintf("%d 0 R", $this_id);
	
	################################################################################

	$pdf["objects"][$parent_id]["dictionary"]["/Kids"] = sprintf("[%s]", _pdf_glue_array($kids));
	$pdf["objects"][$parent_id]["dictionary"]["/Count"] = count($kids);

	################################################################################

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_pages ( array $pdf , string $parent ) : string
################################################################################

function _pdf_add_pages(& $pdf, $parent)
	{
	$this_id = _pdf_get_free_object_id($pdf);

	$pdf["objects"][$this_id] = array
		(
		"id" => $this_id,
		"version" => 0,
		"dictionary" => array
			(
			"/Type" => "/Pages",
#			"/Parent" => "0 0 R",
			"/Kids" => "[]",
			"/Count" => 0
			)
		);

	################################################################################

	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_page: invalid parent: " . $parent);

	$pdf["objects"][$parent_id]["dictionary"]["/Pages"] = sprintf("%d 0 R", $this_id);

	################################################################################

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_add_stream ( array $pdf , string $stream ) : string
################################################################################

function _pdf_add_stream(& $pdf, $stream)
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

	return(sprintf("%d 0 R", $this_id));
	}

################################################################################
# _pdf_get_free_object_id ( array $pdf ) : int
################################################################################

function _pdf_get_free_object_id($pdf, $id = 1)
	{
	if(isset($pdf["objects"]))
		while(isset($pdf["objects"][$id]))
			$id ++;

	return($id);
	}

################################################################################
# _pdf_get_free_font_id ( array $pdf ) : int
################################################################################

function _pdf_get_free_font_id($pdf, $id = 1)
	{
	if(isset($pdf["loaded-resources"]["/Font"]))
		while(isset($pdf["loaded-resources"]["/Font"][$id]))
			$id ++;

	return($id);
	}

################################################################################
# _pdf_get_free_font_id ( array $pdf ) : int
################################################################################

function _pdf_get_free_xobject_id($pdf, $id = 1)
	{
	if(isset($pdf["loaded-resources"]["/XObject"]))
		while(isset($pdf["loaded-resources"]["/Font"][$id]))
			$id ++;

	return($id);
	}
?>
