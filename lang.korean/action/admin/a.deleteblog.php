<?php
if(!defined('__KIMS__')) exit;

checkAdmin(0);

$R = getUidData($table[$m.'list'],$uid);
if (!$R['uid']) getLink('','','존재하지 않는 블로그입니다.','');

include $g['dir_module'].'var/var.php';

$RCD = getDbArray($table[$m.'data'],'blog='.$R['uid'],'*','gid','asc',0,0);
while($_R=db_fetch_array($RCD))
{
	//댓글삭제
	if ($_R['comment'])
	{
		$CCD = getDbArray($table[$m.'comment'],"parent='".$_R['uid']."'",'*','uid','asc',0,0);

		while($_C=db_fetch_array($CCD))
		{
			if ($_C['oneline'])
			{
				$_ONELINE = getDbSelect($table[$m.'oneline'],'parent='.$_C['uid'],'*');
				while($_O=db_fetch_array($_ONELINE))
				{
					if ($d['blog']['c_give_opoint']&&$_O['mbruid'])
					{
						getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$_O['mbruid']."','0','-".$d['blog']['c_give_opoint']."','한줄의견삭제(".getStrCut(str_replace('&amp;',' ',strip_tags($_O['content'])),15,'').")환원','".$date['totime']."'");
						getDbUpdate($table['s_mbrdata'],'point=point-'.$d['blog']['c_give_opoint'],'memberuid='.$_O['mbruid']);
					}
				}
				getDbDelete($table[$m.'oneline'],'parent='.$_C['uid']);
			}
			getDbDelete($table[$m.'comment'],'uid='.$_C['uid']);

			if ($d['blog']['c_give_opoint']&&$_C['mbruid'])
			{
				getDbInsert($table['s_point'],'my_mbruid,by_mbruid,price,content,d_regis',"'".$_C['mbruid']."','0','-".$d['blog']['c_give_opoint']."','댓글삭제(".getStrCut($_C['subject'],15,'').")환원','".$date['totime']."'");
				getDbUpdate($table['s_mbrdata'],'point=point-'.$d['blog']['c_give_opoint'],'memberuid='.$_C['mbruid']);
			}
		}
	}
	//첨부파일삭제
	if ($_R['upload'])
	{
		$UPFILES = getArrayString($_R['upload']);

		foreach($UPFILES['data'] as $_val)
		{
			$U = getUidData($table[$m.'upload'],$_val);
			if ($U['uid'])
			{
				getDbDelete($table[$m.'upload'],$U['uid']);
				if ($U['url']==$d['blog']['ftp_urlpath'])
				{
					$FTP_CONNECT = ftp_connect($d['blog']['ftp_host'],$d['blog']['ftp_port']); 
					$FTP_CRESULT = ftp_login($FTP_CONNECT,$d['blog']['ftp_user'],$d['blog']['ftp_pass']); 
					if ($d['blog']['ftp_pasv']) ftp_pasv($FTP_CONNECT, true);
					if (!$FTP_CONNECT) getLink('','','FTP서버 연결에 문제가 발생했습니다.','');
					if (!$FTP_CRESULT) getLink('','','FTP서버 아이디나 패스워드가 일치하지 않습니다.','');

					ftp_delete($FTP_CONNECT,$d['blog']['ftp_folder'].$U['folder'].'/'.$U['tmpname']);
					if($U['type']==2) ftp_delete($FTP_CONNECT,$d['blog']['ftp_folder'].$U['folder'].'/'.$U['thumbname']);
					ftp_close($FTP_CONNECT);
				}
				else {
					unlink($g['dir_module'].'files/'.$U['folder'].'/'.$U['tmpname']);
					if($U['type']==2) unlink($g['dir_module'].'files/'.$U['folder'].'/'.$U['thumbname']);
				}
			}
		}
	}
	//태그삭제
	if ($_R['tag'])
	{
		$_tagdate = substr($_R['d_regis'],0,8);
		$_tagarr1 = explode(',',$_R['tag']);
		foreach($_tagarr1 as $_t)
		{
			if(!$_t) continue;
			$_TAG = getDbData($table['s_tag'],"site=".$_R['site']." and date='".$_tagdate."' and keyword='".$_t."'",'*');
			if($_TAG['uid'])
			{
				if($_TAG['hit']>1) getDbUpdate($table['s_tag'],'hit=hit-1','uid='.$_TAG['uid']);
				else getDbDelete($table['s_tag'],'uid='.$_TAG['uid']);
			}
		}
	}

	getDbUpdate($table[$m.'day'],'num=num-1',"date='".substr($_R['d_regis'],0,8)."' and blog=".$_R['blog']);
}

getDbDelete($table[$m.'data'],'blog='.$R['uid']);
getDbDelete($table[$m.'category'],'blog='.$R['uid']);
getDbDelete($table[$m.'catidx'],'blog='.$R['uid']);
getDbDelete($table[$m.'seo'],'blog='.$R['uid']);
getDbDelete($table[$m.'members'],'blog='.$R['uid']);
getDbDelete($table[$m.'list'],'uid='.$R['uid']);

unlink($g['dir_module'].'var/var.'.$R['id'].'.php');

getLink('reload','parent.','','');
?>