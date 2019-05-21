<?
################################################################################
# copyright 2019 by Markus Olderdissen
# free for private use or inspiration.
# public use need written permission.
################################################################################

include("pdf-api.php");

_pdf_main();
#_pdf_test();

function _pdf_main()
	{
	$data = file_get_contents("pdf-api-test.pdf");

	$pdf["objects"] = _pdf_parse_document($data);

#	_pdf_add_font($pdf, "Verdana");
#	_pdf_filter_change($pdf);
#	_pdf_filter_change($pdf, "/FlateDecode");
	_pdf_filter_change($pdf, "/ASCIIHexDecode /FlateDecode");
#	_pdf_add_linearized($pdf);
#	print_r($pdf); exit;

	$data = _pdf_glue_document($pdf["objects"]);

	header("Content-Type: application/pdf");
	header("Content-Disposition: inline; filename=parse-test.pdf");
	header("Content-Length: " . strlen($data));

	print($data);
	}

function _pdf_test()
	{
	$pdf = _pdf_new();

	$catalog = _pdf_add_catalog($pdf);

	$outlines = _pdf_add_outlines($pdf, $catalog);

	$pages = _pdf_add_pages($pdf, $catalog);

	$pdf["loaded-resources"]["/ProcSet"] = array("/PDF", "/Text");
	$pdf["loaded-resources"]["/Font"] = array("/F1" => _pdf_add_font($pdf, "Courier"));

	# procset is array. array need to be glued. dictionary will be glued later in any case.

	if(isset($pdf["loaded-resources"]["/ProcSet"]))
		$pdf["loaded-resources"]["/ProcSet"] = sprintf("[%s]", _pdf_glue_array($pdf["loaded-resources"]["/ProcSet"]));

	$resources = $pdf["loaded-resources"];

	foreach(range(1, 1) as $i)
		{
		_pdf_begin_page($pdf, 595, 842);
		$pdf["stream"][] = "BT";
		_pdf_set_font($pdf, "/F1", 12); # use return value of _add_font as fontname
		_pdf_set_leading($pdf, 12);
		_pdf_set_xy($pdf, 3, 3);
		_pdf_set_text($pdf, "ABC " . $i);
		$pdf["stream"][] = "ET";
		_pdf_end_page($pdf); # store loaded resources

		$contents = _pdf_get_buffer($pdf);

		$mediabox = sprintf("[0 0 %d %d]", 595, 842);

		$page = _pdf_add_page($pdf, $pages, $resources, $mediabox, $contents);

		$outline = _pdf_add_outline($pdf, $outlines, $page, "Seite " . $i);
		}

	$pdf["objects"][0]["dictionary"]["/Size"] = count($pdf["objects"]);

	$data = _pdf_glue_document($pdf["objects"]);

	header("Content-Type: application/pdf");
	header("Content-Disposition: inline; filename=parse-test.pdf");
	header("Content-Length: " . strlen($data));

	print($data);
	}
?>
