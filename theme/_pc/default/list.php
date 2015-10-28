<div id="mainbox">



	<?php if($R['uid']):?>
	<div class="title">
		<div class="xleft">
		<span class="tt"><?php echo $R['subject']?></span>
		</div>
		<div class="xright">

			<img src="<?php echo $g['img_core']?>/_public/sns_t1.gif" class="hand" alt="twitter" title="twitter로 보내기" onclick="snsWin('t');" />
			<img src="<?php echo $g['img_core']?>/_public/sns_f1.gif" class="hand" alt="facebook" title="facebook으로 보내기" onclick="snsWin('f');" />
			<img src="<?php echo $g['img_core']?>/_public/sns_m1.gif" class="hand" alt="me2day" title="me2day로 보내기" onclick="snsWin('m');" />
			<img src="<?php echo $g['img_core']?>/_public/sns_y1.gif" class="hand" alt="요즘" title="요즘으로 보내기" onclick="snsWin('y');" />

		</div>
		<div class="clear"></div>
	</div>

	<div class="bContent">
		<?php echo getContents($R['content'],'HTML')?>

		<?php if($R['tag']):?>
		<div class="tag">
		<img src="<?php echo $g['img_core']?>/_public/ico_tag.gif" alt="태그" />
		<?php $_tags=explode(',',$R['tag'])?>
		<?php $_tagn=count($_tags)?>
		<?php $i=0;for($i = 0; $i < $_tagn; $i++):?>
		<?php $_tagk=trim($_tags[$i])?>
		<a href="<?php echo $g['blog_front']?>list&amp;where=<?php echo $table[$m.'data']?>.tag&amp;keyword=<?php echo urlencode($_tagk)?>"><?php echo $_tagk?></a><?php if($i < $_tagn-1):?>, <?php endif?>
		<?php endfor?>
		</div>
		<?php endif?>

		<?php if($R['upload']):$d['upload']=getArrayString($R['upload']);?>
		<div class="attach">
		<ul>
		<?php foreach($d['upload']['data'] as $_upuid):?>
		<?php $_u=getUidData($table[$m.'upload'],$_upuid)?>
		<?php if($_u['hidden'])continue?>
		<li>
			<a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;blog=<?php echo $B['uid']?>&amp;a=upload/download&amp;uid=<?php echo $_u['uid']?>" title="<?php echo $_u['caption']?>"><?php echo $_u['name']?></a>
			<span class="size">(<?php echo getSizeFormat($_u['size'],1)?>)</span>
			<span class="down">(<?php echo number_format($_u['down'])?>)</span>
		</li>
		<?php endforeach?>
		</ul>
		</div>
		<?php endif?>

		<?php if($d['blog']['writeperm']):?>
		<div class="admbox">
		<a href="<?php echo $g['blog_front']?>write&amp;uid=<?php echo $R['uid']?>&amp;cat=<?php echo $cat?>&amp;vtype=<?php echo $vtype?>&amp;recnum=<?php echo $recnum?>">수정</a>
		<a href="<?php echo $g['blog_act']?>post_delete&amp;uid=<?php echo $R['uid']?>" onclick="return hrefCheck(this,true,'정말로 삭제하시겠습니까?');">삭제</a>
		</div>
		<?php endif?>
		
		<?php if(!$R['cutcomment']):?>
		<a name="CMT"></a>
		<div id="commentLayer">
			<div class="comment">
				<img src="<?php echo $g['img_module_skin']?>/ico_comment.gif" alt="" class="icon1" />
				댓글 <span id="comment_num<?php echo $R['uid']?>"><?php echo $R['comment']?></span>개
				<?php if(getNew($R['d_comment'],24)):?><img src="<?php echo $g['img_core']?>/_public/ico_new_01.gif" alt="new" /><?php endif?>
			</div>
			<?php include $g['dir_module_skin'].'list.comment.php'?>
		</div>
		<?php endif?>
	</div>
	<?php endif?>










	<div class="title">
		<div class="xleft">
		<?php if($iframe=='Y'):?>
		<span class="tt">
		<?php if($d['blog']['writeperm']&&$C['metause']&&$C['metaurl']):?>
		<a href="<?php echo $g['blog_front']?>meta&amp;cat=<?php echo $C['uid']?>" class="_metaget">자동수집</a>
		<?php endif?>
		블로그
		<?php if($cat):?> &gt; <?php endif?>
		<?php for ($i = 0; $i < $ctnum; $i++): ?>
		<?php echo $ctarr[$i]['name']?>
		<?php if($i < $ctnum-1):?> &gt; <?php endif?> 
		<?php endfor?>
		<?php if($_keyword):?>
		&gt; &quot;<?php echo $_keyword?>&quot; 검색결과
		<?php endif?>
		</span>
		<?php else:?>
		<span class="tt">
		<?php if($_keyword):?>
		검색결과
		<?php else:?>
		<?php echo $cat ? $C['name'] : '블로그'?> &nbsp; 
		<?php endif?>
		</span>
		<div class="_selcat _slist">
			<span class="cb">
				<a class="lk" onclick="catShow();"><img src="<?php echo $g['img_core']?>/_public/ico_under_01.gif" class="ico" alt="" /> 카테고리</a>
				<div id="__catbox__" class="catbox">
					<div class="catwrap">
						<div class="treetop"><a href="<?php if($g['blog_home_rw']):?><?php echo $g['blog_home_rw']?>/c/0<?php else:?><?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;blog=<?php echo $blog?>&amp;front=list<?php endif?>">블로그</a></div>
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
				</div>
			</span>
			<?php if($my['uid']==$B['mbruid']):?>			
			<a href="<?php echo $g['blog_front']?>admin" class="config">꾸미기</a>
			<?php endif?>
			<?php if($d['blog']['writeperm']):?>
			<a href="<?php echo $g['blog_front']?>write&amp;cat=<?php echo $cat?>" class="config">글쓰기</a>
			<?php if($C['metause']&&$C['metaurl']):?>
			<a href="<?php echo $g['blog_front']?>meta&amp;cat=<?php echo $C['uid']?>" class="_metaget">자동수집</a>
			<?php endif?>
			<?php endif?>
		</div>
		<?php endif?>


		</div>
		<div class="xright">
			<a href="#." onclick="viewTypeChange('list','');"<?php if($vtype=='list'):?> class="on"<?php endif?>><img src="<?php echo $g['img_module_skin']?>/ico_list.gif" alt="" />리스트형</a>
			<a href="#." onclick="viewTypeChange('review','');"<?php if($vtype=='review'):?> class="on"<?php endif?>><img src="<?php echo $g['img_module_skin']?>/ico_review.gif" alt="" />리뷰형</a>
			<a href="#." onclick="viewTypeChange('gall','');"<?php if($vtype=='gall'):?> class="on"<?php endif?>><img src="<?php echo $g['img_module_skin']?>/ico_gall.gif" alt="" />이미지형</a>
			<select onchange="viewTypeChange('',this.value);">
			<?php for($i=10;$i<51;$i=$i+5):?>
			<option value="<?php echo $i?>"<?php if($recnum==$i):?> selected="selected"<?php endif?>><?php echo $i?>개씩 보기</option>
			<?php endfor?>
			</select>
		</div>
		<div class="clear"></div>
	</div>

	<?php if($vtype=='list'):?>
	<div class="listType">
		<table summary="<?php echo $B['name']?$B['name']:'전체'?> 포스트입니다.">
		<caption><?php echo $B['name']?$B['name']:'전체포스트'?></caption> 
		<colgroup> 
		<col width="40"> 
		<col> 
		<col width="80"> 
		<col width="70"> 
		<col width="90"> 
		</colgroup> 
		<thead>
		<tr>
		<th scope="col"></th>
		<th scope="col"></th>
		<th scope="col">글쓴이</th>
		<th scope="col">조회</th>
		<th scope="col">날짜</th>
		</tr>
		</thead>
		<tbody>

		<?php while($_R=db_fetch_array($RCD)):?>
		<?php $_M=getDbData($table['s_mbrdata'],'memberuid='.$_R['mbruid'],'*')?>
		<?php $_L=getOverTime($date['totime'],$_R['d_regis'])?>
		<tr>
		<td><?php echo $NUM-((($p-1)*$recnum)+$_rec++)?></td>
		<td class="sbj">
			<a href="<?php echo ($rwcat?$g['blog_home_rw'].'/c/'.$rwcat.'/':$g['blog_view']).$_R['uid']?>"<?php if($_R['uid']==$uid):?> class="b"<?php endif?>><?php echo $_R['subject']?></a>
			<?php if($_R['isphoto']):?><img src="<?php echo $g['img_core']?>/_public/ico_pic.gif" class="imgpos" alt="사진" title="사진" /><?php endif?>
			<?php if($_R['isvod']):?><img src="<?php echo $g['img_core']?>/_public/ico_vod.gif" class="imgpos" alt="동영상" title="동영상" /><?php endif?>
			<?php if($_R['comment']):?><span class="comment">[<?php echo $_R['comment']?><?php if($_R['oneline']):?>+<?php echo $_R['oneline']?><?php endif?>]</span><?php endif?>
			<?php if(getNew($_R['d_regis'],24)):?><span class="new">new</span><?php endif?>
		</td>
		<td class="name"><?php echo $_M[$_HS['nametype']]?></td>
		<td><?php echo $_R['hit']?></td>
		<td><?php echo $_L[1]<4?$_L[0].$lang['sys']['time'][$_L[1]].'전':getDateFormat($_R['d_regis'],'Y.m.d H:i')?></td>
		</tr> 
		<?php endwhile?> 

		<?php if(!$NUM):?>
		<tr>
		<td>-</td>
		<td class="sbj1">포스트가 없습니다.</td>
		<td>-</td>
		<td>-</td>
		<td>-</td>
		</tr> 
		<?php endif?>

		</tbody>
		</table>

		<div class="pagebox01">
		<?php echo getPageLink(10,$p,$TPG,$g['img_core'].'/page/default')?>
		</div>
	</div>
	<?php endif?>


	<?php if($vtype=='review'):?>
	<div class="reviewType">
		<table>
		<tbody>

		<?php while($_R=db_fetch_array($RCD)):?>
		<?php //$_M=getDbData($table['s_mbrdata'],'memberuid='.$_R['mbruid'],'*')?>
		<?php $_L=getOverTime($date['totime'],$_R['d_regis'])?>
		<?php 
		$_thumbimg = '';
		$imgs = getImgs($_R['content'],'jpg|jpeg');
		if ($imgs[0]) $_thumbimg = $imgs[0];
		else {
			if($_R['isphoto']&&$_R['upload'])
			{
				$upArray = getArrayString($_R['upload']);
				$_U = getUidData($table[$m.'upload'],$upArray['data'][0]);
				$_thumbimg = $_U['url'].$_U['folder'].'/'.$_U['thumbname'];
			}
		}
		?>
		<tr>
		<td>
			<?php if($_thumbimg):?>
			<div class="pic">
				<a href="<?php echo ($rwcat?$g['blog_home_rw'].'/c/'.$rwcat.'/':$g['blog_view']).$_R['uid']?>"><?php if($_thumbimg):?><img src="<?php echo $_thumbimg?>" alt="" /><?php endif?></a>
			</div>
			<?php endif?>
			<div class="sbj"><a href="<?php echo ($rwcat?$g['blog_home_rw'].'/c/'.$rwcat.'/':$g['blog_view']).$_R['uid']?>"><?php echo $_R['subject']?></a></div>
			<div class="cont"><?php echo $_R['review']?$_R['review']:getStrCut(getStripTags($_R['content']),$d['blog']['rlength'],'..')?></div>
		</td>
		</tr> 
		<?php endwhile?> 
		</tbody>
		</table>
		<?php if(!$NUM):?>
		<div class="none">포스트가 없습니다.</div>
		<?php endif?>
		<div class="pagebox01">
		<?php echo getPageLink(10,$p,$TPG,$g['img_core'].'/page/default')?>
		</div>
	</div>
	<?php endif?>


	<?php if($vtype=='gall'):?>
	<div class="gallType">
		<?php while($_R=db_fetch_array($RCD)):?>
		<?php 
		$_thumbimg = '';
		$imgs = getImgs($_R['content'],'jpg|jpeg');
		if ($imgs[0]) $_thumbimg = $imgs[0];
		else {
			if($_R['isphoto']&&$_R['upload'])
			{
				$upArray = getArrayString($_R['upload']);
				$_U = getUidData($table[$m.'upload'],$upArray['data'][0]);
				$_thumbimg = $_U['url'].$_U['folder'].'/'.$_U['thumbname'];
			}
		}
		?>
		<div class="picbox">
			<div class="pic">
				<a href="<?php echo ($rwcat?$g['blog_home_rw'].'/c/'.$rwcat.'/':$g['blog_view']).$_R['uid']?>"><?php if($_thumbimg):?><img src="<?php echo $_thumbimg?>" alt="" /><?php endif?></a>
			</div>
			<div class="sbjx">
				<a href="<?php echo ($rwcat?$g['blog_home_rw'].'/c/'.$rwcat.'/':$g['blog_view']).$_R['uid']?>"<?php if($_R['uid']==$uid):?> class="b"<?php endif?>><?php echo $_R['subject']?></a>
				<?php if($_R['comment']):?><span class="comment">[<?php echo $_R['comment']?><?php if($_R['oneline']):?>+<?php echo $_R['oneline']?><?php endif?>]</span><?php endif?>
				<?php if(getNew($_R['d_regis'],24)):?><span class="new">new</span><?php endif?>
			</div>
		</div>

		<?php endwhile?> 
		<?php if(!$NUM):?>
		<div class="none">포스트가 없습니다.</div>
		<?php endif?>

		<div class="clear"></div>
		<div class="pagebox01">
		<?php echo getPageLink(10,$p,$TPG,$g['img_core'].'/page/default')?>
		</div>
	</div>
	<?php endif?>
</div>

<form name="listForm" method="get" action="<?php echo $g['s']?>/">
<input type="hidden" name="r" value="<?php echo $r?>" />
<input type="hidden" name="m" value="<?php echo $m?>" />
<input type="hidden" name="blog" value="<?php echo $B['id']?>" />
<input type="hidden" name="front" value="list" />
<input type="hidden" name="cat" value="<?php echo $cat?>" />
<input type="hidden" name="vtype" value="<?php echo $vtype?>" />
<input type="hidden" name="recnum" value="<?php echo $recnum?>" />
<input type="hidden" name="where" value="<?php echo $where?>" />
<input type="hidden" name="keyword" value="<?php echo $_keyword?>" />
<input type="hidden" name="uid" value="" />
</form>



<script type="text/javascript">
//<![CDATA[
function viewTypeChange(_vtype,_recnum)
{
	var f = document.listForm;
	if (_vtype != '') f.vtype.value = _vtype;
	if (_recnum != '') f.recnum.value = _recnum;
	f.submit();
}
function snsWin(sns)
{
	var snsset = new Array();
	var enc_tit = "<?php echo urlencode($B['name'])?>";
	var enc_sbj = "<?php echo urlencode($R['subject'])?>";
	var enc_url = "<?php echo urlencode($g['url_root'].'/?r='.$r.'&m='.$m.'&blog='.$B['id'].'&front=list&uid='.$R['uid'])?>";
	var enc_tag = "<?php echo urlencode(str_replace(',',' ',$R['tag']))?>";

	snsset['t'] = 'http://twitter.com/home/?status=' + enc_sbj + '+++' + enc_url;
	snsset['f'] = 'http://www.facebook.com/sharer.php?u=' + enc_url + '&t=' + enc_sbj;
	snsset['m'] = 'http://me2day.net/posts/new?new_post[body]=' + enc_sbj + '+++["'+enc_tit+'":' + enc_url + '+]&new_post[tags]='+enc_tag;
	snsset['y'] = 'http://yozm.daum.net/api/popup/prePost?sourceid=' + enc_url + '&prefix=' + enc_sbj;
	window.open(snsset[sns]);
}
//]]>
</script>



