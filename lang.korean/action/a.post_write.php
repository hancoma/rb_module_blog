<?php
if(!defined('__KIMS__')) exit;

if (!$blog) getLink('','','블로그 아이디가 지정되지 않았습니다.',''); 
$B = getUidData($table[$m.'list'],$blog);
if (!$B['uid']) getLink('','','존재하지 않는 블로그입니다.','');
if (!$my['uid'] || ($my['uid']!=$B['mbruid'] && !strpos('_,'.$B['members'].',',','.$my['id'].','))) getLink('','','포스트 등록권한이 없습니다.',''); 
include $g['dir_module'].'var/var.php';
include $g['dir_module'].'var/var.'.$B['id'].'.php';
include $g['dir_module'].'lib/tree.func.php';

//$uid		=
$blog		= $B['uid'];
//$gid		=
//$isreserve=
//$isphoto	=
//$isvod	=
//$cutcomment = 
$mbruid		= $author ? $author : $my['uid'];
$tag		= trim($tag);
$subject	= trim($subject);
$review		= trim($review);
$content	= trim($content);
$hit		= 0;
$comment	= 0;
$oneline	= 0;
$d_regis	= $isreserve ? $year1.$month1.$day1.$hour1.$min1.'00' : $date['totime'];
if($d_regis < $date['totime'])
{
	$isreserve = 0;
	$d_regis = $date['totime'];
}
$d_modify	= '';
$d_comment	= '';
$sns		= '';
$upload		= $upfiles;

$s_subject	= trim($s_subject);
$s_title	= trim($s_title);
$s_keywords	= trim($s_keywords);
$s_desc		= trim($s_desc);
$s_class	= trim($s_class);
$s_replyto	= trim($s_replyto);
$s_language	= trim($s_language);
$s_build	= trim($s_build);


if ($num_upfile || $num_photo)
{

	include $g['path_core'].'function/thumb.func.php';

	$fserver	= $d['blog']['up_use_fileserver'];
	$fserverurl = $fserver ? $d['blog']['ftp_urlpath'] : $g['url_root'].'/modules/'.$m.'/files/';
	$incPhoto	= '';
	$upload		= $uid ? $upload : '';
	$saveDir	= $g['path_module'].$m.'/files/';
	$savePath1	= $saveDir.substr($date['today'],0,4);
	$savePath2	= $savePath1.'/'.substr($date['today'],4,2);
	$savePath3	= $savePath2.'/'.substr($date['today'],6,2);
	$up_folder	= substr($date['today'],0,4).'/'.substr($date['today'],4,2).'/'.substr($date['today'],6,2);
	$up_caption	= $subject;

	if ($fserver)
	{
		$FTP_CONNECT = ftp_connect($d['blog']['ftp_host'],$d['blog']['ftp_port']); 
		$FTP_CRESULT = ftp_login($FTP_CONNECT,$d['blog']['ftp_user'],$d['blog']['ftp_pass']);
		if ($d['blog']['ftp_pasv']) ftp_pasv($FTP_CONNECT, true);
		if (!$FTP_CONNECT) getLink('','','FTP서버 연결에 문제가 발생했습니다.','');
		if (!$FTP_CRESULT) getLink('','','FTP서버 아이디나 패스워드가 일치하지 않습니다.','');

		ftp_chdir($FTP_CONNECT,$d['blog']['ftp_folder']);
		for ($i = 1; $i < 4; $i++)
		{
			ftp_mkdir($FTP_CONNECT,$d['blog']['ftp_folder'].str_replace('./files/','',${'savePath'.$i}));
		}
	}
	else {
		for ($i = 1; $i < 4; $i++)
		{
			if (!is_dir(${'savePath'.$i}))
			{
				mkdir(${'savePath'.$i},0707);
				@chmod(${'savePath'.$i},0707);
			}
		}
	}

	for ($i = 0; $i < $num_upfile + $num_photo; $i++)
	{
		if (!$_FILES['upfile']['tmp_name'][$i]) continue;
		
		$width		= 0;
		$height		= 0;
		$up_name	= strtolower($_FILES['upfile']['name'][$i]);
		$up_size	= $_FILES['upfile']['size'][$i];
		$up_fileExt	= getExt($up_name);
		$up_fileExt	= $up_fileExt == 'jpeg' ? 'jpg' : $up_fileExt;
		$up_type	= getFileType($up_fileExt);
		$up_tmpname	= md5($up_name).substr($date['totime'],8,14);
		$up_tmpname	= $up_type == 2 ? $up_tmpname.'.'.$up_fileExt : $up_tmpname;
		$up_mingid	= getDbCnt($table[$m.'upload'],'min(gid)','');
		$up_gid		= $up_mingid ? $up_mingid - 1 : 100000000;
		$up_saveFile= $savePath3.'/'.$up_tmpname;
		$up_hidden	= $up_type == 2 ? 1 : 0;

		if ($fserver)
		{
			if ($up_type == 2)
			{
				$up_thumbname = md5($up_tmpname).'.'.$up_fileExt;
				$up_thumbFile = $g['path_tmp'].'backup/'.$up_thumbname;
				ResizeWidth($_FILES['upfile']['tmp_name'][$i],$up_thumbFile,150);
				$IM = getimagesize($_FILES['upfile']['tmp_name'][$i]);
				$width = $IM[0];
				$height= $IM[1];
				ftp_put($FTP_CONNECT,$d['blog']['ftp_folder'].$up_folder.'/'.$up_thumbname,$up_thumbFile,FTP_BINARY);
				unlink($up_thumbFile);
			}
			ftp_put($FTP_CONNECT,$d['blog']['ftp_folder'].$up_folder.'/'.$up_tmpname,$_FILES['upfile']['tmp_name'][$i],FTP_BINARY);
		}
		else {

			if (!is_file($up_saveFile))
			{
				move_uploaded_file($_FILES['upfile']['tmp_name'][$i], $up_saveFile);
				if ($up_type == 2)
				{
					$up_thumbname = md5($up_tmpname).'.'.$up_fileExt;
					$up_thumbFile = $savePath3.'/'.$up_thumbname;
					ResizeWidth($up_saveFile,$up_thumbFile,150);
					@chmod($up_thumbFile,0707);
					$IM = getimagesize($up_saveFile);
					$width = $IM[0];
					$height= $IM[1];
				}
				@chmod($up_saveFile,0707);
			}
		}

		$QKEY = "gid,hidden,tmpcode,blog,parent,mbruid,type,ext,fserver,url,folder,name,tmpname,thumbname,size,width,height,caption,down,d_regis,d_update";
		$QVAL = "'$up_gid','$up_hidden','','".$B['uid']."','0','$mbruid','$up_type','$up_fileExt','$fserver','$fserverurl','$up_folder','$up_name','$up_tmpname','$up_thumbname','$up_size','$width','$height','$up_caption','0','$d_regis',''";
		getDbInsert($table[$m.'upload'],$QKEY,$QVAL);
		$up_lastuid = getDbCnt($table[$m.'upload'],'max(uid)','');
		$upload .= '['.$up_lastuid.']';
		if ($up_type == 2)
		{
			if ($fserver)
			{
				$incPhoto .= '<img src="'.$d['blog']['ftp_urlpath'].$up_folder.'/'.$up_tmpname.'" width="'.$d['blog']['width_img'].'" class="photo" alt="" /><br /><br />';
			}
			else {
				$incPhoto .= '<img src="'.$g['url_root'].'/modules/'.$m.'/files/'.$up_folder.'/'.$up_tmpname.'" width="'.$d['blog']['width_img'].'" class="photo" alt="" /><br /><br />';
			}
		}

		if ($up_gid == 100000000) db_query("OPTIMIZE TABLE ".$table[$m.'upload'],$DB_CONNECT); 

	}

	if ($uid && $upfiles)
	{
		$_uploadtmp = getArrayString($upfiles);
		foreach($_uploadtmp['data'] as $_val)
		{
			$U = getUidData($table[$m.'upload'],$_val);
			if ($U['type'] == 2)
			{
				if ($fserver)
				{
					$incPhoto .= '<img src="'.$d['blog']['ftp_urlpath'].$U['folder'].'/'.$U['tmpname'].'" width="'.$d['blog']['width_img'].'" class="photo" alt="" /><br /><br />';
				}
				else {
					$incPhoto .= '<img src="'.$g['url_root'].'/files/'.$U['folder'].'/'.$U['tmpname'].'" width="'.$d['blog']['width_img'].'" class="photo" alt="" /><br /><br />';
				}
			}
		}
	}

	if ($incPhoto)
	{
		if ($insert_photo == 'top')
		{
			$content = $incPhoto.nl2br($content);
		}
		if ($insert_photo == 'bottom')
		{
			$content = nl2br($content).'<br /><br />'.$incPhoto;
		}
		$html = 'HTML';
	}

	if ($fserver)
	{
		ftp_close($FTP_CONNECT);
	}
}


if ($uid)
{
	$R = getUidData($table[$m.'data'],$uid);
	if (!$R['uid']) getLink('','','존재하지 않는 포스트입니다.','');
	$log = $my[$_HS['nametype']].'|'.getDateFormat($date['totime'],'Y.m.d H:i').'<s>'.$R['log'];
	$d_regis = $isreserve ? $d_regis : $date['totime'];

	$QVAL1 = "isreserve='$isreserve',isphoto='$isphoto',isvod='$isvod',cutcomment='$cutcomment',tag='$tag',subject='$subject',review='$review',content='$content'".($R['isreserve']&&!$isreserve||!$R['isreserve']&&$isreserve?",d_regis='".$d_regis."'":'').",d_modify='$d_regis',upload='$upload',log='$log'";
	$QVAL2 = "subject='$s_subject',title='$s_title',keywords='$s_keywords',description='$s_desc',classification='$s_class',replyto='$s_replyto',language='$s_language',build='$s_build'";
	getDbUpdate($table[$m.'data'],$QVAL1,'uid='.$R['uid']);
	getDbUpdate($table[$m.'seo'],$QVAL2,'parent='.$R['uid']);


	$_orign_category_members = getDbArray($table[$m.'catidx'],'parent='.$R['uid'],'*','uid','asc',0,1);
	while($_ocm=db_fetch_array($_orign_category_members))
	{
		if(!strstr($category_members,'['.$_ocm['category'].']'))
		{
			getDbDelete($table[$m.'catidx'],'uid='.$_ocm['uid']);
			if ($R['isreserve'])
			{
				getDbUpdate($table[$m.'category'],'num_reserve=num_reserve-1','uid='.$_ocm['category']);
			}
			else {
				getDbUpdate($table[$m.'category'],'num_open=num_open-1','uid='.$_ocm['category']);
			}
		}
	}

	$_category_members = array();
	$_category_members = getArrayString($category_members);
	foreach($_category_members['data'] as $_ct1)
	{
		$sql = '';
		$_cats = getArrayString(getMenuCodeToSqlBlog1($table[$m.'category'],$_ct1,$B['uid'],''));
		foreach($_cats['data'] as $_ct2)
		{
			if (!getDbRows($table[$m.'catidx'],'parent='.$R['uid'].' and category='.$_ct2))
			{
				getDbInsert($table[$m.'catidx'],'blog,parent,category',"'".$B['uid']."','".$R['uid']."','".$_ct2."'");
				if ($isreserve)
				{
					getDbUpdate($table[$m.'category'],'num_reserve=num_reserve+1','uid='.$_ct2);
				}
				else {
					getDbUpdate($table[$m.'category'],'num_open=num_open+1','uid='.$_ct2);
				}
			}
			else {
				if ($R['isreserve'])
				{
					if (!$isreserve)
					{
						getDbUpdate($table[$m.'category'],'num_open=num_open+1,num_reserve=num_reserve-1','uid='.$_ct2);
					}
				}
				else {
					if ($isreserve)
					{
						getDbUpdate($table[$m.'category'],'num_open=num_open-1,num_reserve=num_reserve+1','uid='.$_ct2);
					}
				}
			}
		}
	}
}
else 
{
	$mingid = getDbCnt($table[$m.'data'],'min(gid)','');
	$gid = $mingid ? $mingid-1 : 100000000;
	$log = $my[$_HS['nametype']].'|'.getDateFormat($date['totime'],'Y.m.d H:i').'<s>';
	
	$QKEY1 = "blog,gid,isreserve,isphoto,isvod,cutcomment,mbruid,tag,subject,review,content,";
	$QKEY1.= "hit,comment,oneline,d_regis,d_modify,d_comment,sns,upload,log";
	$QVAL1 = "'".$B['uid']."','$gid','$isreserve','$isphoto','$isvod','$cutcomment','$mbruid','$tag','$subject','$review','$content',";
	$QVAL1.= "'0','0','0','$d_regis','','','','$upload','$log'";
	getDbInsert($table[$m.'data'],$QKEY1,$QVAL1);
	getDbUpdate($table[$m.'list'],"num_w=num_w+1,d_last='".$d_regis."'",'uid='.$B['uid']);
	if(!getDbRows($table[$m.'day'],"date='".$date['today']."' and blog=".$B['uid'])) getDbInsert($table[$m.'day'],'date,blog,num',"'".$date['today']."','".$B['uid']."','1'");
	else getDbUpdate($table[$m.'day'],'num=num+1',"date='".$date['today']."' and bbs=".$B['uid']);
	$LASTUID = getDbCnt($table[$m.'data'],'max(uid)','');
	$QKEY2 = "blog,parent,subject,title,keywords,description,classification,replyto,language,build";
	$QVAL2 = "'".$B['uid']."','".$LASTUID."','$s_subject','$s_title','$s_keywords','$s_desc','$s_class','$s_replyto','$s_language','$s_build'";
	getDbInsert($table[$m.'seo'],$QKEY2,$QVAL2);
	getDbUpdate($table[$m.'members'],'num_w=num_w+1','blog='.$B['uid'].' and mbruid='.$my['uid']);


	$_category_members = array();
	$_category_members = getArrayString($category_members);
	foreach($_category_members['data'] as $_ct1)
	{
		$sql = '';
		$_cats = getArrayString(getMenuCodeToSqlBlog1($table[$m.'category'],$_ct1,$B['uid'],''));
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
	}

	
	if ($gid == 100000000)
	{
		db_query("OPTIMIZE TABLE ".$table[$m.'data'],$DB_CONNECT); 
		db_query("OPTIMIZE TABLE ".$table[$m.'day'],$DB_CONNECT); 
	}
}

$NOWUID = $LASTUID ? $LASTUID : $R['uid'];

if ($upload)
{
	$_updata = getArrayString($upload);
	foreach ($_updata['data'] as $_ups)
	{
		$_upv = getUidData($table[$m.'upload'],$_ups);
		if (!$_upv['blog'] || !$_upv['parent'])
		{
			getDbUpdate($table[$m.'upload'],'blog='.$B['uid'].',parent='.$NOWUID,'uid='.$_upv['uid']);
		}
	}
}

if ($tag || $R['tag'])
{
	$_tagarr1 = array();
	$_tagarr2 = explode(',',$tag);
	$_tagdate = $date['today'];

	if ($R['uid'] && $reply != 'Y')
	{
		$_tagdate = substr($R['d_regis'],0,8);
		$_tagarr1 = explode(',',$R['tag']);
		foreach($_tagarr1 as $_t)
		{
			if(!$_t || in_array($_t,$_tagarr2)) continue;
			$_TAG = getDbData($table['s_tag'],"site=".$R['site']." and date='".$_tagdate."' and keyword='".$_t."'",'*');
			if($_TAG['uid'])
			{
				if($_TAG['hit']>1) getDbUpdate($table['s_tag'],'hit=hit-1','uid='.$_TAG['uid']);
				else getDbDelete($table['s_tag'],'uid='.$_TAG['uid']);
			}
		}
	}

	foreach($_tagarr2 as $_t)
	{
		if(!$_t || in_array($_t,$_tagarr1)) continue;
		$_TAG = getDbData($table['s_tag'],'site='.$s." and date='".$_tagdate."' and keyword='".$_t."'",'*');
		if($_TAG['uid']) getDbUpdate($table['s_tag'],'hit=hit+1','uid='.$_TAG['uid']);
		else getDbInsert($table['s_tag'],'site,date,keyword,hit',"'".$s."','".$_tagdate."','".$_t."','1'");
	}
}

if ($snsCallBack && ($sns_t||$sns_f||$sns_m||$sns_y))
{
	$xcync = "[][][][][][r:".$r.",m:".$m.",blog:".$B['id'].",uid:".$NOWUID."]";
	$orignSubject = strip_tags($subject);
	$orignContent = getStrCut($orignSubject,60,'..');
	$orignUrl = 'http://'.$_SERVER['SERVER_NAME'].str_replace('./','/',getCyncUrl($xcync)).'#CMT';

	include_once $g['path_module'].$snsCallBack;

	if ($snsSendResult)
	{
		getDbUpdate($table[$m.'data'],"sns='".$snsSendResult."'",'uid='.$LASTUID);
	}
}

if ($R['uid'])
{
	getLink($g['s'].'/?r='.$r.'&m='.$m.'&blog='.$B['id'].'&front=list&cat='.$cat.'&vtype='.$vtype.'&recnum='.$recnum.'&uid='.$R['uid'],'parent.','','');
}
else {
	getLink($g['s'].'/?r='.$r.'&m='.$m.'&blog='.$B['id'].'&front=list&cat='.$cat.'&vtype='.$vtype.'&recnum='.$recnum,'parent.','','');
}
?>