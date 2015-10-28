<div id="mainbox">

	<div class="title">
		<div class="xleft">
			포스트 <?php echo $R['uid']?'수정':'등록'?>
		</div>
		<div class="xright">
		</div>
		<div class="clear"></div>
	</div>


	<form name="writeForm" method="post" action="<?php echo $g['s']?>/" onsubmit="return writeCheck(this);">
	<input type="hidden" name="r" value="<?php echo $r?>" />
	<input type="hidden" name="m" value="<?php echo $m?>" />
	<input type="hidden" name="a" value="post_write" />
	<input type="hidden" name="blog" value="<?php echo $B['uid']?>" />
	<input type="hidden" name="uid" value="<?php echo $R['uid']?>" />
	<input type="hidden" name="upfiles" id="upfilesValue" value="<?php echo $R['upload']?>" />
	<input type="hidden" name="cat" value="<?php echo $cat?>" />
	<input type="hidden" name="vtype" value="<?php echo $vtype?>" />
	<input type="hidden" name="recnum" value="<?php echo $recnum?>" />
	<input type="hidden" name="category_members" value="" />
	<table>
		<tr>
		<td class="td1">제목</td>
		<td class="td2">
			<input type="text" name="subject" value="<?php echo htmlspecialchars($R['subject'])?>" class="input subject" />
			<label><input type="checkbox"<?php if($R['review']):?> checked="checked"<?php endif?> onclick="layerShowHide1('bbsReview',this);" />리뷰</label>
			<label><input type="checkbox" name="isreserve" value="1"<?php if($R['isreserve']):?> checked="checked"<?php endif?> onclick="layerShowHide1('bbsReserve',this);" />예약</label>
			<label><input type="checkbox"<?php if($_SEO['title']):?> checked="checked"<?php endif?> onclick="layerShowHide1('bbsSeo',this);" />SEO</label>

			<div id="bbsReview" class="guide<?php if(!$R['review']):?> hide<?php endif?>">
			<textarea name="review" class="review"><?php echo $R['review']?></textarea>
			</div>	
			<div id="bbsReserve" class="guide<?php if(!$R['isreserve']):?> hide<?php endif?>">
<?php
if ($R['uid'])
{
	$year1	= substr($R['d_regis'],0,4);
	$month1	= substr($R['d_regis'],4,2);
	$day1	= substr($R['d_regis'],6,2);
	$hour1	= substr($R['d_regis'],8,2);
	$min1	= substr($R['d_regis'],10,2);
}
else {
	$year1	= substr($date['today'],0,4);
	$month1	= substr($date['today'],4,2);
	$day1	= substr($date['today'],6,2);
	$hour1	= 0;
	$min1	= 0;
}
?>
				<select name="year1">
				<?php for($i=$date['year'];$i<$date['year']+2;$i++):?><option value="<?php echo $i?>"<?php if($year1==$i):?> selected="selected"<?php endif?>><?php echo $i?>년</option><?php endfor?>
				</select>
				<select name="month1">
				<?php for($i=1;$i<13;$i++):?><option value="<?php echo sprintf('%02d',$i)?>"<?php if($month1==$i):?> selected="selected"<?php endif?>><?php echo sprintf('%02d',$i)?>월</option><?php endfor?>
				</select>
				<select name="day1">
				<?php for($i=1;$i<32;$i++):?><option value="<?php echo sprintf('%02d',$i)?>"<?php if($day1==$i):?> selected="selected"<?php endif?>><?php echo sprintf('%02d',$i)?>일(<?php echo getWeekday(date('w',mktime(0,0,0,$month2,$i,$year2)))?>)</option><?php endfor?>
				</select>
				<select name="hour1">
				<?php for($i=0;$i<24;$i++):?><option value="<?php echo sprintf('%02d',$i)?>"<?php if($hour1==$i):?> selected="selected"<?php endif?>><?php echo sprintf('%02d',$i)?>시</option><?php endfor?>
				</select>
				<select name="min1">
				<?php for($i=0;$i<60;$i++):?><option value="<?php echo sprintf('%02d',$i)?>"<?php if($min1==$i):?> selected="selected"<?php endif?>><?php echo sprintf('%02d',$i)?>분에 노출함</option><?php endfor?>
				</select>
			</div>

			<div id="bbsSeo" class="guide<?php if(!$_SEO['title']):?> hide<?php endif?>">

			
				<div class="notice">
					검색 엔진 최적화(SEO; Search Engine Optimization)를 위해 메타정보를 등록해 주세요.<br />
					SEO 에서 가장 중요한 요소는 키워드와 설명이므로 필히 등록해 주세요<br />
					<br />
				</div>

				<table>
					<tr>
						<td class="xtd1 b">
							타이틀(title)
							<img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('guide_title','block','none');" />
						</td>
						<td class="xtd2">
							<input type="text" name="s_title" value="<?php echo $_SEO['title']?>" class="input sname2" />
							<a href="#." onclick="document.writeForm.s_title.value=document.writeForm.subject.value;">제목과 동일</a>
							<div id="guide_title" class="guide hide">
							문서의 타이틀을 등록합니다.
							</div>
						</td>
					</tr>
					<tr>
						<td class="xtd1 b">
							주제(subject)
							<img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('guide_subject','block','none');" />
						</td>
						<td class="xtd2">
							<input type="text" name="s_subject" value="<?php echo $_SEO['subject']?>" class="input sname2" />
							<a href="#." onclick="document.writeForm.s_subject.value=document.writeForm.subject.value;">제목과 동일</a>
							<div id="guide_subject" class="guide hide">
							문서의 주제를 등록합니다.
							</div>
						</td>
					</tr>
					<tr>
						<td class="xtd1 b">
							키워드(keywords)
							<img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('guide_keywords','block','none');" />
						</td>
						<td class="xtd2">
							<textarea name="s_keywords" rows="2" cols="39"><?php echo $_SEO['keywords']?></textarea>
							<a href="#." onclick="document.writeForm.s_keywords.value=document.writeForm.tag.value;">태그와 동일</a>
							<a href="#." onclick="document.writeForm.tag.value=document.writeForm.s_keywords.value;">태그에 복사</a>
							<div id="guide_keywords" class="guide hide">
								이 문서의 핵심 키워드를 콤마로 구분하여 지정합니다.<br />
								키워드의 갯수는 20개미만을 권장합니다.<br />
								키워드는 엔터없이 입력해 주세요.<br />
								보기)킴스큐,킴스큐Rb,CMS,웹프레임워크,큐마켓<br />
							</div>
						</td>
					</tr>
					<tr>
						<td class="xtd1 b">
							설명(description)
							<img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('guide_description','block','none');" />
						</td>
						<td class="xtd2">
							<textarea name="s_desc" rows="2" cols="39"><?php echo $_SEO['description']?></textarea>
							<div id="guide_description" class="guide hide">
								검색 결과에 표시되는 문자를 지정합니다.<br />
								이 문서를 키워드 위주로 가급적 150자 이내로 설명해 주세요.<br />
								설명글은 엔터없이 입력해 주세요.<br />
								보기)웹 프레임워크의 혁신 - 킴스큐 Rb 에 대한 다운로드,팁 공유등을 제공합니다.<br />
							</div>
						</td>
					</tr>
					<tr>
						<td class="xtd1 b">
							분류(classification)
							<img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('guide_classification','block','none');" />
						</td>
						<td class="xtd2">
							<input type="text" name="s_class" value="<?php echo $_SEO['classification']?>" class="input sname2" />
							<div id="guide_classification" class="guide hide">
								이 문서의 분류,카테고리라 할 수 있으며 핵심적인 키워드 1개를 기입합니다.<br />
								보기) 킴스큐
							</div>
						</td>
					</tr>
					<tr>
						<td class="xtd1">
							연락처(reply-to)
							<img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('guide_replyto','block','none');" />
						</td>
						<td class="xtd2">
							<input type="text" name="s_replyto" value="<?php echo $_SEO['replyto']?$_SEO['replyto']:$my['email']?>" class="input sname2" />
							<div id="guide_replyto" class="guide hide">
							문서에 관한 문의처 이메일 주소를 등록합니다.
							</div>
						</td>
					</tr>
					<tr>
						<td class="xtd1">
							언어(content-language)
							<img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('guide_language','block','none');" />
						</td>
						<td class="xtd2">
							<input type="text" name="s_language" value="<?php echo $_SEO['language']?$_SEO['language']:'kr'?>" size="10" class="input" />
							<div id="guide_language" class="guide hide">
							제작된 언어를 등록합니다.<br />
							- 한글 "kr"<br />
							- 영어 "en"<br />
							- 일어 "ja"<br />
							</div>
						</td>
					</tr>
					<tr>
						<td class="xtd1">
							제작일(build)
							<img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('guide_build','block','none');" />
						</td>
						<td class="xtd2">
							<input type="text" name="s_build" value="<?php echo $_SEO['build']?$_SEO['build']:date('Y.m.d')?>" size="10" class="input" />
							<div id="guide_build" class="guide hide">
							제작 년월일을 등록합니다. 보기)2012.09.24
							</div>
						</td>
					</tr>
				</table>
			
			</div>	
		</td>
		</tr>
	</table>

	<div class="editbox">
		<div class="iconbox">
			<a href="#." onclick="getLayerBox('<?php echo $g['s']?>/?r=<?php echo $r?>&m=<?php echo $m?>&blog=<?php echo $B['uid']?>&upload=Y&mod=photo&gparam=upfilesValue|upfilesFrame|editFrame','사진 올리기',745,617,'',false,'r');" /><img src="<?php echo $g['img_core']?>/_public/ico_photo.gif" alt="" />사진</a>
			<img src="<?php echo $g['img_core']?>/_public/split_01.gif" alt="" class="split" />
			<a href="#." onclick="getLayerBox('<?php echo $g['s']?>/?r=<?php echo $r?>&m=<?php echo $m?>&blog=<?php echo $B['uid']?>&upload=Y&mod=file&gparam=upfilesValue|upfilesFrame','파일 올리기',450,390,event,true,'b');" /><img src="<?php echo $g['img_core']?>/_public/ico_xfile.gif" alt="" />파일</a>
			<img src="<?php echo $g['img_core']?>/_public/split_01.gif" alt="" class="split" />
			<a href="#." onclick="ToolCheck('layout');">레이아웃</a>
			<img src="<?php echo $g['img_core']?>/_public/split_01.gif" alt="" class="split" />
			<a href="#." onclick="ToolCheck('table');">테이블</a>
			<img src="<?php echo $g['img_core']?>/_public/split_01.gif" alt="" class="split" />
			<a href="#." onclick="ToolCheck('char');">특수문자</a>
			<img src="<?php echo $g['img_core']?>/_public/split_01.gif" alt="" class="split" />

			<a href="#." onclick="ToolCheck('icon');">아이콘</a>
			<img src="<?php echo $g['img_core']?>/_public/split_01.gif" alt="" class="split" />
			<a href="#." onclick="ToolCheck('flash');">플래쉬</a>
			<img src="<?php echo $g['img_core']?>/_public/split_01.gif" alt="" class="split" />
			<a href="#." onclick="ToolCheck('movie');">동영상</a>
			<img src="<?php echo $g['img_core']?>/_public/split_01.gif" alt="" class="split" />
			<a href="#." onclick="frames.editFrame.ToolboxShowHide(0);" /><img src="<?php echo $g['img_core']?>/_public/ico_edit.gif" alt="" />편집</a>
		</div>

		<div>
		<input type="hidden" name="html" id="editFrameHtml" value="HTML" />
		<input type="hidden" name="content" id="editFrameContent" value="<?php echo htmlspecialchars($R['content'])?>" />
		<iframe name="editFrame" id="editFrame" src="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=editor&amp;toolbox=Y&amp;skin=<?php echo $d['blog']['editor']?>" width="100%" height="500" frameborder="0" scrolling="no"></iframe>
		</div>
		
		<div>
		<iframe name="upfilesFrame" id="upfilesFrame" src="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;blog=<?php echo $B['uid']?>&amp;upload=Y&amp;mod=list&amp;gparam=upfilesValue|editFrame&amp;code=<?php echo $R['upload']?>" width="100%" height="0" frameborder="0" scrolling="no"></iframe>
		</div>
	</div>

	<table>
		<tr>
		<td class="td1">검색태그</td>
		<td class="td2">
			<input size="80" type="text" name="tag" value="<?php echo $R['tag']?>" class="input subject" />
			<img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('bbsTag','block','none');" />
			<div id="bbsTag" class="guide hide">
			<img src="<?php echo $g['img_core']?>/_public/ico_notice.gif" alt="" />
			이 포스트를 가장 잘 표현할 수 있는 단어를 콤마(,)로 구분해서 입력해 주세요.
			</div>			
		</td>
		</tr>
		<tr>
		<td class="td1">작성자</td>
		<td class="td2">
			<?php $_AUTHOR=getDbData($table['s_mbrdata'],'memberuid='.$B['mbruid'],'*')?>
			<?php $_AUTHORS=explode(',',$B['members'])?>
			<select name="author">
			<option value="<?php echo $_AUTHOR['memberuid']?>">ㆍ<?php echo $_AUTHOR[$_HS['nametype']]?> (<?php echo $_AUTHOR['email']?>)</option>
			<?php foreach($_AUTHORS as $_ath):if(!trim($_ath))continue;$_B=getDbData($table['s_mbrid'],"id='".$_ath."'",'*');if(!$_B['uid'])continue;$_AUTHOR=getDbData($table['s_mbrdata'],'memberuid='.$_B['uid'],'*')?>
			<option value="<?php echo $_AUTHOR['memberuid']?>"<?php if($R['mbruid']==$_AUTHOR['memberuid']):?> selected="selected"<?php endif?>>ㆍ<?php echo $_AUTHOR[$_HS['nametype']]?> (<?php echo $_AUTHOR['email']?>)</option>
			<?php endforeach?>
			</select>
		</td>
		</tr>
		<tr>
		<td class="td1"></td>
		<td class="td2 shift">
			<label><input type="checkbox" name="isphoto" value="1"<?php if($R['isphoto']):?> checked="checked"<?php endif?> />이 포스트에는 사진이 포함되어 있습니다.</label><br />
			<label><input type="checkbox" name="isvod" value="1"<?php if($R['isvod']):?> checked="checked"<?php endif?> />이 포스트에는 동영상이 포함되어 있습니다.</label><br />
			<label><input type="checkbox" name="cutcomment" value="1"<?php if($R['cutcomment']):?> checked="checked"<?php endif?> />이 포스트는 댓글을 사용하지 않습니다.</label><br />
		</td>

		<?php if(!$R['uid']&&is_file($g['path_module'].$d['blog']['snsconnect'])):?>
		<tr>
		<td class="td1" style="padding-top:18px;">소셜연동</td>
		<td class="td2 shift">
			<br />
			<?php include_once $g['path_module'].$d['blog']['snsconnect']?> 에도 게시물을 등록합니다.
		</td>
		</tr>
		<?php endif?>


	</table>

	<div class="bottombox">
		<input type="button" value="취소" class="btngray" onclick="cancelCheck();" />
		<input type="submit" value="확인" class="btnblue" />
	</div>

	</form>




</div>

<script type="text/javascript">
//<![CDATA[
var submitFlag = false;
function ToolCheck(compo)
{
	frames.editFrame.showCompo();
	frames.editFrame.EditBox(compo);
}
function layerShowHide1(x,obj)
{
	if (obj.checked == true)
	{
		getId(x).style.display = 'block';
	}
	else {
		getId(x).style.display = 'none';
	}
}
function writeCheck(f)
{
	if (submitFlag == true)
	{
		crLayer('포스트 등록중','포스트를 등록하고 있습니다. 잠시만 기다려 주세요.','close',350,150,'30%');
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
		alert('지정된 카테고리가 없습니다.\n적어도 하나이상의 카테고리를 지정해 주세요.     ');
		if (getId('__catbox__'))
		{
			_categoryShow = false;
			catShow();
			self.scrollTo(0,0);
		}
		return false;
	}
	f.category_members.value = s;

	if (f.subject.value == '')
	{
		alert('제목을 입력해 주세요.      ');
		f.subject.focus();
		return false;
	}
	frames.editFrame.getEditCode(f.content,f.html);
	if (f.content.value == '')
	{
		alert('내용을 입력해 주세요.       ');
		frames.editFrame.getEditFocus();
		return false;
	}
	if (getId('upfilesFrame'))
	{
		frames.upfilesFrame.dragFile();
	}

	if (f.isreserve.checked == true)
	{
		var _date1 = f.year1.value + '' + f.month1.value + '' + f.day1.value + '' + f.hour1.value + '' + f.min1.value;
		if (parseInt(_date1) < parseInt('<?php echo substr($date['totime'],0,12)?>'))
		{
			alert('예약시간이 현재시간보다 이전입니다.  ');
			return false;
		}
	}
	
	getIframeForAction(f);
	submitFlag = true;
	return confirm('정말로 <?php echo $R['uid']?'수정':'등록'?>하시겠습니까?    ');
}
//]]>
</script>

