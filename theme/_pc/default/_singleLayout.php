<?php
$d['blog']['vartheme'] = $g['dir_module_skin'].'_var/var.'.$B['id'].'.php';
if (!is_file($d['blog']['vartheme']))
{
	copy($g['dir_module_skin'].'_var/_var.php',$d['blog']['vartheme']);
	@chmod($d['blog']['vartheme'],0707);
}
include $d['blog']['vartheme'];
if($front=='write') $checkbox = true;
?>


<?php if($front!='list'&&$front!='meta'):?>
<div id="_layout">
	<div class="_selcat">
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
		<?php endif?>
	</div>

	<?php if($front=='admin'):?>
	<div class="_config_guide_"><img src="<?php echo $g['img_core']?>/_public/ico_notice.gif" alt="" /> 블로그 전용 레이아웃을 사용하지 않을 경우 일부설정은 적용되지 않습니다.</div>
	<?php endif?>
</div>
<?php endif?>




<?php include $g['dir_module_mode'].'.php'?>

<script type="text/javascript">
//<![CDATA[
var _categoryShow = false;
function catShow()
{
	if (_categoryShow == true)
	{
		getId('__catbox__').style.display = 'none';
		_categoryShow = false;
	}
	else {
		getId('__catbox__').style.display = 'block';
		_categoryShow = true;
	}
}
//]]>
</script>
