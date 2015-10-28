<?php
if(!defined('__KIMS__')) exit;

checkAdmin(0);

foreach($comment_members as $val)
{
	$R = getUidData($table[$m.'comment'],$val);
	if (!$R['uid']) continue;

	getDbUpdate($table[$m.'comment'],'hidden='.$inbox,'uid='.$R['uid']);
	getDbUpdate($table[$m.'oneline'],'hidden='.$inbox,'parent='.$R['uid']);

	if ($R['hidden'])
	{
		if (!$inbox)
		{
			getDbUpdate($table[$m.'data'],'comment=comment+1,oneline=oneline+'.$R['oneline'],'uid='.$R['parent']);
			getDbUpdate($table[$m.'list'],'num_c=num_c+1,num_o=num_o+'.$R['oneline'],'uid='.$R['blog']);
			getDbUpdate($table[$m.'members'],'num_c=num_c+1,num_o=num_o+'.$R['oneline'],'blog='.$R['blog'].' and mbruid='.$R['mbruid']);
		}
	}
	else {
		if ($inbox)
		{
			getDbUpdate($table[$m.'data'],'comment=comment-1,oneline=oneline-'.$R['oneline'],'uid='.$R['parent']);
			getDbUpdate($table[$m.'list'],'num_c=num_c-1,num_o=num_o-'.$R['oneline'],'uid='.$R['blog']);
			getDbUpdate($table[$m.'members'],'num_c=num_c-1,num_o=num_o-'.$R['oneline'],'blog='.$R['blog'].' and mbruid='.$R['mbruid']);
		}
	}
}

getLink('reload','parent.','','');
?>