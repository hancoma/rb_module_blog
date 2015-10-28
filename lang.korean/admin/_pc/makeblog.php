<?php
$sort	= $sort ? $sort : 'gid';
$orderby= $orderby ? $orderby : 'asc';
$recnum	= $recnum && $recnum < 301 ? $recnum : 20;
$bbsque	= '';

$RCD = getDbArray($table[$module.'list'],$bbsque,'*',$sort,$orderby,$recnum,$p);
$NUM = getDbRows($table[$module.'list'],$bbsque);
$TPG = getTotalPage($NUM,$recnum);

if ($uid)
{
	$R = getUidData($table[$module.'list'],$uid);
	$M = getUidData($table['s_mbrid'],$R['mbruid']);

	if ($R['uid'])
	{
		include_once $g['path_module'].$module.'/var/var.'.$R['id'].'.php';
	}
}
?>

<div id="mainbox">
	<div class="category">
		<form name="blogform" action="<?php echo $g['s']?>/" method="post" target="_orderframe_">
		<input type="hidden" name="r" value="<?php echo $r?>" />
		<input type="hidden" name="m" value="<?php echo $module?>" />
		<input type="hidden" name="a" value="admin/order_update" />

		<div class="title">
			<select class="c1" onchange="goHref('<?php echo $g['adm_href']?>&amp;iframe=<?php echo $iframe?>&amp;uid=<?php echo $R['uid']?>&amp;recnum='+this.value);">
			<?php for($i=30;$i<=300;$i=$i+30):?>
			<option value="<?php echo $i?>"<?php if($i==$recnum):?> selected="selected"<?php endif?>>D.<?php echo $i?></option>
			<?php endfor?>
			</select>
			<select class="c2" onchange="goHref('<?php echo $g['adm_href']?>&amp;iframe=<?php echo $iframe?>&amp;uid=<?php echo $R['uid']?>&amp;recnum=<?php echo $recnum?>&amp;p='+this.value);">
			<?php for($i = 1; $i <= $TPG; $i++):?>
			<option value="<?php echo $i?>"<?php if($i==$p):?> selected="selected"<?php endif?>>P.<?php echo $i?></option>
			<?php endfor?>
			</select>

			<a href="<?php echo $g['adm_href']?>&amp;iframe=<?php echo $iframe?>" title="블로그추가"><img src="<?php echo $g['img_core']?>/_public/btn_add.gif" alt="추가" /></a>
			<img src="<?php echo $g['img_core']?>/_public/btn_save.gif" title="순서저장" alt="save" class="hand" onclick="document.blogform.submit();" />

		</div>
		
		<?php if($NUM):?>
		<div class="tree">
			<ul id="bbsorder">
			<?php while($BR = db_fetch_array($RCD)):?>
			<li ondblclick="window.open('<?php echo $g['s']?>/?r=<?php echo $r?>&m=<?php echo $module?>&bid=<?php echo $BR['id']?>');"<?php if($BR['category']):?> class="usecat" title="<?php echo $BR['category']?>"<?php endif?>>
				<input type="checkbox" name="bbsmembers[]" value="<?php echo $BR['uid']?>" checked="checked" />
				<a href="<?php echo $g['adm_href']?>&amp;iframe=<?php echo $iframe?>&amp;recnum=<?php echo $recnum?>&amp;p=<?php echo $p?>&amp;uid=<?php echo $BR['uid']?>"><span class="name<?php if($BR['uid']==$R['uid']):?> on<?php endif?>" title="<?php echo number_format($BR['num_w'])?>개"><?php echo $BR['name']?></span></a><span class="id">(<?php echo $BR['id']?>)</span>
			</li>
			<?php endwhile?>
			</ul>
		</div>
		<?php else:?>
		<div class="none">등록된 블로그가 없습니다.</div>
		<?php endif?>

		</form>
	</div>


	<div class="catinfo">


		<form name="procForm" action="<?php echo $g['s']?>/" method="post" enctype="multipart/form-data" onsubmit="return saveCheck(this);">
		<input type="hidden" name="r" value="<?php echo $r?>" />
		<input type="hidden" name="m" value="<?php echo $module?>" />
		<input type="hidden" name="a" value="admin/makeblog" />
		<input type="hidden" name="blog" value="<?php echo $R['uid']?>" />

		<div class="title">

			<div class="xleft">
				블로그 등록정보
			</div>
			<div class="xright">
				<a href="<?php echo $g['adm_href']?>&amp;iframe=<?php echo $iframe?>">새블로그 만들기</a>
			</div>
		</div>

		<div class="notice">
			블로그의 순서를 변경하려면 게시판을 드래그 후 [save]버튼을 클릭해 주세요.<br />
			블로그를 메뉴나 페이지에 연결하면 연결메뉴/연결페이지의 설정값은 무시됩니다.
		</div>

		<table>
			<tr>
				<td class="td1">
					블로그제목					
					<img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('guide_bbsidname','block','none');" />
				</td>
				<td class="td2">
					<input type="text" name="name" value="<?php echo $R['name']?>" class="input sname" />
					<?php if($R['id']):?>
					<span class="btn01"><a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $module?>&amp;blog=<?php echo $R['id']?>" target="_blank">블로그보기</a></span>
					<input type="hidden" name="id" value="<?php echo $R['id']?>" />
					<?php else:?>
					아이디 <input type="text" name="id" value="" class="input sname2" />
					<?php endif?>

					<div id="guide_bbsidname" class="guide hide">
					<span class="b">블로그제목</span> : 한글,영문등 자유롭게 등록할 수 있습니다.<br />
					<span class="b">아이디</span> : 영문 대소문자+숫자+_ 조합으로 만듭니다.<br />
					</div>

				</td>
			</tr>
			<tr>
				<td class="td1">
					개설회원ID
				</td>
				<td class="td2">
					<input type="text" name="mbrid" value="<?php echo $R['mbruid']?$M['id']:$my['id']?>" class="input sname" />
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
				<td class="td1">레 이 아 웃</td>
				<td class="td2">
					<select name="layout" class="select1">
					<option value="">&nbsp;+ 사이트 대표레이아웃</option>
					<?php $dirs = opendir($g['path_layout'])?>
					<?php while(false !== ($tpl = readdir($dirs))):?>
					<?php if($tpl=='.' || $tpl == '..' || $tpl == '_blank' || is_file($g['path_layout'].$tpl))continue?>
					<?php $dirs1 = opendir($g['path_layout'].$tpl)?>
					<option value="">--------------------------------</option>
					<?php while(false !== ($tpl1 = readdir($dirs1))):?>
					<?php if(!strstr($tpl1,'.php') || $tpl1=='_main.php')continue?>
					<option value="<?php echo $tpl?>/<?php echo $tpl1?>"<?php if($d['blog']['layout']==$tpl.'/'.$tpl1):?> selected="selected"<?php endif?>>ㆍ<?php echo getFolderName($g['path_layout'].$tpl)?>(<?php echo str_replace('.php','',$tpl1)?>)</option>
					<?php endwhile?>
					<?php closedir($dirs1)?>
					<?php endwhile?>
					<?php closedir($dirs)?>
					</select>
					<label style="color:#ff0000;"><input type="checkbox" name="iframe" value="Y"<?php if(!$R['uid']||$d['blog']['iframe']=='Y'):?> checked="checked"<?php endif?> />블로그 전용 레이아웃 사용</label>
				</td>
			</tr>
			<tr>
				<td class="td1">블로그테마</td>
				<td class="td2">
					<select name="theme_pc" class="select1">
					<option value="">&nbsp;+ 블로그 대표테마</option>
					<option value="">--------------------------------</option>
					<?php $tdir = $g['path_module'].$module.'/theme/_pc/'?>
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
					<?php $tdir = $g['path_module'].$module.'/theme/_mobile/'?>
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
					소 셜 연 동
					<img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('guide_snsconnect','block','none');" />	
				</td>
				<td class="td2">
					<select name="snsconnect" class="select1">
					<option value="0">&nbsp;+ 연동안함</option>
					<option value="0">--------------------------------</option>
					<?php $tdir = $g['path_module'].'social/inc/'?>
					<?php if(is_dir($tdir)):?>
					<?php $dirs = opendir($tdir)?>
					<?php while(false !== ($skin = readdir($dirs))):?>
					<?php if($skin=='.' || $skin == '..')continue?>
					<option value="social/inc/<?php echo $skin?>"<?php if($d['blog']['snsconnect']=='social/inc/'.$skin):?> selected="selected"<?php endif?>>ㆍ<?php echo str_replace('.php','',$skin)?></option>
					<?php endwhile?>
					<?php closedir($dirs)?>
					<?php endif?>
					</select>
					<div id="guide_snsconnect" class="guide hide">
					포스트 등록시 SNS에 동시등록을 가능하게 합니다.<br />
					이 서비스를 위해서는 소셜연동 모듈을 설치해야 합니다.<br />
					</div>
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
			<input type="submit" class="btnblue" value="<?php echo $R['uid']?'블로그속성 변경':'새블로그 만들기'?>" />
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
	if (f.blog.value == '')
	{
		if (f.id.value == '')
		{
			alert('블로그 아이디를 입력해 주세요.      ');
			f.id.focus();
			return false;
		}
		if (!chkFnameValue(f.id.value))
		{
			alert('블로그 아이디는 영문 대소문자/숫자/_ 만 사용가능합니다.      ');
			f.id.value = '';
			f.id.focus();
			return false;
		}
	}
	return confirm('정말로 실행하시겠습니까?         ');
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

