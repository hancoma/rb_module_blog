<?php
$sort	= $sort ? $sort : 'gid';
$orderby= $orderby ? $orderby : 'asc';
$recnum	= $recnum && $recnum < 301 ? $recnum : 30;
$bbsque	= 'uid';

if ($blogtype) $bbsque .= ' and blogtype='.$blogtype;
if ($where && $keyw)
{
	if (strstr('[id]',$where)) $bbsque .= " and ".$where."='".$keyw."'";
	else $bbsque .= getSearchSql($where,$keyw,$ikeyword,'or');	
}

$RCD = getDbArray($table[$module.'list'],$bbsque,'*',$sort,$orderby,$recnum,$p);
$NUM = getDbRows($table[$module.'list'],$bbsque);
$TPG = getTotalPage($NUM,$recnum);
?>

<div id="mainbox">

	<div class="sbox">
		<form name="procForm" action="<?php echo $g['s']?>/" method="get">
		<input type="hidden" name="r" value="<?php echo $r?>" />
		<input type="hidden" name="m" value="<?php echo $m?>" />
		<input type="hidden" name="module" value="<?php echo $module?>" />
		<input type="hidden" name="front" value="<?php echo $front?>" />
		<input type="hidden" name="blogtype" value="<?php echo $blogtype?>" />

		<div>
		<select name="sort" onchange="this.form.submit();">
		<option value="gid"<?php if($sort=='gid'):?> selected="selected"<?php endif?>>지정순서</option>
		<option value="uid"<?php if($sort=='uid'):?> selected="selected"<?php endif?>>개설일</option>
		<option value="num_w"<?php if($sort=='num_w'):?> selected="selected"<?php endif?>>자료수</option>
		<option value="num_c"<?php if($sort=='num_c'):?> selected="selected"<?php endif?>>댓글수</option>
		<option value="num_o"<?php if($sort=='num_o'):?> selected="selected"<?php endif?>>의견수</option>
		<option value="d_last"<?php if($sort=='d_last'):?> selected="selected"<?php endif?>>최근게시</option>
		</select>
		<select name="orderby" onchange="this.form.submit();">
		<option value="desc"<?php if($orderby=='desc'):?> selected="selected"<?php endif?>>역순</option>
		<option value="asc"<?php if($orderby=='asc'):?> selected="selected"<?php endif?>>정순</option>
		</select>

		<select name="recnum" onchange="this.form.submit();">
		<?php for($i=30;$i<=300;$i=$i+30):?>
		<option value="<?php echo $i?>"<?php if($i==$recnum):?> selected="selected"<?php endif?>><?php echo $i?>개</option>
		<?php endfor?>
		</select>
		<select name="where">
		<option value="name"<?php if($where=='name'):?> selected="selected"<?php endif?>>블로그명</option>
		<option value="id"<?php if($where=='id'):?> selected="selected"<?php endif?>>아이디</option>
		</select>

		<input type="text" name="keyw" value="<?php echo stripslashes($keyw)?>" class="input" />

		<input type="submit" value="검색" class="btnblue" />
		<input type="button" value="리셋" class="btngray" onclick="location.href='<?php echo $g['adm_href']?>';" />

		<a href="#." class="add" onclick="crLayer('블로그 추가','<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;module=<?php echo $module?>&amp;front=makeblog&amp;iframe=Y','iframe',800,650,'5%');" class="btn btn-primary"><img src="<?php echo $g['img_core']?>/_public/btn_add.gif" alt="추가" />새 블로그 만들기</a>
		</div>

		</form>
	</div>


	<div class="info">

		<div class="article">
			<a href="#." onclick="typeChange('blogtype','');"<?php if($blogtype==''):?> class="on"<?php endif?>>전체<i>(<?php echo getDbRows($table[$module.'list'],'')?>)</i></a>
			<a href="#." onclick="typeChange('blogtype','1');"<?php if($blogtype=='1'):?> class="on"<?php endif?>>개인블로그<i>(<?php echo getDbRows($table[$module.'list'],'blogtype=1')?>)</i></a>
			<a href="#." onclick="typeChange('blogtype','2');"<?php if($blogtype=='2'):?> class="on"<?php endif?>>팀블로그<i>(<?php echo getDbRows($table[$module.'list'],'blogtype=2')?>)</i></a>
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
		<col width="65"></col> 
		<col width="200"></col> 
		<col width="50"></col> 
		<col width="50"></col> 
		<col width="50"></col> 
		<col width="70"></col> 
		<col width="60"></col> 
		<col width="70"></col> 
		<col width="70"></col> 
		<col width="110"></col> 
		<col></col> 
		</colgroup> 
		<thead>
		<tr>
		<th scope="col" class="side1"><img src="<?php echo $g['img_core']?>/_public/ico_check_01.gif" class="hand" alt="" onclick="chkFlag('blog_members[]');" /></th>
		<th scope="col">번호</th>
		<th scope="col">형식</th>
		<th scope="col">아이디</th>
		<th scope="col">블로그명</th>
		<th scope="col">포스트</th>
		<th scope="col">댓글</th>
		<th scope="col">의견</th>
		<th scope="col">개설자</th>
		<th scope="col">레이아웃</th>
		<th scope="col">개설일</th>
		<th scope="col">최근등록</th>
		<th scope="col">관리</th>
		<th scope="col" class="side2"></th>
		</tr>
		</thead>
		<tbody>
		<?php while($R=db_fetch_array($RCD)):?>
		<?php $L=getOverTime($date['totime'],$R['d_last'])?>
		<?php $M = getDbData($table['s_mbrdata'],'memberuid='.$R['mbruid'],'*');?>
		<?php $d=array();include $g['path_module'].$module.'/var/var.'.$R['id'].'.php'?>
		<tr>
		<td><input type="checkbox" name="blog_members[]" value="<?php echo $R['uid']?>" /></td>
		<td><?php echo $NUM-((($p-1)*$recnum)+$_rec++)?></td>
		<td><?php echo $R['blogtype']==1?'개인':'팀'?></td>
		<td class="bid"><a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $module?>&amp;blog=<?php echo $R['id']?>" target="_blank"><?php echo $R['id']?></a></td>
		<td class="sbj">
			<input type="text" name="name_<?php echo $R['uid']?>" value="<?php echo $R['name']?>" />
		</td>
		<td class="ck"><a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;module=<?php echo $module?>&amp;front=post&amp;blog=<?php echo $R['uid']?>"><?php echo number_format($R['num_w'])?></a></td>
		<td class="ck"><a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;module=<?php echo $module?>&amp;front=comment&amp;blog=<?php echo $R['uid']?>"><?php echo number_format($R['num_c'])?></a></td>
		<td class="ck"><a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;module=<?php echo $module?>&amp;front=comment&amp;blog=<?php echo $R['uid']?>&amp;oneline=1"><?php echo number_format($R['num_o'])?></a></td>
		<td><?php echo $M['name']?></td>
		<td><?php echo $d['blog']['iframe']?'<b>전용</b>':'사이트'?></td>
		<td><?php echo getDateFormat($R['d_regis'],'Y.m.d')?></td>
		<td class="lst"><?php if($R['d_last']<$date['totime']):?><?php echo $L[1]<3?$L[0].$lang['sys']['time'][$L[1]].'전':getDateFormat($R['d_last'],'Y.m.d')?><?php if(getNew($R['d_last'],24)):?> <u>new</u><?php endif?><?php endif?></td>
		<td class="mng">
			<a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $module?>&amp;a=admin/deleteblog&amp;uid=<?php echo $R['uid']?>" onclick="return hrefCheck(this,true,'삭제하시면 모든 포스트가 지워지며 복구할 수 없습니다.\n정말로 삭제하시겠습니까?');" class="del">삭제</a>
			<a href="#." onclick="crLayer('블로그 설정','<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;module=<?php echo $module?>&amp;front=makeblog&amp;iframe=Y&amp;uid=<?php echo $R['uid']?>','iframe',800,650,'5%');">설정</a>
			<a href="#." onclick="crLayer('블로그 카테고리','<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;module=<?php echo $module?>&amp;front=makecategory&amp;iframe=Y&amp;uid=<?php echo $R['uid']?>','iframe',800,650,'5%');">분류</a>	
		</td>
		<td></td>
		</tr>
		<?php endwhile?>
		<?php if(!$NUM):?>
		<tr>
		<td colspan="13" class="none">블로그가 없습니다.</td>
		<td class="none"></td>
		</tr> 
		<?php endif?>
		</tbody>
		</table>

		<div class="pagebox01">
		<script type="text/javascript">getPageLink(10,<?php echo $p?>,<?php echo $TPG?>,'<?php echo $g['img_core']?>/page/default');</script>
		</div>	

		<input type="button" value="선택/해제" class="btngray" onclick="chkFlag('blog_members[]');" />
		<input type="button" value="수정" class="btnblue" onclick="actCheck('admin/multi_config');" />
		</form>
	</div>



</div>



<script type="text/javascript">
//<![CDATA[
function typeChange(fi,val)
{
	var f = document.procForm;
	eval('f.'+fi).value = val;
	f.submit();
}
function actCheck(act)
{
	var f = document.listForm;
    var l = document.getElementsByName('blog_members[]');
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
		alert('선택된 블로그가 없습니다.      ');
		return false;
	}
	
	if (act == 'admin/multi_config')
	{
		if(confirm('정말로 실행하시겠습니까?    '))
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


