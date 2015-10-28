<div class="guide">
<span class="b">블로그에 광고를 노출해 보세요.</span><br /><br />
이미지,텍스트 혹은 외부광고를 홈페이지에 간단히 노출시킬 수 있습니다.<br />
광고의 종류와 형식을 선택해 주세요.<br />
광고의 높이는 제한이 없으나 광고의 폭은 사이드 영역에 고정됩니다.<br />
</div>

<form name="themeForm" method="post" action="<?php echo $g['s']?>/" enctype="multipart/form-data" onsubmit="return configCheck2(this);">
<input type="hidden" name="r" value="<?php echo $r?>" />
<input type="hidden" name="m" value="<?php echo $m?>" />
<input type="hidden" name="blog" value="<?php echo $B['uid']?>" />
<input type="hidden" name="a" value="theme/<?php echo $d['blog']['theme']?>/_action/a.config" />
<input type="hidden" name="nowTheme" value="theme/<?php echo $d['blog']['theme']?>" />
<input type="hidden" name="changeType" value="ad" />


<table>
<tr>
<td class="t1">광고의 형식</td>
<td class="t2">:</td>
<td class="t3">
	<label><input type="radio" name="adtype" value="0"<?php if($d['b_layout']['adtype']=='0'):?> checked="checked"<?php endif?> />광고 노출안함</label>
	<label><input type="radio" name="adtype" value="1"<?php if($d['b_layout']['adtype']=='1'):?> checked="checked"<?php endif?> />이미지</label>
	<label><input type="radio" name="adtype" value="2"<?php if($d['b_layout']['adtype']=='2'):?> checked="checked"<?php endif?> />플래쉬</label>
	<label><input type="radio" name="adtype" value="3"<?php if($d['b_layout']['adtype']=='3'):?> checked="checked"<?php endif?> />HTML/스크립트</label>
</td>
</tr>

<tr>
<td class="t1">이미지/링크</td>
<td class="t2">:</td>
<td class="t3">
<input type="file" name="upfile" class="file" value="" /> / 
<input type="text" name="ad_imglink" class="input" value="<?php echo $d['b_layout']['ad_imglink']?>" />
<select name="ad_imgtarget">
<option value="_blank"<?php if($d['b_layout']['ad_imgtarget']=='_blank'):?> selected="selected"<?php endif?>>새창</option>
<option value="_self"<?php if($d['b_layout']['ad_imgtarget']=='_self'):?> selected="selected"<?php endif?>>현재창</option>
</select>
</td>
</tr>
<?php if(is_file($g['dir_module'].'theme/'.$d['blog']['theme'].'/_var/'.$d['b_layout']['ad_img'])):?>
<tr>
<td></td>
<td></td>
<td class="t3">
<br />
<img src="<?php echo $g['url_module'].'/theme/'.$d['blog']['theme'].'/_var/'.$d['b_layout']['ad_img']?>" width="280" alt="" />
<br /><br />
<a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;a=theme/<?php echo $d['blog']['theme']?>/_action/a.logodelete&amp;&imgType=ad_img&amp;nowTheme=theme/<?php echo $d['blog']['theme']?>&amp;blog=<?php echo $B['uid']?>" onclick="return hrefCheck(this,true,'정말로 삭제하시겠습니까?');">이 이미지 삭제하기</a>
<br />
<br />
</td>
</tr>
<?php endif?>
<tr>
<td class="t1">플래쉬</td>
<td class="t2">:</td>
<td class="t3">
<input type="file" name="upfile1" class="file" value="" />
</td>
</tr>
<?php if(is_file($g['dir_module'].'theme/'.$d['blog']['theme'].'/_var/'.$d['b_layout']['ad_swf'])):?>
<tr>
<td></td>
<td></td>
<td class="t3">
<br />
<embed src="<?php echo $g['url_module'].'/theme/'.$d['blog']['theme'].'/_var/'.$d['b_layout']['ad_swf']?>" width="280"></embed>
<br /><br />
<a href="<?php echo $g['s']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;a=theme/<?php echo $d['blog']['theme']?>/_action/a.logodelete&amp;&imgType=ad_swf&amp;nowTheme=theme/<?php echo $d['blog']['theme']?>&amp;blog=<?php echo $B['uid']?>" onclick="return hrefCheck(this,true,'정말로 삭제하시겠습니까?');">이 플래쉬 삭제하기</a>
<br />
<br />
</td>
</tr>
<?php endif?>

<tr>
<td class="t1">HTML/스크립트</td>
<td class="t2">:</td>
<td class="t3">
<textarea name="adcode" rows="8" cols="70"><?php readfile($g['dir_module'].'theme/'.$d['blog']['theme'].'/_var/_ad.txt')?></textarea>
</td>
</tr>

<tr>
<td></td>
<td></td>
<td><br /><br /><input type="submit" value=" 변경하기 " class="btnblue" /></td>
</tr>
</table>

</form>