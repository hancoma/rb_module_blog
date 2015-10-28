<?php
if(!defined('__KIMS__')) exit;

$_SESSION['upsescode'] = '';
if ($iload == 'Y')
{
	getLink('','','','close');
}
else {
	getLink('','parent.parent.getLayerBoxHide();','','');
}
?>