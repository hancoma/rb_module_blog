<?php
if(!defined('__KIMS__')) exit;

include $g['dir_module'].'var/var.php';

if ($upload == 'Y')
{
	if (!$mod) exit;
	$iframe = 'Y';
	$g['dir_module_skin'] = $g['dir_module'].'lib/uploader/';
	$g['url_module_skin'] = $g['url_module'].'/lib/uploader';
	$g['img_module_skin'] = $g['url_module_skin'].'/image';
	$g['dir_module_mode'] = $g['dir_module_skin'].$mod;
	$g['url_module_mode'] = $g['url_module_skin'].'/'.$mod;
	$g['main'] = $g['dir_module_mode'].'.php';
}
else if ($admin == 'Y')
{
	if (!$mod) exit;
	$iframe = 'Y';
	$B = getUidData($table[$m.'list'],$uid);
	if (!$B['uid']) getLink($g['s'].'/?r='.$r,'','정상적인 접근이 아닙니다.','');
	if (!$my['uid']||$my['uid']!=$B['mbruid']) getLink($g['s'].'/?r='.$r,'','관리권한이 없습니다.','');

	$g['dir_module_skin'] = $g['dir_module'].'lib/admin/';
	$g['url_module_skin'] = $g['url_module'].'/lib/admin';
	$g['img_module_skin'] = $g['url_module_skin'].'/image';
	$g['dir_module_mode'] = $g['dir_module_skin'].$mod;
	$g['url_module_mode'] = $g['url_module_skin'].'/'.$mod;
	$g['main'] = $g['dir_module_mode'].'.php';
}
else {
	if(!$blog) getLink($g['s'].'/?r='.$r,'','블로그 아이디가 지정되지 않았습니다.',''); 
	$B = getDbData($table[$m.'list'],"id='".$blog."'",'*');
	if (!$B['uid']) getLink($g['s'].'/?r='.$r,'','존재하지 않는 블로그입니다.','');
	include $g['dir_module'].'var/var.'.$B['id'].'.php';

	$g['meta_tit'] = $_HS['name'].' - '.$B['name'];
	$g['meta_sbj'] = $B['name'];
	$g['meta_key'] = $B['name'];
	if(!$_HS['titlefix']&&!$_HM['uid']) $g['browtitle'] = $_HS['title'].' - '.strip_tags($B['name']);

	$d['blog']['theme'] = $d['blog']['theme_pc'] ? $d['blog']['theme_pc'] : $d['blog']['s_theme_pc'];
	// 모바일 디바이스 접속 & PC모드 아님
	if ($g['mobile']&&$_SESSION['pcmode']!='Y')
	{
		$d['blog']['theme'] = $d['blog']['theme_mobile'] ? $d['blog']['theme_mobile'] : ($d['blog']['s_theme_mobile'] ? $d['blog']['s_theme_mobile'] : $d['blog']['theme_pc']);
	}

	$_HM['layout'] = $d['blog']['layout'];
	$iframe = $d['blog']['iframe'];
	$front = $front ? $front : 'main';
	$C['vtype'] = $d['blog']['vtype'];
	$C['recnum'] = $d['blog']['recnum'];
	$C['vopen'] = $d['blog']['vopen'];

	$CXA = array();
	include $g['dir_module'].'lib/tree.func.php';
	$ISCAT = getDbRows($table[$m.'category'],'blog='.$B['uid']);
	if($rwcat)
	{
		$_rwcat = getDbData($table[$m.'category'],'blog='.$B['uid']." and id='".$rwcat."'",'uid');
		$cat = $_rwcat['uid'];
	}
	if($cat)
	{
		if($front!='write'&&$front!='meta') $front = 'list';
		$C = getUidData($table[$m.'category'],$cat);
		$ctarr = getMenuCodeToPathBlog($table[$m.'category'],$cat,0);
		$ctnum = count($ctarr);
		for ($i = 0; $i < $ctnum; $i++) $CXA[] = $ctarr[$i]['uid'];
		$vtype = $vtype ? $vtype : $C['vtype'];
	}
	$vtype = $vtype ? $vtype : $C['vtype'];

	if ($C['vopen'] && $front != 'main' && $front != 'write' && !$uid && !$keyword)
	{
		$uid = getDbCnt($table[$m.'data'],'max(uid)','isreserve=0');
	}

	$d['blog']['writeperm'] = !$my['uid'] || ($my['uid']!=$B['mbruid'] && !strpos('_,'.$B['members'].',',','.$my['id'].',')) ? false : true;

	$_RSV = getDbArray($table[$m.'data'],$table[$m.'data'].'.blog='.$B['uid'].' and '.$table[$m.'data'].'.isreserve=1','*','gid','asc',0,1);
	while($_R=db_fetch_array($_RSV))
	{
		if ($_R['d_regis'] < $date['totime'])
		{
			getDbUpdate($table[$m.'data'],'isreserve=0','uid='.$_R['uid']);
			$_orign_category_members = getDbArray($table[$m.'catidx'],'parent='.$_R['uid'],'*','uid','asc',0,1);
			while($_ocm=db_fetch_array($_orign_category_members))
			{
				getDbUpdate($table[$m.'category'],'num_open=num_open+1,num_reserve=num_reserve-1','uid='.$_ocm['category']);
			}
		}
	}

	if ($front == 'list')
	{
		$recnum = $recnum ? $recnum : $C['recnum'];
		$sort = 'gid';
		$orderby = 'asc';
		$blogque = $table[$m.'data'].'.blog='.$B['uid'].($d['blog']['writeperm']?'':' and '.$table[$m.'data'].'.isreserve=0');
		if ($where && $keyword)
		{
			if (strlen($_keyword)>2)
			{
				if ($where == 'term') $blogque .= ' and '.$table[$m.'data'].".d_regis like '".$keyword."%'";
				else $blogque .= getSearchSql($where,$keyword,$ikeyword,'or');
			}
			else {
				$blogque .= ' and '.$table[$m.'data'].'.uid=0';
			}
		}
		if ($cat)
		{
			$blogque .= ' and ('.$table[$m.'catidx'].'.category='.$cat.' and '.$table[$m.'data'].'.uid='.$table[$m.'catidx'].'.parent)';
			$NUM = getDbRows($table[$m.'catidx'].','.$table[$m.'data'],$blogque);
			$RCD = getDbArray($table[$m.'catidx'].','.$table[$m.'data'],$blogque,'*',$sort,$orderby,$recnum,$p);
		}
		else {
			$NUM = getDbRows($table[$m.'data'],$blogque);
			$RCD = getDbArray($table[$m.'data'],$blogque,'*',$sort,$orderby,$recnum,$p);
		}
		$TPG = getTotalPage($NUM,$recnum);
	}

	if ($uid)
	{
		if($front!='write') $front = 'list';
		$R = getUidData($table[$m.'data'],$uid);
		if (!$R['uid']) getLink($g['s'].'/?r='.$r.'&m='.$m,'','존재하지 않는 포스트입니다.','');

		$_SEO = getDbData($table[$m.'seo'],'parent='.$R['uid'],'*');
		if ($_SEO['uid'])
		{
			$g['meta_tit'] = $_SEO['title'];
			$g['meta_sbj'] = $_SEO['subject'];
			$g['meta_key'] = $_SEO['keywords'];
			$g['meta_des'] = $_SEO['description'];
			$g['meta_cla'] = $_SEO['classification'];
			$g['meta_rep'] = $_SEO['replyto'];
			$g['meta_lan'] = $_SEO['language'];
			$g['meta_bui'] = $_SEO['build'];
		}
		else {
			$g['meta_tit'] = $_HS['name'].' - '.$R['subject'];
			$g['meta_sbj'] = $R['subject'];
			$g['meta_key'] = $R['tag']?$R['tag']:$R['subject'];
			if(!$_HS['titlefix']&&!$_HM['uid']) $g['browtitle'] = $_HS['title'].' - '.strip_tags($R['subject']);
		}


		if (!strpos('_'.$_SESSION['module_'.$m.'_view'],'['.$R['uid'].']'))
		{
			getDbUpdate($table[$m.'data'],'hit=hit+1','uid='.$R['uid']);
			$_SESSION['module_'.$m.'_view'] .= '['.$R['uid'].']';
		}

	}

	$g['blog_home'] = $g['s'].'/?r='.$r.'&amp;m='.$m.'&amp;blog='.$B['id'];
	$g['blog_front']= $g['blog_home'].'&amp;front=';
	$g['blog_cat']	= $g['blog_home'].'&amp;cat=';
	$g['blog_base'] = $g['blog_cat'].$cat.'&amp;front=list&amp;vtype='.$vtype.'&amp;recnum='.$recnum.'&amp;where='.$where.'&amp;keyword='.urlencode($_keyword);
	$g['blog_act']  = $g['blog_home'].$cat.'&amp;vtype='.$vtype.'&amp;recnum='.$recnum.'&amp;where='.$where.'&amp;keyword='.urlencode($_keyword).'&amp;a=';
	$g['blog_view'] = $g['blog_base'].'&amp;p='.$p.'&amp;uid=';
	$g['pagelink']	= $g['blog_base'];

	if ($d['blog']['rewrite'])
	{
		$g['blog_home_rw'] = $g['s'].'/blog/'.$B['id'];
		if($front=='main')
		{
			$g['blog_view'] = $g['blog_home_rw'].'/';
		}
	}

	$g['dir_module_skin'] = $g['dir_module'].'theme/'.$d['blog']['theme'].'/';
	$g['url_module_skin'] = $g['url_module'].'/theme/'.$d['blog']['theme'];
	$g['img_module_skin'] = $g['url_module_skin'].'/image';

	$g['dir_module_mode'] = $g['dir_module_skin'].$front;
	$g['url_module_mode'] = $g['url_module_skin'].'/'.$front;
	
	$g['main'] = $g['dir_module_skin'].($iframe=='Y'?'_fullLayout':'_singleLayout').'.php';

}
?>