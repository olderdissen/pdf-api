<?
################################################################################
# _pdf_parse_array ( string $data ) : array
# returns array of found element as array and unparsed data as string.
################################################################################

function _pdf_parse_array($data)
	{
	$retval = array();

	while(1)
		{
		if(strlen($data) == 0)
			die("_pdf_parse_array: process runs out of data.");
		elseif(in_array($data[0], array("\t", "\n", "\r", " ")))
			$data = substr($data, 1);
		elseif($data[0] == "(")
			{
			$data = substr($data, 1);

			list($value, $data) = _pdf_parse_string($data);

			$data = substr($data, 1);

			$retval[] = sprintf("(%s)", $value);
			}
		elseif($data[0] == "/")
			{
			$data = substr($data, 1);

			list($value, $data) = _pdf_parse_name($data);

			$retval[] = sprintf("/%s", $value);
			}
		elseif(substr($data, 0, 2) == "<<")
			{
			$data = substr($data, 2);

			list($value, $data) = _pdf_parse_dictionary($data);

			$data = substr($data, 2);

			$retval[] = sprintf("<< %s >>", _pdf_glue_dictionary($value)); # pdf-api-glue.php
			}
		elseif($data[0] == "<")
			{
			$data = substr($data, 1);

			list($value, $data) = _pdf_parse_hex($data);

			$data = substr($data, 1);

			$retval[] = sprintf("<%s>", $value);
			}
		elseif($data[0] == "[")
			{
			$data = substr($data, 1);

			list($value, $data) = _pdf_parse_array($data);

			$data = substr($data, 1);

			$retval[] = sprintf("[%s]", _pdf_glue_array($value)); # pdf-api-glue.php
			}
		elseif($data[0] == "]")
			break;
		elseif(substr($data, 0, 5) == "false")
			{
			$data = substr($data, 5);

			list($value, $data) = array("false", $data);

			$retval[] = $value;
			}
		elseif(substr($data, 0, 4) == "true")
			{
			$data = substr($data, 4);

			list($value, $data) = array("true", $data);

			$retval[] = $value;
			}
		elseif(preg_match("/^(\d+ \d+ R)(.*)/is", $data, $matches) == 1)
			{
			list($null, $value, $data) = $matches;

			$retval[] = $value;
			}
		else
			{
			list($value, $data) = _pdf_parse_numeric($data);

			$retval[] = $value;
			}
		}

	return(array($retval, $data));
	}
?>
