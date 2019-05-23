<?
################################################################################
# _pdf_glue_string ( string $value ) : string
# returns $value as string (escaped-format).
################################################################################

function _pdf_glue_string($value)
	{
	$value = utf8_decode($value);

	$value = str_replace(array("\\", "(", ")"), array("\\\\", "\(", "\)"), $value);

	return($value);
	}
?>
