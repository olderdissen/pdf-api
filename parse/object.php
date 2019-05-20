################################################################################
# _pdf_parse_object ( string $data ) : array
# returns array of found element as array and unparsed data as string.
################################################################################

function _pdf_parse_object($data)
	{
	$retval = array();

	if(preg_match("/^(\d+)[\s|\n]+(\d+)[\s|\n]+obj[\s|\n]*(.+)[\s|\n]*endobj[\s|\n]*/is", $data, $matches) == 0)
		{
		print($data);

		die("_pdf_parse_object: something is seriously wrong");
		}

	list($null, $id, $version, $temp) = $matches;

	$retval["id"] = $id;
	$retval["version"] = $version;

 	if(substr($temp, 0, 2) == "<<")
		{		
		$temp = substr($temp, 2);

		list($value, $temp) = _pdf_parse_dictionary($temp);

		$temp = substr($temp, 2);

		$retval["dictionary"] = $value;
		}

	$temp = ltrim($temp);

	if(preg_match("/^stream[\s|\n]+(.+)[\s|\n]+endstream(.*)/is", $temp, $matches) == 1)
		{
		list($null, $stream, $temp) = $matches; # data for value

		$retval["stream"] = $stream;
		}

	$temp = ltrim($temp);

	if(strlen($temp) > 0)
		$retval["value"] = $temp;

	return(array($retval, $data));
	}
