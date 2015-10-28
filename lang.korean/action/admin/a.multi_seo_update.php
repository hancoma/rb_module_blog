<?php
if(!defined('__KIMS__')) exit;

checkAdmin(0);

foreach($post_members as $val)
{
	$QVAL = "subject='".${'s_subject'.$val}."',title='".${'s_title'.$val}."',keywords='".${'s_keywords'.$val}."',description='".${'s_desc'.$val}."',classification='".${'s_class'.$val}."',replyto='".${'s_replyto'.$val}."',language='".${'s_language'.$val}."',build='".${'s_build'.$val}."'";

	getDbUpdate($table[$m.'seo'],$QVAL,'parent='.$val);
}
getLink('reload','parent.','갱신되었습니다.','');
?>