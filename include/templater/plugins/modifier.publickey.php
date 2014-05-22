<?php
if (!defined('XCART_START')) { header("Location: ../../../"); die("Access denied"); }

function smarty_modifier_publickey($string)
{
	return func_query_first_cell("SELECT param01 FROM xcart_ccprocessors WHERE processor='cc_securesubmit.php'");
}

?>
