<?
################################################################################
# _pdf_filter_asciihex_decode ( string $value ) : string
################################################################################

function _pdf_filter_asciihex_decode($data)
	{
	return(hex2bin($data));
	}
?>
