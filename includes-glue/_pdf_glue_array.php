<?
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
?>
