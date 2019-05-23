<?
################################################################################
# _pdf_parse_string ( string $data ) : array
# returns array of found element as string and unparsed data as string.
################################################################################

function _pdf_parse_string($data)
	{
	$retval = "";

	while(1)
		{
		if(strlen($data) == 0)
			die("_pdf_parse_string: process runs out of data.");
		elseif($data[0] == ")")
			break;
		elseif($data[0] == "\\")
			{
			$retval .= $data[0];

			$data = substr($data, 1);
			}

		$retval .= $data[0];

		$data = substr($data, 1);
		}

	return(array($retval, $data));
	}
?>
