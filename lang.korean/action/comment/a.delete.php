<?php
if(!defined('__KIMS__')) exit;

$R = getUidData($table[$m.'comment'],$uid);
if (!$R['uid']) getLink('','','존재하지 않는 댓글입니다.','');
$B = getUidData($table[$m.'list'],$R['blog']);



if (!$my['uid'] || ($my['uid']!=$R['mbruid'] && $my['uid']!=$B['mbruid'] && !strpos('_,'.$B['members'].',',','.$my['id'].',')))
{
	if ($pw)
	{
		if (md5($pw) != $R['pw']) getLink('','','비밀번호가 일치하지 않습니다.','');
	}
	else {
		 getLink('','','비밀번호를 입력해 주세요.','');
	}
}
include $g['dir_module'].'var/var.php';

if ($d['blog']['c_onelinedel'])
{
	if($R['oneline'])
	{
		getLink('','','한줄의견이 있는 댓글은 삭제할 수 없습니다.','');
	}
}

//한줄의견삭제
if ($R['oneline'])
{
	$_ONELINE = getDbSelect($table[$m.'oneline'],'parent='.$R['uid'],'*');
	while($_O=db_fetch_array($_ONELINE))
	{
		if ($d['blog']['c_give_opoint']&&$_O['mbruid'])
		{
			getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$_O['mbruid']."','0','-".$d['blog']['c_give_opoint']."','한줄의견삭제(".getStrCut(str_replace('&amp;',' ',strip_tags($_O['content'])),15,'').")환원','".$date['totime']."'");
			getDbUpdate($table['s_mbrdata'],'point=point-'.$d['blog']['c_give_opoint'],'memberuid='.$_O['mbruid']);
		}
	}
	getDbDelete($table[$m.'oneline'],'parent='.$R['uid']);
}

getDbDelete($table[$m.'comment'],'uid='.$R['uid']);
getDbUpdate($table[$m.'data'],'comment=comment-1,oneline=oneline-'.$R['oneline'],'uid='.$R['parent']);
getDbUpdate($table[$m.'list'],'num_c=num_c-1,num_o=num_o-'.$R['oneline'],'uid='.$R['blog']);
getDbUpdate($table[$m.'members'],'num_c=num_c-1,num_o=num_o-'.$R['oneline'],'blog='.$R['blog'].' and mbruid='.$R['mbruid']);


if ($d['blog']['c_give_opoint']&&$R['mbruid'])
{
	getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$R['mbruid']."','0','-".$d['blog']['c_give_opoint']."','블로그 댓글삭제(".getStrCut($R['subject'],15,'').")환원','".$date['totime']."'");
	getDbUpdate($table['s_mbrdata'],'point=point-'.$d['blog']['c_give_opoint'],'memberuid='.$R['mbruid']);
}


if ($stop)
{
	exit;
}
else {
	if ($backurl)
	{
		getLink($backurl.($CMT=='Y'?'&CMT=Y':''),'parent.','','');
	}
	else {
		getLink('reload','parent.','','');
	}
}
?>