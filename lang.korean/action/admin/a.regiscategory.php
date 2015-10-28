<?php
if(!defined('__KIMS__')) exit;

checkAdmin(0);

$metaurl = trim($metaurl);
$id = trim($id);

include $g['path_module'].$m.'/lib/tree.func.php';

if ($cat && !$vtype)
{
	$R = getUidData($table[$m.'category'],$cat);

	if ($id != $R['id'])
	{
		$ISMCODE = getDbData($table[$m.'category'],"id='".$id."' and blog=".$blog,'*');
		if ($ISMCODE['uid']) getLink('','','카테고리코드 ['.$ISMCODE['id'].'] 는 다른카테고리 ['.$ISMCODE['name'].'] 에서 사용중입니다.','');
	}

	$QVAL = "metaurl='$metaurl',metause='$metause',id='$id',name='$name',mobile='$mobile',hidden='$hidden',vtype='$vtype1',recnum='$recnum',vopen='$vopen'";
	getDbUpdate($table[$m.'category'],$QVAL,'uid='.$cat);
	getLink('reload','parent.','변경되었습니다.','');
}
else {

	$MAXC = getDbCnt($table[$m.'category'],'max(gid)','depth='.($depth+1).' and parent='.$parent);
	$sarr = explode(',' , trim($name));
	$slen = count($sarr);

	for ($i = 0 ; $i < $slen; $i++)
	{
		if (!$sarr[$i]) continue;

		$gid	= $MAXC+1+$i;
		$xdepth	= $depth+1;
		$xname	= trim($sarr[$i]);
		$xnarr	= explode('=',$xname);

		$QKEY = "gid,blog,metaurl,metause,isson,parent,depth,id,name,mobile,hidden,num_open,num_reserve,vtype,recnum,vopen,d_last";
		$QVAL = "'$gid','$blog','$metaurl','$metause','0','$parent','$xdepth','$xnarr[1]','$xnarr[0]','$mobile','$hidden','0','0','$vtype1','$recnum','$vopen',''";

		getDbInsert($table[$m.'category'],$QKEY,$QVAL);
		$lastmenu = getDbCnt($table[$m.'category'],'max(uid)','');

		if (!$xnarr[1])
		{
			getDbUpdate($table[$m.'category'],"id='".$lastmenu."'",'uid='.$lastmenu);
		}
		else {
			$ISMCODE = getDbData($table[$m.'category'],"uid<> ".$lastmenu." and id='".$xnarr[1]."' and blog=".$blog,'*');
			if ($ISMCODE['uid'])
			{
				getDbUpdate($table[$m.'category'],"id='".$lastmenu."'",'uid='.$lastmenu);
			}
		}
	}

	if ($parent)
	{
		getDbUpdate($table[$m.'category'],'isson=1','uid='.$parent);
	}
	db_query("OPTIMIZE TABLE ".$table[$m.'category'],$DB_CONNECT); 

	getLink($g['s'].'/?r='.$r.'&m=admin&module='.$m.'&front=makecategory&iframe=Y'.($parent?'&cat='.$parent:'').'&uid='.$blog,'parent.','','');
}
?>