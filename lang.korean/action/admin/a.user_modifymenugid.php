<?php
if(!defined('__KIMS__')) exit;

if (!$uid) getLink('','','정상적인 접근이 아닙니다.','');
$_BX = getUidData($table[$m.'list'],$uid);
if (!$_BX['uid']) getLink('','','정상적인 접근이 아닙니다.','');
if ($my['uid']!=$_BX['mbruid']) getLink('','','관리권한이 없습니다.','');

$i = 0;
foreach($menumembers as $val) getDbUpdate($table[$m.'category'],'gid='.($i++),'uid='.$val);

getLink('reload','parent.','','');
?>