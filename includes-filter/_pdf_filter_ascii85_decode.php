<?
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
?>
