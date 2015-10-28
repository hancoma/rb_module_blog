<?php
if(!defined('__KIMS__')) exit;

checkAdmin(0);

$i = 0;
foreach($menumembers as $val) getDbUpdate($table[$m.'category'],'gid='.($i++),'uid='.$val);

getLink('reload','parent.','','');
?>