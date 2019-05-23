<?
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
?>
