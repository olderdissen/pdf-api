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

			$retval[] = sprintf("<< %s >>", _pdf_glue_dictionary($value));
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

			$retval[] = sprintf("[%s]", _pdf_glue_array($value));
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

################################################################################
# _pdf_parse_comment ( string $data ) : array
# returns array of found element as string and unparsed data as string.
# PDF 1.3 - 3.1.2 - Comments
################################################################################

function _pdf_parse_comment($data)
	{
	$retval = "";

	while(1)
		{
		if(strlen($data) == 0)
			break;
		elseif(in_array($data[0], array("\n", "\r")))
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

################################################################################
# _pdf_parse_dictionary ( string $data ) : array
# returns array of found element as array and unparsed data as string.
################################################################################

function _pdf_parse_dictionary($data)
	{
	$retval = array();

	$loop = 0;

	while(1)
		{
		if(strlen($data) == 0)
			break;
		elseif(in_array($data[0], array("\t", "\n", "\r", " ")))
			$data = substr($data, 1);
		elseif(substr($data, 0, 2) == ">>")
			break;
		else
			{
			$key = "";

			while(1)
				{
				if(strlen($data) == 0)
					die("_pdf_parse_dictionary: process runs out of data (key).");
				elseif(in_array($data[0], array("\t", "\n", "\r", " ")))
					$data = substr($data, 1);
				elseif(in_array($data[0], array("(", "<", "[", "f", "t")))
					die("_pdf_parse_dictionary: no other char than / allowed for key. data follows: " . $data);
				elseif($data[0] == "/")
					{
					$data = substr($data, 1);

					list($value, $data) = _pdf_parse_name($data);

					$key = sprintf("/%s", $value);

					break;
					}
				else
					die("_pdf_parse_dictionary: no other char than / allowed for key. data follows: " . $data);
				}

			$value = "";

			while(1)
				{
				if(strlen($data) == 0)
					die("_pdf_parse_dictionary: process runs out of data (value).");
				elseif(in_array($data[0], array("\t", "\n", "\r", " ")))
					$data = substr($data, 1);
				elseif($data[0] == "(")
					{
					$data = substr($data, 1);

					list($value, $data) = _pdf_parse_string($data);

					$data = substr($data, 1);

					$value = sprintf("(%s)", $value);

					break;
					}
				elseif($data[0] == "/")
					{
					$data = substr($data, 1);

					list($value, $data) = _pdf_parse_name($data);

					$value = sprintf("/%s", $value);

					break;
					}
				elseif(substr($data, 0, 2) == "<<")
					{
					$data = substr($data, 2);

					list($value, $data) = _pdf_parse_dictionary($data);

					$data = substr($data, 2);

					$value = sprintf("<< %s >>", _pdf_glue_dictionary($value));

					break;
					}
				elseif($data[0] == "<")
					{
					$data = substr($data, 1);

					list($value, $data) = _pdf_parse_hex($data);

					$data = substr($data, 1);

					$value = sprintf("<%s>", $value);

					break;
					}
				elseif($data[0] == "[")
					{
					$data = substr($data, 1);

					list($value, $data) = _pdf_parse_array($data);

					$data = substr($data, 1);

					$value = sprintf("[%s]", _pdf_glue_array($value));

					break;
					}
				elseif(substr($data, 0, 5) == "false")
					{
					$data = substr($data, 5);

					list($value, $data) = array("false", $data);

					break;
					}
				elseif(substr($data, 0, 4) == "true")
					{
					$data = substr($data, 4);

					list($value, $data) = array("true", $data);

					break;
					}
				elseif(preg_match("/^(\d+ \d+ R)(.*)/is", $data, $matches) == 1)
					{
					list($null, $value, $data) = $matches;

					break;
					}
				else
					{
					list($value, $data) = _pdf_parse_numeric($data);

					break;
					}
				}

			$retval[$key] = $value;
			}

		$loop ++;

		if($loop > 1024)
			die("_pdf_parse_dictionary: process stuck on data " . $data);
		}

	return(array($retval, $data));
	}

################################################################################
# _pdf_parse_document ( string $data ) : array
# returns array of found element as array and unparsed data as warning.
################################################################################

function _pdf_parse_document($data)
	{
	$retval = array();

	if(preg_match("/^%PDF-(\d+)\.(\d+)[\s|\n]+(.*)[\s|\n]+startxref[\s|\n]+(\d+)[\s|\n]+%%EOF[\s|\n]+(.*)/is", $data, $matches) == 0)
		die("_pdf_parse_document: something is seriously wrong (invalid structure).");

	list($null, $major, $minor, $body, $startxref, $null) = $matches;

	################################################################################
	# pdf_parse_xref ( string $data ) : array
	# returns offsets from $data as string.
	################################################################################

	$offsets = array();

	$table = substr($data, $startxref);

	while(1)
		{
		if(strlen($table) == 0)
			break;
		elseif(in_array($table[0], array("\t", "\n", "\r", " ")))
			$table = substr($table, 1);
		elseif(substr($table, 0, 4) == "xref")
			{
			$table = substr($table, 4);

			while(1)
				{
				if(strlen($table) == 0)
					break;
				elseif(in_array($table[0], array("\t", "\n", "\r", " ")))
					$table = substr($table, 1);
				else
					{
					list($first, $table) = _pdf_parse_numeric($table);

					break;
					}
				}

			while(1)
				{
				if(strlen($table) == 0)
					break;
				elseif(in_array($table[0], array("\t", "\n", "\r", " ")))
					$table = substr($table, 1);
				else
					{
					list($count, $table) = _pdf_parse_numeric($table);

					break;
					}
				}

			foreach(range($first, $first + $count - 1) as $index)
				{
				while(1)
					{
					if(strlen($table) == 0)
						break;
					elseif(in_array($table[0], array("\t", "\n", "\r", " ")))
						$table = substr($table, 1);
					else
						{
						list($offset, $table) = _pdf_parse_numeric($table);

						break;
						}
					}

				while(1)
					{
					if(strlen($table) == 0)
						break;
					elseif(in_array($table[0], array("\t", "\n", "\r", " ")))
						$table = substr($table, 1);
					else
						{
						list($generation, $table) = _pdf_parse_numeric($table);

						break;
						}
					}

				while(1)
					{
					if(strlen($table) == 0)
						break;
					elseif(in_array($table[0], array("\t", "\n", "\r", " ")))
						$table = substr($table, 1);
					else
						{
						list($used, $table) = _pdf_parse_name($table);

						break;
						}
					}

				if($used == "n")
					$offsets[$index] = $offset; # _pdf_parse_object($data)
				}
			}
		elseif(substr($table, 0, 7) == "trailer")
			{			
			$table = substr($table, 7);

			while(1)
				{
				if(strlen($table) == 0)
					break;
				elseif(in_array($table[0], array("\t", "\n", "\r", " ")))
					$table = substr($table, 1);
				elseif(substr($table, 0, 2) == "<<")
					{
					$table = substr($table, 2);

					list($trailer, $table) = _pdf_parse_dictionary($table);

					$table = substr($table, 2);

					break;
					}
				}

			if(isset($retval[0]["dictionary"]) === false)
				$retval[0]["dictionary"] = $trailer;
			}
		elseif(substr($table, 0, 9) == "startxref")
			{
			if(isset($trailer["/Prev"]))
				{
				$startxref = $trailer["/Prev"];

				unset($retval[0]["dictionary"]["/Prev"]);

				$table = substr($data, $startxref);
				}
			else
				{
				break;
				}
			}
		else
			{
			if(preg_match_all("/(\d+[\s|\n]+\d+[\s|\n]+obj[\s|\n]+.*?[\s|\n]+endobj|xref[\s|\n]+.*|trailer[\s|\n]+.*|startxref[\s|\n]+\d+[\s|\n]+.*)/is", $data, $matches) == 0)
				die("111");

			foreach($matches[0] as $object)
				{
				if(substr($object, 0, 9) == "startxref")
					continue;

				if(substr($object, 0, 7) == "trailer")
					{
					$object = substr($object, 7);

					$object = ltrim($object);

					$object = substr($object, 2);
					list($retval[0]["dictionary"], $null) = _pdf_parse_dictionary($object);
					$object = substr($object, 2);

					continue;
					}

				list($k, $null) = _pdf_parse_object($object);

				$id = $k["id"];

				$retval[$id] = $k;
				}

			ksort($retval);

			return($retval);
			}
		}

#	print_r($retval);exit;

	################################################################################
	# get objects by offset
	################################################################################

	foreach($offsets as $index => $offset_start)
		{
		$offset_stop = $startxref;

		foreach($offsets as $offset_test)
			{
			if($offset_test < $offset_stop)
				if($offset_test > $offset_start)
					$offset_stop = $offset_test;
			}

		$help = substr($data, $offset_start, $offset_stop - $offset_start - 1);

		list($value, $null) = _pdf_parse_object($help);

		if($value["id"] != $index)
			die("_pdf_parse_document: something is seriously wrong (invalid id).");

		$retval[$index]= $value;
		}

	################################################################################

	return($retval);
	}

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
