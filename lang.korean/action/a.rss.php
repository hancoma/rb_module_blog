<?php
if(!defined('__KIMS__')) exit;

$B = getUidData($table[$m.'list'],$blog);
if (!$B['uid']) exit;

$QUE = 'blog='.$B['uid'].' and isreserve=0';
$RCD = getDbArray($table[$m.'data'],$QUE,'*','gid','asc',20,1);

Header("Content-type: text/xml");
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");   
echo "<?xml version='1.0' encoding='utf-8'?>\r\n\r\n";

if($type == 'rss1') :?>

<rss version='2.0' xmlns:dc='http://purl.org/dc/elements/1.1/'>
	<channel>
		<title><?php echo htmlspecialchars($B['name'])?></title>
		<link><?php echo $g['url_root']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;blog=<?php echo $B['id']?></link> 
		<dc:language><?php echo substr($_HS['lang'],0,2)?></dc:language>
		<description><?php echo htmlspecialchars($B['name'])?></description>
<?php while($R=db_fetch_array($RCD)):?>
<?php $_M=getDbData($table['s_mbrdata'],'memberuid='.$R['mbruid'],'*')?>
		<item>
			<title><?php echo htmlspecialchars($R['subject'])?></title>
			<description><![CDATA[<?php echo getContents($R['content'],'HTML')?>]]></description>
			<link><?php echo $g['url_root']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;blog=<?php echo $B['id']?>&amp;front=list&amp;uid=<?php echo $R['uid']?></link>
			<dc:creator><?php echo htmlspecialchars($_M[$_HS['nametype']])?></dc:creator>
			<?php if($R['tag']):?>
			<?php $tags=explode(',',trim($R['tag']))?>
			<?php $tagn=count($tags)?>
			<?php for($i = 0; $i < $tagn; $i++):if(!$tags[$i])continue?>
			<category><![CDATA[<?php echo htmlspecialchars($tags[$i])?>]]></category>
			<?php endfor?>
			<?php endif?>
			<guid><?php echo $g['url_root']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;blog=<?php echo $B['id']?>&amp;front=list&amp;uid=<?php echo $R['uid']?></guid>
			<pubDate><?php echo getDateFormat($R['d_regis'],'r')?></pubDate>
			<dc:subject></dc:subject>
		</item>
<?php endwhile?>
	</channel>
</rss>

<?php elseif($type == 'atom') :?>

<feed version="0.3"
	xmlns="http://purl.org/atom/ns#"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:taxo="http://purl.org/rss/1.0/modules/taxonomy/" 
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" >

	<title><?php echo htmlspecialchars($B['name'])?></title>

	<id><?php echo $g['url_root']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;blog=<?php echo $B['id']?></id> 
	<author><name><?php echo htmlspecialchars($B['name']?$B['name']:$_HS['name'])?></name></author>
	<info><![CDATA[<?php echo htmlspecialchars($B['name']?$B['name']:$_HS['name'])?>]]></info>

<?php while($R=db_fetch_array($RCD)):?>
	<entry>
		<title><?php echo htmlspecialchars($R['subject'])?></title>
		<link rel="alternate" type="text/html" href="<?php echo $g['url_root']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;blog=<?php echo $B['id']?>&amp;front=list&amp;uid=<?php echo $R['uid']?>" />
		<id><?php echo $g['url_root']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;blog=<?php echo $B['id']?>&amp;front=list&amp;uid=<?php echo $R['uid']?></id>
		<created><?php echo getDateFormat($R['d_regis'],'r')?></created>
		<modified><?php echo getDateFormat($R['d_modify'],'r')?></modified>
		<summary type="text/html" mode="escaped"><![CDATA[<?php echo getContents($R['content'],'HTML')?>]]></summary>
	</entry>
<?php endwhile?>

</feed>


<?php else :?>

<rss version='2.0' xmlns:dc='http://purl.org/dc/elements/1.1/'>
	<channel>
		<title><?php echo htmlspecialchars($B['name'])?></title>
		<link><?php echo $g['url_root']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;blog=<?php echo $B['id']?></link> 
		<dc:language><?php echo substr($_HS['lang'],0,2)?></dc:language>
		<description><?php echo htmlspecialchars($B['name'])?></description>
<?php while($R=db_fetch_array($RCD)):?>
<?php $_M=getDbData($table['s_mbrdata'],'memberuid='.$R['mbruid'],'*')?>
		<item>
			<title><?php echo htmlspecialchars($R['subject'])?></title>
			<description><![CDATA[<?php echo getContents($R['content'],'HTML')?>]]></description>
			<link><?php echo $g['url_root']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;blog=<?php echo $B['id']?>&amp;front=list&amp;uid=<?php echo $R['uid']?></link>
			<dc:creator><?php echo htmlspecialchars($_M[$_HS['nametype']])?></dc:creator>
			<?php if($R['tag']):?>
			<?php $tags=explode(',',trim($R['tag']))?>
			<?php $tagn=count($tags)?>
			<?php for($i = 0; $i < $tagn; $i++):if(!$tags[$i])continue?>
			<category><![CDATA[<?php echo htmlspecialchars($tags[$i])?>]]></category>
			<?php endfor?>
			<?php endif?>
			<guid><?php echo $g['url_root']?>/?r=<?php echo $r?>&amp;m=<?php echo $m?>&amp;blog=<?php echo $B['id']?>&amp;front=list&amp;uid=<?php echo $R['uid']?></guid>
			<pubDate><?php echo getDateFormat($R['d_regis'],'r')?></pubDate>
			<dc:subject></dc:subject>
		</item>
<?php endwhile?>
	</channel>
</rss>

<?php endif?>

<?php exit?>
