<?php
if(!defined('__KIMS__')) exit;

$B = getUidData($table[$m.'list'],$blog);
if (!$B['uid']) getLink($g['s'].'/?r='.$r,'','존재하지 않는 블로그입니다.','');
if (!$my['uid'] || $my['uid']!=$B['mbruid']) getLink($g['s'].'/?r='.$r,'','블로그 관리권한이 없습니다.',''); 

$_tmpdfile = $g['dir_module'].$nowTheme.'/_var/var.'.$B['id'].'.php';
$d['b_layout'] = array();
include $_tmpdfile;

if ($imgType=='logo')
{
	@unlink($g['dir_module'].$nowTheme.'/_var/'.$d['b_layout']['imglogo']);

	$d['b_layout']['imglogo'] = '';
	$d['b_layout']['imglogo_w'] = 0;
	$d['b_layout']['imglogo_h'] = 0;
	$d['b_layout']['imglogo_use'] = '';
}
if ($imgType=='bg')
{
	@unlink($g['dir_module'].$nowTheme.'/_var/'.$d['b_layout']['bg']);

	$d['b_layout']['bg'] = '';
	$d['b_layout']['bg_use'] = '';
}
if ($imgType=='ad_img')
{
	@unlink($g['dir_module'].$nowTheme.'/_var/'.$d['b_layout']['ad_img']);

	$d['b_layout']['ad_img'] = '';
}
if ($imgType=='ad_swf')
{
	@unlink($g['dir_module'].$nowTheme.'/_var/'.$d['b_layout']['ad_swf']);

	$d['b_layout']['ad_swf'] = '';
}

$fp = fopen($_tmpdfile,'w');
fwrite($fp, "<?php\n");
foreach ($d['b_layout'] as $key => $val)
{
	fwrite($fp, "\$d['b_layout']['".$key."'] = \"".$val."\";\n");
}
fwrite($fp, "?>");
fclose($fp);
@chmod($_tmpdfile,0707);


getLink('reload','parent.','','');
?>