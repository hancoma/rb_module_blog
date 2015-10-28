<?php
if(!defined('__KIMS__')) exit;

checkAdmin(0);

include $g['dir_module'].'var/var.php';

foreach($upfile_members as $val)
{
	$R = getUidData($table[$m.'upload'],$val);
	if ($R['uid'])
	{
		getDbDelete($table[$m.'upload'],'uid='.$R['uid']);

		if ($R['url']==$d['blog']['ftp_urlpath'])
		{
			$FTP_CONNECT = ftp_connect($d['blog']['ftp_host'],$d['blog']['ftp_port']); 
			$FTP_CRESULT = ftp_login($FTP_CONNECT,$d['blog']['ftp_user'],$d['blog']['ftp_pass']); 
			if ($d['blog']['ftp_pasv']) ftp_pasv($FTP_CONNECT, true);
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
}

getLink('reload','parent.','','');
?>