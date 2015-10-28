<?php
$maxupsize = str_replace('M','',ini_get('upload_max_filesize'));
?>

<div id="mainbox">
	<h2>데이터 마이그레이션</h2>
	<div class="notice">
		타 블로그의 데이터를 킴스큐-Rb 블로그용 데이터로 이전할 수 있습니다.<br />
		킴스큐-Rb 블로그용 마이그레이션 XML파일을 직접 등록하시거나 주소를 입력해 주세요..<br />
	</div>


	<form name="procForm" action="<?php echo $g['s']?>/" method="post" enctype="multipart/form-data" onsubmit="return saveCheck(this);">
	<input type="hidden" name="r" value="<?php echo $r?>" />
	<input type="hidden" name="m" value="<?php echo $module?>" />
	<input type="hidden" name="a" value="admin/mig" />
	<input type="hidden" name="category_select" value="" />

	<div class="msg">
		<img src="<?php echo $g['img_core']?>/_public/ico_notice.gif" alt="" />
		블로그 데이터를 이전합니다.(블로그와 카테고리를 미리 생성해주세요)
	</div>

	<table>
		<?php $BLOGLIST = getDbArray($table[$module.'list'],'uid','*','gid','asc',0,1)?>
		<tr>
			<td class="td1">이전대상 블로그</td>
			<td class="td2">
				<select name="blog" class="select1" onchange="goHref('<?php echo $g['s']?>/?r=<?php echo $r?>&m=<?php echo $m?>&module=<?php echo $module?>&front=<?php echo $front?>&blog='+this.value);">
				<option value="">&nbsp;+ 선택하세요</option>
				<option value="">----------------------------------------------------------------</option>
				<?php while($R=db_fetch_array($BLOGLIST)):?>
				<option value="<?php echo $R['uid']?>"<?php if($R['uid']==$blog):$_blog=$R['id']?> selected="selected"<?php endif?>>ㆍ<?php echo $R['name']?>(<?php echo $R['id']?> - <?php echo number_format($R['num_w'])?>개)</option>
				<?php endwhile?>
				</select>
				<a href="#." onclick="crLayer('블로그 추가','<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;module=<?php echo $module?>&amp;front=makeblog&amp;iframe=Y','iframe',800,650,'5%');"><img src="<?php echo $g['img_core']?>/_public/btn_add.gif" alt="게시판만들기" /></a>
			</td>
		</tr>
		<?php if($blog):$ISCAT = getDbRows($table[$module.'category'],'blog='.$blog)?>
		<?php include $g['path_module'].$module.'/lib/tree.func.php'?>
		<?php $checkbox=true?>
		<tr>
			<td class="td1">대상 카테고리</td>
			<td class="td2">


<div class="tbox">
	<div class="treetop"><a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $module?>&amp;blog=<?php echo $_blog?>" target="_blank">블로그보기</a></div>
	<div class="joinimg"></div>
	<div class="tree">

	<?php if($ISCAT):?>
	<script type="text/javascript">
	//<![CDATA[
	var TreeImg = "<?php echo $g['img_core']?>/tree/default_none";
	var ulink = "#.";
	//]]>
	</script>
	<script type="text/javascript" src="<?php echo $g['s']?>/_core/js/tree.js"></script>
	<script type="text/javascript">
	//<![CDATA[
	var TREE_ITEMS = [['', null, <?php getMenuShowBlog($blog,$table[$module.'category'],0,0,0,$cat,$CXA,0)?>]];
	new tree(TREE_ITEMS, tree_tpl);
	<?php echo $MenuOpen?>
	//]]>
	</script>
	<?php else:?>
	<div class="none">카테고리가 없습니다.</div>
	<?php endif?>
	</div>
</div>


			</td>
		</tr>
		<?php endif?>
		<tr>
			<td class="td1">XML파일 등록</td>
			<td class="td2">
				<input type="file" name="xmlfile" size="65" value="" class="file" /> (<?php echo $maxupsize?>MB 이내)
				<a href="<?php echo $g['s']?>/modules/<?php echo $module?>/lang.korean/admin/_pc/var/post.xml" target="_blank" class="sp">샘플보기</a>
				<div class="guide">
				이전할 데이터는 샘플파일과 같은 형식이어야 합니다.<br />
				샘플파일(modules/<?php echo $module?>/lang.korean/admin/_pc/var/post.xml)과 같은 형식으로<br />
				이전할 블로그의 포스트를 추출한 후 해당 XML 파일을 등록해 주세요.<br />
				이전가능대상 : 제목,요약내용,상세내용,태그,조회수,등록일시,수정일시<br />
				</div>
			</td>
		</tr>
	</table>

	<div class="submitbox">
		<input type="submit" class="btnblue" value=" 이전하기 " />
		<label><input type="checkbox" name="viewresult" value="1" />이전 후 결과보기</label>
	</div>

	</form>


</div>

<script type="text/javascript">
//<![CDATA[
var migflag = false;
function saveCheck(f)
{
	if (migflag == true)
	{
		alert('데이터를 이전중에 있습니다. 잠시만 기다려 주세요.    ');
		return false;
	}
	if (f.blog.value == '')
	{
		alert('이전대상 블로그를 선택해 주세요.    ');
		f.blog.focus();
		return false;
	}

    var l = document.getElementsByName('category_members[]');
    var n = l.length;
	var j = 0;
    var i;
	var s = '';

    for (i = 0; i < n; i++)
	{
		if(l[i].checked == true)
		{
			j++;
			s += '['+l[i].value+']';
		}
	}
	if (!j)
	{
		alert('지정된 카테고리가 없습니다.     ');
		return false;
	}
	if (j > 1)
	{
		alert('이전할 카테고리는 1개만 선택가능합니다.\n(이전 후 수정시 복수선택가능)     ');
		return false;
	}

	f.category_select.value = s;

	if (f.xmlfile.value == '')
	{
		alert('XML파일을 등록해 주세요.     ');
		f.xmlfile.focus();
		return false;
	}

	var extarr = f.xmlfile.value.split('.');
	var filext = extarr[extarr.length-1].toLowerCase();
	var permxt = '[xml]';

	if (permxt.indexOf(filext) == -1)
	{
		alert('xml 파일만 등록할 수 있습니다.    ');
		f.xmlfile.focus();
		return false;
	}

	getIframeForAction(f);


	if(confirm('데이터의 양에 따라 다소 시간이 걸릴 수 있습니다.  \n정말로 실행하시겠습니까?'))
	{
		migflag = true;
		return true;
	}
	return false;
}
//]]>
</script>


