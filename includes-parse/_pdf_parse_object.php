<?
################################################################################
# _pdf_parse_object ( string $data ) : array
# returns array of found element as array and unparsed data as string.
################################################################################

function _pdf_parse_object($data)
	{
	$retval = array();

	if(preg_match("/^(\d+)[\s|\n]+(\d+)[\s|\n]+obj[\s|\n]*(.+)[\s|\n]*endobj.*/is", $data, $matches) == 0)
		die("_pdf_parse_object: something is seriously wrong");

	list($null, $id, $version, $data) = $matches;

	$retval["id"] = $id;
	$retval["version"] = $version;

	$data = ltrim($data); # try to overcome this

 	if(substr($data, 0, 2) == "<<")
		{		
		$data = substr($data, 2);

		list($dictionary, $data) = _pdf_parse_dictionary($data);

		$data = substr($data, 2);

		$retval["dictionary"] = $dictionary;

		$data = ltrim($data); # try to overcome this

		if(preg_match("/^stream[\s|\n]+(.+)[\s|\n]+endstream.*/is", $data, $matches) == 1) # !!! fails on hex streams sometimes
			{
			list($null, $stream) = $matches; # data for value

			$retval["stream"] = $stream;
			}
		}
	elseif(preg_match("/^stream[\s|\n]+(.+)[\s|\n]+endstream.*/is", $data, $matches) == 1) # !!! fails on hex streams sometimes
		{
		list($null, $stream) = $matches; # data for value

		$retval["stream"] = $stream;
		}
	else
		$retval["value"] = $data;

	return(array($retval, ""));
	}
?>
