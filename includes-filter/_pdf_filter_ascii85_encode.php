<?
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
?>
