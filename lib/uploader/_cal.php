<?php
if(!defined('__KIMS__')) exit;

$S = 0;
$N = 0;

if ($files)
{
	$d['_upload'] = getArrayString($files);
	foreach($d['_upload']['data'] as $_val)
	{
		$U = getUidData($table[$m.'upload'],$_val);
		if ($U['uid'])
		{
			$S+= $U['size'];
			$N++;
		}
	}
}


$P = array();

if (!$_SESSION['upsescode'])
{
	$_SESSION['upsescode'] = str_replace('.','',$g['time_start']);
}

$sescode = $_SESSION['upsescode'];

if ($sescode)
{
	$PHOTOS = getDbArray($table[$m.'upload'],"tmpcode='".$sescode."'",'*','uid','asc',0,0);
	while($R = db_fetch_array($PHOTOS))
	{
		$P[] = $R;
		$S += $R['size'];
		$N++;
	}
}


if ($mod == 'photo')
{
	$d['blog']['up_limitnum'] = $d['blog']['up_maxnum_img'];
	$d['blog']['up_limitsize'] = $d['blog']['up_maxsize_img'];
}
else if ($mod == 'file') {
	$d['blog']['up_limitnum'] = $d['blog']['up_maxnum_file'];
	$d['blog']['up_limitsize'] = $d['blog']['up_maxsize_file'];
}
else {
	$d['blog']['up_limitnum'] = 1;
	$d['blog']['up_limitsize'] = 10;
}
$d['blog']['up_limitsize'] = $d['blog']['up_limitsize'] * 1024 * 1024;


$LimitNum = $d['blog']['up_limitnum'] - $N;
$LimitSize= $d['blog']['up_limitsize'] - $S;

$gparamExp= explode('|',$gparam);
?>