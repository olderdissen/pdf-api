<?
################################################################################
# copyright 2019 by Markus Olderdissen
# free for private use or inspiration.
# public use need written permission.
################################################################################

include("pdf-api.php");

#_pdf_main();
_pdf_test();

function _pdf_main()
	{
	$data = file_get_contents("pdf-api-test.pdf");

	$pdf["objects"] = _pdf_parse_document($data);

#		_pdf_add_font($pdf, "Verdana");

#		_pdf_filter_change($pdf);
#		_pdf_filter_change($pdf, "/FlateDecode");
		_pdf_filter_change($pdf, "/ASCIIHexDecode /FlateDecode");

#		print_r($pdf); exit;

	$data = _pdf_glue_document($pdf["objects"]);

	header("Content-Type: application/pdf");
	header("Content-Disposition: inline; filename=parse-test.pdf");
	header("Content-Length: " . strlen($data));

	print($data);
	}

function _pdf_test()
	{
	$pdf = _pdf_new();

	_pdf_begin_document($pdf);

		# returns /Fx where x is integer
		$font = _pdf_load_font($pdf, "Courier");

		# returns /Fx where x is integer but returns error on unloaded font.
		$font = _pdf_find_font($pdf, "Courier");

		foreach(range(1, 1) as $i)
			{
			_pdf_begin_page($pdf, 595, 842);

				_pdf_begin_text($pdf);

					# use return value of _pdf_add_font as fontname
					_pdf_set_font($pdf, $font, 12);

					_pdf_set_leading($pdf, 12);
					_pdf_set_xy($pdf, 3, 3);
					_pdf_set_text($pdf, "ABC " . $i);

				_pdf_end_text($pdf);

			# store loaded resources
			$page = _pdf_end_page($pdf);

#			$outline = _pdf_add_outline($pdf, $pdf["outlines"], $page, "page " . $i);
			}

	_pdf_end_document($pdf);

	$data = _pdf_get_buffer($pdf);

	header("Content-Type: application/pdf");
	header("Content-Disposition: inline; filename=parse-test.pdf");
	header("Content-Length: " . strlen($data));

	print($data);
	}
?>
