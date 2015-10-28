<?php
if(!defined('__KIMS__')) exit;

if (!$file_uid) exit;

$R = getUidData($table[$m.'upload'],$file_uid);
if (!$R['uid']) exit;
if (!$my['admin'] && (!$R['mbruid'] || $my['uid'] != $R['mbruid'])) exit;

getDbUpdate($table[$m.'upload'],'hidden='.($R['hidden']?0:1),'uid='.$R['uid']);
getLink($g['s'].'/?r='.$r.'&m='.$m.'&upload=Y&mod=list&blog='.$blog.'&gparam='.$gparam.'&code='.$code,'parent.','','');
?>