<?php
$publish = $publish ? $publish : 1;
$sort	= $sort ? $sort : 'gid';
$orderby= $orderby ? $orderby : 'asc';
$recnum	= $recnum && $recnum < 301 ? $recnum : 30;
$bbsque	= 'isreserve='.($publish==1?'0':'1');

if ($blog) $bbsque .= ' and blog='.$blog;
if ($isphoto) $bbsque .= ' and isphoto='.$isphoto; 
if ($isvod) $bbsque .= ' and isvod='.$isvod; 
if ($cutcomment) $bbsque .= ' and cutcomment='.$cutcomment; 
if ($where && $keyw)
{
	$bbsque .= getSearchSql($where,$keyw,$ikeyword,'or');	
}

$RCD = getDbArray($table[$module.'data'],$bbsque,'*',$sort,$orderby,$recnum,$p);
$NUM = getDbRows($table[$module.'data'],$bbsque);
$TPG = getTotalPage($NUM,$recnum);
?>

<div id="mainbox">


	<div class="sbox">
		<form name="procForm" action="<?php echo $g['s']?>/" method="get">
		<input type="hidden" name="r" value="<?php echo $r?>" />
		<input type="hidden" name="m" value="<?php echo $m?>" />
		<input type="hidden" name="module" value="<?php echo $module?>" />
		<input type="hidden" name="front" value="<?php echo $front?>" />
		<input type="hidden" name="publish" value="<?php echo $publish?>" />

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
		<option value="subject"<?php if($where=='subject'):?> selected="selected"<?php endif?>>제목</option>
		<option value="tag"<?php if($where=='tag'):?> selected="selected"<?php endif?>>태그</option>
		</select>

		<input type="text" name="keyw" value="<?php echo stripslashes($keyw)?>" class="input" />

		<input type="submit" value="검색" class="btnblue" />
		<input type="button" value="리셋" class="btngray" onclick="location.href='<?php echo $g['adm_href']?>';" />

		&nbsp;&nbsp;
		<label><input type="checkbox" name="inccont" value="1"<?php if($inccont):?> checked="checked"<?php endif?>onclick="this.form.submit();" />리뷰보기</label>
		<label><input type="checkbox" name="seo" value="1"<?php if($seo):?> checked="checked"<?php endif?>onclick="this.form.submit();" />SEO</label>
		<label><input type="checkbox" name="isphoto" value="1"<?php if($isphoto):?> checked="checked"<?php endif?>onclick="this.form.submit();" />사진</label>
		<label><input type="checkbox" name="isvod" value="1"<?php if($isvod):?> checked="checked"<?php endif?>onclick="this.form.submit();" />동영상</label>
		<label><input type="checkbox" name="cutcomment" value="1"<?php if($cutcomment):?> checked="checked"<?php endif?>onclick="this.form.submit();" />댓글차단</label>
		</div>

		</form>
	</div>


	<div class="info">

		<div class="article">
			<a href="#."<?php if($publish==1):?> class="on"<?php endif?> onclick="publishSelect(1);">발행<i>(<?php echo getDbRows($table[$module.'data'],'isreserve=0')?>)</i></a>
			<a href="#."<?php if($publish==2):?> class="on"<?php endif?> onclick="publishSelect(2);">예약<i>(<?php echo getDbRows($table[$module.'data'],'isreserve=1')?>)</i></a>
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
		<input type="hidden" name="seo" value="<?php echo $seo?>" />


		<table>
		<colgroup> 
		<col width="30"></col> 
		<col width="40"></col> 
		<col width="30"></col> 
		<col width="30"></col> 
		<col width="30"></col> 
		<col width="330"></col> 
		<col width="200"></col> 
		<col width="60"></col> 
		<col width="70"></col> 
		<col width="40"></col> 
		<col width="100"></col> 
		<col></col> 
		</colgroup> 
		<thead>
		<tr>
		<th scope="col" class="side1"><img src="<?php echo $g['img_core']?>/_public/ico_check_01.gif" class="hand" alt="" onclick="chkFlag('post_members[]');" /></th>
		<th scope="col">번호</th>
		<th scope="col">사진</th>
		<th scope="col">영상</th>
		<th scope="col">댓차</th>
		<th scope="col">제목(열람)</th>
		<th scope="col">태그</th>
		<th scope="col">작성자</th>
		<th scope="col">댓글</th>
		<th scope="col">첨부</th>
		<th scope="col"><?php echo $publish==1?'등록':'예약'?>일시(수정)</th>
		<th scope="col" class="side2"></th>
		</tr>
		</thead>
		<tbody>
		<?php while($R=db_fetch_array($RCD)):?>
		<?php $_B=getUidData($table[$module.'list'],$R['blog'])?>
		<?php $_M=getDbData($table['s_mbrdata'],'memberuid='.$R['mbruid'],'*')?>
		<?php $L1=getOverTime($date['totime'],$R['d_regis'])?>
		<tr>
		<td><input type="checkbox" name="post_members[]" value="<?php echo $R['uid']?>" /></td>
		<td><?php echo $NUM-((($p-1)*$recnum)+$_rec++)?></td>
		<td><?php echo $R['isphoto']?'Y':'-'?></td>
		<td><?php echo $R['isvod']?'Y':'-'?></td>
		<td><?php echo $R['cutcomment']?'Y':'-'?></td>
		<td class="sbj">

			<a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $module?>&amp;blog=<?php echo $_B['id']?>&amp;front=list&amp;uid=<?php echo $R['uid']?>" target="_blank"><?php echo $R['subject']?></a>
			<div id="_review_<?php echo $R['uid']?>"<?php if(!$inccont):?> class="hide"<?php endif?>><?php echo $R['review']?getStrCut($R['review'],150,'..'):getStrCut(getStripTags($R['content']),150,'..')?></div>
		</td>
		<td class="tag">
			<?php if($R['tag']):?>
			<?php $_tags=explode(',',$R['tag'])?>
			<?php $_tagn=count($_tags)?>
			<?php $i=0;for($i = 0; $i < $_tagn; $i++):?>
			<?php $_tagk=trim($_tags[$i])?>
			<a href="#." onclick="tagDrop('<?php echo $_tagk?>');"><?php echo $_tagk?></a><?php if($i < $_tagn-1):?>, <?php endif?>
			<?php endfor?>
			<?php else:?>
			-
			<?php endif?>		
		</td>
		<td class="name tooltip">
			<?php echo $_M[$_HS['nametype']]?>
			<span class="_left _l250 _w250">
			<b>작성자 로그</b><hr />
			<?php echo str_replace('<s>','<br />',str_replace('|',' | ',$R['log']))?>
			<i></i>
			</span>
		</td>
		<td class="ck">
		<?php if($R['comment']):?>
		<a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;module=<?php echo $module?>&amp;front=comment&amp;parent=<?php echo $R['uid']?>"><?php echo $R['comment']?><?php if($R['oneline']):?> + <?php echo $R['oneline']?><?php endif?></a>
		<?php else:?>
		-
		<?php endif?>
		</td>
		<td class="ck">
		<?php if($R['upload']):?>
		<?php $_u=getArrayString($R['upload'])?>
		<a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;module=<?php echo $module?>&amp;front=upload&amp;parent=<?php echo $R['uid']?>"><?php echo $_u['count']?></a>
		<?php else:?>
		-
		<?php endif?>
		</td>
		<td class="lst">
		<a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $module?>&amp;blog=<?php echo $_B['id']?>&amp;front=write&amp;uid=<?php echo $R['uid']?>" target="_blank">
		<?php if($R['isreserve']):?>
		<?php echo getDateFormat($R['d_regis'],'Y.m.d H:i')?>
		<?php else:?>
		<?php echo $L1[1]<3?$L1[0].$lang['sys']['time'][$L1[1]].'전':getDateFormat($R['d_regis'],'Y.m.d H:i')?><?php if(getNew($R['d_regis'],24)):?> <u>new</u><?php endif?>
		<?php endif?>
		</a>
		</td>

		<td></td>
		</tr>
		<?php if($seo):$_SEO=getDbData($table[$module.'seo'],'parent='.$R['uid'],'*')?>
		<tr class="seotr">
		<td colspan="5"></td>
		<td colspan="6" class="seo">
		
			<table>
				<tr>
					<td class="xtd1 b">
						타이틀(title)
					</td>
					<td class="xtd2">
						<input type="text" name="s_title<?php echo $R['uid']?>" value="<?php echo $_SEO['title']?>" class="input sname2" />
						<a href="#." onclick="document.listForm.s_title<?php echo $R['uid']?>.value='<?php echo $R['subject']?>';">제목과 동일</a>
					</td>
				</tr>
				<tr>
					<td class="xtd1 b">
						주제(subject)
					</td>
					<td class="xtd2">
						<input type="text" name="s_subject<?php echo $R['uid']?>" value="<?php echo $_SEO['subject']?>" class="input sname2" />
						<a href="#." onclick="document.listForm.s_subject<?php echo $R['uid']?>.value='<?php echo $R['subject']?>';">제목과 동일</a>
					</td>
				</tr>
				<tr>
					<td class="xtd1 b">
						키워드(keywords)
					</td>
					<td class="xtd2">
						<textarea name="s_keywords<?php echo $R['uid']?>" rows="2" cols="39"><?php echo $_SEO['keywords']?></textarea>
						<a href="#." onclick="document.listForm.s_keywords<?php echo $R['uid']?>.value='<?php echo $R['tag']?>';">태그와 동일</a>
					</td>
				</tr>
				<tr>
					<td class="xtd1 b">
						설명(description)
					</td>
					<td class="xtd2">
						<textarea name="s_desc<?php echo $R['uid']?>" rows="2" cols="39"><?php echo $_SEO['description']?></textarea>
						<a href="#." onclick="document.listForm.s_desc<?php echo $R['uid']?>.value=getId('_review_<?php echo $R['uid']?>').innerHTML;">리뷰와 동일</a>
					</td>
				</tr>
				<tr>
					<td class="xtd1 b">
						분류(classification)
					</td>
					<td class="xtd2">
						<input type="text" name="s_class<?php echo $R['uid']?>" value="<?php echo $_SEO['classification']?>" class="input sname2" />
					</td>
				</tr>
				<tr>
					<td class="xtd1">
						연락처(reply-to)
					</td>
					<td class="xtd2">
						<input type="text" name="s_replyto<?php echo $R['uid']?>" value="<?php echo $_SEO['replyto']?$_SEO['replyto']:$my['email']?>" class="input sname2" />
					</td>
				</tr>
				<tr>
					<td class="xtd1">
						언어(content-language)
					</td>
					<td class="xtd2">
						<input type="text" name="s_language<?php echo $R['uid']?>" value="<?php echo $_SEO['language']?$_SEO['language']:'kr'?>" size="10" class="input" />
					</td>
				</tr>
				<tr>
					<td class="xtd1">
						제작일(build)
					</td>
					<td class="xtd2">
						<input type="text" name="s_build<?php echo $R['uid']?>" value="<?php echo $_SEO['build']?$_SEO['build']:date('Y.m.d')?>" size="10" class="input" />
						<input type="button" value="수정하기" class="btngray" onclick="seoUpdate('<?php echo $R['uid']?>');" />
					</td>
				</tr>
			</table>
		
		</td>
		<td></td>
		</tr>
		<?php endif?>
		<?php endwhile?>
		<?php if(!$NUM):?>
		<tr>
		<td colspan="11" class="none">포스트가 없습니다.</td>
		<td class="none"></td>
		</tr> 
		<?php endif?>
		</tbody>
		</table>

		<div class="pagebox01">
		<script type="text/javascript">getPageLink(10,<?php echo $p?>,<?php echo $TPG?>,'<?php echo $g['img_core']?>/page/default');</script>
		</div>	

		<input type="button" value="선택/해제" class="btngray" onclick="chkFlag('post_members[]');" />
		<input type="button" value="삭제" class="btnblue" onclick="actCheck('admin/multi_post_delete');" />
		<?php if($seo):?>
		<input type="button" value="수정" class="btnblue" onclick="actCheck('admin/multi_seo_update');" />
		<?php endif?>
		</form>
	</div>



</div>

<script type="text/javascript">
//<![CDATA[
function publishSelect(n)
{
	var f = document.procForm;
	f.publish.value = n;
	f.submit();
}
function tagDrop(t)
{
	var f = document.procForm;
	f.where.value = 'tag';
	f.keyw.value = t;
	f.submit();

}
function seoUpdate(uid)
{
	var f = document.listForm;
    var l = document.getElementsByName('post_members[]');
    var n = l.length;
	var j = 0;
    var i;

    for (i = 0; i < n; i++)
	{
		l[i].checked = false;
	}
    for (i = 0; i < n; i++)
	{
		if(uid == l[i].value)
		{
			l[i].checked = true;
			break;
		}
	}
	f.a.value = 'admin/multi_seo_update';
	getIframeForAction(f);
	f.submit();
}
function actCheck(act)
{
	var f = document.listForm;
    var l = document.getElementsByName('post_members[]');
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
		alert('선택된 포스트가 없습니다.      ');
		return false;
	}
	
	if (act != '')
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

