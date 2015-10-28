<?php
if(!defined('__KIMS__')) exit;

if (!$blog) getLink('','','블로그 아이디가 지정되지 않았습니다.',''); 
$B = getUidData($table[$m.'list'],$blog);
if (!$B['uid']) getLink('','','존재하지 않는 블로그입니다.','');
if (!$my['uid'] || ($my['uid']!=$B['mbruid'] && !strpos('_,'.$B['members'].',',','.$my['id'].','))) getLink('','','포스트 등록권한이 없습니다.','');
$C = getUidData($table[$m.'category'],$cat);
if (!$C['metause'] || !$C['metaurl']) getLink('','','정상적인 접근이 아닙니다.',''); 

include $g['dir_module'].'lib/tree.func.php';
include $g['dir_module'].'var/var.php';
include $g['dir_module'].'var/var.'.$B['id'].'.php';

$mbruid=$my['uid'];
$d_regis = $date['totime'];
$_tagdate = $date['today'];
$log = $my[$_HS['nametype']].'|'.getDateFormat($date['totime'],'Y.m.d H:i').'<s>';
$_cats = getArrayString(getMenuCodeToSqlBlog1($table[$m.'category'],$cat,$B['uid'],''));
$i = 0;

rsort($meta_post);
reset($meta_post);
foreach($meta_post as $_val)
{
	$_name = trim(${'name'.$_val});
	$_category = trim(${'category'.$_val});
	$_subject = trim(${'subject'.$_val});
	$_link = trim(${'link'.$_val});
	$_content = trim(${'content'.$_val});
	$_d_regis = trim(${'d_regis'.$_val});
	$_tag = str_replace(' ',',',trim(${'tag'.$_val}));
	

	if (!$_subject || !$_content) continue;


	$mingid = getDbCnt($table[$m.'data'],'min(gid)','');
	$gid = $mingid ? $mingid-1 : 100000000;
	
	$QKEY1 = "blog,gid,isreserve,isphoto,isvod,cutcomment,mbruid,tag,subject,review,content,";
	$QKEY1.= "hit,comment,oneline,d_regis,d_modify,d_comment,sns,upload,log";
	$QVAL1 = "'".$B['uid']."','$gid','0','0','0','0','$mbruid','$_tag','$_subject','".getStrCut(getStripTags($_content),500,'..')."','$_content',";
	$QVAL1.= "'0','0','0','$d_regis','','','','','$log'";
	getDbInsert($table[$m.'data'],$QKEY1,$QVAL1);
	getDbUpdate($table[$m.'list'],"num_w=num_w+1,d_last='".$d_regis."'",'uid='.$B['uid']);
	if(!getDbRows($table[$m.'day'],"date='".$date['today']."' and blog=".$B['uid'])) getDbInsert($table[$m.'day'],'date,blog,num',"'".$date['today']."','".$B['uid']."','1'");
	else getDbUpdate($table[$m.'day'],'num=num+1',"date='".$date['today']."' and bbs=".$B['uid']);
	$LASTUID = getDbCnt($table[$m.'data'],'max(uid)','');
	$QKEY2 = "blog,parent,subject,title,keywords,description,classification,replyto,language,build";
	$QVAL2 = "'".$B['uid']."','".$LASTUID."','$_subject','$_subject','$_tag','".getStrCut(getStripTags($_content),150,'..')."','','','',''";
	getDbInsert($table[$m.'seo'],$QKEY2,$QVAL2);
	getDbUpdate($table[$m.'members'],'num_w=num_w+1','blog='.$B['uid'].' and mbruid='.$my['uid']);


	$sql = '';
	foreach($_cats['data'] as $_ct2)
	{
		if (!getDbRows($table[$m.'catidx'],'parent='.$LASTUID.' and category='.$_ct2))
		{
			getDbInsert($table[$m.'catidx'],'blog,parent,category',"'".$B['uid']."','".$LASTUID."','".$_ct2."'");
			if ($isreserve)
			{
				getDbUpdate($table[$m.'category'],'num_reserve=num_reserve+1','uid='.$_ct2);
			}
			else {
				getDbUpdate($table[$m.'category'],'num_open=num_open+1','uid='.$_ct2);
			}
		}
	}

	if ($_tag)
	{
		$_tagarr1 = array();
		$_tagarr2 = explode(',',$_tag);

		foreach($_tagarr2 as $_t)
		{
			if(!$_t) continue;
			$_TAG = getDbData($table['s_tag'],'site='.$s." and date='".$_tagdate."' and keyword='".$_t."'",'*');
			if($_TAG['uid']) getDbUpdate($table['s_tag'],'hit=hit+1','uid='.$_TAG['uid']);
			else getDbInsert($table['s_tag'],'site,date,keyword,hit',"'".$s."','".$_tagdate."','".$_t."','1'");
		}
	}
	$i++;
}


getLink($g['s'].'/?r='.$r.'&m='.$m.'&blog='.$B['id'].'&front=list&cat='.$cat.'&vtype='.$vtype,'parent.','총 '.$i.'개의 포스트를 로컬DB로 가져왔습니다.','');
?>