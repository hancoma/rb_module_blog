<?php
if(!defined('__KIMS__')) exit;

if (!$uid) getLink('','','정상적인 접근이 아닙니다.','');
$_BX = getUidData($table[$m.'list'],$uid);
if (!$_BX['uid']) getLink('','','정상적인 접근이 아닙니다.','');
if ($my['uid']!=$_BX['mbruid']) getLink('','','관리권한이 없습니다.','');

if (!$cat) getLink($g['s'].'/?r='.$r.'&m='.$m.'&admin=Y&mod=makecategory&uid='.$uid,'parent.','','');


include $g['path_module'].$m.'/lib/tree.func.php';
$subQue = getMenuCodeToSqlBlog($table[$m.'category'],$cat,'uid');

if ($subQue)
{
	$DAT = getDbSelect($table[$m.'category'],$subQue,'*');
	while($R=db_fetch_array($DAT))
	{
		getDbDelete($table[$m.'category'],'uid='.$R['uid']);
	}
	
	if ($parent)
	{
		if (!getDbRows($table[$m.'category'],'parent='.$parent))
		{
			getDbUpdate($table[$m.'category'],'isson=0','uid='.$parent);
		}
	}
	db_query("OPTIMIZE TABLE ".$table[$m.'category'],$DB_CONNECT); 
}

getLink($g['s'].'/?r='.$r.'&m='.$m.'&admin=Y&mod=makecategory&uid='.$uid.'&cat='.$parent,'parent.','','');
?>