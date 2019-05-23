<?
################################################################################
# _pdf_parse_numeric ( string $data ) : array
# returns array of found element as string and unparsed data as string.
################################################################################

function _pdf_parse_numeric($data)
	{
	$retval = "";

	while(1)
		{
		if(strlen($data) == 0)
			die("_pdf_parse_numeric: process runs out of data.");
		elseif(in_array($data[0], array("\t", "\n", "\r", " ", "(", "/", "<", ">", "[", "]", "f", "t")))
			break;

		$retval .= $data[0];

		$data = substr($data, 1);
		}

	return(array($retval, $data));
	}
?>
