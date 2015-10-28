<div id="mainbox">

	<?php if($d['b_layout']['bbs0']!='-1'):?>
	<?php if($d['b_layout']['bbs0']) $_C=getDbData($table[$m.'category'],'uid='.$d['b_layout']['bbs0'],'*')?>
	<?php $_C['sort']=explode(',',$d['b_layout']['sort0'])?>
	<?php $_RCD = $d['b_layout']['bbs0'] ? getDbArray($table[$m.'catidx'].','.$table[$m.'data'],$table[$m.'data'].'.blog='.$B['uid'].' and '.$table[$m.'data'].'.isreserve=0 and ('.$table[$m.'catidx'].'.category='.$d['b_layout']['bbs0'].' and '.$table[$m.'data'].'.uid='.$table[$m.'catidx'].'.parent)','*',$_C['sort'][0],$_C['sort'][1],$d['b_layout']['bbs0_num'],1) : getDbArray($table[$m.'data'],'blog='.$B['uid'].' and isreserve=0','*',$_C['sort'][0],$_C['sort'][1],$d['b_layout']['bbs0_num'],1)?>
	<div class="gallType">

		<?php while($_R=db_fetch_array($_RCD)):?>
		<?php 
		$_thumbimg = '';
		$imgs = getImgs($_R['content'],'jpg|jpeg');
		if ($imgs[0]) $_thumbimg = $imgs[0];
		else {
			if($_R['isphoto']&&$_R['upload'])
			{
				$upArray = getArrayString($_R['upload']);
				$_U = getUidData($table[$m.'upload'],$upArray['data'][0]);
				$_thumbimg = $_U['url'].$_U['folder'].'/'.$_U['thumbname'];
			}
		}
		?>
		<div class="picbox">
			<div class="pic">
				<a href="<?php echo $g['blog_view'].$_R['uid']?>"><?php if($_thumbimg):?><img src="<?php echo $_thumbimg?>" alt="" /><?php endif?></a>
			</div>
			<div class="sbjx">
				<a href="<?php echo $g['blog_view'].$_R['uid']?>"<?php if($_R['uid']==$uid):?> class="b"<?php endif?>><?php echo $_R['subject']?></a>
				<?php if($_R['comment']):?><span class="comment">[<?php echo $_R['comment']?><?php if($_R['oneline']):?>+<?php echo $_R['oneline']?><?php endif?>]</span><?php endif?>
				<?php if(getNew($_R['d_regis'],24)):?><span class="new">new</span><?php endif?>
			</div>
		</div>

		<?php endwhile?> 
		<?php if(!db_num_rows($_RCD)):?>
		<div class="none">등록된 포스트가 없습니다.</div>
		<?php endif?>
		<div class="clear"></div>
	</div>
	<?php endif?>







	<?php for($i=1;$i<6;$i++):?>
	<?php if($d['b_layout']['bbs'.$i]=='-1')continue?>
	<?php if($d['b_layout']['bbs'.$i]) $_C=getDbData($table[$m.'category'],'uid='.$d['b_layout']['bbs'.$i],'*')?>
	<?php $_C['sort']=explode(',',$d['b_layout']['sort'.$i])?>
	<div class="subtt">
		<div class="xleft"><?php echo $_C['name']?></div>
		<div class="xright"><a href="<?php echo $g['blog_cat'].$_C['uid']?>">더보기</a></div>
		<div class="clear"></div>
	</div>
	
	<div class="reviewType">
		<table>
		<tbody>

		<?php $_RCD = getDbArray($table[$m.'catidx'].','.$table[$m.'data'],$table[$m.'data'].'.blog='.$B['uid'].' and '.$table[$m.'data'].'.isreserve=0 and ('.$table[$m.'catidx'].'.category='.$d['b_layout']['bbs'.$i].' and '.$table[$m.'data'].'.uid='.$table[$m.'catidx'].'.parent)','*',$_C['sort'][0],$_C['sort'][1],$d['b_layout']['bbs'.$i.'_num'],1)?>
		<?php while($_R=db_fetch_array($_RCD)):?>
		<?php //$_M=getDbData($table['s_mbrdata'],'memberuid='.$_R['mbruid'],'*')?>
		<?php $_L=getOverTime($date['totime'],$_R['d_regis'])?>
		<?php 
		$_thumbimg = '';
		$imgs = getImgs($_R['content'],'jpg|jpeg');
		if ($imgs[0]) $_thumbimg = $imgs[0];
		else {
			if($_R['isphoto']&&$_R['upload'])
			{
				$upArray = getArrayString($_R['upload']);
				$_U = getUidData($table[$m.'upload'],$upArray['data'][0]);
				$_thumbimg = $_U['url'].$_U['folder'].'/'.$_U['thumbname'];
			}
		}
		?>
		<tr>
		<td>
			<?php if($_thumbimg):?>
			<div class="pic">
				<a href="<?php echo $g['blog_view'].$_R['uid']?>"><?php if($_thumbimg):?><img src="<?php echo $_thumbimg?>" alt="" /><?php endif?></a>
			</div>
			<?php endif?>
			<div class="sbj"><a href="<?php echo $g['blog_view'].$_R['uid']?>"><?php echo $_R['subject']?></a></div>
			<div class="cont"><?php echo $_R['review']?getStrCut($_R['review'],$d['b_layout']['cont_length'],'..'):getStrCut(getStripTags($_R['content']),$d['b_layout']['cont_length'],'..')?></div>
		</td>
		</tr> 
		<?php endwhile?> 
		<?php if(!db_num_rows($_RCD)):?>
		<tr><td class="none"></td></tr>
		<?php endif?>
		</tbody>
		</table>
	</div>
	<?php endfor?>


</div>

<script type="text/javascript">
//<![CDATA[
//]]>
</script>
