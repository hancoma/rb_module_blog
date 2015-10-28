<?php
if(!defined('__KIMS__')) exit;

checkAdmin(0);

$badword = trim($badword);
$badword = str_replace("\r\n","",$badword);
$badword = str_replace("\n","",$badword);
$ftp_port = $ftp_port ? trim($ftp_port) : '21';


$fdset = array(
	's_theme_pc','s_theme_mobile','badword','badword_action','badword_escape',
	'up_maxsize_file','up_maxnum_file','up_maxsize_img','up_maxnum_img','up_name_file','up_name_img','up_ext_file','up_ext_img','up_ext_cut','up_width_img','up_use_classicup',
	'use_fileserver','ftp_host','ftp_port','ftp_user','ftp_pass','ftp_folder','ftp_urlpath','ftp_pasv',
	'c_perm_write','c_snsconnect','c_use_hidden','c_recnum','c_orderby1','c_orderby2','c_onelinedel','c_give_point','c_give_opoint','rewrite'
);

$gfile= $g['dir_module'].'var/var.php';
$fp = fopen($gfile,'w');
fwrite($fp, "<?php\n");
foreach ($fdset as $val)
{
	fwrite($fp, "\$d['blog']['".$val."'] = \"".trim(${$val})."\";\n");
}
fwrite($fp, "?>");
fclose($fp);
@chmod($gfile,0707);



getLink('reload','parent.','','');
?>