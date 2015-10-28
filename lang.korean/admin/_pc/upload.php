<?php
$inbox = $inbox ? $inbox : 1;
$sort	= $sort ? $sort : 'gid';
$orderby= $orderby ? $orderby : 'asc';
$recnum	= $recnum && $recnum < 301 ? $recnum : 30;
$bbsque	= 'uid';

if ($blog) $bbsque .= ' and blog='.$blog;
if ($parent) $bbsque .= ' and parent='.$parent;
if ($notuse) $bbsque .= ' and parent=0';
if ($mbruid) $bbsque .= ' and mbruid='.$mbruid;
if ($inbox==2) $bbsque .= ' and type=2';
if ($inbox==3) $bbsque .= ' and type<>2';
if ($ext) $bbsque .= " and ext='".$ext."'";
if ($where && $keyw)
{
	$bbsque .= getSearchSql($where,$keyw,$ikeyword,'or');	
}

$RCD = getDbArray($table[$module.'upload'],$bbsque,'*',$sort,$orderby,$recnum,$p);
$NUM = getDbRows($table[$module.'upload'],$bbsque);
$TPG = getTotalPage($NUM,$recnum);
?>
<div id="mainbox">


	<div class="sbox">
		<form name="procForm" action="<?php echo $g['s']?>/" method="get">
		<input type="hidden" name="r" value="<?php echo $r?>" />
		<input type="hidden" name="m" value="<?php echo $m?>" />
		<input type="hidden" name="module" value="<?php echo $module?>" />
		<input type="hidden" name="front" value="<?php echo $front?>" />
		<input type="hidden" name="inbox" value="<?php echo $inbox?>" />
		<input type="hidden" name="mbruid" value="<?php echo $mbruid?>" />
		<input type="hidden" name="ext" value="<?php echo $ext?>" />

		<div>
		<select name="blog" style="width:250px;" onchange="this.form.submit();">
		<option value="">&nbsp;+ 블로그(전체)</option>
		<option value="">---------------------------------------------------</option>
		<?php $BLOGS = getDbArray($table[$module.'list'],'','*','gid','asc',100,1)?>
		<?php while($B=db_fetch_array($BLOGS)):?>
		<option value="<?php echo $B['uid']?>"<?php if($B['uid']==$blog):?> selected="selected"<?php endif?>>ㆍ<?php echo $B['name']?>(<?php echo $B['id']?>)</option>
		<?php endwhile?>
		</select>
		&nbsp;
		<select name="recnum" onchange="this.form.submit();">
		<?php for($i=30;$i<=300;$i=$i+30):?>
		<option value="<?php echo $i?>"<?php if($i==$recnum):?> selected="selected"<?php endif?>><?php echo $i?>개</option>
		<?php endfor?>
		</select>
		<select name="where">
		<option value="name"<?php if($where=='name'):?> selected="selected"<?php endif?>>파일명</option>
		<option value="caption"<?php if($where=='caption'):?> selected="selected"<?php endif?>>캡션</option>
		</select>

		<input type="text" name="keyw" value="<?php echo stripslashes($keyw)?>" class="input" />

		<input type="submit" value="검색" class="btnblue" />
		<input type="button" value="리셋" class="btngray" onclick="location.href='<?php echo $g['adm_href']?>';" />

		&nbsp;&nbsp;
		<label><input type="checkbox" name="notuse" value="1"<?php if($notuse):?> checked="checked"<?php endif?>onclick="this.form.submit();" />미사용파일</label>

		</div>

		</form>
	</div>


	<div class="info">

		<div class="article">
			<a href="#."<?php if($inbox==1):?> class="on"<?php endif?> onclick="inboxSelect(1);">전체<i>(<?php echo getDbRows($table[$module.'upload'],'')?>)</i></a>
			<a href="#."<?php if($inbox==2):?> class="on"<?php endif?> onclick="inboxSelect(2);">이미지<i>(<?php echo getDbRows($table[$module.'upload'],'type=2')?>)</i></a>
			<a href="#."<?php if($inbox==3):?> class="on"<?php endif?> onclick="inboxSelect(3);">파일<i>(<?php echo getDbRows($table[$module.'upload'],'type<>2')?>)</i></a>
		</div>
		
		<div class="category">

		</div>
		<div class="clear"></div>
	</div>


	<div class="bbsbody">
		<form name="listForm" action="<?php echo $g['s']?>/" method="post">
		<input type="hidden" name="r" value="<?php echo $r?>" />
		<input type="hidden" name="m" value="<?php echo $module?>" />
		<input type="hidden" name="a" value="" />


		<table>
		<colgroup> 
		<col width="30"></col> 
		<col width="40"></col> 
		<col width="40"></col> 
		<col width="400"></col> 
		<col width="60"></col> 
		<col width="50"></col> 
		<col width="70"></col> 
		<col width="80"></col> 
		<col width="50"></col> 
		<col width="100"></col> 
		<col></col> 
		</colgroup> 
		<thead>
		<tr>
		<th scope="col" class="side1"><img src="<?php echo $g['img_core']?>/_public/ico_check_01.gif" class="hand" alt="" onclick="chkFlag('upfile_members[]');" /></th>
		<th scope="col">번호</th>
		<th scope="col">형식</th>
		<th scope="col">파일명</th>
		<th scope="col">폴더</th>
		<th scope="col">다운</th>
		<th scope="col">용량</th>
		<th scope="col">소유자</th>
		<th scope="col">사용처</th>
		<th scope="col">등록일시</th>
		<th scope="col" class="side2"></th>
		</tr>
		</thead>
		<tbody>
		<?php while($R=db_fetch_array($RCD)):?>
		<?php $_M=getDbData($table['s_mbrdata'],'memberuid='.$R['mbruid'],'*')?>
		<?php $_B=getUidData($table[$module.'list'],$R['blog'])?>
		<?php $L1=getOverTime($date['totime'],$R['d_regis'])?>
		<tr>
		<td><input type="checkbox" name="upfile_members[]" value="<?php echo $R['uid']?>" /></td>
		<td><?php echo $NUM-((($p-1)*$recnum)+$_rec++)?></td>
		<td><a href="#." onclick="extDrop('<?php echo getExt($R['name'])?>');"><?php echo getExt($R['name'])?></a></td>
		<td class="sbj">
			<?php if($R['type']==2):?>
			<img src="<?php echo $R['url'].'/'.$R['folder'].'/'.$R['thumbname']?>" align="left" class="hand" onclick="imgOrignWin('<?php echo $R['url'].'/'.$R['folder'].'/'.$R['tmpname']?>');" alt="" />
			<?php endif?>
			<?php echo $R['name']?>
			<?php if($R['width']&&$R['height']):?>
			<div>(<?php echo $R['width']?> * <?php echo $R['height']?> px)</div><?php endif?>
		</td>
		<td><?php echo $R['folder']?></td>
		<td><?php echo $R['down']?></td>
		<td><?php echo getSizeFormat($R['size'],1)?></td>
		<td><a href="#" onclick="userDrop('<?php echo $R['mbruid']?>');"><?php echo $_M[$_HS['nametype']]?></a></td>
		<td>
			<?php if($R['parent']):?>
			<a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $module?>&amp;blog=<?php echo $_B['id']?>&amp;front=list&amp;uid=<?php echo $R['parent']?>" target="_blank">보기</a>
			<?php else:?>
			-
			<?php endif?>
		</td>
		<td class="lst"><?php echo $L1[1]<3?$L1[0].$lang['sys']['time'][$L1[1]].'전':getDateFormat($R['d_regis'],'Y.m.d H:i')?><?php if(getNew($R['d_regis'],24)):?> <u>new</u><?php endif?></td>

		<td></td>
		</tr>
		<?php endwhile?>
		<?php if(!$NUM):?>
		<tr>
		<td colspan="10" class="none">첨부파일이 없습니다.</td>
		<td class="none"></td>
		</tr> 
		<?php endif?>
		</tbody>
		</table>

		<div class="pagebox01">
		<script type="text/javascript">getPageLink(10,<?php echo $p?>,<?php echo $TPG?>,'<?php echo $g['img_core']?>/page/default');</script>
		</div>	

		<input type="button" value="선택/해제" class="btngray" onclick="chkFlag('upfile_members[]');" />
		<input type="button" value="삭제" class="btnblue" onclick="actCheck('admin/multi_file_delete');" />
		</form>
	</div>



</div>

<script type="text/javascript">
//<![CDATA[
function inboxSelect(n)
{
	var f = document.procForm;
	f.inbox.value = n;
	f.submit();
}
function userDrop(user)
{
	var f = document.procForm;
	f.mbruid.value = user;
	f.submit();
}
function extDrop(xext)
{
	var f = document.procForm;
	f.ext.value = xext;
	f.submit();
}
function actCheck(act)
{
	var f = document.listForm;
    var l = document.getElementsByName('upfile_members[]');
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
		alert('선택된 첨부파일이 없습니다.      ');
		return false;
	}
	
	if (act == 'admin/multi_file_delete')
	{
		if(confirm('정말로 삭제하시겠습니까?    '))
		{
			getIframeForAction(f);
			f.a.value = act;
			f.submit();
		}
	}
	return false;
}

//]]>
</script>

