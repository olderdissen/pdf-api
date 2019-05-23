<?
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
?>
