<?
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
?>
