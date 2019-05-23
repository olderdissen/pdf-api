<?
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
?>
