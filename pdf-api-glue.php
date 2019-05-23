<?
################################################################################
# copyright 2019 by Markus Olderdissen
# free for private use or inspiration.
# public use need written permission.
################################################################################

################################################################################
# _pdf_glue_array ( array $array ) : string
# returns $array as string.
################################################################################

function _pdf_glue_array($array, $optional = true)
	{
	$retval = array();

	foreach($array as $value)
		{
		if($optional)
			if(is_array($value))
				$value = sprintf("[%s]", _pdf_glue_array($value));

		$retval[] = sprintf("%s", $value);
		}

	return(implode(" ", $retval));
	}

################################################################################
# _pdf_glue_dictionary ( array $dictionary ) : string
# returns $dictionary as string.
################################################################################

function _pdf_glue_dictionary($dictionary, $optional = true)
	{
	$retval = array();

	foreach($dictionary as $key => $value)
		{
		if($optional)
			if(is_array($value))
				$value = sprintf("<< %s >>", _pdf_glue_dictionary($value));

		$retval[] = sprintf("%s %s", $key, $value);
		}

	return(implode(" ", $retval));
	}

################################################################################
# _pdf_glue_document ( array $objects ) : string
# returns $objects as string (pdf-format).
################################################################################

function _pdf_glue_document($objects, $optional = true)
	{
	################################################################################
	# fix count
	################################################################################

	$objects[0]["dictionary"]["/Size"] = count($objects); # inclusive null-object

	################################################################################
	# header
	################################################################################

	$retval = array("%PDF-1.5");

	################################################################################
	# body
	################################################################################

	$offsets = array();

	foreach($objects as $index => $object)
		{
		if($index == 0) # trailer
			continue;

		$offsets[$index] = strlen(implode("\n", $retval)) + 1; # +EOL

		$retval[] = _pdf_glue_object($object);
		}

	################################################################################
	# cross-reference table
	################################################################################

	$startxref = strlen(implode("\n", $retval)) + 1; # +EOL
	$trailer = $objects[0]["dictionary"];

	if($optional)
		ksort($objects);

	$count = 0;
	$start = 0;

	$retval[] = sprintf("xref");

	foreach($objects as $index => $object)
		{
		if($count == 0)
			$start = $index;

		$count++;

		if(isset($objects[$index + 1]))
			continue;

		$retval[] = sprintf("%d %d", $start, $count);

		foreach(range($start, $start + $count - 1) as $id)
			if($id == 0)
				$retval[] = sprintf("%010d %05d %s", 0, 65535, "f");
			else
				$retval[] = sprintf("%010d %05d %s", $offsets[$id], $objects[$id]["version"], "n");

		$count = 0;
		}

	################################################################################
	# trailer
	################################################################################

	$retval[] = "trailer";
	$retval[] = sprintf("<< %s >>", _pdf_glue_dictionary($trailer));

	$retval[] = "startxref";
	$retval[] = $startxref;

	$retval[] = "%%EOF";

	################################################################################
	# final pdf file
	################################################################################

	return(implode("\n", $retval));
	}

################################################################################
# _pdf_glue_object ( array $object ) : string
# returns $object as string (obj-format).
################################################################################

function _pdf_glue_object($object)
	{
	$retval = array();

	$retval[] = sprintf("%d %d obj", $object["id"], $object["version"]);

		if(isset($object["dictionary"]))
			$retval[] = sprintf("<< %s >>", _pdf_glue_dictionary($object["dictionary"]));

		if(isset($object["stream"]))
			$retval[] = sprintf("stream\n%s\nendstream", $object["stream"]);

		if(isset($object["value"]))
			$retval[] = $object["value"];

	$retval[] = "endobj";

	return(implode("\n", $retval));
	}

################################################################################
# _pdf_glue_object ( string $value ) : string
# returns $value as string (escaped-format).
################################################################################

function _pdf_glue_string($value)
	{
	$value = utf8_decode($value);

	$value = str_replace(array("\\", "(", ")"), array("\\\\", "\(", "\)"), $value);

	return($value);
	}
?>
