<?php
if(!defined('__KIMS__')) exit;
if(!$uid) exit;

$R = getUidData($table[$m.'comment'],$uid);
if (!$R['uid']) getLink('','','존재하지 않는 댓글입니다.','');


if (!$pw)
{
	getLink('','','정상적인 접근이 아닙니다.','');
}
else {
	if(md5($pw) != $R['pw'])
	{
		getLink('','','비밀번호가 일치하지 않습니다.','');
	}
}

$_SESSION['module_'.$m.'_comment'] = str_replace('['.$R['uid'].']','',$_SESSION['module_'.$m.'_comment']).'['.$R['uid'].']';

if ($backurl)
{
	getLink($backurl.'&type=modify&comment='.$uid.($CMT=='Y'?'&WRITE=Y':''),'parent.','','');
}
else {
	getLink('reload','parent.','','');
}
?>