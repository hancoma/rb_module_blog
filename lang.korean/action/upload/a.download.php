<?php
if(!defined('__KIMS__')) exit;

include $g['dir_module'].'var/var.php';

$R=getUidData($table[$m.'upload'],$uid);
if (!$R['uid']) getLink('','','정상적인 요청이 아닙니다.','');

$filename = getUTFtoKR($R['name']);
$filetmpname = getUTFtoKR($R['tmpname']);

if ($R['url']==$d['blog']['ftp_urlpath'])
{
	$filepath = $d['blog']['ftp_urlpath'].$R['folder'].'/'.$filetmpname;
	$filesize = $R['size'];
}
else {
	$filepath = $g['dir_module'].'files/'.$R['folder'].'/'.$filetmpname;
	$filesize = filesize($filepath);
}

getDbUpdate($table[$m.'upload'],'down=down+1','uid='.$R['uid']);

header("Content-Type: application/octet-stream"); 
header("Content-Length: " .$filesize); 
header('Content-Disposition: attachment; filename="'.$filename.'"'); 
header("Cache-Control: private, must-revalidate"); 
header("Pragma: no-cache");
header("Expires: 0");

if ($R['url']==$d['blog']['ftp_urlpath'])
{
	$FTP_CONNECT = ftp_connect($d['blog']['ftp_host'],$d['blog']['ftp_port']); 
	$FTP_CRESULT = ftp_login($FTP_CONNECT,$d['blog']['ftp_user'],$d['blog']['ftp_pass']); 
	if ($d['blog']['ftp_pasv']) ftp_pasv($FTP_CONNECT, true);
	if (!$FTP_CONNECT) getLink('','','FTP서버 연결에 문제가 발생했습니다.','');
	if (!$FTP_CRESULT) getLink('','','FTP서버 아이디나 패스워드가 일치하지 않습니다.','');
	
	$filepath = $g['path_tmp'].'session/'.$filetmpname;
	ftp_get($FTP_CONNECT,$filepath,$d['blog']['ftp_folder'].$R['folder'].'/'.$filetmpname,FTP_BINARY);
	ftp_close($FTP_CONNECT);
	$fp = fopen($filepath, 'rb');
	if (!fpassthru($fp)) fclose($fp);
	unlink($filepath);
}
else {
	$fp = fopen($filepath, 'rb');
	if (!fpassthru($fp)) fclose($fp);
}
exit;
?>