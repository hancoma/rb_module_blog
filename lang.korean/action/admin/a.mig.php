<?php
if(!defined('__KIMS__')) exit;

checkAdmin(0);

include $g['dir_module'].'lib/tree.func.php';
include $g['path_core'].'function/rss.func.php';

function getRssAddslashes($s,$f)
{
	return addslashes(getRssContent($s,$f));
}
$RSS = array();

$tmpname	= $_FILES['xmlfile']['tmp_name'];
$realname	= $_FILES['xmlfile']['name'];
$fileExt	= strtolower(getExt($realname));

if (is_uploaded_file($tmpname))
{
	if (!strpos('[xml]',$fileExt))
	{
		getLink('','','xml 파일만 등록할 수 있습니다.','');
	}
	$RSS['data'] = implode('',file($tmpname));
}
else {
	getLink('','','xml 파일이 등록되지 않았습니다.','');
}
$B = getUidData($table[$m.'list'],$blog);

$mCount = 0;
$RSS['array']= explode('<item>',$RSS['data']);
$RSS['count']= count($RSS['array']);

$mingid = getDbCnt($table[$m.'data'],'min(gid)','');
$gid = $mingid ? $mingid-1 : 100000000;

$blog = $B['uid'];
$mbruid	= $my['uid'];
$log = $my[$_HS['nametype']].'|'.getDateFormat($date['totime'],'Y.m.d H:i').'<s>';

for($i = 1; $i < $RSS['count']; $i++)
{
	$tag		= getRssAddslashes($RSS['array'][$i],'tag');
	$subject	= getRssAddslashes($RSS['array'][$i],'subject');
	$review		= getRssAddslashes($RSS['array'][$i],'review');
	$content	= getRssAddslashes($RSS['array'][$i],'content');
	$hit		= getRssAddslashes($RSS['array'][$i],'hit');;
	$d_regis	= getRssAddslashes($RSS['array'][$i],'d_regis');
	$d_modify	= getRssAddslashes($RSS['array'][$i],'d_modify');
	$d_today	= substr($d_regis,0,8);

	$QKEY1 = "blog,gid,isreserve,isphoto,isvod,cutcomment,mbruid,tag,subject,review,content,";
	$QKEY1.= "hit,comment,oneline,d_regis,d_modify,d_comment,sns,upload,log";
	$QVAL1 = "'$blog','$gid','0','0','0','0','$mbruid','$tag','$subject','$review','$content',";
	$QVAL1.= "'$hit','0','0','$d_regis','$d_modify','','','','$log'";
	getDbInsert($table[$m.'data'],$QKEY1,$QVAL1);
	getDbUpdate($table[$m.'list'],"num_w=num_w+1,d_last='".$d_regis."'",'uid='.$B['uid']);
	if(!getDbRows($table[$m.'day'],"date='".$d_today."' and blog=".$B['uid'])) getDbInsert($table[$m.'day'],'date,blog,num',"'".$d_today."','".$B['uid']."','1'");
	else getDbUpdate($table[$m.'day'],'num=num+1',"date='".$d_today."' and bbs=".$B['uid']);
	$LASTUID = getDbCnt($table[$m.'data'],'max(uid)','');
	$QKEY2 = "blog,parent,subject,title,keywords,description,classification,replyto,language,build";
	$QVAL2 = "'$blog','$LASTUID','$subject','','$tag','','','','',''";
	getDbInsert($table[$m.'seo'],$QKEY2,$QVAL2);
	getDbUpdate($table[$m.'members'],'num_w=num_w+1','blog='.$B['uid'].' and mbruid='.$my['uid']);


	$_category_members = array();
	$_category_members = getArrayString($category_select);
	foreach($_category_members['data'] as $_ct1)
	{
		$sql = '';
		$_cats = getArrayString(getMenuCodeToSqlBlog1($table[$m.'category'],$_ct1,$B['uid'],''));
		foreach($_cats['data'] as $_ct2)
		{
			if (!getDbRows($table[$m.'catidx'],'parent='.$LASTUID.' and category='.$_ct2))
			{
				getDbInsert($table[$m.'catidx'],'blog,parent,category',"'".$B['uid']."','".$LASTUID."','".$_ct2."'");
				getDbUpdate($table[$m.'category'],'num_open=num_open+1','uid='.$_ct2);
			}
		}
	}
	$gid--;
	$mCount++;
}

if ($viewresult)
{
	echo '<meta http-equiv="content-type" content="text/html;charset=utf-8" />';
	echo '<script type="text/javascript">';
	echo "alert('[".number_format($mCount)."]건의 게시물데이터 이전작업이 완료되었습니다.');";
	echo "window.open('".$g['s'].'/?r='.$r.'&m='.$m.'&blog='.$B['id']."');";
	echo "parent.location.reload();";
	echo '</script>';
}
else {
	getLink('reload','parent.','['.number_format($mCount).']건의 데이터 이전작업이 완료되었습니다.','');
}
?>