<?php
include $g['path_module'].$module.'/lib/tree.func.php';
$BLOGS = getDbArray($table[$module.'list'],'','*','gid','asc',0,$p);
$ISCAT = getDbRows($table[$module.'category'],'blog='.$uid);

if($cat)
{
	$CINFO = getUidData($table[$module.'category'],$cat);
	$ctarr = getMenuCodeToPathBlog($table[$module.'category'],$cat,0);
	$ctnum = count($ctarr);
	for ($i = 0; $i < $ctnum; $i++) $CXA[] = $ctarr[$i]['uid'];
}

$catcode = '';
$is_fcategory =  $CINFO['uid'] && $vtype != 'sub';
$is_regismode = !$CINFO['uid'] || $vtype == 'sub';
if ($is_regismode)
{
	$CINFO['name']	   = '';
	$CINFO['mobile']   = '';
	$CINFO['hidden']   = '';
	$CINFO['metaurl']   = '';
	$CINFO['metause']   = '';
	$CINFO['recnum'] = 20;
}
?>


<div id="mainbox">

	<div class="category">
		<div class="title">
			
			<select onchange="goHref('<?php echo $g['adm_href']?>&iframe=Y&amp;uid='+this.value);">
			<?php while($S = db_fetch_array($BLOGS)):?>
			<option value="<?php echo $S['uid']?>"<?php if($uid==$S['uid']):?> selected="selected"<?php endif?>>ㆍ<?php echo $S['name']?></option>
			<?php endwhile?>
			<?php if(!db_num_rows($BLOGS)):?>
			<option value="">등록된 블로그가 없습니다.</option>
			<?php endif?>
			</select>
			
			<a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;module=<?php echo $module?>&amp;front=makeblog&amp;iframe=Y"><img src="<?php echo $g['img_core']?>/_public/btn_add.gif" alt="블로그추가" title="블로그추가" /></a>

		</div>
		<?php if($ISCAT):?>
		<div class="joinimg"></div>
		<div class="tree<?php if(strstr($_SERVER['HTTP_USER_AGENT'],'MSIE 7')):?> ie7<?php endif?>">
		<?php if(!$_isDragScript):?>
		<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/core.js"></script>
		<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/events.js"></script>
		<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/css.js"></script>
		<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/coordinates.js"></script>
		<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/drag.js"></script>
		<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/dragsort.js"></script>
		<script type="text/javascript">
		//<![CDATA[
		var dragsort = ToolMan.dragsort();
		//]]>
		</script>
		<?php endif?>
		<script type="text/javascript">
		//<![CDATA[
		var dragsort = ToolMan.dragsort();
		var TreeImg = "<?php echo $g['img_core']?>/tree/default_none";
		var ulink = "<?php echo $g['adm_href']?>&amp;uid=<?php echo $uid?>&amp;iframe=<?php echo $iframe?>&amp;cat=";
		//]]>
		</script>
		<script type="text/javascript" src="<?php echo $g['s']?>/_core/js/tree.js"></script>
		<script type="text/javascript">
		//<![CDATA[
		var TREE_ITEMS = [['', null, <?php getMenuShowBlog($uid,$table[$module.'category'],0,0,0,$cat,$CXA,0)?>]];
		new tree(TREE_ITEMS, tree_tpl);
		<?php echo $MenuOpen?>
		//]]>
		</script>
		</div>
		<?php else:?>
		<div class="none">등록된 카테고리가 없습니다.</div>
		<?php endif?>

		<?php if($CINFO['isson']||(!$cat&&$ISCAT)):?>
		<form action="<?php echo $g['s']?>/" method="post" target="_action_frame_<?php echo $m?>">
		<input type="hidden" name="r" value="<?php echo $r?>" />
		<input type="hidden" name="m" value="<?php echo $module?>" />
		<input type="hidden" name="a" value="admin/modifymenugid" />

		<div class="savebtn">
			<img src="<?php echo $g['img_core']?>/_public/btn_admin.gif" alt="" title="펼치기" onclick="orderOpen();" />
			<input type="image" src="<?php echo $g['img_core']?>/_public/btn_save.gif" title="순서저장" />
		</div>
		<div class="tt1">출력순서</div>
		<ul id="menuorder">
		<?php $_MENUS=getDbSelect($table[$module.'category'],'blog='.$uid.' and parent='.intval($CINFO['uid']).' and depth='.($CINFO['depth']+1).' order by gid asc','*')?>
		<?php while($_M=db_fetch_array($_MENUS)):?>
		<li>
			<input type="checkbox" name="menumembers[]" value="<?php echo $_M['uid']?>" checked="checked" />
			<img src="<?php echo $g['img_core']?>/_public/ico_drag.gif" alt="" class="drag" />
			<?php echo $_M['name']?>
			<?php if($_M['hidden']):?><img src="<?php echo $g['img_core']?>/_public/ico_hidden.gif" alt="" /><?php endif?>
		</li>
		<?php endwhile?>
		</ul>
		</form>
		<?php endif?>


	</div>


	<div class="catinfo">


		<form name="procForm" action="<?php echo $g['s']?>/" method="post" target="_action_frame_<?php echo $m?>" enctype="multipart/form-data" onsubmit="return saveCheck(this);">
		<input type="hidden" name="r" value="<?php echo $r?>" />
		<input type="hidden" name="m" value="<?php echo $module?>" />
		<input type="hidden" name="a" value="admin/regiscategory" />
		<input type="hidden" name="blog" value="<?php echo $uid?>" />
		<input type="hidden" name="cat" value="<?php echo $CINFO['uid']?>" />
		<input type="hidden" name="vtype" value="<?php echo $vtype?>" />
		<input type="hidden" name="depth" value="<?php echo intval($CINFO['depth'])?>" />
		<input type="hidden" name="parent" value="<?php echo intval($CINFO['uid'])?>" />

		<div class="title">

			<div class="xleft">
			
			<?php if($is_regismode):?>
				<?php if($vtype == 'sub'):?>서브 카테고리 만들기<?php else:?>최상위 카테고리 만들기<?php endif?>
			<?php else:?>
				카테고리 등록정보
			<?php endif?>
			
			</div>
			<div class="xright">

				<a href="<?php echo $g['adm_href']?>&amp;uid=<?php echo $uid?>&amp;iframe=<?php echo $iframe?>&amp;type=makecategory">최상위 카테고리 등록</a>

			</div>





		</div>

		<div class="notice">
			<?php if($is_regismode):?>
			복수의 카테고리를 한번에 등록하시려면 카테고리명을 콤마(,)로 구분해 주세요.<br />
			보기)회사소개,커뮤니티,고객센터<br />
			카테고리코드를 같이 등록하시려면 다음과 같은 형식으로 등록해 주세요.<br />
			보기)회사소개=company,커뮤니티=community,고객센터=center<br />
			카테고리코드는 미등록시 자동생성됩니다.
			<?php else:?>
			속성을 변경하려면 설정값을 변경한 후 [속성변경] 버튼을 클릭해주세요.<br />
			카테고리를 삭제하면 소속된 하위 카테고리까지 모두 삭제됩니다.
			<?php endif?>
		</div>

		<table>
			<?php if($vtype == 'sub'):?>
			<tr>
				<td class="td1">상위 카테고리</td>
				<td class="td2 b">
				<?php for ($i = 0; $i < $ctnum; $i++): ?>
				<a href="<?php echo $g['adm_href']?>&amp;uid=<?php echo $uid?>&amp;iframe=<?php echo $iframe?>&amp;cat=<?php echo $ctarr[$i]['uid']?>"><?php echo $ctarr[$i]['name']?></a>
				<?php if($i < $ctnum-1):?> &gt; <?php endif?> 
				<?php $catcode .= $ctarr[$i]['id'].'/';endfor?>
				</td>
			</tr>
			<?php else:?>
			<?php if($cat):?>
			<tr>
				<td class="td1">상위 카테고리</td>
				<td class="td2 b">
				<?php for ($i = 0; $i < $ctnum-1; $i++): ?>
				<a href="<?php echo $g['adm_href']?>&amp;uid=<?php echo $uid?>&amp;iframe=<?php echo $iframe?>&amp;cat=<?php echo $ctarr[$i]['uid']?>"><?php echo $ctarr[$i]['name']?></a>
				<?php if($i < $ctnum-2):?> &gt; <?php endif?> 
				<?php $delparent=$ctarr[$i]['uid'];$catcode .= $ctarr[$i]['id'].'/';endfor?>
				<?php if(!$delparent):?>최상위 카테고리<?php endif?>
				</td>
			</tr>
			<?php endif?>
			<?php endif?>
			<tr>
				<td class="td1">카테고리 명칭</td>
				<td class="td2">
					<input type="text" name="name" value="<?php echo $CINFO['name']?>" class="input sname<?php echo $is_fcategory?1:2?>" />
					<?php if($is_fcategory):?>
					<span class="btn01"><a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $module?>&amp;a=admin/deletecategory&amp;uid=<?php echo $uid?>&amp;cat=<?php echo $cat?>&amp;parent=<?php echo $delparent?>" target="_action_frame_<?php echo $m?>" onclick="return confirm('정말로 삭제하시겠습니까?     ')">카테고리 삭제</a></span>
					<span class="btn01"><a href="<?php echo $g['adm_href']?>&amp;uid=<?php echo $uid?>&amp;iframe=<?php echo $iframe?>&amp;cat=<?php echo $cat?>&amp;vtype=sub">서브 카테고리 등록</a></span>
					<?php endif?>
				</td>
			</tr>
			<?php if($CINFO['uid']&&!$vtype):?>
			<tr>
				<td class="td1">
					카테고리 코드
					<img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('guide_menucode','block','none');" />
				</td>
				<td class="td2">
					<input type="text" name="id" value="<?php echo $CINFO['id']?>" maxlength="20" class="input sname1" /> <span>(고유키=<?php echo sprintf('%05d',$CINFO['uid'])?>)</span>
					<div id="guide_menucode" class="guide hide">
					이 카테고리를 잘 표현할 수 있는 단어로 입력해 주세요.<br />
					영문대소문자/숫자/_/- 조합으로 등록할 수 있으며 중복될 수는 없습니다.<br />
					</div>
				</td>
			</tr>
			<?php endif?>

			<tr>
				<td class="td1">
					메뉴옵션
					<img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('guide_mpro','block','none');" />
				</td>
				<td class="td2 shift">
					<input type="checkbox" name="mobile" id="xmobile" value="1"<?php if($CINFO['mobile']):?> checked="checked"<?php endif?> /><label for="xmobile">모바일출력</label>
					<input type="checkbox" name="hidden" id="cat_hidden" value="1"<?php if($CINFO['hidden']):?> checked="checked"<?php endif?> /><label for="cat_hidden">카테고리숨김</label>

					<div id="guide_mpro" class="guide hide">
					<span class="b">모바일출력 : </span>모바일 레이아웃 사용시 이 카테고리를 출력합니다.<br />
					<span class="b">카테고리숨김 : </span>카테고리를 출력하지 않습니다.(링크접근가능)<br />
					</div>
				</td>
			</tr>
			<tr>
				<td class="td1">
					포스트 진열
				</td>
				<td class="td2">
					<select name="vtype1">
					<option value="review"<?php if($CINFO['vtype']=='review'):?> selected="selected"<?php endif?>>리뷰형</option>
					<option value="list"<?php if($CINFO['vtype']=='list'):?> selected="selected"<?php endif?>>리스트형</option>
					<option value="gall"<?php if($CINFO['vtype']=='gall'):?> selected="selected"<?php endif?>>이미지형</option>
					</select>
					<select name="recnum">
					<?php for($i=10;$i<51;$i=$i+5):?>
					<option value="<?php echo $i?>"<?php if($CINFO['recnum']==$i):?> selected="selected"<?php endif?>><?php echo $i?>개씩 보기</option>
					<?php endfor?>
					</select>
					<input type="checkbox" name="vopen" id="vopen" value="1"<?php if($CINFO['vopen']):?> checked="checked"<?php endif?> /><label for="vopen">1개씩 펼쳐보기</label>
				</td>
			</tr>
			<tr>
				<td class="td1">
					포스트 수집
					<img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('guide_meta','block','none');" />
				</td>
				<td class="td2">
					<input type="text" name="metaurl" value="<?php echo $CINFO['metaurl']?>" class="input sname2" />
					<input type="checkbox" name="metause" id="xmetause" value="1"<?php if($CINFO['metause']):?> checked="checked"<?php endif?> /><label for="xmetause">작동</label>
					<div id="guide_meta" class="guide hide">
					RSS를 지원하는 블로그나 웹페이지의 URL을 등록하면 해당<br />
					사이트의 포스트를 간편하게 자동으로 수집할 수 있습니다.<br />
					http://를 포함하여 입력해 주세요.<br />
					</div>
				</td>
			</tr>
		</table>
		<div class="submitbox">

			<?php if($vtype=='sub'):?><input type="button" class="btngray" value="등록취소" onclick="history.back();" /><?php endif?>
			<input type="submit" class="btnblue" value="<?php echo $is_fcategory?'카테고리속성 변경':'신규카테고리 등록'?>" />
			<div class="clear"></div>
		</div>

		</form>
		
	</div>
	<div class="clear"></div>
</div>

<script type="text/javascript">
//<![CDATA[
var orderopen = false;
function orderOpen()
{
	if (orderopen == false)
	{
		getId('menuorder').style.display = 'block';
		orderopen = true;
	}
	else {
		getId('menuorder').style.display = 'none';
		orderopen = false;
	}
}
function saveCheck(f)
{
	if (f.name.value == '')
	{
		alert('카테고리 명칭을 입력해 주세요.      ');
		f.name.focus();
		return false;
	}
	if (f.id)
	{
		if (f.id.value == '')
		{
			alert('카테고리 코드를 입력해 주세요.      ');
			f.id.focus();
			return false;
		}
		if (!chkFnameValue(f.id.value))
		{
			alert('카테고리 코드는 영문대소문자/숫자/_/- 만 사용할 수 있습니다.      ');
			f.id.focus();
			return false;
		}
	}
	return confirm('정말로 실행하시겠습니까?         ');
}
function slideshowOpen()
{
	if(getId('menuorder')) dragsort.makeListSortable(getId("menuorder"));
}
slideshowOpen();
<?php if($type == 'makecategory'):?>
document.procForm.name.focus();
<?php endif?>
//]]>
</script>