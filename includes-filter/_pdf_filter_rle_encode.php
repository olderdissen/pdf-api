<?
################################################################################
# _pdf_filter_rle_encode ( string $value ) : string
################################################################################

function _pdf_filter_rle_encode($data)
	{
	return(preg_replace_callback('/(.)\1*/', function($match) { return (strlen($match[0]) . $match[1]); }, $data));
	}
?>
