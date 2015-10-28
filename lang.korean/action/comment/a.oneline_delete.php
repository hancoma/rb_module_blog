<?php
if(!defined('__KIMS__')) exit;

if (!$my['uid']) getLink('','','정상적인 접근이 아닙니다.','');
$R = getUidData($table[$m.'oneline'],$uid);
if (!$R['uid']) getLink('','','존재하지 않는 한줄의견입니다.','');
$C = getUidData($table[$m.'comment'],$R['parent']);
$B = getUidData($table[$m.'list'],$R['blog']);
if ($my['uid']!=$R['mbruid'] && $my['uid']!=$B['mbruid'] && !strpos('_,'.$B['members'].',',','.$my['id'].',')) getLink('','','삭제권한이 없습니다.','');

include $g['dir_module'].'var/var.php';

getDbDelete($table[$m.'oneline'],'uid='.$R['uid']);
getDbUpdate($table[$m.'comment'],'oneline=oneline-1','uid='.$C['uid']);
getDbUpdate($table[$m.'data'],'oneline=oneline-1','uid='.$C['parent']);
getDbUpdate($table[$m.'members'],'num_o=num_o-1','blog='.$C['blog'].' and mbruid='.$R['mbruid']);
getDbUpdate($table[$m.'list'],'num_o=num_o-1','uid='.$C['blog']);

if ($d['blog']['c_give_opoint']&&$R['mbruid'])
{
	getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$R['mbruid']."','0','-".$d['blog']['c_give_opoint']."','한줄의견삭제(".getStrCut(str_replace('&amp;',' ',strip_tags($R['content'])),15,'').")환원','".$date['totime']."'");
	getDbUpdate($table['s_mbrdata'],'point=point-'.$d['blog']['c_give_opoint'],'memberuid='.$R['mbruid']);
}


if ($stop)
{
	exit;
}
else {
	if ($backurl)
	{
		getLink($backurl.($CMT=='Y'?'&comment='.$parent:''),'parent.','','');
	}
	else {
		getLink('reload','parent.','','');
	}
}
?>