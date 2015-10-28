<div class="guide">
<span class="b">체크만으로 출력여부를 지정할 수 있습니다.</span><br /><br />
출력을 원하시는 요소들에 체크한 후 설정을 저장해 주세요.<br />
변경된 설정값은 실시간으로 적용됩니다.<br />
</div>

<form name="themeForm" method="post" action="<?php echo $g['s']?>/" enctype="multipart/form-data" onsubmit="return configCheck(this);">
<input type="hidden" name="r" value="<?php echo $r?>" />
<input type="hidden" name="m" value="<?php echo $m?>" />
<input type="hidden" name="blog" value="<?php echo $B['uid']?>" />
<input type="hidden" name="a" value="theme/<?php echo $d['blog']['theme']?>/_action/a.config" />
<input type="hidden" name="nowTheme" value="theme/<?php echo $d['blog']['theme']?>" />
<input type="hidden" name="changeType" value="side" />




<table>
<tr>
<td class="t1">레이아웃 정렬/폭</td>
<td class="t2">:</td>
<td class="t3">
	<select name="layout_align" onchange="widthAlign(this);">
	<option value="0 auto 0 10px"<?php if($d['b_layout']['layout_align']=='0 auto 0 10px'):?> selected="selected"<?php endif?>>좌측</option>
	<option value="auto"<?php if($d['b_layout']['layout_align']=='auto'):?> selected="selected"<?php endif?>>중앙</option>
	</select>
	<input type="text" name="layout_width" class="input sf1" value="<?php echo $d['b_layout']['layout_width']?>" /><span class="small">px 또는 %로 입력해 주세요</span>
</td>
</tr>
<tr>
<td class="t1">사이드 정렬/너비</td>
<td class="t2">:</td>
<td class="t3">
	<select name="side_align" onchange="sideAlign('_container',this);">
	<option value="left"<?php if($d['b_layout']['side_align']=='left'):?> selected="selected"<?php endif?>>좌측</option>
	<option value="right"<?php if($d['b_layout']['side_align']=='right'):?> selected="selected"<?php endif?>>우측</option>
	</select>
	<input type="text" name="side_width" class="input sf1" value="<?php echo $d['b_layout']['side_width']?>" /><span class="small">px</span>
</td>
</tr>
<tr>
<td class="t1">카테고리</td>
<td class="t2">:</td>
<td class="t3">
	<label><input type="checkbox" name="dsp_category" value="1"<?php if($d['b_layout']['dsp_category']):?> checked="checked"<?php endif?> />출력합니다.</label>
</td>
</tr>
<tr>
<td class="t1">최근사진</td>
<td class="t2">:</td>
<td class="t3">
	<label><input type="checkbox" name="dsp_photo" value="1"<?php if($d['b_layout']['dsp_photo']):?> checked="checked"<?php endif?> />출력합니다.</label>
	<select name="dsp_photo_num">
	<?php for($i=2;$i<11;$i++):?><option value="<?php echo $i?>"<?php if($d['b_layout']['dsp_photo_num']==$i):?> selected="selected"<?php endif?>><?php echo $i?>개</option><?php endfor?>
	</select>
</td>
</tr>
<tr>
<td class="t1">최근글</td>
<td class="t2">:</td>
<td class="t3">
	<label><input type="checkbox" name="dsp_post" value="1"<?php if($d['b_layout']['dsp_post']):?> checked="checked"<?php endif?> />출력합니다.</label>
	<select name="dsp_post_num">
	<?php for($i=1;$i<15;$i++):?><option value="<?php echo $i?>"<?php if($d['b_layout']['dsp_post_num']==$i):?> selected="selected"<?php endif?>><?php echo $i?>개</option><?php endfor?>
	</select>
</td>
</tr>
<tr>
<td class="t1">최근댓글</td>
<td class="t2">:</td>
<td class="t3">
	<label><input type="checkbox" name="dsp_comment" value="1"<?php if($d['b_layout']['dsp_comment']):?> checked="checked"<?php endif?> />출력합니다.</label>
	<select name="dsp_comment_num">
	<?php for($i=1;$i<15;$i++):?><option value="<?php echo $i?>"<?php if($d['b_layout']['dsp_comment_num']==$i):?> selected="selected"<?php endif?>><?php echo $i?>개</option><?php endfor?>
	</select>
</td>
</tr>
<tr>
<td class="t1">태그</td>
<td class="t2">:</td>
<td class="t3">
	<label><input type="checkbox" name="dsp_tag" value="1"<?php if($d['b_layout']['dsp_tag']):?> checked="checked"<?php endif?> />출력합니다.</label>
</td>
</tr>
<tr>
<td class="t1">칼렌더</td>
<td class="t2">:</td>
<td class="t3">
	<label><input type="checkbox" name="dsp_calendar" value="1"<?php if($d['b_layout']['dsp_calendar']):?> checked="checked"<?php endif?> />출력합니다.</label>
</td>
</tr>
<tr>
<td class="t1">트위터 활동피드</td>
<td class="t2">:</td>
<td class="t3">
	<label><input type="checkbox" name="dsp_twitter" value="1"<?php if($d['b_layout']['dsp_twitter']):?> checked="checked"<?php endif?> />출력합니다.</label>
	<span class="small">회원 아이디</span> <input type="text" name="dsp_twitter_id" class="input sf1" value="<?php echo $d['b_layout']['dsp_twitter_id']?>" />
</td>
</tr>
<tr>
<td class="t1">페이스북 활동피드</td>
<td class="t2">:</td>
<td class="t3">
	<label><input type="checkbox" name="dsp_facebook" value="1"<?php if($d['b_layout']['dsp_facebook']):?> checked="checked"<?php endif?> />출력합니다.</label>
	<span class="small">APP 아이디</span> <input type="text" name="dsp_facebook_id" class="input sf1" value="<?php echo $d['b_layout']['dsp_facebook_id']?>" />
</td>
</tr>
<tr>
<td class="t1">광고</td>
<td class="t2">:</td>
<td class="t3">
	<label><input type="checkbox"<?php if($d['b_layout']['adtype']):?> checked="checked"<?php endif?> disabled="disabled" />광고 <span class="small">(광고설정 페이지에서 설정하세요)</span></label>
</td>
</tr>
<tr>
<td class="t1">접속통계</td>
<td class="t2">:</td>
<td class="t3">
	<label><input type="checkbox" name="dsp_counter" value="1"<?php if($d['b_layout']['dsp_counter']):?> checked="checked"<?php endif?> />출력합니다.</label>
</td>
</tr>
<tr>
<td class="t1">RSS</td>
<td class="t2">:</td>
<td class="t3">
	<label><input type="checkbox" name="dsp_rss" value="1"<?php if($d['b_layout']['dsp_rss']):?> checked="checked"<?php endif?> />출력합니다.</label>
</td>
</tr>


<tr>
<td></td>
<td></td>
<td><br /><br /><input type="submit" value=" 변경하기 " class="btnblue" /></td>
</tr>
</table>

</form>