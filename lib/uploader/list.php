<?php $gparamExp = explode('|',$gparam)?>


<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/core.js"></script>
<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/events.js"></script>
<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/css.js"></script>
<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/coordinates.js"></script>
<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/drag.js"></script>
<script type="text/javascript" src="<?php echo $g['s']?>/_core/opensrc/tool-man/dragsort.js"></script>
<script type="text/javascript">
//<![CDATA[
var dragsort = ToolMan.dragsort();
//]]>
</script>

<div id="uplist">
	<div id="captionDiv" class="caption">
		<form name="captionForm" action="<?php echo $g['s']?>/" method="post" target="_action_frame_" onsubmit="return captionRegis(this);">
		<input type="hidden" name="r" value="<?php echo $r?>" />
		<input type="hidden" name="m" value="<?php echo $m?>" />
		<input type="hidden" name="blog" value="<?php echo $blog?>" />
		<input type="hidden" name="a" value="upload/caption_regis" />
		<input type="hidden" name="tmpcode" value="" />
		<input type="hidden" name="uid" value="" />
		<table>
		<tr>
		<td class="x1">파일명 <input type="text" name="name" value="" class="input" /> 캡션</td>
		<td class="x2"><input type="text" name="caption" value="" class="input" /></td>
		<td class="x3"><input type="submit" value="등록" class="btnblue" /> <input type="button" value="취소" class="btngray" onclick="captionClose();" /></td>
		</tr>
		</table>
		</form>
	</div>
	<div id="upfilebox" class="upfilebox" style="display:<?php echo $code?'block':'none'?>;">
	<div class="thumbbox"><div id="thumbbox" class="thumbimg"></div></div>
	<div class="upfilelist">
	<ul id="upfilelist" class="scrollbar01<?php if($album=='Y'):?> album<?php endif?>">
	<?php $UFILES = getArrayString($code)?>
	<?php foreach($UFILES['data'] as $_val):?>
	<?php $U = getUidData($table[$m.'upload'],$_val)?>
	<?php if(!$U['uid']) continue?>
	<?php $file_ext = getExt($U['name'])?>

	<li id="uLi<?php echo $U['uid']?>">
		<input type="checkbox" name="filemembers[]" value="<?php echo $U['uid']?>" checked="checked" />
		<span id="finfo_name_<?php echo $U['uid']?>" class="hide"><?php echo htmlspecialchars(str_replace('.'.getExt($U['name']),'',$U['name']))?></span>
		<span id="finfo_caption_<?php echo $U['uid']?>" class="hide"><?php echo htmlspecialchars($U['caption'])?></span>
		<span id="finfo_tmpcode_<?php echo $U['uid']?>" class="hide"><?php echo $U['tmpcode']?></span>
		<span class="delicon">
			<img src="<?php echo $g['img_core']?>/_public/ico_drag.gif" class="drag" alt="순서변경" title="순서변경" />
			<a class="hand" title="삭제" onclick="upfile_delete('<?php echo $U['uid']?>','<?php echo $U['type']?>','<?php echo $U['tmpname']?>');"><img src="<?php echo $g['img_core']?>/_public/btn_del_s01.gif" class="xdel" alt="삭제" /></a>
			<?php if($album!='Y'):?>
			<a class="hand" title="다운로드목록 출력여부" onclick="upfile_hidden('<?php echo $U['uid']?>');"><img src="<?php echo $g['img_core']?>/_public/ico_<?php echo $U['hidden']?'hide':'show'?>.gif" class="eye" alt="다운로드목록 출력여부" /></a>
			<?php else:?>
			&nbsp;
			<?php endif?>
		</span>
		<span class="name">
		<img src="<?php echo $g['img_core']?>/file/small/<?php echo is_file($g['path_core'].'image/file/small/'.$file_ext.'.gif')?$file_ext:'unknown'?>.gif" class="ext" alt="" />
		<?php if($album=='Y'):?>
		<a class="hand" onmousemove="upfile_thumb('<?php echo getExt($U['name'])?>','<?php echo $U['url'].$U['folder'].'/'.$U['thumbname']?>');" onclick="captionCheck('<?php echo $U['uid']?>');"><?php echo $U['name']?></a>
		<?php else:?>
		<a class="hand" onmousemove="upfile_thumb('<?php echo getExt($U['name'])?>','<?php echo $U['url'].$U['folder'].'/'.$U['thumbname']?>');" onclick="captionCheck('<?php echo $U['uid']?>');"<?php if($U['type']==2):?> ondblclick="dropEditArea('<?php echo $U['url'].$U['folder'].'/'.$U['tmpname']?>','<?php echo $U['width']?>');" title="*클릭 : 파일명/캡션수정<?php if($gparamExp[1]!='_none_'):?> *더블클릭 : 본문삽입<?php endif?>"<?php endif?>><?php echo $U['name']?></a>
		<span class="size">(<?php echo getSizeFormat($U['size'],1)?>)</span>
		<?php endif?>
		</span>
	</li>

	<?php endforeach?>
	</ul>
	</div>
	<div class="clear"></div>
	</div>
</div>


<script type="text/javascript">
//<![CDATA[
function captionClose()
{
	getId('captionDiv').style.display = 'none';
	setFrameSize();
}
function captionRegis(f)
{
	if (f.uid.value == '')
	{
		return false;
	}
	if (f.name.value == '')
	{
		alert('파일명을 입력해 주세요. ');
		f.name.focus();
		return false;
	}
	return confirm('정말로 실행하시겠습니까?  ');
}
function captionCheck(xuid)
{
	var f = document.captionForm;
	var xname = getId('finfo_name_'+xuid).innerHTML;
	var xcaption = getId('finfo_caption_'+xuid).innerHTML;
	var xtmpcode = getId('finfo_tmpcode_'+xuid).innerHTML;
	getId('captionDiv').style.display = 'block';
	f.uid.value = xuid;
	f.name.value = xname;
	f.caption.value = xcaption;
	f.tmpcode.value = xtmpcode;
	f.caption.focus();
	setFrameSize();
}
function dragFile()
{
    var l = document.getElementsByName('filemembers[]');
    var n = l.length;
    var i;
	var j = 0;
	var upfiles = '';

	for	(i = 0; i < n; i++)
	{
		if (l[i].checked == true)
		{
			j++;
			upfiles += '['+l[i].value+']';
		}
	}
	
	<?php if($gparamExp[0]):?>
	if(parent.getId('<?php echo $gparamExp[0]?>'))
	{
		parent.getId('<?php echo $gparamExp[0]?>').value = upfiles;
	}
	<?php endif?>
}
function dropEditArea(pic,w)
{
	<?php if($gparamExp[1]):?>
	if (parent.getId('<?php echo $gparamExp[1]?>'))
	{
		if (confirm('이미 본문에 삽입된 이미지일 수 있습니다.\n정말로 본문에 삽입하시겠습니까?    '))
		{
			var x_width = parseInt('<?php echo $d['blog']['up_width_img']?>');
				x_width = parseInt(w) < x_width ? parseInt(w) : x_width;

			var photos = '<br /><img src="'+pic+'" width="'+x_width+'" class="photo hand" imgOrignWin(this.src) alt="" /><br />';
			parent.frames.<?php echo $gparamExp[1]?>.EditDrop(photos);
		}
	}
	<?php endif?>
}
function upfile_delete(uid,type,tmpname)
{
	if (type == '2')
	{
		var tmsg = '서버에서 파일을 삭제합니다.\n\n정말로 삭제하시겠습니까?\n본문에 삽입된 이미지는 제거됩니다.          ';
	}
	else {
		var tmsg = '서버에서 파일을 삭제합니다.\n정말로 삭제하시겠습니까?          ';
	}

	if (confirm(tmsg))
	{
		if (parent.getId('<?php echo $gparamExp[1]?>'))
		{
			var content = parent.frames.<?php echo $gparamExp[1]?>.editStartMode == 'HTML' && parent.frames.<?php echo $gparamExp[1]?>.editSrcMode == false ? parent.frames.<?php echo $gparamExp[1]?>.frames.editAreaIframe.document.body.innerHTML : parent.frames.<?php echo $gparamExp[1]?>.getId('editAreaTextarea').value;
			
			if (type == '2' && content.indexOf(tmpname) != -1)
			{

				var contarr = content.split(tmpname);

				var contar1 = contarr[0].split('<');
				var contar2 = contarr[1].split('>');

				var resultc = '';
				var i;
				for (i = 1; i < contar1.length-1; i++)
				{
					if (!contar1[i])
					{
						continue;
					}
					resultc += '<'+contar1[i];
				}
				for (i = 1; i < contar2.length-1; i++)
				{
					if (!contar2[i])
					{
						continue;
					}
					resultc += contar2[i] + '>';
				}

			}
			else {
				var resultc = content;
			}
		}

		getId('uLi'+uid).style.display = 'none';
		getId('thumbbox').style.background = '';
		frames._action_frame_.location.href = '<?php echo $g['s']?>/?r=<?php echo $r?>&m=<?php echo $m?>&blog=<?php echo $blog?>&a=upload/files_delete&file_uid=' + uid;


		if (parent.getId('<?php echo $gparamExp[0]?>'))
		{
			parent.getId('<?php echo $gparamExp[0]?>').value = parent.getId('<?php echo $gparamExp[0]?>').value.replace('['+uid+']','');
		}
		if (parent.getId('<?php echo $gparamExp[1]?>Content'))
		{
			parent.getId('<?php echo $gparamExp[1]?>Content').value = resultc;
			parent.frames.<?php echo $gparamExp[1]?>.getAreaSet(parent.frames.<?php echo $gparamExp[1]?>.editStartMode,'start');
		}

		if (parent.getId('<?php echo $gparamExp[0]?>').value == '')
		{
			getId('upfilebox').style.display = 'none';
		}
		setFrameSize();
	}
}
function upfile_hidden(uid)
{
	frames._action_frame_.location.href = '<?php echo $g['s']?>/?r=<?php echo $r?>&m=<?php echo $m?>&blog=<?php echo $blog?>&a=upload/files_hidden&gparam=<?php echo $gparam?>&code=<?php echo $code?>&file_uid=' + uid;
}
function upfile_thumb(ext,img)
{
	if (ext == 'gif' || ext == 'jpg' || ext == 'png')
	{
		getId('thumbbox').style.background = "url("+img+") center center no-repeat";
	}
	else {
		getId('thumbbox').style.background = "url("+rooturl+"/_core/image/file/big/"+ext+".gif) center center no-repeat";
	}
}
function setFrameSize()
{
	var frameName = frames.name;

	if (!frameName)
	{
		location.href = '<?php echo $g['r']?>/';
	}
	else {

		dragsort.makeListSortable(getId("upfilelist"));
		parent.getId(frameName).style.height = (parseInt(document.body.clientHeight) + (navigator.appVersion.indexOf('MSIE 8')!=-1?-4:4)) + 'px';

		if (parent.parent.getId(parent.frames.name))
		{
			parent.frameSetting();
		}
	}
}
function JserrorCheck(msg,file_loc,line_no)
{
	e_msg = msg;
	e_file = file_loc;
	e_line = line_no;
	return true;
}
window.onerror = JserrorCheck;
window.onload = setFrameSize;
//]]>
</script>

<!-- action_iframe name 중복방지 -->
<?php $m=''?>
