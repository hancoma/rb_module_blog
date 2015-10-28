<?php
if(!defined('__KIMS__')) exit;

$B = getUidData($table[$m.'list'],$blog);
if (!$B['uid']) getLink($g['s'].'/?r='.$r,'','존재하지 않는 블로그입니다.','');
if (!$my['uid'] || $my['uid']!=$B['mbruid']) getLink($g['s'].'/?r='.$r,'','블로그 관리권한이 없습니다.',''); 

$_tmpdfile = $g['dir_module'].$nowTheme.'/_var/var.'.$B['id'].'.php';
$d['b_layout'] = array();
include $_tmpdfile;

if ($changeType == 'side')
{
	$d['b_layout']['layout_width'] = trim($layout_width);
	$d['b_layout']['layout_align'] = trim($layout_align);
	$d['b_layout']['side_width'] = trim($side_width);
	$d['b_layout']['side_align'] = trim($side_align);
	$d['b_layout']['dsp_category'] = trim($dsp_category);
	$d['b_layout']['dsp_photo'] = trim($dsp_photo);
	$d['b_layout']['dsp_photo_num'] = trim($dsp_photo_num);
	$d['b_layout']['dsp_post'] = trim($dsp_post);
	$d['b_layout']['dsp_post_num'] = trim($dsp_post_num);
	$d['b_layout']['dsp_comment'] = trim($dsp_comment);
	$d['b_layout']['dsp_comment_num'] = trim($dsp_comment_num);
	$d['b_layout']['dsp_tag'] = trim($dsp_tag);
	$d['b_layout']['dsp_calendar'] = trim($dsp_calendar);
	$d['b_layout']['dsp_counter'] = trim($dsp_counter);
	$d['b_layout']['dsp_rss'] = trim($dsp_rss);
	$d['b_layout']['dsp_twitter'] = trim($dsp_twitter);
	$d['b_layout']['dsp_twitter_id'] = trim($dsp_twitter_id);
	$d['b_layout']['dsp_facebook'] = trim($dsp_facebook);
	$d['b_layout']['dsp_facebook_id'] = trim($dsp_facebook_id);
}

if ($changeType == 'main')
{
	$d['b_layout']['cont_length'] = trim($cont_length);
	for ($i = 0; $i < 6; $i++)
	{
		$d['b_layout']['bbs'.$i] = ${'bbs'.$i};
		$d['b_layout']['sort'.$i] = ${'sort'.$i};
		$d['b_layout']['bbs'.$i.'_num'] = trim(${'bbs'.$i.'_num'});
	}
}

if ($changeType == 'detail')
{
	$_newupload = false;
	$tmpname	= $_FILES['upfile']['tmp_name'];
	$realname	= $_FILES['upfile']['name'];
	$fileExt	= strtolower(getExt($realname));
	$fileExt	= $fileExt == 'jpeg' ? 'jpg' : $fileExt;
	$photo		= 'logo.'.$B['id'].'.'.$fileExt;
	$saveFile1	= $g['dir_module'].$nowTheme.'/_var/'.$d['b_layout']['imglogo'];
	$saveFile2	= $g['dir_module'].$nowTheme.'/_var/'.$photo;

	if (is_uploaded_file($tmpname))
	{
		if (!strstr('[gif][jpg][png]',$fileExt))
		{
			getLink('','','gif/jpg/png 파일만 등록할 수 있습니다.','');
		}
		if (is_file($saveFile1))
		{
			unlink($saveFile1);
		}

		move_uploaded_file($tmpname,$saveFile2);
		@chmod($saveFile2,0707);
		$isize=getimagesize($saveFile2);

		$d['b_layout']['imglogo'] = $photo;
		$d['b_layout']['imglogo_w'] = $isize[0];
		$d['b_layout']['imglogo_h'] = $isize[1];
		$_newupload = true;
	}

	$tmpname	= $_FILES['upfile1']['tmp_name'];
	$realname	= $_FILES['upfile1']['name'];
	$fileExt	= strtolower(getExt($realname));
	$fileExt	= $fileExt == 'jpeg' ? 'jpg' : $fileExt;
	$photo		= 'bgtitle.'.$B['id'].'.'.$fileExt;
	$saveFile1	= $g['dir_module'].$nowTheme.'/_var/'.$d['b_layout']['bg'];
	$saveFile2	= $g['dir_module'].$nowTheme.'/_var/'.$photo;

	if (is_uploaded_file($tmpname))
	{
		if (!strstr('[gif][jpg][png]',$fileExt))
		{
			getLink('','','gif/jpg/png 파일만 등록할 수 있습니다.','');
		}
		if (is_file($saveFile1))
		{
			unlink($saveFile1);
		}

		move_uploaded_file($tmpname,$saveFile2);
		@chmod($saveFile2,0707);

		$d['b_layout']['bg'] = $photo;
	}

	$d['b_layout']['height_header'] = trim($height_header);
	$d['b_layout']['title'] = trim($title);
	$d['b_layout']['title_t'] = trim($title_t);
	$d['b_layout']['title_b'] = trim($title_b);
	$d['b_layout']['title_s'] = trim($title_s);
	$d['b_layout']['title_size'] = trim($title_size);
	$d['b_layout']['title_font'] = trim($title_font);
	$d['b_layout']['imglogo_use'] = is_file($g['dir_module'].$nowTheme.'/_var/'.$d['b_layout']['imglogo']) ? $imglogo_use : 0;
	$d['b_layout']['bg_use'] = $bg_use;
	$d['b_layout']['bg_o'] = $bg_o;
	$d['b_layout']['title_color'] = $title_color;
	$d['b_layout']['memberlink_color'] = $memberlink_color;
	$d['b_layout']['split_color'] = $split_color;
	$d['b_layout']['bg_header'] = trim($bg_header);
	$d['b_layout']['height_header_bt'] = trim($height_header_bt);
	$d['b_layout']['bg_header_bt'] = trim($bg_header_bt);
	$d['b_layout']['height_header_bb'] = trim($height_header_bb);
	$d['b_layout']['bg_header_bb'] = trim($bg_header_bb);
	$d['b_layout']['search_border'] = trim($search_border);
	$d['b_layout']['layout_width'] = trim($layout_width);
	$d['b_layout']['layout_align'] = trim($layout_align);
	$d['b_layout']['side_width'] = trim($side_width);
	$d['b_layout']['side_align'] = trim($side_align);
	$d['b_layout']['dsp_search'] = $dsp_search;


	if (!$_newupload)
	{
		$d['b_layout']['imglogo_w'] = trim($imglogo_w);
		$d['b_layout']['imglogo_h'] = trim($imglogo_h);
	}
}
if ($changeType == 'ad')
{
	$tmpname	= $_FILES['upfile']['tmp_name'];
	$realname	= $_FILES['upfile']['name'];
	$fileExt	= strtolower(getExt($realname));
	$fileExt	= $fileExt == 'jpeg' ? 'jpg' : $fileExt;
	$photo		= 'ad.'.$fileExt;
	$saveFile1	= $g['dir_module'].$nowTheme.'/_var/'.$d['b_layout']['ad_img'];
	$saveFile2	= $g['dir_module'].$nowTheme.'/_var/'.$photo;

	if (is_uploaded_file($tmpname))
	{
		if (!strstr('[gif][jpg][png]',$fileExt))
		{
			getLink('','','이미지는 gif/jpg/png 파일만 등록할 수 있습니다.','');
		}
		if (is_file($saveFile1))
		{
			unlink($saveFile1);
		}

		move_uploaded_file($tmpname,$saveFile2);
		@chmod($saveFile2,0707);
		$d['b_layout']['ad_img'] = $photo;
	}
	$tmpname	= $_FILES['upfile1']['tmp_name'];
	$realname	= $_FILES['upfile1']['name'];
	$fileExt	= strtolower(getExt($realname));
	$fileExt	= $fileExt == 'jpeg' ? 'jpg' : $fileExt;
	$photo		= 'ad.'.$fileExt;
	$saveFile1	= $g['dir_module'].$nowTheme.'/_var/'.$d['b_layout']['ad_swf'];
	$saveFile2	= $g['dir_module'].$nowTheme.'/_var/'.$photo;

	if (is_uploaded_file($tmpname))
	{
		if (!strstr('[swf]',$fileExt))
		{
			getLink('','','플래쉬는 swf 파일만 등록할 수 있습니다.','');
		}
		if (is_file($saveFile1))
		{
			unlink($saveFile1);
		}

		move_uploaded_file($tmpname,$saveFile2);
		@chmod($saveFile2,0707);
		$d['b_layout']['ad_swf'] = $photo;
	}


	$d['b_layout']['adtype'] = $adtype;
	$d['b_layout']['ad_imglink'] = trim($ad_imglink);
	$d['b_layout']['ad_imgtarget'] = $ad_imgtarget;

	$_adfile = $g['dir_module'].$nowTheme.'/_var/_ad.txt';
	$fp = fopen($_adfile,'w');
	fwrite($fp,trim(stripslashes($adcode)));
	fclose($fp);

	@chmod($_adfile,0707);
}

$fp = fopen($_tmpdfile,'w');
fwrite($fp, "<?php\n");
foreach ($d['b_layout'] as $key => $val)
{
	fwrite($fp, "\$d['b_layout']['".$key."'] = \"".$val."\";\n");
}
fwrite($fp, "?>");
fclose($fp);
@chmod($_tmpdfile,0707);



getLink('reload','parent.','','');
?>