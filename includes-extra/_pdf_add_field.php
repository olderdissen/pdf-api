<?
################################################################################
# _pdf_add_field ( array $pdf , string $parent , string $resource ) : string
################################################################################

function _pdf_add_field(& $pdf, $parent, $resource)
	{
	if(sscanf($parent, "%d %d R", $parent_id, $parent_version) != 2)
		die("_pdf_add_field: invalid parent: " . $parent);

	if(sscanf($resource, "%d %d R", $resource_id, $resource_version) != 2)
		die("_pdf_add_field: invalid field: " . $field);

	# apply resource to fields
	if(isset($pdf["objects"][$parent_id]["dictionary"]["/Fields"]))
		$data = $pdf["objects"][$parent_id]["dictionary"]["/Fields"];
	else
		$data = "[]";

	$data = substr($data, 1);
	list($fields, $data) = _pdf_parse_array($data); # pdf-api-parse.php
	$data = substr($data, 1);
	
	$fields[] = $resource;
	
	$pdf["objects"][$parent_id]["dictionary"]["/Fields"] = sprintf("[%s]", _pdf_glue_array($fields)); # pdf-api-glue.php
	}
?>
