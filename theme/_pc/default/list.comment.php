<?php
if(!defined('__KIMS__')) exit;
function getCmentLink($lnum,$p,$tpage,$img)
{
	$_N = $GLOBALS['g']['pagelink'].'&amp;';
	$g_p1 = '<img src="'.$img.'/p1.gif" alt="이전 '.$lnum.' 페이지" />';
	$g_p2 = '<img src="'.$img.'/p2.gif" alt="이전 '.$lnum.' 페이지" />';
	$g_n1 = '<img src="'.$img.'/n1.gif" alt="다음 '.$lnum.' 페이지" />';
	$g_n2 = '<img src="'.$img.'/n2.gif" alt="다음 '.$lnum.' 페이지" />';
	$g_cn = '<img src="'.$img.'/l.gif" class="split" alt="" />';
	$g_q  = $p > 1 ? '<a href="'.$_N.'cp=1"><img src="'.$img.'/fp.gif" alt="처음페이지" /></a>' : '<img src="'.$img.'/fp1.gif" alt="처음페이지" />';
	if($p < $lnum+1) { $g_q .= $g_p1; }
	else{ $pp = (int)(($p-1)/$lnum)*$lnum; $g_q .= '<a href="'.$_N.'cp='.$pp.'">'.$g_p2.'</a>';} $g_q .= $g_cn;
	$st1 = (int)(($p-1)/$lnum)*$lnum + 1;
	$st2 = $st1 + $lnum;
	for($jn = $st1; $jn < $st2; $jn++)
	if ( $jn <= $tpage)
	($jn == $p)? $g_q .= '<span class="selected" title="'.$jn.' 페이지">'.$jn.'</span>'.$g_cn : $g_q .= '<a href="'.$_N.'cp='.$jn.'" class="notselected" title="'.$jn.' 페이지">'.$jn.'</a>'.$g_cn;
	if($tpage < $lnum || $tpage < $jn) { $g_q .= $g_n1; }
	else{$np = $jn; $g_q .= '<a href="'.$_N.'cp='.$np.'">'.$g_n2.'</a>'; }
	$g_q  .= $tpage > $p ? '<a href="'.$_N.'cp='.$tpage.'"><img src="'.$img.'/lp.gif" alt="마지막페이지" /></a>' : '<img src="'.$img.'/lp1.gif" alt="마지막페이지" />';
	return $g_q;
}
$_CNCD = array();
$_CRCD = array();
$cp = $cp ? $cp : 1;
$cmentque = ' and hidden=0 and parent='.$R['uid'];
$_CPCD = getDbArray($table[$m.'comment'],'notice=1'.$cmentque,'*','uid',$d['blog']['c_orderby1'],0,0);
$_CTCD = getDbArray($table[$m.'comment'],'notice=0'.$cmentque,'*','uid',$d['blog']['c_orderby1'],$d['blog']['c_recnum'],$cp);
$_CNUM = getDbRows($table[$m.'comment'],'notice=0'.$cmentque);
$_CTPG = getTotalPage($_CNUM,$d['blog']['c_recnum']);
while($_R = db_fetch_array($_CPCD)) $_CNCD[] = $_R;
while($_R = db_fetch_array($_CTCD)) $_CRCD[] = $_R;

$g['pagelink']	= $g['blog_view'].$R['uid'].'&amp;p='.$p.'&amp;CMT=Y';
$g['snseng'] = array('t'=>'Twitter','f'=>'Facebook','m'=>'Me2day','y'=>'Yozm');
$g['snskor'] = array('t'=>'트위터','f'=>'페이스북','m'=>'미투데이','y'=>'요즘');
?>


<div id="clist">
	
	<?php $_CRCD=array_merge($_CNCD,$_CRCD)?>
	<?php foreach($_CRCD as $_C):?>
	<?php $_L=getOverTime($date['totime'],$_C['d_regis'])?>
	<?php $_C['mobile']=isMobileConnect($_C['agent'])?>
	<?php if($_C['mbruid']) $M=getDbData($table['s_mbrdata'],'memberuid='.$_C['mbruid'],'*');else $M=array()?>
	<?php $isSECRETCHECK=false?>

	<a name="_comment_<?php echo $_C['uid']?>"></a>
	<div id="_comment_<?php echo $_C['uid']?>" class="commentbox">
		<div class="pic">
			<div class="simbol"><?php if($M['photo']):?><img src="<?php echo $g['s']?>/_var/simbol/<?php echo $M['photo']?>" alt="" width="50" height="50" /><?php endif?></div>
		</div>
		<div class="info">
			<div class="cont">
				<?php if($_C['notice']):?><img src="<?php echo $g['img_module_skin']?>/ico_notice.gif" alt="" style="margin-bottom:-4px;" /><?php endif?>
				<?php if($_C['hidden']):?><img src="<?php echo $g['img_core']?>/_public/ico_hidden.gif" alt="" style="margin-bottom:-1px;" /><?php endif?>
				<?php if (!$_C['hidden'] || $d['blog']['writeperm'] || ($my['uid']&&$my['uid']==$_C['mbruid']) || strstr($_SESSION['module_'.$m.'_comment'],'['.$_C['uid'].']')):$isSECRETCHECK=true?>
				<?php echo getContents($_C['content'],'TEXT')?>
				<?php else:?>
				<a href="<?php echo $g['blog_view'].$R['uid']?>&amp;comment=<?php echo $_C['uid']?>" onclick="return cmentHidden('<?php echo $_C['uid']?>',event);">비공개 댓글입니다.</a>
				<?php endif?>
			</div>
			<div class="date">
				<div class="xl">
					<?php if($_C['sns']):?>
					<?php $_C['snsset']=explode(',',$_C['sns'])?>
					<?php foreach($_C['snsset'] as $_skey):if(!$_skey)continue?>
					<?php $_C['snsdata']=getUidData($table['socialdata'],$_skey)?>
					<a href="<?php echo $_C['snsdata']['targeturl']?>" target="_blank"><img src="<?php echo $g['img_core']?>/_public/sns_<?php echo $_C['snsdata']['provider']?>0.gif" alt="" style="margin-bottom:-3px;" title="<?php echo $g['snskor'][$_C['snsdata']['provider']]?>에 보내졌습니다." /></a>
					<?php endforeach?>
					<?php endif?>
					<?php echo $_L[1]<4?$_L[0].$lang['sys']['time'][$_L[1]].'전':getDateFormat($_C['d_regis'],'Y.m.d H:i')?>
					<?php if(getNew($_C['d_regis'],24)):?><span class="new">new</span><?php endif?>
					<span>|</span> 
					<?php echo $_C['name']?>님
					<span>|</span> 
					<a href="<?php echo $g['blog_view'].$R['uid']?>&amp;type=modify&amp;comment=<?php echo $_C['uid']?>" onclick="return cmentModify('<?php if(($my['uid']&&$my['uid']==$_C['mbruid'])||$d['blog']['writeperm']):?>true<?php else:?>false<?php endif?>','<?php echo $_C['uid']?>',this,event);">수정</a>
					<span>|</span> 
					<a href="<?php echo $g['blog_act']?>comment/delete&amp;uid=<?php echo $_C['uid']?>&amp;stop=Y" target="__iframe_for_action__" onclick="return cmentDel('<?php if(($my['uid']&&$my['uid']==$_C['mbruid'])||$d['blog']['writeperm']):?>true<?php else:?>false<?php endif?>','<?php echo $d['blog']['c_onelinedel']?>','<?php echo $_C['oneline']?>','<?php echo $_C['uid']?>',event);">삭제</a>
				</div>
				<div class="xr">
				</div>
				<div class="clear"></div>
			</div>
			<div class="tool">
				<div class="oneline">
					<a class="hand" onclick="onelineOpen('<?php echo $_C['uid']?>');">댓글 <span id="oneline_num<?php echo $_C['uid']?>" class="b"><?php echo $_C['oneline']?></span></a>
					<?php if(getNew($_C['d_oneline'],24)):?><span class="new">new</span><?php endif?>

					<div id="onelinebox<?php echo $_C['uid']?>"<?php if($_C['uid']!=$comment):?> class="hide"<?php endif?>>

						<?php if($_C['oneline']&&$isSECRETCHECK==true):?>
						<?php $_OTCD = getDbArray($table[$m.'oneline'],'parent='.$_C['uid'],'*','uid',$d['blog']['c_orderby2'],0,0)?>
						<?php while($O=db_fetch_array($_OTCD)):?>
						<?php if($O['uid']!=$ouid):?>
						<?php $O['mobile']=isMobileConnect($O['agent'])?>
						<?php $_OL=getOverTime($date['totime'],$O['d_regis'])?>
						<?php $_OM=getDbData($table['s_mbrdata'],'memberuid='.$O['mbruid'],'*')?>
						<div id="_oneline_<?php echo $O['uid']?>" class="obox">

							<div class="name"><a><?php echo $_OM[$_HS['nametype']]?>님</a></div>
							<div class="memo"><?php echo getContents($O['content'],'TEXT')?></div>
							<div class="date">
							<?php echo $_OL[1]<4?$_OL[0].$lang['sys']['time'][$_OL[1]].'전':getDateFormat($O['d_regis'],'Y.m.d H:i')?>
							<?php if(($my['uid']&&$my['uid']==$O['mbruid'])||$d['blog']['writeperm']):?>
							<span>|</span> 
							<a href="<?php echo $g['blog_view'].$R['uid']?>&amp;comment=<?php echo $_C['uid']?>&amp;ouid=<?php echo $O['uid']?>" onclick="return oneModify('<?php echo $_C['uid']?>','<?php echo $O['uid']?>','<?php echo $O['mbruid']?>',this,event);">수정</a>
							<span>|</span> 
							<a href="<?php echo $g['blog_act']?>comment/oneline_delete&amp;uid=<?php echo $O['uid']?>&amp;stop=Y" target="__iframe_for_action__" onclick="return oneDel('<?php echo $_C['uid']?>','<?php echo $O['uid']?>');">삭제</a>
							<?php endif?>
							</div>
						</div>
						<?php else:?>
						<div class="wbox">
							<form method="post" action="<?php echo $g['s']?>/" onsubmit="return oneCheck(this);">
							<input type="hidden" name="r" value="<?php echo $r?>" />
							<input type="hidden" name="a" value="comment/oneline_write" />
							<input type="hidden" name="m" value="<?php echo $m?>" />
							<input type="hidden" name="blog" value="<?php echo $B['uid']?>" />
							<input type="hidden" name="parent" value="<?php echo $_C['uid']?>" />
							<input type="hidden" name="hidden" value="<?php echo $_C['hidden']?>" />
							<input type="hidden" name="uid" value="<?php echo $O['uid']?>" />
							<input type="hidden" name="backurl" value="" />
							<input type="hidden" name="CMT" value="Y" />
							<table>
							<tr>
							<td width="100%"><textarea name="content" id="oneline_comment<?php echo $_C['uid']?>"><?php echo $O['content']?></textarea></td>
							<td valign="bottom"><input type="image" src="<?php echo $g['img_module_skin']?>/btn_onemodify.gif" alt="수정" /></td>
							</tr>
							</table>
							</form>
							<div id="oneline_comment_str<?php echo $_C['uid']?>" class="boxresize" onclick="oneline_comment_flag('<?php echo $_C['uid']?>');">입력상자 늘리기</div>
						</div>
						<?php endif?>
						<?php endwhile?>
						<?php endif?>
						<?php if(!$ouid):?>
						<div class="wbox">
							<form method="post" action="<?php echo $g['s']?>/" onsubmit="return oneCheck(this);">
							<input type="hidden" name="r" value="<?php echo $r?>" />
							<input type="hidden" name="a" value="comment/oneline_write" />
							<input type="hidden" name="m" value="<?php echo $m?>" />
							<input type="hidden" name="blog" value="<?php echo $B['uid']?>" />
							<input type="hidden" name="parent" value="<?php echo $_C['uid']?>" />
							<input type="hidden" name="hidden" value="<?php echo $_C['hidden']?>" />
							<input type="hidden" name="backurl" value="" />
							<input type="hidden" name="CMT" value="Y" />
							<table>
							<tr>
							<td width="100%"><textarea name="content" id="oneline_comment<?php echo $_C['uid']?>"><?php if(!$my['uid']):?>로그인하셔야 이용하실 수 있습니다.<?php endif?></textarea></td>
							<td valign="bottom"><input type="image" src="<?php echo $g['img_module_skin']?>/btn_onewrite.gif" alt="등록" /></td>
							</tr>
							</table>
							</form>
							<div id="oneline_comment_str<?php echo $_C['uid']?>" class="boxresize" onclick="oneline_comment_flag('<?php echo $_C['uid']?>');">입력상자 늘리기</div>
						</div>
						<?php endif?>

					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>

	<?php endforeach?>
	<?php $_C=array()?>
	<?php if($TPG>1):?>
	<div class="page pagebox01">
		<?php echo getCmentLink(10,$cp,$_CTPG,$g['img_core'].'/page/default')?>
	</div>
	<?php endif?>

</div>
<a name="_writeform_">&nbsp;</a>
<div id="cwrite">
	
	<?php if($d['blog']['c_perm_write'] <= $my['level'] || $d['blog']['writeperm'] || ($type=='modify'&&$comment)):?>
	<?php if($type=='modify'&&$comment) $_C=getUidData($table[$m.'comment'],$comment)?>
	<form name="writeForm" method="post" action="<?php echo $g['s']?>/" onsubmit="return writeCheck(this);">
	<input type="hidden" name="r" value="<?php echo $r?>" />
	<input type="hidden" name="m" value="<?php echo $m?>" />
	<input type="hidden" name="blog" value="<?php echo $B['uid']?>" />
	<input type="hidden" name="a" value="comment/write" />
	<input type="hidden" name="parent" value="<?php echo $R['uid']?>" />
	<input type="hidden" name="uid" value="<?php echo $_C['uid']?>" />
	<input type="hidden" name="backurl" value="" />
	<input type="hidden" name="CMT" value="Y" />
	<div class="box">
		
		<div class="tt">
			<?php if($_C['uid']):?>댓글수정<?php else:?>댓글쓰기<?php endif?>
			<span>- 타인을 비방하거나 개인정보를 유출하는 글의 게시를 삼가주세요.</span>
		</div>

		<div class="inputbox">
			<?php if(!$my['id']):?>
			<div>
				<input type="text" name="name" value="<?php echo $_C['name']?>" class="input1" /> <span>(이름)</span>
				<input type="password" name="pw" value="<?php echo $pw?>" class="input1" /> <span>(비번)</span>
			</div>
			<?php endif?>
		</div>

		<div class="editbox">
			<textarea name="content"><?php echo htmlspecialchars($_C['content'])?></textarea>
		</div>

		<div class="bottom">
			<div class="l">
				<?php if($d['blog']['writeperm']):?>
				<input type="checkbox" name="notice" value="1"<?php if($_C['notice']):?> checked="checked"<?php endif?> />공지글
				<?php endif?>
				<?php if($d['blog']['c_use_hidden']):?>
				<input type="checkbox" name="hidden" value="1"<?php if($_C['hidden']):?> checked="checked"<?php endif?> />비밀글
				<?php endif?>
				<?php if(!$_C['uid']&&is_file($g['path_module'].$d['blog']['c_snsconnect'])):?>
				<?php include_once $g['path_module'].$d['blog']['c_snsconnect']?>
				<?php endif?>
			</div>
			<div class="r">
				<?php if($_C['uid']):?>
				<img src="<?php echo $g['img_module_skin']?>/btn_cancel.gif" alt="취소" class="hand" onclick="history.back();">
				<input type="image" src="<?php echo $g['img_module_skin']?>/btn_modify.gif" alt="수정" />
				<?php else:?>
				<input type="image" src="<?php echo $g['img_module_skin']?>/btn_write.gif" alt="등록" />
				<?php endif?>
			</div>
			<div class="clear"></div>
		</div>

	</div>

	</form>
	<?php else:?>
	<?php if(!$my['uid']):?>
	<div class="box">
		<div class="tt">
			댓글쓰기
			<span>- 로그인한 후 댓글작성권한이 있을 경우 이용하실 수 있습니다.</span>
			<a href="#." onclick="crLayer('로그인','<?php echo $g['s']?>/?r=<?php echo $r?>&system=iframe.login&iframe=Y&referer=<?php echo urlencode($g['s'].'/?'.$_SERVER['QUERY_STRING'])?>','iframe',515,250,'15%');">로그인</a>
		</div>
	</div>
	<?php endif?>
	<?php endif?>


</div>





<div id="pwbox">
	<div id="chkbox">

		<div class="msg">
			<h3><img src="<?php echo $g['img_core']?>/_public/ico_notice.gif" alt="" /> 비밀번호 확인</h3>
			<div>댓글 등록시에 입력했던 비밀번호를 입력해 주세요.</div>
		</div>

		<form name="checkForm" method="post" action="<?php echo $g['s']?>/" onsubmit="return permCheck(this);">
		<input type="hidden" name="r" value="<?php echo $r?>" />
		<input type="hidden" name="m" value="<?php echo $m?>" />
		<input type="hidden" name="blog" value="<?php echo $B['uid']?>" />
		<input type="hidden" name="a" value="" />
		<input type="hidden" name="uid" value="" />
		<input type="hidden" name="backurl" value="" />
		<input type="hidden" name="CMT" value="" />

		<div class="ibox">
			<input type="password" name="pw" class="input" />
			<input type="submit" value=" 확인 " class="btnblue" />
			<input type="button" value=" 취소 " class="btngray" onclick="cmentDelClose();" />
		</div>

		</form>
	</div>
</div>



<script type="text/javascript">
//<![CDATA[
var submitFlag = false;
function writeCheck(f)
{
	if (submitFlag == true)
	{
		crLayer('댓글 등록중','댓글을 등록하고 있습니다. 잠시만 기다려 주세요.','close',350,150,'30%');
		return false;
	}
	if (f.notice && f.hidden)
	{
		if (f.notice.checked == true && f.hidden.checked == true)
		{
			alert('공지글은 비밀글로 등록할 수 없습니다.  ');
			f.hidden.checked = false;
			return false;
		}
	}
	if (f.name && f.name.value == '')
	{
		alert('이름을 입력해 주세요. ');
		f.name.focus();
		return false;
	}
	if (f.pw && f.pw.value == '')
	{
		alert('비밀번호를 입력해 주세요. ');
		f.pw.focus();
		return false;
	}
	if (f.content.value == '')
	{
		alert('내용을 입력해 주세요.       ');
		f.content.focus();
		return false;
	}
	var locexp1 = location.href.split('#');
	var locexp2 = locexp1[0].split('&CMT=');
	var locexp3 = locexp2[0].split('&comment=');
	f.backurl.value =  locexp3[0];
	submitFlag = true;
	getIframeForAction(f);
	return true;
}
function oneCheck(f)
{
	if (memberid == '')
	{
		alert('로그인하셔야 이용하실 수 있습니다.');
		return false;
	}
	if (f.content.value == '')
	{
		alert('내용을 입력해 주세요.    ');
		f.content.focus();
		return false;
	}
	var locexp1 = location.href.split('#');
	var locexp2 = locexp1[0].split('&CMT=');
	var locexp3 = locexp2[0].split('&comment=');
	f.backurl.value =  locexp3[0];

	getIframeForAction(f);
	return true;
}
function onelineOpen(uid)
{
	var x = getId('onelinebox' + uid);
	if (x.style.display != 'block')
	{
		x.style.display = 'block';
	}
	else
	{
		x.style.display = 'none';
	}
}
function oneline_comment_flag(uid)
{
	if (oResizeFlag == false)
	{
		getId('oneline_comment'+uid).style.height = '200px';
		getId('oneline_comment_str'+uid).innerHTML = '입력상자 줄이기';
		oResizeFlag = true;
	}
	else
	{
		getId('oneline_comment'+uid).style.height = '28px';
		getId('oneline_comment_str'+uid).innerHTML = '입력상자 늘리기';
		oResizeFlag = false;
	}
}
function oneDel(cuid,uid)
{
	if (confirm('정말 삭제하시겠습니까?    '))
	{
		getIframeForAction('');
		getId('oneline_num'+cuid).innerHTML = parseInt(getId('oneline_num'+cuid).innerHTML)-1;
		getId('_oneline_'+uid).style.display = 'none';
		return true;
	}
	return false;
}
function cmentDel(flag,cdel,oneline,cuid,e)
{
	if (cdel == '1')
	{
		if (oneline != '0')
		{
			alert('한줄의견이 있는 댓글은 삭제할 수 없습니다.');
			return false;
		}
	}
	if (flag == 'true')
	{
		if(confirm('정말 삭제하시겠습니까?    '))
		{
			getIframeForAction('');
			getId('comment_num<?php echo $R['uid']?>').innerHTML = parseInt(getId('comment_num<?php echo $R['uid']?>').innerHTML)-1;
			getId('_comment_'+cuid).style.display = 'none';
			return true;
		}
		return false;
	}
	else {
		layerOpen(e);
		var f = document.checkForm;
		var locexp1 = location.href.split('#');
		var locexp2 = locexp1[0].split('&CMT=');
		var locexp3 = locexp2[0].split('&comment=');
		f.backurl.value =  locexp3[0];
		f.CMT.value = 'Y';
		getIframeForAction(f);
		f.a.value = 'comment/delete';
		f.uid.value = cuid;
		f.pw.focus();
		return false;
	}
}
function cmentModify(flag,cuid,obj,e)
{
	if (flag == 'true')
	{
		return true;
	}
	else {
		layerOpen(e);
		var f = document.checkForm;
		getIframeForAction(f);
		f.a.value = 'comment/pwcheck';
		var locexp1 = location.href.split('#');
		var locexp2 = locexp1[0].split('&CMT=');
		var locexp3 = locexp2[0].split('&comment=');
		f.backurl.value =  locexp3[0];
		f.CMT.value = 'Y';
		f.uid.value = cuid;
		f.pw.focus();
		return false;
	}
}
function cmentDelClose()
{
	getId('pwbox').style.display = 'none';
}
function cmentHidden(cuid,e)
{
	layerOpen(e);
	var f = document.checkForm;
	getIframeForAction(f);
	f.a.value = 'comment/pwcheck';
	f.uid.value = cuid;
	f.backurl.value = '';
	f.pw.focus();
	return false;
}
function permCheck(f)
{
	if (f.pw.value == '')
	{
		alert('비밀번호를 입력해 주세요.   ');
		f.pw.focus();
		return false;
	}
	getIframeForAction(f);
	return true;
}
var oResizeFlag = false;
function layerOpen(e)
{
	var x = myagent != 'ie' ? e.pageX : event.x+(document.body.scrollLeft || document.documentElement.scrollLeft);
	var y = myagent != 'ie' ? e.pageY : event.y+(document.body.scrollTop || document.documentElement.scrollTop);
	var layer = getId('pwbox');
	
	layer.style.display = 'block';
	layer.style.left = (x - 50) + 'px';
	layer.style.top = (y + 20) + 'px';
}
<?php if($CMT=='Y'):?>
location.href = location.href + '#CMT';
<?php endif?>
<?php if(WRITE=='Y'):?>
location.href = location.href + '#_writeform_';
<?php endif?>
<?php if($comment):?>
location.href = location.href + '#_comment_<?php echo $comment?>';
<?php endif?>



















function oneModify(cuid,ouid,mbruid,obj,e)
{

	if ((mbruid == '<?php echo $my['uid']?>') || is_admin != '')
	{
		return true;
	}
	else {
		alert('권한이 없습니다.   ');
		return false;
	}
}
//]]>
</script>