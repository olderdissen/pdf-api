<?
################################################################################
# _pdf_new ( void ) : array
################################################################################

function _pdf_new()
	{
	return
		(
		array
			(
			"objects" => array
				(
				0 => array
					(
					"dictionary" => array
						(
						"/Size" => 0
						)
					)
				),

			"apiname" => sprintf("PDFlib Lite Clone %d.%d.%d (PHP/%s)", 1, 0, 0, PHP_OS),
			"major" => 1,
			"minor" => 4
			)
		);
	}
?>
