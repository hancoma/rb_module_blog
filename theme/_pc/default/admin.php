<?php
$spage = $spage ? $spage : 'ele';
?>
<div id="mainbox">

	<div class="tabbox1">
	<ul>
	<li<?php if($spage=='ele'):?> class="on"<?php endif?>><a href="<?php echo $g['blog_front'].$front?>&amp;spage=ele">기본설정</a></li>
	<li<?php if($spage=='main'):?> class="on"<?php endif?>><a href="<?php echo $g['blog_front'].$front?>&amp;spage=main">메인화면</a></li>
	<li<?php if($spage=='side'):?> class="on"<?php endif?>><a href="<?php echo $g['blog_front'].$front?>&amp;spage=side">사이드위젯</a></li>
	<li<?php if($spage=='ad'):?> class="on"<?php endif?>><a href="<?php echo $g['blog_front'].$front?>&amp;spage=ad">광고</a></li>
	</ul>
	<div class="clear"></div>
	</div>

	<?php include  $g['dir_module_skin'].'_admin/_'.$spage.'.php'?>

</div>



<script type="text/javascript">
//<![CDATA[
function widthAlign(obj)
{
	if (getId('_layout'))
	{
		getId('_layout').children[0].children[0].style.margin = obj.value;
		getId('_container').style.margin = obj.value;
		getId('_footer').children[0].style.margin = obj.value;
	}
}
function sideAlign(x,obj)
{

}
function colorChange(f,v)
{
	if (f == 'memberlink_color_' || f == 'split_color_')
	{
		if (getId('_layout_memberlink_'))
		{
			var ele = getId('_layout_memberlink_').childNodes;
			var n = ele.length;
			var i;
			for (i = 0; i < n; i++)
			{
				if (f == 'split_color_')
				{
					if (ele[i].tagName == 'I') ele[i].style.background = '#'+v;
				}
				else {
					if (ele[i].tagName == 'A') ele[i].style.color = '#'+v;
				}
			}
		}
	}
	else if (f == 'bg_header_')
	{
		 if (getId('_layout')) getId('_layout').children[0].style.background = '#'+v;
	}
	else if (f == 'bg_header_bt_')
	{
		 if (getId('_layout')) getId('_layout').children[0].style.borderTopColor = '#'+v;
	}
	else if (f == 'bg_header_bb_')
	{
		 if (getId('_layout')) getId('_layout').children[0].style.borderBottomColor = '#'+v;
	}
	else if (f == 'search_border_')
	{
		if(getId('_layout_'+f)) getId('_layout_'+f).style.borderColor = '#'+v;
	}
	else {
		if(getId('_layout_'+f)) getId('_layout_'+f).style.color = '#'+v;
	}
}
function headerBorder(obj,flag)
{
	if (getId('_layout')) 
	{
		if (flag == 'top') getId('_layout').children[0].style.borderTopWidth = obj.value + 'px';	
		else getId('_layout').children[0].style.borderBottomWidth = obj.value + 'px';
		if (flag == 'bottom')
		{
			if (obj.value == '0')
			{
				getId('_layout').children[0].children[0].style.borderBottomWidth = '1px';
			}
			else {
				getId('_layout').children[0].children[0].style.borderBottomWidth = '0';
			}
		}
	}
}
function configCheck(f)
{
	getIframeForAction(f);
	return confirm('정말로 변경하시겠습니까?      ');
}
function configCheck1(f)
{
	if (f.title.value == '')
	{
		alert('타이틀을 입력해 주세요.   ');
		f.title.focus();
		return false;
	}

	if (f.upfile.value != '')
	{
		var extarr = f.upfile.value.split('.');
		var filext = extarr[extarr.length-1].toLowerCase();
		var permxt = '[gif][jpg][jpeg][png]';

		if (permxt.indexOf(filext) == -1)
		{
			alert('gif/jpg/png 파일만 등록할 수 있습니다.    ');
			f.upfile.focus();
			return false;
		}
	}

	getIframeForAction(f);
	return confirm('정말로 변경하시겠습니까?      ');
}
function configCheck2(f)
{
	if (f.upfile.value != '')
	{
		var extarr = f.upfile.value.split('.');
		var filext = extarr[extarr.length-1].toLowerCase();
		var permxt = '[gif][jpg][jpeg][png]';

		if (permxt.indexOf(filext) == -1)
		{
			alert('gif/jpg/png 파일만 등록할 수 있습니다.    ');
			f.upfile.focus();
			return false;
		}
	}
	if (f.upfile1.value != '')
	{
		var extarr1 = f.upfile1.value.split('.');
		var filext1 = extarr1[extarr1.length-1].toLowerCase();
		var permxt1 = '[swf]';

		if (permxt1.indexOf(filext1) == -1)
		{
			alert('swf 파일만 등록할 수 있습니다.    ');
			f.upfile1.focus();
			return false;
		}
	}
	getIframeForAction(f);
	return confirm('정말로 변경하시겠습니까?      ');
}
//]]>
</script>


