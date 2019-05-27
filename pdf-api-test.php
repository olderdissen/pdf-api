<?
################################################################################
# copyright 2019 by Markus Olderdissen
# free for private use or inspiration.
# public use need written permission.
################################################################################

include_once("pdf-api.php");

#_pdf_main();
_pdf_test();

function _pdf_main()
	{
	$data = file_get_contents("pdf-api-test.pdf");

	$pdf["objects"] = _pdf_parse_document($data);

#		_pdf_add_font($pdf, "Verdana");

#		_pdf_filter_change($pdf);
#		_pdf_filter_change($pdf, "/FlateDecode");
#		_pdf_filter_change($pdf, "/ASCIIHexDecode /FlateDecode");

#		print_r($pdf); exit;

	$data = _pdf_glue_document($pdf["objects"]);

	header("Content-Type: application/pdf");
	header("Content-Disposition: inline; filename=parse-test.pdf");
	header("Content-Length: " . strlen($data));

	print($data);
	}

function _pdf_test()
	{
	$pdf = pdf_new();

	pdf_begin_document($pdf, "");

		# returns /Fx where x is integer
		# $font = pdf_load_font($pdf, "Verdana", "winansi");
		$font = pdf_load_font($pdf, "Courier", "winansi");

		# returns /Fx where x is integer but returns error on unloaded font.
		# $font = pdf_findfont($pdf, "Verdana");

		# returns /Xx where x is integer
		$image = pdf_load_image($pdf, "png", "pdf-api-test.png");

		foreach(range(1, 1) as $i)
			{
			pdf_begin_page($pdf, 595, 842);

				# use return value of _pdf_add_font as fontname
				pdf_setfont($pdf, $font, 72);

				pdf_set_leading($pdf, 12);
				pdf_show_xy($pdf, "ABC " . $i, 3, 3);

				pdf_fit_image($pdf, $image, 20, 20, array("scale" => 10));

			# store loaded resources
			$page = pdf_end_page($pdf);

			$outline = pdf_add_outline($pdf, "page " . $i, "", $page);
			}

	pdf_end_document($pdf);

	$data = pdf_get_buffer($pdf);

	header("Content-Type: application/pdf");
	header("Content-Disposition: inline; filename=parse-test.pdf");
	header("Content-Length: " . strlen($data));

	print($data);
	}
?>
