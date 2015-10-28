<?php
if(!defined('__KIMS__')) exit;

if (!$my['uid']) getLink('','','정상적인 접근이 아닙니다.','');
$R = getUidData($table[$m.'comment'],$parent);
if (!$R['uid']) getLink('','','부모댓글이 지정되지 않았습니다.','');
include $g['dir_module'].'var/var.php';

$blog		= $R['blog'];
$parent		= $R['uid'];
$hidden		= $R['hidden'];
$mbruid		= $my['uid'];
$content	= trim($content);
$d_regis	= $date['totime'];
$d_modify	= '';
$ip			= $_SERVER['REMOTE_ADDR'];
$agent		= $_SERVER['HTTP_USER_AGENT'];

if ($d['blog']['badword_action'])
{
	$badwordarr = explode(',' , $d['blog']['badword']);
	$badwordlen = count($badwordarr);
	for($i = 0; $i < $badwordlen; $i++)
	{
		if(!$badwordarr[$i]) continue;

		if(strstr($content,$badwordarr[$i]))
		{
			if ($d['blog']['badword_action'] == 1)
			{
				getLink('','','등록이 제한된 단어를 사용하셨습니다.','');
			}
			else {
				$badescape = strCopy($badwordarr[$i],$d['blog']['badword_escape']);
				$content = str_replace($badwordarr[$i],$badescape,$content);
			}
		}
	}
}

if ($uid)
{
	$O = getUidData($table[$m.'oneline'],$uid);
	if (!$O['uid']) getLink('','','존재하지 않는 한줄의견입니다.','');
	if ($my['uid']!=$O['mbruid']&&!$my['admin']) getLink('','','정상적인 접근이 아닙니다.','');

	$QVAL = "hidden='$hidden',content='$content',d_modify='$d_regis'";
	getDbUpdate($table[$m.'oneline'],$QVAL,'uid='.$O['uid']);

	if ($backurl)
	{
		getLink($backurl.($CMT=='Y'?'&comment='.$parent:''),'parent.','','');
	}
	else {
		getLink('reload','parent.','','');
	}
}
else 
{

	$maxuid = getDbCnt($table[$m.'oneline'],'max(uid)','');
	$uid = $maxuid ? $maxuid+1 : 1;
	
	$QKEY = "uid,blog,parent,hidden,mbruid,content,d_regis,d_modify,ip,agent";
	$QVAL = "'$uid','$blog','$parent','$hidden','$mbruid','$content','$d_regis','$d_modify','$ip','$agent'";
	getDbInsert($table[$m.'oneline'],$QKEY,$QVAL);
	getDbUpdate($table[$m.'comment'],"oneline=oneline+1,d_oneline='".$d_regis."'",'uid='.$parent);
	getDbUpdate($table[$m.'data'],"oneline=oneline+1",'uid='.$R['parent']);
	getDbUpdate($table[$m.'members'],'num_o=num_o+1','blog='.$blog.' and mbruid='.$my['uid']);
	getDbUpdate($table[$m.'list'],"num_o=num_o+1,d_last='".$d_regis."'",'uid='.$blog);

	if ($uid == 1) db_query("OPTIMIZE TABLE ".$table[$m.'oneline'],$DB_CONNECT); 

	if ($d['blog']['c_give_opoint']&&$my['uid'])
	{
		getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$my['uid']."','0','".$d['blog']['c_give_opoint']."','한줄의견(".getStrCut(str_replace('&amp;',' ',strip_tags($content)),15,'').")포인트','".$date['totime']."'");
		getDbUpdate($table['s_mbrdata'],'point=point+'.$d['blog']['c_give_opoint'],'memberuid='.$my['uid']);
	}

	if ($backurl)
	{
		getLink($backurl.($CMT=='Y'?'&comment='.$parent:''),'parent.','','');
	}
	else {
		getLink('reload','parent.','','');
	}
}
?>