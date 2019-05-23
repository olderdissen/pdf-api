<?
################################################################################
# copyright 2019 by Markus Olderdissen
# free for private use or inspiration. 
# public use need written permission.
################################################################################

define("FONTDESCRIPTOR_FLAG_FIXEDPITCH", 1 << 1);
define("FONTDESCRIPTOR_FLAG_SERIF", 1 << 2);
define("FONTDESCRIPTOR_FLAG_SYMBOLIC", 1 << 3);
define("FONTDESCRIPTOR_FLAG_SCRIPT", 1 << 4);
define("FONTDESCRIPTOR_FLAG_NONSYMBOLIC", 1 << 6);
define("FONTDESCRIPTOR_FLAG_ITALIC", 1 << 7);
define("FONTDESCRIPTOR_FLAG_ALLCAP", 1 << 17);
define("FONTDESCRIPTOR_FLAG_SMALLCAP", 1 << 18);
define("FONTDESCRIPTOR_FLAG_FORCEBOLD", 1 << 19);

_pdf_load_includes("includes-core");
_pdf_load_includes("includes-extra");
_pdf_load_includes("includes-filter");
_pdf_load_includes("includes-glue");
_pdf_load_includes("includes-parse");

################################################################################
# _pdf_load_includes ( string $path ) : bool
################################################################################

function _pdf_load_includes($path)
	{
	foreach(glob($path . "/*.php") as $file)
		include_once($file);

	return(true);
	}
?>
