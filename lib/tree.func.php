<?php
//메뉴출력
function getMenuShowBlog($blog,$table,$j,$parent,$depth,$uid,$CXA,$hidden)
{
	global $cat;
	global $MenuOpen,$numhidden,$checkbox,$headfoot;
	static $j;

	$CD=getDbSelect($table,($blog?'blog='.$blog.' and ':'').'depth='.($depth+1).' and parent='.$parent.($hidden ? ' and hidden=0':'').' order by gid asc','*');
	while($C=db_fetch_array($CD))
	{
		$j++;
		if(@in_array($C['uid'],$CXA)) $MenuOpen .= 'trees[0].tmB('.$j.');';
		$numprintx = !$numhidden && $C['num_open'] ? '&lt;span class="num"&gt;('.$C['num_open'].')&lt;/span&gt;' : '';
		if($GLOBALS['d']['blog']['writeperm']) $numprintx.= !$numhidden && $C['num_reserve'] ? '&lt;span class="num1"&gt;('.$C['num_reserve'].')&lt;/span&gt;' : '';
		$C['name'] = $headfoot && ($C['imghead']||$C['imgfoot']||$C['codhead']||$C['codfoot']) ? '&lt;b&gt;'.$C['name'].'&lt;b&gt;' : $C['name'];
		$name = $C['uid'] != $cat ? addslashes($C['name']): '&lt;span class="on"&gt;'.addslashes($C['name']).'&lt;/span&gt;';

		if($checkbox)
		{
			$icon1 = '&lt;input type="checkbox" name="category_members[]" value="'.$C['uid'].'"'.($cat==$C['uid']||getDbRows($GLOBALS['table'][$GLOBALS['m'].'catidx'],'category='.$C['uid'].' and parent='.$GLOBALS['R']['uid'])?' checked="checked"':'').' /&gt;';
		}
		$icon2 = $C['mobile'] ? ' &lt;img src="'.$GLOBALS['g']['img_core'].'/_public/ico_mobile.gif" class="mobile" alt="" /&gt;' : '';
		$icon3 = $C['reject'] ? ' &lt;img src="'.$GLOBALS['g']['img_core'].'/_public/ico_hidden.gif" alt="" /&gt;' : '';

		if ($C['isson'])
		{
			echo "['".$icon1.$name.$icon2.$numprintx."','".($GLOBALS['g']['blog_home_rw']?$C['id']:$C['uid'])."',";
			getMenuShowBlog($blog,$table,$j,$C['uid'],$C['depth'],$uid,$CXA,$hidden);
			echo "],\n";
		}
		else {
			echo "['".$icon1.$name.$icon2.$icon3.$numprintx."','".($GLOBALS['g']['blog_home_rw']?$C['id']:$C['uid'])."',''],\n";
		}
	}
}
//메뉴코드->경로
function getMenuCodeToPathBlog($table,$cat,$j)
{
	global $DB_CONNECT;
	static $arr;

	$R=getUidData($table,$cat);
	if($R['parent'])
	{
		$arr[$j]['uid'] = $R['uid'];
		$arr[$j]['id'] = $R['id'];
		$arr[$j]['name']= $R['name'];
		getMenuCodeToPathBlog($table,$R['parent'],$j+1);
	}
	else {
		$C=getUidData($table,$cat);
		$arr[$j]['uid'] = $C['uid'];
		$arr[$j]['id'] = $C['id'];
		$arr[$j]['name']= $C['name'];
	}
	sort($arr);
	reset($arr);
	return $arr;
}
//메뉴코드->SQL
function getMenuCodeToSqlBlog($table,$cat,$f)
{
	static $sql;

	$R=getUidData($table,$cat);
	if ($R['uid']) $sql .= $f.'='.$R['uid'].' or ';
	if ($R['isson'])
	{
		$RDATA=getDbSelect($table,'parent='.$R['uid'],'uid');
		while($C=db_fetch_array($RDATA)) getMenuCodeToSqlBlog($table,$C['uid'],$f);
	}
	return substr($sql,0,strlen($sql)-4);
}
function getMenuCodeToSqlBlog1($tbl,$cat,$blog,$sql)
{
	global $sql;
	$R=getUidData($tbl,$cat);
	if(!strstr($sql,'['.$R['uid'].']')) $sql = $sql.'['.$R['uid'].']';
	if($R['parent'])
	{
		$C=getUidData($tbl,$R['parent']);
		if(!strstr($sql,'['.$C['uid'].']')) $sql = $sql.'['.$C['uid'].']';
		getMenuCodeToSqlBlog1($tbl,$C['uid'],$blog,$sql);
	}
	return $sql;
}
function getMenuCodeToSqlBlog2($tbl,$cat,$blog,$sql)
{
	global $sql;
	$R=getUidData($tbl,$cat);
	if(!strstr($sql,'['.$R['uid'].']')) $sql = $sql.'['.$R['uid'].']';
	if($R['isson'])
	{
		$RDATA=getDbSelect($tbl,'blog='.$blog.' and parent='.$R['uid'],'uid');
		while($C=db_fetch_array($RDATA))
		{
			if(!strstr($sql,'['.$C['uid'].']')) $sql = $sql.'['.$C['uid'].']';
			getMenuCodeToSqlBlog2($tbl,$C['uid'],$blog,$sql);
		}
	}
	return $sql;
}
?>