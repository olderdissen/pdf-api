<?
################################################################################
# copyright 2019 by Markus Olderdissen
# free for private use or inspiration.
# public use need written permission.
################################################################################

define("MYSQL_HOST", "192.168.147.164");
define("MYSQL_PORT", 3306);
define("MYSQL_USER", "root");
define("MYSQL_PASS", "34096");
define("MYSQL_NAME", "documents");

include("pdf-api.php");

#_pdf_konto();
_main();

exit;

#foreach(glob("/home/nomatrix/externe_platte/dokumente/*.pnm") as $file)
#	{
	$file = "/home/nomatrix/externe_platte/dokumente/044527fa-f689-43e6-96a6-9296471d47aa.pnm";

	$palette = array();

	$data = file_get_contents($file);

	list($type, $data) = explode("\n", $data, 2);

	while(1)
		{
		if($data[0] == "#")
			list($comment, $data) = explode("\n", $data, 2);
		elseif($data[0] == "\n")
			list($comment, $data) = explode("\n", $data, 2);
		else
			break;
		}

	list($size, $colors, $data) = explode("\n", $data, 3);

	$data = bin2hex($data);

	if($type == "P5") # g
		{
		foreach(str_split($data, 2) as $color)
			$palette[$color] = $color;

		printf("name: %s size: % 10s colors: % 6d count: % 6d g\n", basename($file), $size, $colors, count($palette));
		}
	elseif($type == "P6") # rg
		{
		foreach(str_split($data, 6) as $color)
			$palette[$color] = $color;

		printf("name: %s size: % 10s colors: % 6d count: % 6d rg\n", basename($file), $size, $colors, count($palette));
		}
	else
		die($type);

#	ksort($palette);
#	print_r($palette);
#	}

function _main()
	{
#	$data = file_get_contents("/home/nomatrix/externe_platte/daten/pdf/n26/kontoauszüge/statement-2018-04.pdf");
#	$data = file_get_contents("/home/nomatrix/externe_platte/daten/pdf/arcor/web-bill/olderdissen.markus/rechnungen/Rechnung_3547279983.pdf");
#	$data = file_get_contents("002052527027.doc_11_04_19_09_45_26.pdf");
	$data = file_get_contents("brief.pdf");
#	$data = file_get_contents("22116a9b-c6b2-4901-91e8-3c18a7dd2f87.pdf");
#	$data = file_get_contents("https://www.mobiel.de/fileadmin/user_upload/Hauptnavigation/Fahrplaene/Fahrplaene/afp/data1804/afp_4195__21.pdf");

	$pdf["objects"] = _pdf_parse_document($data);

#	_pdf_add_font($pdf, "Verdana");
#	_pdf_filter_change($pdf);
	_pdf_filter_change($pdf, "/FlateDecode");
#	_pdf_filter_change($pdf, "/ASCIIHexDecode /FlateDecode");

	$data = _pdf_glue_document($pdf["objects"]);

	header("Content-Type: application/pdf");
	header("Content-Disposition: inline; filename=parse-test.pdf");
	header("Content-Length: " . strlen($data));

	print($data);
	}

function _pdf_konto_info($file)
	{
	if($resource = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_NAME, MYSQL_PORT))
		{
		$resource->query("set names 'utf8';");

		if($query = $resource->query("select * from documents where (id = '" . $file . "');"))
			while($result = $query->fetch_object())
				{
				$subject = $result->subject;
				$timestamp = $result->timestamp;
				}

		$resource->close();
		}

	return(array($subject, $timestamp));
	}

function _pdf_konto()
	{
	$pdf = pdf_new();

	pdf_begin_document($pdf, "");

		$courier_bold = pdf_load_font($pdf, "Courier-Bold", "winansi");
		$verdana = pdf_load_font($pdf, "Helvetica", "winansi");

		$text = array();
		$top = 758;
		$temp = 0;

		foreach(file("volksbank.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $file)
			{
			list($subject, $timestamp) = _pdf_konto_info($file);

			$subject = date("d.m.Y H.i", strtotime($timestamp)) . " - " . $subject;

			$year = date("Y", strtotime($timestamp));

			if($year != $temp)
				$text[] = "";

			if($year != $temp)
				$top -= 12;

			$temp = $year;

			$text[] = "(" . $subject . ") Tj";

			$top -= 12;

			if($top > 72)
				continue;

			pdf_begin_page($pdf, 595, 842);
				pdf_save($pdf);
					pdf_setfont($pdf, $courier_bold, 12);
					pdf_set_leading($pdf, 12);
					$pdf["stream"][] = "BT";
						pdf_set_text_pos($pdf, 72, 758);
						pdf_show($pdf, "Inhaltsverzeichnis");
						pdf_set_text_pos($pdf, 24, 0 - 12);
						$pdf["stream"][] = implode(" T* ", $text);
					$pdf["stream"][] = "ET";
				pdf_restore($pdf);
			$page = pdf_end_page($pdf);

			if(isset($outline["Inhaltsverzeichnis"]) === false)
				$outline["Inhaltsverzeichnis"] = pdf_add_outline($pdf, "Inhaltsverzeichnis", $pdf["outlines"], $page);

			$text = array();
			$top = 758;
			$temp = 0;
			}

		pdf_begin_page($pdf, 595, 842);
			pdf_save($pdf);
				pdf_setfont($pdf, $courier_bold, 12);
				pdf_set_leading($pdf, 12);
				$pdf["stream"][] = "BT";
					pdf_set_text_pos($pdf, 72, 758);
					pdf_show($pdf, "Inhaltsverzeichnis");
					pdf_set_text_pos($pdf, 24, 0 - 12);
					$pdf["stream"][] = implode(" T* ", $text);
				$pdf["stream"][] = "ET";
			pdf_restore($pdf);
		$page = pdf_end_page($pdf);

		if(isset($outline["Inhaltsverzeichnis"]) === false)
			$outline["Inhaltsverzeichnis"] = pdf_add_outline($pdf, "Inhaltsverzeichnis", $pdf["outlines"], $page);




		foreach(file(__DIR__ . "/volksbank.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $file)
			{
			$filename = "/home/nomatrix/externe_platte/dokumente/" . $file[0] . "/" . $file . ".pnm";

			$temp = file_get_contents($filename);

			$w = 0;
			$h = 0;

			if(preg_match("/(P\d)[\n|\s](\d*)[\n|\s](\d*)[\n|\s](\d*)[\n|\s](.*)/is", $temp, $matches))
				{
				$w = $matches[2] * 0.72;
				$h = $matches[3] * 0.72;
				}

			################################################################################

#			$image = _pdf_load_image($pdf, "pnm", $filename),

			$filename = "/home/nomatrix/externe_platte/dokumente/" . $file[0] . "/" . $file . ".txt";

			$text = (file_exists($filename) ? file($filename, FILE_IGNORE_NEW_LINES) : array());

			array_reverse($text);

			foreach($text as $a => $b)
				$text[$a] = ($b ? "(" . $b . ") Tj " : "");

			pdf_begin_page($pdf, $w, $h);
#				pdf_save($pdf);
#					pdf_fit_image($pdf, "/X1", 0, 0, 1);
#				pdf_restore($pdf);
				pdf_save($pdf);
					pdf_setgray($pdf, 0.5);
					pdf_setfont($pdf, $verdana, 60);
					pdf_rotate($pdf, 45);
					pdf_show_xy($pdf, "KOPIE", 220, -100);
				pdf_restore($pdf);
				pdf_save($pdf);
					pdf_setfont($pdf, $courier_bold, 12);
					pdf_set_leading($pdf, 12);
					pdf_set_horizontal_scaling($pdf, 100);
					$pdf["stream"][] = "BT";
					pdf_set_text_pos($pdf, 48, $h - 24);
					$pdf["stream"][] = implode("T* ", $text);
					$pdf["stream"][] = "ET";
				pdf_restore($pdf);
			$page = pdf_end_page($pdf);

			list($subject, $timestamp) = _pdf_konto_info($file);

			$year = date("Y", strtotime($timestamp));

			if(isset($outline[$year]) === false)
				$outline[$year] = pdf_add_outline($pdf, $year, $pdf["outlines"], $page);
			
			$subject = str_replace("Kontoauszug - ", "", $subject) . " (" . date("d.m.y H.i", strtotime($timestamp)) . ")";

			pdf_add_outline($pdf, $subject, $outline[$year], $page);
			}

		pdf_set_info($pdf, "author", "Volksbank Bielefeld eG");
		pdf_set_info($pdf, "creator", basename(__FILE__));
		pdf_set_info($pdf, "keywords", "48060036, 0515433210");
		pdf_set_info($pdf, "subject", "Kontoauszüge");
		pdf_set_info($pdf, "title", "Kontoauszüge");

	pdf_end_document($pdf);

	$data = pdf_get_buffer($pdf);

	header("Content-Type: application/pdf");
	header("Content-Disposition: inline; filename=parse-test.pdf");
	header("Content-Length: " . strlen($data));

	print($data);
	}
?>
