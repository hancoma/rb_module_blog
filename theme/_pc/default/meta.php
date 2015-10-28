<?php
include $g['path_core'].'function/rss.func.php';
$_MRCD = getRssArray($C['metaurl'],'item');
$NUM = 0;
?>

<div id="mainbox">



	<form name="metaForm" method="post" action="<?php echo $g['s']?>/">
	<input type="hidden" name="r" value="<?php echo $r?>" />
	<input type="hidden" name="m" value="<?php echo $m?>" />
	<input type="hidden" name="blog" value="<?php echo $B['uid']?>" />
	<input type="hidden" name="a" value="meta_post" />
	<input type="hidden" name="cat" value="<?php echo $cat?>" />
	<input type="hidden" name="vtype" value="<?php echo $vtype?>" />

	<div class="title">
		<div class="xleft">
		<?php if($iframe=='Y'):?>
		<span class="tt">
		<?php if($d['blog']['writeperm']&&$C['metause']&&$C['metaurl']):?>
		<a href="#." class="_metause" onclick="chkFlag('meta_post[]');">전체선택</a>
		<a href="#." class="_metaget" onclick="metaPostGet();">로컬DB로 포스트 가져오기</a>
		<?php endif?>
		블로그
		<?php if($cat):?> &gt; <?php endif?>
		<?php for ($i = 0; $i < $ctnum; $i++): ?>
		<?php echo $ctarr[$i]['name']?>
		<?php if($i < $ctnum-1):?> &gt; <?php endif?> 
		<?php endfor?>
		</span>
		<?php else:?>
		<span class="tt">
		<?php echo $C['name']?> &nbsp; 
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
			<a href="<?php echo $g['blog_front']?>admin" class="config">설정</a>
			<?php endif?>
			<?php if($d['blog']['writeperm']):?>
			<a href="<?php echo $g['blog_front']?>write&amp;cat=<?php echo $cat?>" class="config">글쓰기</a>
			<?php if($C['metause']&&$C['metaurl']):?>
			<a href="#." class="config" onclick="chkFlag('meta_post[]');">전체선택</a>
			<a href="#." class="_metaget" onclick="metaPostGet();">로컬DB로 포스트 가져오기</a>
			<?php endif?>
			<?php endif?>
		</div>
		<?php endif?>


		</div>
		<div class="xright">
			<a href="#." onclick="viewTypeChange('list','');"<?php if($vtype=='list'):?> class="on"<?php endif?>><img src="<?php echo $g['img_module_skin']?>/ico_list.gif" alt="" />리스트형</a>
			<a href="#." onclick="viewTypeChange('review','');"<?php if($vtype=='review'):?> class="on"<?php endif?>><img src="<?php echo $g['img_module_skin']?>/ico_review.gif" alt="" />리뷰형</a>
			<a href="#." onclick="viewTypeChange('gall','');"<?php if($vtype=='gall'):?> class="on"<?php endif?>><img src="<?php echo $g['img_module_skin']?>/ico_gall.gif" alt="" />이미지형</a>
		</div>
		<div class="clear"></div>
	</div>
	<div class="_guideline_">
	<div class="_config_guide_"><img src="<?php echo $g['img_core']?>/_public/ico_notice.gif" alt="" /> 포스트가 정상적으로 가져와졌을 경우 체크 후 가져오기 버튼을 클릭하세요.</div>
	</div>


	<?php if($vtype=='list'):?>
	<div class="listType">
		<table summary="<?php echo $B['name']?$B['name']:'전체'?> 포스트입니다.">
		<caption><?php echo $B['name']?$B['name']:'전체포스트'?></caption> 
		<colgroup> 
		<col width="30"> 
		<col> 
		<col width="150"> 
		<col width="200"> 
		</colgroup> 
		<thead>
		<tr>
		<th scope="col"></th>
		<th scope="col"></th>
		<th scope="col">글쓴이</th>
		<th scope="col">날짜</th>
		</tr>
		</thead>
		<tbody>

		<?php foreach($_MRCD as $_MR):$NUM++?>
		<?php
		$_name = getRssContent($_MR,'author');
		$_category = getRssContent($_MR,'category');
		$_subject = getRssContent($_MR,'title');
		$_link = getRssContent($_MR,'link');
		$_content = str_replace('&amp;nbsp;',' ',getRssContent($_MR,'description'));
		$_d_regis = getRssContent($_MR,'pubDate');
		$_tag = getRssContent($_MR,'tag');
		?>
		<tr>
		<td><input type="checkbox" name="meta_post[]" value="<?php echo $NUM?>" /></td>
		<td class="sbj">
			<a href="<?php echo $_link?>" target="_blank""><?php echo $_subject?></a>
			<div class="hide">
				<textarea name="name<?php echo $NUM?>"><?php echo $_name?></textarea>
				<textarea name="category<?php echo $NUM?>"><?php echo $_category?></textarea>
				<textarea name="subject<?php echo $NUM?>"><?php echo $_subject?></textarea>
				<textarea name="link<?php echo $NUM?>"><?php echo $_link?></textarea>
				<textarea name="content<?php echo $NUM?>"><?php echo htmlspecialchars($_content)?></textarea>
				<textarea name="d_regis<?php echo $NUM?>"><?php echo $_d_regis?></textarea>
				<textarea name="tag<?php echo $NUM?>"><?php echo $_tag?></textarea>
			</div>
		</td>
		<td class="name"><?php echo $_name?></td>
		<td><?php echo $_d_regis?></td>
		</tr> 
		<?php endforeach?>

		<?php if(!$NUM):?>
		<tr>
		<td>-</td>
		<td class="sbj1">포스트가 없습니다.</td>
		<td>-</td>
		<td>-</td>
		</tr> 
		<?php endif?>

		</tbody>
		</table>


	</div>
	<?php endif?>





	<?php if($vtype=='review'):?>
	<div class="reviewType">
		<table>
		<tbody>

		<?php foreach($_MRCD as $_MR):$NUM++?>
		<?php
		$_name = getRssContent($_MR,'author');
		$_category = getRssContent($_MR,'category');
		$_subject = getRssContent($_MR,'title');
		$_link = getRssContent($_MR,'link');
		$_content = str_replace('&amp;nbsp;',' ',getRssContent($_MR,'description'));
		$_d_regis = getRssContent($_MR,'pubDate');
		$_tag = getRssContent($_MR,'tag');
		$_thumbimg = '';
		$imgs = getImgs($_content,'jpg|jpeg');
		if ($imgs[0]) $_thumbimg = $imgs[0];
		?>
		<tr>
		<td>
			<?php if($_thumbimg):?>
			<div class="pic">
				<a href="<?php echo $_link?>" target="_blank"><?php if($_thumbimg):?><img src="<?php echo $_thumbimg?>" alt="" /><?php endif?></a>
			</div>
			<?php endif?>
			<div class="sbj"><input type="checkbox" name="meta_post[]" value="<?php echo $NUM?>" /><a href="<?php echo $_link?>" target="_blank"><?php echo $_subject?></a></div>
			<div class="cont"><?php echo getStrCut(getStripTags($_content),$d['blog']['rlength'],'..')?></div>
			<div class="hide">
				<textarea name="name<?php echo $NUM?>"><?php echo $_name?></textarea>
				<textarea name="category<?php echo $NUM?>"><?php echo $_category?></textarea>
				<textarea name="subject<?php echo $NUM?>"><?php echo $_subject?></textarea>
				<textarea name="link<?php echo $NUM?>"><?php echo $_link?></textarea>
				<textarea name="content<?php echo $NUM?>"><?php echo htmlspecialchars($_content)?></textarea>
				<textarea name="d_regis<?php echo $NUM?>"><?php echo $_d_regis?></textarea>
				<textarea name="tag<?php echo $NUM?>"><?php echo $_tag?></textarea>
			</div>
		</td>
		</tr> 
		<?php endforeach?>
		</tbody>
		</table>
		<?php if(!$NUM):?>
		<div class="none">포스트가 없습니다.</div>
		<?php endif?>
	</div>
	<?php endif?>





	<?php if($vtype=='gall'):?>
	<div class="gallType">
		<?php foreach($_MRCD as $_MR):$NUM++?>
		<?php
		$_name = getRssContent($_MR,'author');
		$_category = getRssContent($_MR,'category');
		$_subject = getRssContent($_MR,'title');
		$_link = getRssContent($_MR,'link');
		$_content = str_replace('&amp;nbsp;',' ',getRssContent($_MR,'description'));
		$_d_regis = getRssContent($_MR,'pubDate');
		$_tag = getRssContent($_MR,'tag');
		$_thumbimg = '';
		$imgs = getImgs($_content,'jpg|jpeg');
		if ($imgs[0]) $_thumbimg = $imgs[0];
		?>
		<div class="picbox">
			<div class="pic">
				<a href="<?php echo $_link?>" target="_blank"><?php if($_thumbimg):?><img src="<?php echo $_thumbimg?>" alt="" /><?php endif?></a>
			</div>
			<div class="sbjx">
				<input type="checkbox" name="meta_post[]" value="<?php echo $NUM?>" />
				<a href="<?php echo $_link?>" target="_blank"><?php echo $_subject?></a>
				<div class="hide">
					<textarea name="name<?php echo $NUM?>"><?php echo $_name?></textarea>
					<textarea name="category<?php echo $NUM?>"><?php echo $_category?></textarea>
					<textarea name="subject<?php echo $NUM?>"><?php echo $_subject?></textarea>
					<textarea name="link<?php echo $NUM?>"><?php echo $_link?></textarea>
					<textarea name="content<?php echo $NUM?>"><?php echo htmlspecialchars($_content)?></textarea>
					<textarea name="d_regis<?php echo $NUM?>"><?php echo $_d_regis?></textarea>
					<textarea name="tag<?php echo $NUM?>"><?php echo $_tag?></textarea>
				</div>
			</div>
		</div>

		<?php endforeach?>
		<?php if(!$NUM):?>
		<div class="none">포스트가 없습니다.</div>
		<?php endif?>

		<div class="clear"></div>
	</div>
	<?php endif?>
</div>
</form>

<form name="listForm" method="get" action="<?php echo $g['s']?>/">
<input type="hidden" name="r" value="<?php echo $r?>" />
<input type="hidden" name="m" value="<?php echo $m?>" />
<input type="hidden" name="blog" value="<?php echo $B['id']?>" />
<input type="hidden" name="front" value="meta" />
<input type="hidden" name="cat" value="<?php echo $cat?>" />
<input type="hidden" name="vtype" value="<?php echo $vtype?>" />
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
function metaPostGet()
{
	var f = document.metaForm;
    var l = document.getElementsByName('meta_post[]');
    var n = l.length;
	var j = 0;
    var i;

    for (i = 0; i < n; i++)
	{
		if(l[i].checked == true)
		{
			j++;
		}
	}
	if (!j)
	{
		alert('선택된 포스트가 없습니다.      ');
		return false;
	}
	if(confirm('정말로 실행하시겠습니까?    '))
	{
		getIframeForAction(f);
		f.submit();
	}
	return false;
}
//]]>
</script>



