<?php
if(!defined('__KIMS__')) exit;

$sort	= $sort ? $sort : 'gid';
$orderby= $orderby ? $orderby : 'asc';
$recnum	= $recnum && $recnum < 301 ? $recnum : 20;
$bbsque	= 'mbruid='.$my['uid'];

$RCD = getDbArray($table[$m.'list'],$bbsque,'*',$sort,$orderby,$recnum,$p);
$NUM = getDbRows($table[$m.'list'],$bbsque);
$TPG = getTotalPage($NUM,$recnum);

if ($uid)
{
	$R = getUidData($table[$m.'list'],$uid);
	$M = getUidData($table['s_mbrid'],$R['mbruid']);

	if ($R['uid'])
	{
		include_once $g['path_module'].$m.'/var/var.'.$R['id'].'.php';
	}
}
$g['adm_href'] = $g['s'].'/?r='.$r.'&amp;m='.$m.'&amp;admin='.$admin.'&amp;mod='.$mod;
?>

<div id="mainbox">
	<div class="category">

		<div class="title">
			<span class="b">운영중인 블로그</span>
		</div>
		
		<?php if($NUM):?>
		<div class="tree">
			<ul id="bbsorder">
			<?php while($BR = db_fetch_array($RCD)):?>
			<li ondblclick="window.open('<?php echo $g['s']?>/?r=<?php echo $r?>&m=<?php echo $m?>&bid=<?php echo $BR['id']?>');"<?php if($BR['category']):?> class="usecat" title="<?php echo $BR['category']?>"<?php endif?>>
				<input type="checkbox" name="bbsmembers[]" value="<?php echo $BR['uid']?>" checked="checked" />
				<a href="<?php echo $g['adm_href']?>&amp;recnum=<?php echo $recnum?>&amp;p=<?php echo $p?>&amp;uid=<?php echo $BR['uid']?>"><span class="name<?php if($BR['uid']==$R['uid']):?> on<?php endif?>" title="<?php echo number_format($BR['num_w'])?>개"><?php echo $BR['name']?></span></a><span class="id">(<?php echo $BR['id']?>)</span>
			</li>
			<?php endwhile?>
			</ul>
		</div>
		<?php else:?>
		<div class="none">등록된 블로그가 없습니다.</div>
		<?php endif?>

	</div>


	<div class="catinfo">


		<form name="procForm" action="<?php echo $g['s']?>/" method="post" enctype="multipart/form-data" onsubmit="return saveCheck(this);">
		<input type="hidden" name="r" value="<?php echo $r?>" />
		<input type="hidden" name="m" value="<?php echo $m?>" />
		<input type="hidden" name="a" value="admin/user_makeblog" />
		<input type="hidden" name="blog" value="<?php echo $R['uid']?>" />
		<input type="hidden" name="admin" value="<?php echo $admin?>" />
		<input type="hidden" name="mod" value="<?php echo $mod?>" />

		<input type="hidden" name="layout" value="<?php echo $d['blog']['layout']?>" />
		<input type="hidden" name="iframe" value="<?php echo $d['blog']['iframe']?>" />
		<input type="hidden" name="snsconnect" value="<?php echo $d['blog']['snsconnect']?>" />

		<div class="title">

			<div class="xleft">
				블로그 등록정보
			</div>
			<div class="xright">

			</div>
		</div>

		<div class="notice">
			왼쪽의 블로그 리스트는 현재 회원님께서 운영중인 블로그들입니다.<br />
			팀원을 추가하실때는 팀원의 정확한 아이디를 입력해야 합니다.
		</div>

		<table>
			<tr>
				<td class="td1">
					블로그제목					
				</td>
				<td class="td2">
					<input type="text" name="name" value="<?php echo $R['name']?>" class="input sname" />
				</td>
			</tr>
			<tr>
				<td class="td1">
					블로그형식
				</td>
				<td class="td2">
					<input type="radio" name="blogtype" value="1"<?php if(!$R['blogtype']||$R['blogtype']==1):?> checked="checked"<?php endif?> />개인블로그
					<input type="radio" name="blogtype" value="2"<?php if($R['blogtype']==2):?> checked="checked"<?php endif?> />팀블로그
				</td>
			</tr>
			<tr>
				<td class="td1">
					팀원아이디
					<img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('guide_category','block','none');" />
				</td>
				<td class="td2">
					<input type="text" name="members" value="<?php echo $R['members']?>" class="input sname1" />
					<div id="guide_category" class="guide hide">
					이 블로그에 팀원을 추가하려면 회원아이디를 콤마(,)로 구분해서 등록해 주세요.<br />
					추가된 팀원은 포스트 공동작성/삭제권한이 부여됩니다.<br />
					</div>
				</td>
			</tr>
			<tr>
				<td class="td1">블로그테마</td>
				<td class="td2">
					<select name="theme_pc" class="select1">
					<option value="">&nbsp;+ 블로그 대표테마</option>
					<option value="">--------------------------------</option>
					<?php $tdir = $g['path_module'].$m.'/theme/_pc/'?>
					<?php $dirs = opendir($tdir)?>
					<?php while(false !== ($skin = readdir($dirs))):?>
					<?php if($skin=='.' || $skin == '..' || is_file($tdir.$skin))continue?>
					<option value="_pc/<?php echo $skin?>" title="<?php echo $skin?>"<?php if($d['blog']['theme_pc']=='_pc/'.$skin):?> selected="selected"<?php endif?>>ㆍ<?php echo getFolderName($tdir.$skin)?>(<?php echo $skin?>)</option>
					<?php endwhile?>
					<?php closedir($dirs)?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="td1 sfont1">(모바일접속)</td>
				<td class="td2">
					<select name="m_skin" class="select1">
					<option value="">&nbsp;+ 모바일 대표테마</option>
					<option value="">--------------------------------</option>
					<?php $tdir = $g['path_module'].$m.'/theme/_mobile/'?>
					<?php $dirs = opendir($tdir)?>
					<?php while(false !== ($skin = readdir($dirs))):?>
					<?php if($skin=='.' || $skin == '..' || is_file($tdir.$skin))continue?>
					<option value="_mobile/<?php echo $skin?>" title="<?php echo $skin?>"<?php if($d['blog']['theme_mobile']=='_mobile/'.$skin):?> selected="selected"<?php endif?>>ㆍ<?php echo getFolderName($tdir.$skin)?>(<?php echo $skin?>)</option>
					<?php endwhile?>
					<?php closedir($dirs)?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="td1">적용편집기</td>
				<td class="td2">
					<select name="editor" class="select1">
					<?php $tdir = $g['path_module'].'editor/theme/'?>
					<?php $dirs = opendir($tdir)?>
					<?php while(false !== ($skin = readdir($dirs))):?>
					<?php if($skin=='.' || $skin == '..' || is_file($tdir.$skin))continue?>
					<option value="<?php echo $skin?>" title="<?php echo $skin?>"<?php if($d['blog']['editor']==$skin):?> selected="selected"<?php endif?>>ㆍ<?php echo getFolderName($tdir.$skin)?>(<?php echo $skin?>)</option>
					<?php endwhile?>
					<?php closedir($dirs)?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="td1">
					포스트 진열
				</td>
				<td class="td2">
					<select name="vtype">
					<option value="review"<?php if($d['blog']['vtype']=='review'):?> selected="selected"<?php endif?>>리뷰형</option>
					<option value="list"<?php if($d['blog']['vtype']=='list'):?> selected="selected"<?php endif?>>리스트형</option>
					<option value="gall"<?php if($d['blog']['vtype']=='gall'):?> selected="selected"<?php endif?>>이미지형</option>
					</select>
					<select name="recnum">
					<?php $d['blog']['recnum']=$d['blog']['recnum']?$d['blog']['recnum']:20;for($i=10;$i<51;$i=$i+5):?>
					<option value="<?php echo $i?>"<?php if($d['blog']['recnum']==$i):?> selected="selected"<?php endif?>><?php echo $i?>개씩 보기</option>
					<?php endfor?>
					</select>
					<input type="checkbox" name="vopen" id="vopen" value="1"<?php if($d['blog']['vopen']):?> checked="checked"<?php endif?> /><label for="vopen">1개씩 펼쳐보기</label>
				</td>
			</tr>
			<tr>
				<td class="td1">
					리뷰 글자수
				</td>
				<td class="td2">
					<input type="text" name="rlength" value="<?php echo $d['blog']['rlength']?$d['blog']['rlength']:200?>" size="5" class="input" />자
				</td>
			</tr>
		</table>


		<div class="submitbox">
			<input type="submit" class="btnblue" value="블로그 속성변경" />
			<div class="clear"></div>
		</div>

		</form>
		
	</div>
	<div class="clear"></div>
</div>

<iframe name="_orderframe_" class="hide"></iframe>

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
function saveCheck(f)
{
	if (f.name.value == '')
	{
		alert('블로그 제목을 입력해 주세요.     ');
		f.name.focus();
		return false;
	}
	return confirm('정말로 수정하시겠습니까?         ');
}
window.onload = function()
{
	<?php if($NUM):?>
	dragsort.makeListSortable(getId("bbsorder"));
	<?php endif?>

	parent.getId('_modal_on_').style.height = '90%';
	var xy = getOfs(parent.getId('_modal_on_'));
	parent.getId('_modal_on_').children[1].children[0].style.height = (xy.height-39) + 'px';
	document.procForm.name.focus();
}
//]]>
</script>

