<?php if($d['blog']['writeperm']):?>
<div class="admin" style="width:<?php echo $d['b_layout']['side_width']?>px;">
<?php if($my['admin']):?>
<a href="#." onclick="crLayer('블로그 카테고리','<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=admin&amp;module=<?php echo $m?>&amp;front=makecategory&amp;iframe=Y&amp;uid=<?php echo $B['uid']?>','iframe',800,650,'5%');" class="config">추가</a>
<a href="#." onclick="crLayer('블로그 설정','<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=admin&amp;module=<?php echo $m?>&amp;front=makeblog&amp;iframe=Y&amp;uid=<?php echo $B['uid']?>','iframe',800,650,'5%');" class="config">설정</a>
<?php else:?>
<?php if($my['uid']==$B['mbruid']):?>
<a href="#." onclick="crLayer('블로그 카테고리','<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;admin=Y&amp;mod=makecategory&amp;uid=<?php echo $B['uid']?>','iframe',800,650,'5%');" class="config">추가</a>
<a href="#." onclick="crLayer('블로그 설정','<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;admin=Y&amp;mod=makeblog&amp;uid=<?php echo $B['uid']?>','iframe',800,650,'5%');" class="config">설정</a>
<?php endif?>
<?php endif?>
<a href="<?php echo $g['blog_front']?>admin" class="config">꾸미기</a>
<a href="<?php echo $g['blog_front']?>write&amp;cat=<?php echo $cat?>">글쓰기</a>
</div>
<?php endif?>




<?php if($d['b_layout']['dsp_category']):if($front=='write')$checkbox=true?>
<div class="tbox">
	<div class="treetop"><a href="<?php if($g['blog_home_rw']):?><?php echo $g['blog_home_rw']?>/c/0<?php else:?><?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;blog=<?php echo $blog?>&amp;front=list<?php endif?>">카테고리</a></div>
	<div class="joinimg"></div>
	<div class="tree">

	<?php if($ISCAT):?>
	<script type="text/javascript">
	//<![CDATA[
	var TreeImg = "<?php echo $g['img_core']?>/tree/default_none";
	var ulink = "<?php if($g['blog_home_rw']):?><?php echo $g['blog_home_rw']?>/c/<?php else:?><?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;blog=<?php echo $blog?>&amp;cat=<?php endif?>";
	//]]>
	</script>
	<script type="text/javascript" src="<?php echo $g['s']?>/_core/js/tree.js"></script>
	<script type="text/javascript">
	//<![CDATA[
	var TREE_ITEMS = [['', null, <?php getMenuShowBlog($B['uid'],$table[$m.'category'],0,0,0,$cat,$CXA,0)?>]];
	new tree(TREE_ITEMS, tree_tpl);
	<?php echo $MenuOpen?>
	//]]>
	</script>
	<?php else:?>
	<div class="none">카테고리가 없습니다.</div>
	<?php endif?>
	</div>
</div>
<div class="btm"></div>
<?php endif?>




<?php if($d['b_layout']['dsp_photo']):?>
<div class="title">
	<span>최근사진</span>
	<a></a>
	<div class="clear"></div>
</div>
<div class="photo">
	<?php $_RCD=getDbArray($table[$m.'data'],'blog='.$B['uid'].' and isreserve=0 and isphoto=1','*','gid','asc',$d['b_layout']['dsp_photo_num'],1);?>
	<?php for($i = 0; $i < $d['b_layout']['dsp_photo_num']; $i++):?>
	<?php $_R=db_fetch_array($_RCD)?>
	<?php $_TU=getArrayString($_R['upload'])?>
	<?php $_U=getUidData($table[$m.'upload'],$_TU['data'][0])?>
	<div class="pic">
		<?php if($_U['uid']):?>
		<a href="<?php echo $g['blog_view'].$_R['uid']?>"><img src="<?php echo $_U['url'].$_U['folder'].'/'.$_U['thumbname']?>" alt="" title="<?php echo $_U['caption']?$_U['caption']:$_R['subject']?>" /></a>
		<?php else:?>
		<img src="<?php echo $g['img_core']?>/blank.gif" alt="" />
		<?php endif?>
	</div>
	<?php endfor?>
	<div class="clear"></div>
</div>
<div class="btm"></div>
<?php endif?>




<?php if($d['b_layout']['dsp_post']):?>
<div class="title">최근포스트</div>
<div class="post">
	<ul>
	<?php $_RCD=getDbArray($table[$m.'data'],'blog='.$B['uid'].' and isreserve=0','*','gid','asc',$d['b_layout']['dsp_post_num'],1);?>
	<?php while($_R=db_fetch_array($_RCD)):?>
	<?php $L=getOverTime($date['totime'],$_R['d_regis'])?>
	<li>
		<a href="<?php echo $g['blog_view'].$_R['uid']?>"><?php echo $_R['subject']?></a><?php if($_R['comment']+$_R['oneline']):?><span>(<?php echo $_R['comment']+$_R['oneline']?>)</span><?php endif?>
		<i><?php echo $L[1]<4?$L[0].$lang['sys']['time'][$L[1]].'전':getDateFormat($_R['d_regis'],'Y.m.d H:i')?></i>
		<?php if(getNew($_R['d_regis'],24)):?><u>new</u><?php endif?>
	</li>
	<?php endwhile?>
	</ul>
</div>
<div class="btm"></div>
<?php endif?>




<?php if($d['b_layout']['dsp_comment']):?>
<div class="title">최근댓글</div>
<div class="post">
	<ul>
	<?php $_RCD=getDbArray($table[$m.'comment'],'blog='.$B['uid'].' and hidden=0','*','uid','asc',$d['b_layout']['dsp_comment_num'],1);?>
	<?php while($_R=db_fetch_array($_RCD)):?>
	<?php $L=getOverTime($date['totime'],$_R['d_regis'])?>
	<li>
		<a href="<?php echo $g['blog_view'].$_R['parent']?><?php if(!$g['blog_home_rw']):?>&amp;comment=<?php echo $_R['uid']?><?php endif?>"><?php echo getStrCut($_R['content'],50,'..')?></a><?php if($_R['oneline']):?><span>(<?php echo $_R['oneline']?>)</span><?php endif?>
		<i><?php echo $L[1]<4?$L[0].$lang['sys']['time'][$L[1]].'전':getDateFormat($_R['d_regis'],'Y.m.d H:i')?> , <?php echo $_R['name']?>님</i>
		<?php if(getNew($_R['d_regis'],24)):?><u>new</u><?php endif?>
	</li>
	<?php endwhile?>
	</ul>
</div>
<div class="btm"></div>
<?php endif?>




<?php if($d['b_layout']['dsp_tag']):?>
<div class="title">태그</div>
<div class="tags">

	<?php $d_regis1 = date('Ymd',mktime(0,0,0,substr($date['today'],4,2),substr($date['today'],6,2)-7,substr($date['today'],0,4)))?>
	<?php $d_regis2 = $date['today']?>
	<?php $WHEREIS1 = 'site='.$s.' and date between '.$d_regis1.' and '.$d_regis2?>
	<?php $_RCD=getDbArray($table['s_tag'],$WHEREIS1,'*','hit','desc',20,1)?>
	<?php while($TG=db_fetch_array($_RCD)):?>
	<?php $TARR[]=$TG['keyword']?>
	<?php endwhile?>
	<?php $TGN=count($TARR)?>
	<?php if($TGN)$rkey = array_rand($TARR,$TGN)?>
	
	<?php $x2 = 0?>
	<?php $x1 = 5?>
	<?php for($i = 0; $i < $TGN; $i++):?>
	<?php $TCNUM=$rkey[$i]>$x1?1:($rkey[$i]>$x2?2:3)?>

	<a href="<?php echo $g['blog_front']?>list&amp;where=<?php echo $table[$m.'data']?>.tag&amp;keyword=<?php echo urlencode($TARR[$rkey[$i]])?>"><span class="tags_<?php echo $TCNUM?>"><?php echo $TARR[$rkey[$i]]?></span></a>
	<?php endfor?>

</div>
<div class="btm"></div>
<?php endif?>




<?php if($d['b_layout']['dsp_calendar']):?>
<?php
if ($where == 'term' && $keyword)
{
	$_yr = substr($keyword,0,4);
	$_mh = substr($keyword,4,2);
}
else {
	$_yr	= $_yr ? $_yr : $date['year'];
	$_mh	= $_mh ? $_mh : substr($date['month'],4,2);
}
$todaynum	= $date['today'];
$firstday	= mktime(0,0,0,$_mh,1,$_yr);
$startday	= date('w',$firstday);
$endday		= date('t',$firstday);

$prevyear	= date('Y',mktime(0,0,0,$_mh-1,1,$_yr));
$nextyear	= date('Y',mktime(0,0,0,$_mh+1,1,$_yr));
$prevmonth	= date('m',mktime(0,0,0,$_mh-1,1,$_yr));
$nextmonth	= date('m',mktime(0,0,0,$_mh+1,1,$_yr));

$DAYNUM = getDbSelect($table[$m.'day'],"date like '".$_yr.$_mh."%' and blog=".$B['uid'].' order by date asc','*');
while($_R=db_fetch_array($DAYNUM)) if($_R['num']) $_D[$_R['date']] = $_R['num'];
?>

<div class="daily">
	<a name="calendar"></a>
	<div class="tt">
		<a href="<?php echo $g['blog_home']?>&amp;_yr=<?php echo $prevyear?>&amp;_mh=<?php echo $prevmonth?>#calendar"><img src="<?php echo $g['img_module_skin']?>/b_prev.gif" alt="" title="이전달" /></a>
		<?php echo $_yr?>.<?php echo $_mh?>
		<a href="<?php echo $g['blog_home']?>&amp;_yr=<?php echo $nextyear?>&amp;_mh=<?php echo $nextmonth?>#calendar"><img src="<?php echo $g['img_module_skin']?>/b_next.gif" alt="" title="다음달" /></a>
	</div>
	<table> 
		<tr class="week">
			<td class="sunday">일</td>
			<td>월</td>
			<td>화</td>
			<td>수</td>
			<td>목</td>
			<td>금</td>
			<td>토</td>
		</tr>
	<?php for( $i = 1; $i < 7; $i++ ):?>
		<tr align="center" height="20"> 
		<?php for( $j = 0; $j < 7; $j++ ):$xday = $day+1; $pday = sprintf("%02d",$xday); $onday = $_yr.$_mh.$pday?>
			<td width="14%">
			<?php if((($i == 1 && $startday == $j) || $day > 0) && $day < $endday): $day++?>
			<?php if($_D[$onday]):?>
			<a href="<?php echo $g['blog_front']?>list&amp;where=term&amp;keyword=<?php echo $onday?>" title="<?php echo $_D[$onday]?> Post">
				<?php if($j == 0):?>
				<span class="sunday b"><?php echo $pday?></span>
				<?php else:?>
				<span class="b"><?php echo $pday?></span>
				<?php endif?>
			</a>
			<?php else:?>
				<?php if($j == 0):?>
				<span class="sunday"><?php echo $pday?></span>
				<?php else:?>
				<?php echo $pday?>
				<?php endif?>
			<?php endif?>
			<?php else:?>&nbsp;<?php endif?>
			</td>
		<?php endfor?>
		</tr>
	<?php if($day >= $endday) break?>
	<?php endfor?>
	</table>
</div>
<div class="btm"></div>
<?php endif?>




<?php if($d['b_layout']['dsp_twitter']):?>
<script type="text/javascript" src="http://widgets.twimg.com/j/2/widget.js"></script>
<script type="text/javascript">
//<![CDATA[
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 10,
  interval: 5000,
  width: <?php echo $d['b_layout']['side_width']?>,
  height: 200,
  theme: {
    shell: {
      background: '#dfdfdf',
      color: '#333333'
    },
    tweets: {
      background: '#ffffff',
      color: '#666666',
      links: '#2276BB'
    }
  },
  features: {
    scrollbar: true,
    loop: true,
    live: true,
    hashtags: true,
    timestamp: true,
    avatars: false,
    behavior: 'alternative'
  }
}).render().setUser('<?php echo $d['b_layout']['dsp_twitter_id']?>').start();
//]]>
</script>
<div class="btm"></div>
<?php endif?>


<?php if($d['b_layout']['dsp_facebook']):?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ko_KR/all.js#xfbml=1&appId=<?php echo $d['b_layout']['dsp_facebook_id']?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-activity" data-width="<?php echo $d['b_layout']['side_width']?>" data-height="300" data-header="true" data-recommendations="false"></div>
<div class="btm"></div>
<?php endif?>



<?php if($d['b_layout']['adtype']):?>
<div class="banner">
<?php if($d['b_layout']['adtype']==1):?><a href="<?php echo $d['b_layout']['ad_imglink']?>" target="<?php echo $d['b_layout']['ad_imgtarget']?>"><img src="<?php echo $g['url_module_skin'].'/_var/'.$d['b_layout']['ad_img']?>" alt="" style="max-width:<?php echo $d['b_layout']['side_width']?>px;" /></a><?php endif?>
<?php if($d['b_layout']['adtype']==2):?><embed src="<?php echo $g['url_module_skin'].'/_var/'.$d['b_layout']['ad_swf']?>" style="max-width:<?php echo $d['b_layout']['side_width']?>px;"></embed><?php endif?>
<?php if($d['b_layout']['adtype']==3) include $g['dir_module_skin'].'_var/_ad.txt'?>
</div>
<div class="btm"></div>
<?php endif?>




<?php if($d['b_layout']['dsp_counter']):?>
<div class="counter">
Total : <?php echo number_format(getDbCnt($table['s_counter'],'sum(hit)','site='.$s))?><br />
Yesterday : <?php echo number_format(getDbCnt($table['s_counter'],'sum(hit)','site='.$s." and date='".getDateCal('Ymd',$date['totime'],-24)."'"))?><br />
Today : <?php echo number_format(getDbCnt($table['s_counter'],'sum(hit)','site='.$s." and date='".$date['today']."'"))?><br />
</div>
<div class="btm"></div>
<?php endif?>




<?php if($d['b_layout']['dsp_rss']):?>
<div class="rss">
	<img src="<?php echo $g['img_core']?>/_public/btn_rss_gray.gif" alt="rss" />
	<a href="<?php echo $g['s']?>/?m=<?php echo $m?>&amp;blog=<?php echo $B['uid']?>&amp;a=rss&amp;type=rss2" target="_blank">RSS 2.0</a> |
	<a href="<?php echo $g['s']?>/?m=<?php echo $m?>&amp;blog=<?php echo $B['uid']?>&amp;a=rss&amp;type=rss1" target="_blank">RSS 1.0</a> |
	<a href="<?php echo $g['s']?>/?m=<?php echo $m?>&amp;blog=<?php echo $B['uid']?>&amp;a=rss&amp;type=atom" target="_blank">ATOM 0.3</a>
</div>
<div class="btm"></div>
<?php endif?>


