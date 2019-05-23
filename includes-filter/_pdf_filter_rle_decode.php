<?
################################################################################
# _pdf_filter_rle_decode ( string $value ) : string
################################################################################

function _pdf_filter_rle_decode($data)
	{
	return(preg_replace_callback('/(\d+)(\D)/', function($match) { return(str_repeat($match[2], $match[1])); }, $data));
	}
?>
