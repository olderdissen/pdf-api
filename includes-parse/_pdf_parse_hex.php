<?
################################################################################
# _pdf_parse_hex ( string $data ) : array
# returns array of found element as string and unparsed data as string.
################################################################################

function _pdf_parse_hex($data)
	{
	$retval = "";

	while(1)
		{
		if(strlen($data) == 0)
			die("_pdf_parse_hex: process runs out of data.");
		elseif($data[0] == ">")
			break;

		$retval .= $data[0];

		$data = substr($data, 1);
		}

	return(array($retval, $data));
	}
?>
