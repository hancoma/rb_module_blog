<?php
if(!defined('__KIMS__')) exit;

include $g['dir_module'].'var/var.php';

if ($file_uid)
{
	$R = getUidData($table[$m.'upload'],$file_uid);
	if (!$my['admin'] && (!$R['mbruid'] || $my['uid'] != $R['mbruid']))
	{
		getLink('','','삭제권한이 없습니다.','');
	}
	if ($R['uid'])
	{
		getDbDelete($table[$m.'upload'],'uid='.$R['uid']);

		if ($R['url']==$d['blog']['ftp_urlpath'])
		{
			$FTP_CONNECT = ftp_connect($d['blog']['ftp_host'],$d['blog']['ftp_port']); 
			$FTP_CRESULT = ftp_login($FTP_CONNECT,$d['blog']['ftp_user'],$d['blog']['ftp_pass']); 
			if (!$FTP_CONNECT) getLink('','','FTP서버 연결에 문제가 발생했습니다.','');
			if (!$FTP_CRESULT) getLink('','','FTP서버 아이디나 패스워드가 일치하지 않습니다.','');

			ftp_delete($FTP_CONNECT,$d['blog']['ftp_folder'].$R['folder'].'/'.$R['tmpname']);
			if($R['type']==2) ftp_delete($FTP_CONNECT,$d['blog']['ftp_folder'].$R['folder'].'/'.$R['thumbname']);
			ftp_close($FTP_CONNECT);
		}
		else {
			unlink($g['dir_module'].'files/'.$R['folder'].'/'.$R['tmpname']);
			if($R['type']==2) unlink($g['dir_module'].'files/'.$R['folder'].'/'.$R['thumbname']);
		}
	}
	
	if ($isreload == 'Y')
	{
		getLink('reload','parent.','','');
	}
	exit;
}
else {

	$sescode = $_SESSION['upsescode'];

	if ($sescode)
	{
		if ($d['blog']['up_use_fileserver'])
		{
			$FTP_CONNECT = ftp_connect($d['blog']['ftp_host'],$d['blog']['ftp_port']); 
			$FTP_CRESULT = ftp_login($FTP_CONNECT,$d['blog']['ftp_user'],$d['blog']['ftp_pass']); 
			if ($d['blog']['ftp_pasv']) ftp_pasv($FTP_CONNECT, true);
			if (!$FTP_CONNECT) getLink('','','FTP서버 연결에 문제가 발생했습니다.','');
			if (!$FTP_CRESULT) getLink('','','FTP서버 아이디나 패스워드가 일치하지 않습니다.','');


			$FILES = getDbArray($table[$m.'upload'],"tmpcode='".$sescode."'",'*','uid','asc',0,0);
			while($R = db_fetch_array($FILES))
			{
				getDbDelete($table[$m.'upload'],'uid='.$R['uid']);
				
				ftp_delete($FTP_CONNECT,$d['blog']['ftp_folder'].$R['folder'].'/'.$R['tmpname']);
				if($R['type']==2)
				{
					ftp_delete($FTP_CONNECT,$d['blog']['ftp_folder'].$R['folder'].'/'.$R['thumbname']);
				}
			}
			
			ftp_close($FTP_CONNECT);
		}
		else {

			$FILES = getDbArray($table[$m.'upload'],"tmpcode='".$sescode."'",'*','uid','asc',0,0);
			while($R = db_fetch_array($FILES))
			{
				getDbDelete($table[$m.'upload'],'uid='.$R['uid']);
				unlink($g['dir_module'].'files/'.$R['folder'].'/'.$R['tmpname']);
				if($R['type']==2)
				{
					unlink($g['dir_module'].'files/'.$R['folder'].'/'.$R['thumbname']);
				}
			}
		}
	}
	if ($close == 'Y')
	{
		$_SESSION['upsescode'] = '';
		if ($iload == 'Y')
		{
			getLink('','','','close');
		}
		else {
			getLink('','parent.parent.getLayerBoxHide();','','');
		}
	}
	else {
		getLink('reload','parent.','','');
	}
}
?>