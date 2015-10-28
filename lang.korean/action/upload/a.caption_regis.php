<?php
if(!defined('__KIMS__')) exit;

if (!$uid || !$tmpcode) getLink('','','잘못된 접근입니다','');
$R = getUidData($table[$m.'upload'],$uid);
if (!$R['uid']) getLink('','','없는 파일입니다','');
if (!$my['admin'] && $R['tmpcode'] != $tmpcode && $my['uid'] != $R['mbruid']) getLink('','','권한이 없습니다.','');

$name = trim($name);
$name = $name ? str_replace('.'.$R['ext'],'',$name).'.'.$R['ext'] : $R['name'];
$name = strip_tags($name);
$caption = $my['admin'] ? trim($caption) : strip_tags(trim($caption));

getDbUpdate($table[$m.'upload'],"name='".$name."',caption='".$caption."',d_update='".$date['totime']."'",'uid='.$R['uid']);

if($stop=='Y') exit;
getLink('reload','parent.','반영되었습니다.','');
?>