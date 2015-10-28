<?php
function getBlogLogo($blog)
{
	if ($blog['imglogo_use'])
	{
		return '<a href="'.($GLOBALS['g']['blog_home_rw']?$GLOBALS['g']['blog_home_rw']:$GLOBALS['g']['blog_home']).'" class="_logo_img"><img src="'.$GLOBALS['g']['url_module'].'/theme/'.$GLOBALS['d']['blog']['theme'].'/_var/'.$blog['imglogo'].'" width="'.$blog['imglogo_w'].'" height="'.$blog['imglogo_h'].'" alt="" /></a>';
	}
	else {
		return '<h1 class="_logo_txt"><a id="_layout_title_color_" href="'.($GLOBALS['g']['blog_home_rw']?$GLOBALS['g']['blog_home_rw']:$GLOBALS['g']['blog_home']).'" style="font-family:'.$blog['title_font'].';font-size:'.$blog['title_size'].'px;color:'.$blog['title_color'].';">'.$blog['title'].'</a></h1>';
	}
}

$d['blog']['vartheme'] = $g['dir_module_skin'].'_var/var.'.$B['id'].'.php';
if (!is_file($d['blog']['vartheme']))
{
	copy($g['dir_module_skin'].'_var/_var.php',$d['blog']['vartheme']);
	@chmod($d['blog']['vartheme'],0707);
}
include $d['blog']['vartheme'];
?>

<div id="_layout"<?php if($d['b_layout']['bg_use']):?> style="background:url('<?php echo $g['url_module'].'/theme/'.$d['blog']['theme'].'/_var/'.$d['b_layout']['bg']?>') <?php echo $d['b_layout']['bg_o']?>;"<?php endif?>>
<div class="_header" style="border-top:<?php echo $d['b_layout']['bg_header_bt']?> solid <?php echo $d['b_layout']['height_header_bt']?>px;border-bottom:<?php echo $d['b_layout']['bg_header_bb']?> solid <?php echo $d['b_layout']['height_header_bb']?>px;<?php if(!$d['b_layout']['bg_use']):?>background:<?php echo $d['b_layout']['bg_header']?>;<?php endif?>">
	<div class="wrap" style="margin:<?php echo $d['b_layout']['layout_align']?>;width:<?php echo $d['b_layout']['layout_width']?>;height:<?php echo $d['b_layout']['height_header']?>px;<?php if($d['b_layout']['height_header_bb']):?>border-bottom:0;<?php endif?>">
		<div class="logo" style="top:<?php echo $d['b_layout']['title_t']?>px;">
			<?php echo getBlogLogo($d['b_layout'])?>
		</div>
		<?php if($d['b_layout']['dsp_search']):?>
		<div class="search" style="top:<?php echo $d['b_layout']['title_s']?>px;">
			<form action="<?php echo $g['s']?>/" method="get" id="_layout_search_border_" style="border:<?php echo $d['b_layout']['search_border']?> solid 1px;">
			<input type="hidden" name="r" value="<?php echo $r?>" />
			<input type="hidden" name="m" value="<?php echo $m?>" />
			<input type="hidden" name="blog" value="<?php echo $B['id']?>" />
			<input type="hidden" name="front" value="list" />
			<input type="hidden" name="where" value="<?php echo $table[$m.'data']?>.subject|<?php echo $table[$m.'data']?>.tag" />
			<input type="text" name="keyword" placeholder="통합검색" class="keyword<?php if($_keyword):?> done<?php endif?>" value="<?php echo $_keyword?>" />
			<input type="image" src="<?php echo $g['img_module_skin']?>/btn_search.gif" class="sbtn" alt="search" />
			</form>
		</div>
		<?php endif?>
		<div id="_layout_memberlink_" class="login" style="top:<?php echo $d['b_layout']['title_b']?>px;">
			<a href="<?php echo $g['s']?>/?r=<?php echo $r?>" style="color:<?php echo $d['b_layout']['memberlink_color']?>;">홈</a> <i style="background:<?php echo $d['b_layout']['split_color']?>;"></i> 
			<a href="<?php echo $g['blog_home_rw']?$g['blog_home_rw']:$g['blog_home']?>" style="color:<?php echo $d['b_layout']['memberlink_color']?>;">블로그</a> <i style="background:<?php echo $d['b_layout']['split_color']?>;"></i> 
			<?php if($my['uid']):?>
			<a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;a=logout" style="color:<?php echo $d['b_layout']['memberlink_color']?>;">로그아웃</a>
			<?php else:?>
			<a href="#." onclick="crLayer('로그인','<?php echo $g['s']?>/?r=<?php echo $r?>&system=iframe.login&iframe=Y&referer=<?php echo urlencode($g['s'].'/?'.$_SERVER['QUERY_STRING'])?>','iframe',515,250,'15%');" style="color:<?php echo $d['b_layout']['memberlink_color']?>;">로그인</a>
			<?php endif?>
		</div>
		<div class="clear"></div>
	</div>
</div>

<div id="_container" style="margin:<?php echo $d['b_layout']['layout_align']?>;width:<?php echo $d['b_layout']['layout_width']?>;">

	<table width="100%" cellspacing="0" cellpadding="0">
	<tr valign="top">
	<?php if($d['b_layout']['side_align']=='left'):?>
	<td class="_snb _xleft1" style="width:<?php echo $d['b_layout']['side_width']?>px;">
	<?php include $g['dir_module_skin'].'_side.php'?>
	</td>
	<td class="_content _xleft">
	<?php include $g['dir_module_mode'].'.php'?>
	</td>
	<?php else:?>
	<td class="_content _xright">
	<?php include $g['dir_module_mode'].'.php'?>
	</td>
	<td class="_snb _xright1" style="width:<?php echo $d['b_layout']['side_width']?>px;">
	<?php include $g['dir_module_skin'].'_side.php'?>
	</td>
	<?php endif?>
	</tr>
	</table>

</div>

<div id="_footer" class="_footer">
	<div class="wrap" style="margin:<?php echo $d['b_layout']['layout_align']?>;width:<?php echo $d['b_layout']['layout_width']?>;">
		<div class="copyright">
			Copyright &copy; <?php echo $date['year']?> <?php echo $_SERVER['HTTP_HOST']?>
		</div>
	</div>
</div>
</div>


