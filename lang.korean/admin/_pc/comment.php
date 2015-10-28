<?php
$inbox = $inbox ? $inbox : 1;
$sort	= $sort ? $sort : 'uid';
$orderby= $orderby ? $orderby : 'asc';
$recnum	= $recnum && $recnum < 301 ? $recnum : 30;
$bbsque	= 'hidden='.($inbox-1);

if ($blog) $bbsque .= ' and blog='.$blog;
if ($parent) $bbsque .= ' and parent='.$parent;
if ($where && $keyw)
{
	$bbsque .= getSearchSql($where,$keyw,$ikeyword,'or');	
}

$RCD = getDbArray($table[$module.'comment'],$bbsque,'*',$sort,$orderby,$recnum,$p);
$NUM = getDbRows($table[$module.'comment'],$bbsque);
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
		<input type="hidden" name="parent" value="<?php echo $parent?>" />
		<input type="hidden" name="comment" value="<?php echo $comment?>" />

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
		<option value="content"<?php if($where=='content'):?> selected="selected"<?php endif?>>내용</option>
		</select>

		<input type="text" name="keyw" value="<?php echo stripslashes($keyw)?>" class="input" />

		<input type="submit" value="검색" class="btnblue" />
		<input type="button" value="리셋" class="btngray" onclick="location.href='<?php echo $g['adm_href']?>';" />

		&nbsp;&nbsp;
		<label><input type="checkbox" name="oneline" value="1"<?php if($oneline):?> checked="checked"<?php endif?>onclick="this.form.submit();" />한줄의견보기</label>

		</div>

		</form>
	</div>


	<div class="info">

		<div class="article">
			<a href="#."<?php if($inbox==1):?> class="on"<?php endif?> onclick="inboxSelect(1);">승인<i>(<?php echo getDbRows($table[$module.'comment'],'hidden=0')?>)</i></a>
			<a href="#."<?php if($inbox==2):?> class="on"<?php endif?> onclick="inboxSelect(2);">보류<i>(<?php echo getDbRows($table[$module.'comment'],'hidden=1')?>)</i></a>
			<a href="#."<?php if($inbox==3):?> class="on"<?php endif?> onclick="inboxSelect(3);">스팸<i>(<?php echo getDbRows($table[$module.'comment'],'hidden=2')?>)</i></a>
			<a href="#."<?php if($inbox==4):?> class="on"<?php endif?> onclick="inboxSelect(4);">휴지통<i>(<?php echo getDbRows($table[$module.'comment'],'hidden=3')?>)</i></a>
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
		<col width="70"></col> 
		<col width="500"></col> 
		<col width="50"></col> 
		<col width="100"></col> 
		<col></col> 
		</colgroup> 
		<thead>
		<tr>
		<th scope="col" class="side1"><img src="<?php echo $g['img_core']?>/_public/ico_check_01.gif" class="hand" alt="" onclick="chkFlag('comment_members[]');" /></th>
		<th scope="col">번호</th>
		<th scope="col"></th>
		<th scope="col">작성자</th>
		<th scope="col">내용</th>
		<th scope="col">한줄의견</th>
		<th scope="col">등록일시</th>
		<th scope="col" class="side2"></th>
		</tr>
		</thead>
		<tbody>
		<?php while($R=db_fetch_array($RCD)):?>
		<?php $_M=getDbData($table['s_mbrdata'],'memberuid='.$R['mbruid'],'*')?>
		<?php $_B=getUidData($table[$module.'list'],$R['blog'])?>
		<?php $_R=getUidData($table[$module.'data'],$R['parent'])?>
		<?php $L1=getOverTime($date['totime'],$R['d_regis'])?>
		<tr>
		<td><input type="checkbox" name="comment_members[]" value="<?php echo $R['uid']?>" /></td>
		<td><?php echo $NUM-((($p-1)*$recnum)+$_rec++)?></td>
		<td valign="top">
			<?php if($_M['photo']):?>
			<img src="<?php echo $g['s']?>/_var/simbol/<?php echo $_M['photo']?>" alt="" width="35" height="35" align="left" />
			<?php else:?>
			<img src="<?php echo $g['s']?>/_var/simbol/1.gif" alt="" width="35" height="35" align="left" />			
			<?php endif?>
			
		</td>
		<td valign="top" style="padding-top:20px;"><?php echo $_M['memberuid']?$_M[$_HS['nametype']]:$R['name']?></td>
		<td class="sbj">
			<a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $module?>&amp;blog=<?php echo $_B['id']?>&amp;front=list&amp;uid=<?php echo $_R['uid']?>&amp;comment=<?php echo $R['uid']?>" target="_blank"><?php echo $_R['subject']?></a>
			<div class="cmt">
			<?php echo getStrCut($R['content'],100,'..')?>
			</div>
			<?php if($comment==$R['uid']||$oneline):?>
				<?php $_OTCD = getDbArray($table[$module.'oneline'],'parent='.$R['uid'],'*','uid','desc',20,1)?>
				<?php while($O=db_fetch_array($_OTCD)):?>
				<?php $_M1=getDbData($table['s_mbrdata'],'memberuid='.$O['mbruid'],'*')?>
				<div class="oneline">
				<div class="ico">
				<?php if($_M1['photo']):?>
				<img src="<?php echo $g['s']?>/_var/simbol/<?php echo $_M1['photo']?>" alt="" width="35" height="35" />
				<?php else:?>
				<img src="<?php echo $g['s']?>/_var/simbol/1.gif" alt="" width="35" height="35" />			
				<?php endif?>
				</div>
				<div class="cont">
				<?php echo getStrCut($O['content'],90,'..')?><br />
				<?php echo $_M1[$_HS['nametype']]?> , <?php echo getDateFormat($O['d_regis'],'Y.m.d H:i')?>
				<?php if(getNew($O['d_regis'],24)):?> <u>new</u><?php endif?>
				</div>
				<div class="clear"></div>
				</div>
				<?php endwhile?>
			<?php endif?>
		</td>
		<td class="ck">
			<?php if($R['oneline']):?>
			<a href="#." onclick="onelineClick('<?php echo $R['uid']?>');"><?php echo $R['oneline']?></a>
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
		<td colspan="6" class="none">댓글이 없습니다.</td>
		<td class="none"></td>
		</tr> 
		<?php endif?>
		</tbody>
		</table>

		<div class="pagebox01">
		<script type="text/javascript">getPageLink(10,<?php echo $p?>,<?php echo $TPG?>,'<?php echo $g['img_core']?>/page/default');</script>
		</div>	

		<input type="button" value="선택/해제" class="btngray" onclick="chkFlag('comment_members[]');" />
		<input type="button" value="삭제" class="btnblue" onclick="actCheck('admin/multi_comment_delete');" />
		<select name="inbox">
		<?php if($inbox!=1):?><option value="0">승인</option><?php endif?>
		<?php if($inbox!=2):?><option value="1">보류</option><?php endif?>
		<?php if($inbox!=3):?><option value="2">스팸</option><?php endif?>
		<?php if($inbox!=4):?><option value="3">휴지통</option><?php endif?>
		</select>
		<input type="button" value="옮기기" class="btnblue" onclick="actCheck('admin/multi_comment_move');" />
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
function onelineClick(uid)
{
	var f = document.procForm;
	f.comment.value = uid;
	f.submit();
}
function actCheck(act)
{
	var f = document.listForm;
    var l = document.getElementsByName('comment_members[]');
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
		alert('선택된 댓글이 없습니다.      ');
		return false;
	}
	
	if (act == 'admin/multi_comment_delete')
	{
		if(confirm('정말로 삭제하시겠습니까?    '))
		{
			getIframeForAction(f);
			f.a.value = act;
			f.submit();
		}
	}
	if (act == 'admin/multi_comment_move')
	{
		if(confirm('정말로 옮기시겠습니까?    '))
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
