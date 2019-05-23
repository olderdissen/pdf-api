<?
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
?>
