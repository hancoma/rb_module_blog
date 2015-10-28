<?php
if(!defined('__KIMS__')) exit;

checkAdmin(0);

include $g['dir_module'].'var/var.php';

foreach ($post_members as $val)
{
	$R = getUidData($table[$m.'data'],$val);
	if (!$R['uid']) continue;
	$B = getUidData($table[$m.'list'],$R['blog']);
	if (!$B['uid']) continue;

	//댓글삭제
	if ($R['comment'])
	{
		$CCD = getDbArray($table[$m.'comment'],"parent='".$R['uid']."'",'*','uid','asc',0,0);

		while($_C=db_fetch_array($CCD))
		{
			if ($_C['upload'])
			{
				$UPFILES = getArrayString($_C['upload']);

				foreach($UPFILES['data'] as $_val)
				{
					$U = getUidData($table[$m.'upload'],$_val);
					if ($U['uid'])
					{
						getDbDelete($table[$m.'upload'],'uid='.$U['uid']);
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
			if ($_C['oneline'])
			{
				$_ONELINE = getDbSelect($table[$m.'oneline'],'parent='.$_C['uid'],'*');
				while($_O=db_fetch_array($_ONELINE))
				{
					getDbUpdate($table[$m.'members'],'num_o=num_o-1','blog='.$B['uid'].' and mbruid='.$_O['mbruid']);
				}
				getDbDelete($table[$m.'oneline'],'parent='.$_C['uid']);

			}
			getDbDelete($table[$m.'comment'],'uid='.$_C['uid']);
			getDbUpdate($table[$m.'members'],'num_c=num_c-1','blog='.$B['uid'].' and mbruid='.$_C['mbruid']);
		}
	}
	//첨부파일삭제
	if ($R['upload'])
	{
		$UPFILES = getArrayString($R['upload']);

		foreach($UPFILES['data'] as $_val)
		{
			$U = getUidData($table[$m.'upload'],$_val);
			if ($U['uid'])
			{
				getDbDelete($table[$m.'upload'],'uid='.$U['uid']);
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
	if ($R['tag'])
	{
		$_tagdate = substr($R['d_regis'],0,8);
		$_tagarr1 = explode(',',$R['tag']);
		foreach($_tagarr1 as $_t)
		{
			if(!$_t) continue;
			$_TAG = getDbData($table['s_tag'],"site=".$R['site']." and date='".$_tagdate."' and keyword='".$_t."'",'*');
			if($_TAG['uid'])
			{
				if($_TAG['hit']>1) getDbUpdate($table['s_tag'],'hit=hit-1','uid='.$_TAG['uid']);
				else getDbDelete($table['s_tag'],'uid='.$_TAG['uid']);
			}
		}
	}

	getDbUpdate($table[$m.'day'],'num=num-1',"date='".substr($R['d_regis'],0,8)."' and blog=".$B['uid']);
	getDbDelete($table[$m.'data'],'uid='.$R['uid']);
	getDbDelete($table[$m.'seo'],'parent='.$R['uid']);
	getDbUpdate($table[$m.'list'],'num_w=num_w-1,num_c=num_c-'.$R['comment'].',num_o=num_o-'.$R['oneline'],'uid='.$B['uid']);
	getDbUpdate($table[$m.'members'],'num_w=num_w-1','blog='.$B['uid'].' and mbruid='.$R['mbruid']);


	$_orign_category_members = getDbArray($table[$m.'catidx'],'parent='.$R['uid'],'*','uid','asc',0,1);
	while($_ocm=db_fetch_array($_orign_category_members))
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


getLink('reload','parent.','','');
?>