<?
################################################################################
# copyright 2019 by Markus Olderdissen
# free for private use or inspiration.
# public use need written permission.
################################################################################

################################################################################
# _pdf_filter_change ( array $pdf , string $filter ) : array
################################################################################

function _pdf_filter_change(& $pdf, $filter = "")
	{
	foreach($pdf["objects"] as $index => $object)
		{
		if($index == 0) # trailer
			continue;

		if(isset($object["stream"]) === false)
			continue;

		if(isset($object["dictionary"]["/Filter"]))
			list($filter_old, $null) = _pdf_filter_parse($object["dictionary"]["/Filter"]);
		else
			$filter_old = array();

		$data = $object["stream"];

		while(1)
			{
			if(count($filter_old) == 0)
				break;

			if($filter_old[0] == "/ASCII85Decode")
				$data = _pdf_filter_ascii85_decode($data);

			if($filter_old[0] == "/ASCIIHexDecode")
				$data = _pdf_filter_asciihex_decode($data);

			if($filter_old[0] == "/DCTDecode")
				break; # image

			if($filter_old[0] == "/FlateDecode")
				$data = _pdf_filter_flate_decode($data);

			if($filter_old[0] == "/LZWDecode")
				$data = _pdf_filter_lzw_decode($data);

			$filter_old = array_slice($filter_old, 1);
			}

		$pdf["objects"][$index]["stream"] = $data;
		$pdf["objects"][$index]["dictionary"]["/Length"] = strlen($data);

		if(count($filter_old) == 0)
			unset($pdf["objects"][$index]["dictionary"]["/Filter"]);
		elseif(count($filter_old) == 1)
			$pdf["objects"][$index]["dictionary"]["/Filter"] = sprintf("%s", _pdf_glue_array($filter_old));
		else
			$pdf["objects"][$index]["dictionary"]["/Filter"] = sprintf("[%s]", _pdf_glue_array($filter_old));
		}
	
	################################################################################

	foreach($pdf["objects"] as $index => $object)
		{
		if($index == 0) # trailer
			continue;

		if(isset($object["stream"]) === false)
			continue;

		if(isset($object["dictionary"]["/Filter"]))
			list($filter_old, $null) = _pdf_filter_parse($object["dictionary"]["/Filter"]);
		else
			$filter_old = array();

		list($filter_new, $null) = _pdf_filter_parse($filter);

		$filter_new = array_reverse($filter_new);

		$data = $object["stream"];

		while(1)
			{
			if(count($filter_new) == 0)
				break;

			if($filter_new[0] == "/ASCII85Decode")
				$data = _pdf_filter_ascii85_encode($pdf["objects"][$index]["stream"]);

			if($filter_new[0] == "/ASCIIHexDecode")
				$data = _pdf_filter_asciihex_encode($data);

			if($filter_new[0] == "/FlateDecode")
				$data = _pdf_filter_flate_encode($data);

			if($filter_new[0] == "/LZWDecode")
				$data = _pdf_filter_lzw_encode($data);

			$filter_old = array_merge(array($filter_new[0]), $filter_old);

			$filter_new = array_slice($filter_new, 1);
			}

		$pdf["objects"][$index]["stream"] = $data;
		$pdf["objects"][$index]["dictionary"]["/Length"] = strlen($data);

		if(count($filter_old) == 0)
			unset($pdf["objects"][$index]["dictionary"]["/Filter"]);
		elseif(count($filter_old) == 1)
			$pdf["objects"][$index]["dictionary"]["/Filter"] = sprintf("%s", _pdf_glue_array($filter_old));
		else
			$pdf["objects"][$index]["dictionary"]["/Filter"] = sprintf("[%s]", _pdf_glue_array($filter_old));
		}

	return(true);
	}

################################################################################
# _pdf_filter_parse ( string $data ) : array
# this function is needed because user can setup /Filter for final writing
################################################################################

function _pdf_filter_parse($data = "")
	{
	$retval = array();

	while(1)
		{
		if(strlen($data) == 0)
			break;
		elseif($data[0] == " ")
			$data = substr($data, 1);
		elseif($data[0] == "[")
			{
			$data = substr($data, 1);

			list($retval, $data) = _pdf_parse_array($data);

			$data = substr($data, 1);
			}
		elseif($data[0] == "]")
			break;
		elseif($data[0] == "/")
			{
			$data = substr($data, 1);

			list($name, $data) = _pdf_parse_name($data);

			$retval[] = sprintf("/%s", $name);
			}
		else
			die("_pdf_filter_parse: you should never be here: data follows: " . $data);
		}

	return(array($retval, $data));
	}

################################################################################
# _pdf_filter_ascii85_decode ( string $value ) : string
################################################################################

function _pdf_filter_ascii85_decode($value)
	{
	$return = "";

	$base = array();

	foreach(range(0, 4) as $i)
		$base[$i] = pow(85, $i);

	foreach(str_split($value, 5) as $tuple)
		{
		if($tuple === "zzzzz")
			{
			$return .= str_repeat(chr(0), 4);

			continue;
			}

		$bin_tuple = "0";

		$len = strlen($tuple);

		$tuple .= str_repeat("u", 5 - $len);

		foreach(range(0, 4) as $i)
			$bin_tuple += ((ord($tuple[$i]) - 33) * $base[4 - $i]);

		$i = 4;

		$tuple = "";

		$len -= 1;

		while($len --)
			$tuple .= chr((bindec(sprintf("%032b", $bin_tuple)) >> (-- $i * 8)) & 0xFF);

		$return .= $tuple;
		}

	return($return);
	}

################################################################################
# _pdf_filter_ascii85_encode ( string $value ) : string
################################################################################

function _pdf_filter_ascii85_encode($string)
	{
	$return = "";

	foreach(str_split($string, 4) as $tuple)
		{
		$binary = 0;

		for($i = 0; $i < strlen($tuple); $i ++)
			$binary |= (ord($tuple[$i]) << ((3 - $i) * 8));

		$tuple = "";

		foreach(range(0, 4) as $i)
			{
			$tuple = chr($binary % 85 + 33) . $tuple;

			$binary /= 85;
			}

		$return .= substr($tuple, 0, strlen($tuple) + 1);;
		}

	return($return);
	}

################################################################################
# _pdf_filter_asciihex_decode ( string $value ) : string
################################################################################

function _pdf_filter_asciihex_decode($data)
	{
	return(hex2bin($data));
	}

################################################################################
# _pdf_filter_asciihex_encode ( string $value ) : string
################################################################################

function _pdf_filter_asciihex_encode($data)
	{
	return(bin2hex($data));
	}

################################################################################
# _pdf_filter_flate_decode ( string $value ) : string
################################################################################

function _pdf_filter_flate_decode($data)
	{
	return(gzuncompress($data));
	}

################################################################################
# _pdf_filter_flate_encode ( string $value ) : string
################################################################################

function _pdf_filter_flate_encode($data)
	{
	return(gzcompress($data, 9));
	}

################################################################################
# _pdf_filter_lzw_decode ( string $value ) : string
################################################################################

function _pdf_filter_lzw_decode($binary)
	{
	$dictionary_count = 256;
	$bits = 8;
	$codes = array();
	$rest = 0;
	$rest_length = 0;

	for($i = 0; $i < strlen($binary); $i ++)
		{
		$rest = ($rest << 8) + ord($binary[$i]);
		$rest_length += 8;

		if($rest_length >= $bits)
			{
			$rest_length -= $bits;
			$codes[] = $rest >> $rest_length;
			$rest = $rest & ((1 << $rest_length) - 1);
			$dictionary_count += 1;

			if($dictionary_count >> $bits)
				$bits += 1;
			}
		}

	$dictionary = range("\x00", "\xFF");
	$return = "";

	foreach($codes as $i => $code)
		{
		if(isset($dictionary[$code]) === false)
			$element = $word . $word[0];
		else
			$element = $dictionary[$code];

		$return = $return . $element;

		if($i > 0)
			$dictionary[] = $word . $element[0];

		$word = $element;
		}

	return($return);
	}

################################################################################
# _pdf_filter_lzw_encode ( string $value ) : string
################################################################################

function _pdf_filter_lzw_encode($string)
	{
	$dictionary = array_flip(range("\x00", "\xFF"));
	$word = "";
	$codes = array();

	for($i = 0; $i <= strlen($string); $i = $i +1)
		{
		$x = substr($string, $i, 1);

		if(strlen($x) > 0 && isset($dictionary[$word . $x]) === true)
			$word = $word . $x;
		elseif($i > 0)
			{
			$codes[] = $dictionary[$word];
			$dictionary[$word . $x] = count($dictionary);
			$word = $x;
			}
		}

	$dictionary_count = 256;
	$bits = 8;
	$return = "";
	$rest = 0;
	$rest_length = 0;

	foreach($codes as $code)
		{
		$rest = ($rest << $bits) + $code;
		$rest_length += $bits;
		$dictionary_count += 1;

		if($dictionary_count >> $bits)
			$bits += 1;

		while($rest_length > 7)
			{
			$rest_length -= 8;
			$return .= chr($rest >> $rest_length);
			$rest &= ((1 << $rest_length) - 1);
			}
		}

	return($return . ($rest_length > 0 ? chr($rest << (8 - $rest_length)) : ""));
	}

################################################################################
# _pdf_filter_rle_decode ( string $value ) : string
################################################################################

function _pdf_filter_rle_decode($data)
	{
	return(preg_replace_callback('/(\d+)(\D)/', function($match) { return(str_repeat($match[2], $match[1])); }, $data));
	}

################################################################################
# _pdf_filter_rle_encode ( string $value ) : string
################################################################################

function _pdf_filter_rle_encode($data)
	{
	return(preg_replace_callback('/(.)\1*/', function($match) { return (strlen($match[0]) . $match[1]); }, $data));
	}
?>
