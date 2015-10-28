<?php
if(!defined('__KIMS__')) exit;

if ($d['blog']['c_perm_write'] > $my['level'] && !$my['admin'])
{
	getLink('','','댓글등록 권한이 없습니다.','');
}

include $g['dir_module'].'var/var.php';

//$blog		= 
//$parent	= 
//$notice	= 
//$hidden	= 
$mbruid		= $my['uid'];
$name		= $my['uid'] ? $my[$_HS['nametype']] : trim($name);
$url		= $url ? str_replace('http://','',trim($url)) : '';
$pw			= $pw ? md5($pw) : ''; 
$content	= trim($content);
//$oneline	= 
$d_regis	= $date['totime'];
$d_modify	= '';
$d_oneline	= '';
$ip			= $_SERVER['REMOTE_ADDR'];
$agent		= $_SERVER['HTTP_USER_AGENT'];
//$sns		=
$hidden		= $hidden ? (int)$hidden : 0;
$notice		= $notice ? (int)$notice : 0;

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
	$R = getUidData($table[$m.'comment'],$uid);
	if (!$R['uid']) getLink('','','존재하지 않는 댓글입니다.','');

	if (!$my['uid'] || ($my['uid'] != $R['mbruid'] && !$my['admin']))
	{
		if (!$pw)
		{
			getLink('','','정상적인 접근이 아닙니다.','');
		}
		else {
			if($pw != $R['pw'])
			{
				getLink('','','비밀번호가 일치하지 않습니다.','');
			}
		}
	}

	$QVAL = "notice='$notice',hidden='$hidden',name='$name',url='$url',content='$content',d_modify='$d_regis'";
	getDbUpdate($table[$m.'comment'],$QVAL,'uid='.$R['uid']);
	getLink('reload','parent.','','');
}
else 
{
	$minuid = getDbCnt($table[$m.'comment'],'min(uid)','');
	$uid = $minuid ? $minuid-1 : 1000000000;

	$QKEY = "uid,blog,parent,notice,hidden,mbruid,name,url,pw,content,";
	$QKEY.= "oneline,d_regis,d_modify,d_oneline,ip,agent,sns";
	$QVAL = "'$uid','$blog','$parent','$notice','$hidden','$mbruid','$name','$url','$pw','$content',";
	$QVAL.= "'$oneline','$d_regis','$d_modify','$d_oneline','$ip','$agent','$sns'";
	getDbInsert($table[$m.'comment'],$QKEY,$QVAL);
	getDbUpdate($table[$m.'data'],"comment=comment+1,d_comment='".$date['totime']."'",'uid='.$parent);
	getDbUpdate($table[$m.'list'],"d_last='".$date['totime']."',num_c=num_c+1",'uid='.$blog);
	getDbUpdate($table[$m.'members'],'num_c=num_c+1','blog='.$blog.' and mbruid='.$my['uid']);

	if ($uid == 1000000000) db_query("OPTIMIZE TABLE ".$table[$m.'comment'],$DB_CONNECT);

	if ($d['blog']['c_give_point']&&$my['uid'])
	{
		getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$my['uid']."','0','".$d['blog']['c_give_point']."','블로그댓글(".getStrCut($content,15,'').")포인트','".$date['totime']."'");
		getDbUpdate($table['s_mbrdata'],'point=point+'.$d['blog']['c_give_point'],'memberuid='.$my['uid']);
	}


	if ($snsCallBack && ($sns_t||$sns_f||$sns_m||$sns_y))
	{
		$B=getUidData($table[$m.'list'],$blog);
		$xcync = "[][][][][][r:".$r.",m:".$m.",blog:".$B['id'].",uid:".$parent."]";
		$orignContent = getStrCut(strip_tags($content),60,'..');
		$orignSubject = $orignContent;
		$orignUrl = 'http://'.$_SERVER['SERVER_NAME'].str_replace('./','/',getCyncUrl($xcync)).'#CMT';

		include_once $g['path_module'].$snsCallBack;

		if ($snsSendResult)
		{
			getDbUpdate($table[$m.'comment'],"sns='".$snsSendResult."'",'uid='.$uid);
		}
	}

	if ($backurl)
	{
		getLink($backurl.($CMT=='Y'?'&CMT=Y':''),'parent.','','');
	}
	else {
		getLink('reload','parent.','','');
	}
}
?>