<?php
$_RCD = array();
$_TCD = getDbArray($table[$m.'category'],'blog='.$B['uid'].' and depth=1','*','gid','asc',0,0);
while($_R = db_fetch_array($_TCD)) $_RCD[] = $_R;
?>

<div class="guide">
<span class="b">메인화면에 출력할 요소를 지정해 주세요.</span><br /><br />
블로그를 접속했을때 초기 메인화면을 어떻게 출력할지 설정합니다.<br />
</div>

<form name="themeForm" method="post" action="<?php echo $g['s']?>/" onsubmit="return configCheck(this);">
<input type="hidden" name="r" value="<?php echo $r?>" />
<input type="hidden" name="m" value="<?php echo $m?>" />
<input type="hidden" name="blog" value="<?php echo $B['uid']?>" />
<input type="hidden" name="a" value="theme/<?php echo $d['blog']['theme']?>/_action/a.config" />
<input type="hidden" name="nowTheme" value="theme/<?php echo $d['blog']['theme']?>" />
<input type="hidden" name="changeType" value="main" />


<table>
<tr>
<td class="t1">상단 사진포스트</td>
<td class="t2">:</td>
<td class="t3">
	<select name="bbs0" class="select1">
	<option value="-1"<?php if($d['b_layout']['bbs0']=='-1'):?> selected="selected"<?php endif?>>ㆍ출력안함</option>
	<option value="0"<?php if($d['b_layout']['bbs0']=='0'):?> selected="selected"<?php endif?>>ㆍ전체포스트</option>
	<option value="-1"<?php if($d['b_layout']['bbs'.$i]=='-1'):?> selected="selected"<?php endif?>>----------------------------------</option>
	<?php foreach($_RCD as $_B):?>
	<option value="<?php echo $_B['uid']?>"<?php if($d['b_layout']['bbs0']==$_B['uid']):?> selected="selected"<?php endif?>>ㆍ<?php echo $_B['name']?>(<?php echo $_B['id']?>)</option>
	<?php endforeach?>
	</select>
	<select name="sort0">
	<option value="gid,asc"<?php if($d['b_layout']['sort0']=='gid,asc'):?> selected="selected"<?php endif?>>등록순</option>
	<option value="hit,desc"<?php if($d['b_layout']['sort0']=='hit,desc'):?> selected="selected"<?php endif?>>조회순</option>
	<option value="comment,desc"<?php if($d['b_layout']['sort0']=='comment,desc'):?> selected="selected"<?php endif?>>댓글순</option>
	</select>
	<select name="bbs0_num">
	<?php for($j = 1; $j < 21;$j++):?>
	<option value="<?php echo $j?>"<?php if($d['b_layout']['bbs0_num']==$j):?> selected="selected"<?php endif?>><?php echo $j?>개</option>
	<?php endfor?>
	</select>
</td>
</tr>
<?php for($i=1;$i<6;$i++):?>
<tr>
<td class="t1">포스트 그룹 (<?php echo $i?>)</td>
<td class="t2">:</td>
<td class="t3">
	<select name="bbs<?php echo $i?>" class="select1">
	<option value="-1"<?php if($d['b_layout']['bbs'.$i]=='-1'):?> selected="selected"<?php endif?>>ㆍ출력안함</option>
	<option value="-1"<?php if($d['b_layout']['bbs'.$i]=='-1'):?> selected="selected"<?php endif?>>----------------------------------</option>
	<?php foreach($_RCD as $_B):?>
	<option value="<?php echo $_B['uid']?>"<?php if($d['b_layout']['bbs'.$i]==$_B['uid']):?> selected="selected"<?php endif?>>ㆍ<?php echo $_B['name']?>(<?php echo $_B['id']?>)</option>
	<?php endforeach?>
	</select>
	<select name="sort<?php echo $i?>">
	<option value="gid,asc"<?php if($d['b_layout']['sort'.$i]=='gid,asc'):?> selected="selected"<?php endif?>>등록순</option>
	<option value="hit,desc"<?php if($d['b_layout']['sort'.$i]=='hit,desc'):?> selected="selected"<?php endif?>>조회순</option>
	<option value="comment,desc"<?php if($d['b_layout']['sort'.$i]=='comment,desc'):?> selected="selected"<?php endif?>>댓글순</option>
	</select>
	<select name="bbs<?php echo $i?>_num">
	<?php for($j = 1; $j < 21;$j++):?>
	<option value="<?php echo $j?>"<?php if($d['b_layout']['bbs'.$i.'_num']==$j):?> selected="selected"<?php endif?>><?php echo $j?>개</option>
	<?php endfor?>
	</select>
</td>
</tr>
<?php endfor?>
<tr>
<td class="t1">리뷰 글자수</td>
<td class="t2">:</td>
<td class="t3">
	<input type="text" name="cont_length" class="input sf" value="<?php echo $d['b_layout']['cont_length']?>" maxlength="3" /><span class="small">자 (포스트 미리보기 내용 글자수)</span>
	<div class="small">리뷰가 등록되지 않은 포스트는 내용의 일부를 리뷰로 대체합니다.</div>
</td>
</tr>
<tr>
<td></td>
<td></td>
<td><br /><br /><input type="submit" value=" 변경하기 " class="btnblue" /></td>
</tr>
</table>

</form>

