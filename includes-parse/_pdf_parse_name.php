<?
################################################################################
# _pdf_parse_name ( string $data ) : array
# returns array of found element as string and unparsed data as string.
################################################################################

function _pdf_parse_name($data)
	{
	$retval = "";

	while(1)
		{
		if(strlen($data) == 0)
			break;
		elseif(in_array($data[0], array("\t", "\n", "\r", " ", "(", "/", "<", ">", "[", "]")))
			break;

		$retval .= $data[0];

		$data = substr($data, 1);
		}

	return(array($retval, $data));
	}
?>
