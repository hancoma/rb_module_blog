<?php include $g['path_module'].$module.'/var/var.php'?>

<div id="mainbox">

	<form name="procForm" action="<?php echo $g['s']?>/" method="post" onsubmit="return saveCheck(this);">
	<input type="hidden" name="r" value="<?php echo $r?>" />
	<input type="hidden" name="m" value="<?php echo $module?>" />
	<input type="hidden" name="a" value="admin/config" />

	<div class="title">
		블로그 기초환경
	</div>

	<table>
		<tr>
			<td class="td1">
				대표테마
				<img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('guide_skin','block','none');" />
			</td>
			<td class="td2">
				
				<select name="s_theme_pc" class="select1">
				<option value="">&nbsp;+ 선택하세요</option>
				<option value="">--------------------------------</option>
				<?php $tdir = $g['path_module'].$module.'/theme/_pc/'?>
				<?php $dirs = opendir($tdir)?>
				<?php while(false !== ($skin = readdir($dirs))):?>
				<?php if($skin=='.' || $skin == '..' || is_file($tdir.$skin))continue?>
				<option value="_pc/<?php echo $skin?>" title="<?php echo $skin?>"<?php if($d['blog']['s_theme_pc']=='_pc/'.$skin):?> selected="selected"<?php endif?>>ㆍ<?php echo getFolderName($tdir.$skin)?>(<?php echo $skin?>)</option>
				<?php endwhile?>
				<?php closedir($dirs)?>
				</select>
				<div id="guide_skin" class="guide hide">
				지정된 대표테마는 블로그 개별 설정시 별도의 테마를 지정하지 않으면 자동으로 적용됩니다.<br />
				가장 많이 사용하는 테마를 지정해 주세요.
				</div>
			</td>
		</tr>
		<tr>
			<td class="td1 m">
				(모바일테마)
			</td>
			<td class="td2">
				
				<select name="s_theme_mobile" class="select1">
				<option value="">ㆍPC모드와 동일</option>
				<option value="">--------------------------------</option>
				<?php $tdir = $g['path_module'].$module.'/theme/_mobile/'?>
				<?php $dirs = opendir($tdir)?>
				<?php while(false !== ($skin = readdir($dirs))):?>
				<?php if($skin=='.' || $skin == '..' || is_file($tdir.$skin))continue?>
				<option value="_mobile/<?php echo $skin?>" title="<?php echo $skin?>"<?php if($d['blog']['s_theme_mobile']=='_mobile/'.$skin):?> selected="selected"<?php endif?>>ㆍ<?php echo getFolderName($tdir.$skin)?>(<?php echo $skin?>)</option>
				<?php endwhile?>
				<?php closedir($dirs)?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="td1">
				짧은주소
			</td>
			<td class="td2">
				<input type="checkbox" name="rewrite" value="1"<?php if($d['blog']['rewrite']):?> checked="checked"<?php endif?> />짧은주소를 사용함
				<div class="guide">
				짧은주소 사용에 체크할 경우 다음의 코드를 .htaccess 에 추가해야 합니다.(Apache외의 서버일 경우 별도적용)
<textarea>
#블로그모듈
RewriteRule ^(blog)/([a-zA-Z0-9_\-]+)/?$ ./index.php?r=<?php echo $r?>&m=$1&blog=$2 [L]
RewriteRule ^(blog)/([a-zA-Z0-9_\-]+)/c/([a-zA-Z0-9_\-]+)/?$ ./index.php?r=<?php echo $r?>&m=$1&blog=$2&front=list&rwcat=$3 [L]
RewriteRule ^(blog)/([a-zA-Z0-9_\-]+)/c/([a-zA-Z0-9_\-]+)/([0-9]+)/?$ ./index.php?r=<?php echo $r?>&m=$1&blog=$2&front=list&rwcat=$3&uid=$4 [L]
RewriteRule ^(blog)/([a-zA-Z0-9_\-]+)/([0-9]+)/?$ ./index.php?r=<?php echo $r?>&m=$1&blog=$2&front=list&uid=$3 [L]
</textarea>
				</div>
			</td>
		</tr>
	</table>

	<div class="title">
		댓글 설정
	</div>


	<table>
		<tr>
			<td class="td1">댓글제한단어</td>
			<td class="td2">
				<textarea name="badword" rows="5" cols="70" onfocus="this.style.color='#000000';" onblur="this.style.color='#ffffff';"><?php echo $d['blog']['badword']?></textarea>
			
			</td>
		</tr>
		<tr>
			<td class="td1">제한단어 처리</td>
			<td class="td2">
				<input type="radio" name="badword_action" value="0"<?php if($d['blog']['badword_action']==0):?> checked="checked"<?php endif?> />제한단어 체크하지 않음<br />
				<input type="radio" name="badword_action" value="1"<?php if($d['blog']['badword_action']==1):?> checked="checked"<?php endif?> />등록을 차단함<br />
				<input type="radio" name="badword_action" value="2"<?php if($d['blog']['badword_action']==2):?> checked="checked"<?php endif?> />제한단어를 다음의 문자로 치환하여 등록함
				<input type="text" name="badword_escape" value="<?php echo $d['blog']['badword_escape']?>" size="1" maxlength="1" class="input" />
			</td>
		</tr>

		<tr>
			<td class="td1">댓글권한</td>
			<td class="td2">
				<select name="c_perm_write" class="select1">
				<option value="0">&nbsp;+ 전체허용</option>
				<option value="0">--------------------------------</option>
				<?php $_LEVEL=getDbArray($table['s_mbrlevel'],'','*','uid','asc',0,1)?>
				<?php while($_L=db_fetch_array($_LEVEL)):?>
				<option value="<?php echo $_L['uid']?>"<?php if($_L['uid']==$d['blog']['c_perm_write']):?> selected="selected"<?php endif?>>ㆍ<?php echo $_L['name']?>(<?php echo number_format($_L['num'])?>) 이상</option>
				<?php if($_L['gid'])break; endwhile?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="td1">소셜연동</td>
			<td class="td2">
				<select name="c_snsconnect" class="select1">
				<option value="0">&nbsp;+ 연동안함</option>
				<option value="0">--------------------------------</option>
				<?php $tdir = $g['path_module'].'social/inc/'?>
				<?php if(is_dir($tdir)):?>
				<?php $dirs = opendir($tdir)?>
				<?php while(false !== ($skin = readdir($dirs))):?>
				<?php if($skin=='.' || $skin == '..')continue?>
				<option value="social/inc/<?php echo $skin?>"<?php if($d['blog']['c_snsconnect']=='social/inc/'.$skin):?> selected="selected"<?php endif?>>ㆍ<?php echo str_replace('.php','',$skin)?></option>
				<?php endwhile?>
				<?php closedir($dirs)?>
				<?php endif?>
				</select> (소셜모듈을 설치 후 사용가능)
			</td>
		</tr>
		<tr>
			<td class="td1">비밀댓글등록</td>
			<td class="td2">
				<select name="c_use_hidden">
				<option value="1"<?php if($d['blog']['c_use_hidden']==1):?> selected="selected"<?php endif?>>사용함</option>
				<option value="0"<?php if($d['blog']['c_use_hidden']==0):?> selected="selected"<?php endif?>>사용안함</option>
				</select>			
			</td>
		</tr>
		<tr>
			<td class="td1">댓글출력수</td>
			<td class="td2">
				<input type="text" name="c_recnum" value="<?php echo $d['blog']['c_recnum']?>" size="5" class="input" />개
			</td>
		</tr>
		<tr>
			<td class="td1">댓글정렬</td>
			<td class="td2 shift">
				<input type="radio" name="c_orderby1" value="asc"<?php if(!$d['blog']['c_orderby1']||$d['blog']['c_orderby1']=='asc'):?> checked="checked"<?php endif?> />최근댓글이 위로정렬
				<input type="radio" name="c_orderby1" value="desc"<?php if($d['blog']['c_orderby1']=='desc'):?> checked="checked"<?php endif?> />최근댓글이 아래로정렬
			</td>
		</tr>
		<tr>
			<td class="td1">한줄의견정렬</td>
			<td class="td2 shift">
				<input type="radio" name="c_orderby2" value="desc"<?php if($d['blog']['c_orderby2']=='desc'):?> checked="checked"<?php endif?> />최근한줄의견이 위로정렬
				<input type="radio" name="c_orderby2" value="asc"<?php if(!$d['blog']['c_orderby2']||$d['blog']['c_orderby2']=='asc'):?> checked="checked"<?php endif?> />최근한줄의견이 아래로정렬
			</td>
		</tr>
		<tr>
			<td class="td1">삭제제한</td>
			<td class="td2 shift">
				<input type="checkbox" name="c_onelinedel" value="1"<?php if($d['blog']['c_onelinedel']):?> checked="checked"<?php endif?> />한줄의견이 있는 댓글의 삭제를 제한합니다.
			</td>
		</tr>
		<tr>
			<td class="td1">댓글포인트</td>
			<td class="td2">
				<input type="text" name="c_give_point" value="<?php echo $d['blog']['c_give_point']?>" size="5" class="input" />포인트지급 (등록한 댓글을 삭제시 환원됩니다)
			</td>
		</tr>
		<tr>
			<td class="td1">한줄의견포인트</td>
			<td class="td2">
				<input type="text" name="c_give_opoint" value="<?php echo $d['blog']['c_give_opoint']?>" size="5" class="input" />포인트지급 (등록한 한줄의견을 삭제시 환원됩니다)
			</td>
		</tr>

	</table>
	
	
	<div class="title">
		첨부파일 설정
	</div>


	<table>
		<tr>
			<td class="td1">일반파일 첨부</td>
			<td class="td2">
				<input type="text" name="up_maxnum_file" value="<?php echo $d['blog']['up_maxnum_file']?>" size="5" class="input" />개
				(<input type="text" name="up_maxsize_file" value="<?php echo $d['blog']['up_maxsize_file']?>" size="5" class="input" />MB이내)
			</td>
		</tr>
		<tr>
			<td class="td1">사진파일 첨부</td>
			<td class="td2">
				<input type="text" name="up_maxnum_img" value="<?php echo $d['blog']['up_maxnum_img']?>" size="5" class="input" />개
				(<input type="text" name="up_maxsize_img" value="<?php echo $d['blog']['up_maxsize_img']?>" size="5" class="input" />MB이내)
				<div class="guide">
				현재 서버에서 허용하고 있는 1회 최대 첨부용량은 <span class="b"><?php echo str_replace('M','',ini_get('upload_max_filesize'))?>MB</span>입니다.
				</div>
			</td>
		</tr>
		<tr>
			<td class="td1">일반파일 명칭</td>
			<td class="td2">
				<input type="text" name="up_name_file" value="<?php echo $d['blog']['up_name_file']?>" size="10" class="input" />
				허용확장자 : 
				<input type="text" name="up_ext_file" value="<?php echo $d['blog']['up_ext_file']?>" size="30" class="input" />
			</td>
		</tr>
		<tr>
			<td class="td1">사진파일 명칭</td>
			<td class="td2">
				<input type="text" name="up_name_img" value="<?php echo $d['blog']['up_name_img']?>" size="10" class="input" />
				허용확장자 : 
				<input type="text" name="up_ext_img" value="<?php echo $d['blog']['up_ext_img']?>" size="30" class="input" />
			</td>
		</tr>
		<tr>
			<td class="td1">사진삽입 사이즈</td>
			<td class="td2">
				<select name="up_width_img">
				<option value="240"<?php if($d['blog']['up_width_img']=='240'):?> selected="selected"<?php endif?>>240px</option>
				<option value="320"<?php if($d['blog']['up_width_img']=='320'):?> selected="selected"<?php endif?>>320px</option>
				<option value="400"<?php if($d['blog']['up_width_img']=='400'):?> selected="selected"<?php endif?>>400px</option>
				<option value="480"<?php if($d['blog']['up_width_img']=='480'):?> selected="selected"<?php endif?>>480px</option>
				<option value="640"<?php if($d['blog']['up_width_img']=='640'):?> selected="selected"<?php endif?>>640px</option>
				<option value="720"<?php if($d['blog']['up_width_img']=='720'):?> selected="selected"<?php endif?>>720px</option>
				<option value="1024"<?php if($d['blog']['up_width_img']=='1024'):?> selected="selected"<?php endif?>>1024px</option>
				</select>
				(사진이 본문에 삽입될때 사진의 가로사이즈)
			</td>
		</tr>
		<tr>
			<td class="td1">첨부제한 파일</td>
			<td class="td2">
				<input type="text" name="up_ext_cut" value="<?php echo $d['blog']['up_ext_cut']?>" size="55" class="input" />
				<div class="guide">
				파일첨부시 모든파일에 대해서 파일명 필터링이 이루어지므로 php 관련파일을 첨부해도 안전합니다.<br />
				그래도 제한하려면 *.php *.php3 *.html *.inc *.cgi *.pl *.js 를 등록해 주세요.
				</div>
			</td>
		</tr>
		<tr>
			<td class="td1">일반첨부 지원</td>
			<td class="td2 shift">
				<input type="checkbox" name="up_use_classicup" value="1"<?php if($d['blog']['up_use_classicup']):?> checked="checked"<?php endif?> />플래쉬 업로더가 작동안되는 환경에서 일반 파일첨부를 가능하게 합니다.
				<a name="ftp">&nbsp;</a>
			</td>
		</tr>
	</table>


	<div class="title">
		파일서버 설정
		<img src="<?php echo $g['img_core']?>/_public/ico_q.gif" alt="도움말" title="도움말" class="hand" onclick="layerShowHide('guide_fserver','block','none');" />
	</div>


	<div id="guide_fserver" class="notice hide">
		파일서버를 별도로 분리하여 운영하고자 할 경우 사용합니다.<br />
		이 모듈을 통해 업로드되는 모든 첨부파일들은 지정된 파일서버로 전송됩니다.<br />
		전용서버가 아니면 파일업로드 및 삭제/갱신시에 오히려 더 느려질 수 있습니다.<br />
		반드시 필요한 경우가 아니라면 사용을 권장하지 않습니다.<br />
		FTP연결은 되나 에러코드가 발생할 경우 Passive Mode에 체크한 후 시도해 보세요<br />
	</div>



	<table>
		<tr>
			<td class="td1">FTP주소/포트</td>
			<td class="td2">
				<input type="text" name="ftp_host" value="<?php echo $d['blog']['ftp_host']?>" size="20" class="input" />
				<input type="text" name="ftp_port" value="<?php echo $d['blog']['ftp_port']?>" size="5" class="input" />
				<input type="checkbox" name="ftp_pasv" value="1"<?php if($d['blog']['ftp_pasv']):?> checked="checked"<?php endif?> />Passive Mode
			</td>
		</tr>
		<tr>
			<td class="td1">아이디</td>
			<td class="td2">
				<input type="text" name="ftp_user" value="<?php echo $d['blog']['ftp_user']?>" size="20" class="input" />
			</td>
		</tr>
		<tr>
			<td class="td1">패스워드</td>
			<td class="td2 guide">
				<input type="password" name="ftp_pass" value="<?php echo $d['blog']['ftp_pass']?>" size="20" class="input" />
			</td>
		</tr>
		<?php if($d['blog']['ftp_host']&&$d['blog']['ftp_user']&&$d['blog']['ftp_pass']):?>
		<tr>
			<td class="td1">파일서버 사용</td>
			<td class="td2">
				<input type="checkbox" name="use_fileserver" value="1"<?php if($d['blog']['use_fileserver']):?> checked="checked"<?php endif?> /><span class="b" style="color:#ff0000;font-size:12px;">YES</span>


				<input type="hidden" name="FTP_PWD" value="<?php echo $FTP_PWD?>" />
				<input type="hidden" name="xmod" value="<?php echo $xmod?>" />

				<?php if($xmod == 'ftp'):?>

				<div class="ftitle">
					파일서버 내부 디렉토리 리스트
				</div>
				
				<?php $FTP_CONNECT = @ftp_connect($d['blog']['ftp_host'],$d['blog']['ftp_port'])?>
				<?php $FTP_CRESULT = @ftp_login($FTP_CONNECT,$d['blog']['ftp_user'],$d['blog']['ftp_pass'])?>
				<?php if (!$FTP_CONNECT) getLink('','','FTP서버에 연결할 수 없습니다.','-1')?>
				<?php if (!$FTP_CRESULT) getLink('','','아이디나 패스워드가 일치하지 않습니다.','-1')?>
				<?php if ($d['blog']['ftp_pasv']) ftp_pasv($FTP_CONNECT, true)?>
				<?php $FTP_PWD = $FTP_PWD ? $FTP_PWD : '/'?>
				<?php $PWD_EXP = explode('/',$FTP_PWD)?>
				<?php $PWD_LEN = count($PWD_EXP)?>
				<?php if (substr($FTP_PWD,0,1)!='/') getLink('','','잘못된 접근입니다.','-1')?>
				<?php ftp_chdir($FTP_CONNECT,$FTP_PWD)?>
				<?php $FTP_LIST = ftp_rawlist($FTP_CONNECT,$FTP_PWD)?>

<pre class="ftplist">
<div class="pwd"><a href="<?php echo $g['adm_href']?>&amp;xmod=<?php echo $xmod?>#ftp">처음</a><?php $i=0; $_xval=''; foreach ($PWD_EXP as $val): $_xval .= $val.'/'?><a href="<?php echo $g['adm_href']?>&amp;xmod=<?php echo $xmod?>&amp;FTP_PWD=<?php echo $_xval?>#ftp"><?php echo $val?></a><?php if($i<$PWD_LEN-1):?>/<?php endif?><?php $i++; endforeach?></div>
<?php foreach($FTP_LIST as $val):?>
<?php if(substr($val,0,1)=='d'):?>
<?php $valexp=explode(' ',$val)?>
<a href="<?php echo $g['adm_href']?>&amp;xmod=<?php echo $xmod?>&amp;FTP_PWD=<?php echo $FTP_PWD.$valexp[count($valexp)-1]?>/#ftp" class="b"><?php echo $val?></a> 
<?php else:?>
<?php //echo $val?> 
<?php endif?>
<?php endforeach?>
</pre>

				<?php ftp_close($FTP_CONNECT)?>
				<?php endif?>


			</td>
		</tr>
		<tr>
			<td class="td1">첨부폴더</td>
			<td class="td2">
				<input type="hidden" name="ftp_folder" value="<?php echo $FTP_PWD?$FTP_PWD:$d['blog']['ftp_folder']?>" />
				<span class="b" style="color:#ff0000;font-size:14px;"><?php echo $FTP_PWD?$FTP_PWD:$d['blog']['ftp_folder']?></span>
				<?php if($xmod!='ftp'):?><a href="<?php echo $g['adm_href']?>&amp;xmod=ftp#ftp" class="sftp">첨부폴더 지정하기</a><?php endif?>

				<div class="guide">
					FTP접속시 최상위폴더로 부터 실제 첨부할 폴더의 서버경로입니다.<br />
					지정하려는 경로를 위의 FTP 폴더리스트에서 클릭해 주세요.<br />
				</div>
			</td>
		</tr>
		<tr>
			<td class="td1">URL경로</td>
			<td class="td2">
				<input type="text" name="ftp_urlpath" value="<?php echo $d['blog']['ftp_urlpath']?>" size="55" class="input" />
				<div class="guide">
					첨부폴더를 웹상에서 접근할 수 있는 URL주소를 http://포함하여 입력해 주세요.<br />
					경로의 마지막은 반드시 슬래쉬(/)로 끝나야 합니다.<br />
				</div>
			</td>
		</tr>
		<?php endif?>
	</table>


	<div class="submitbox">
		<input type="submit" class="btnblue" value=" 확인 " />
	</div>

	</form>

</div>

<script type="text/javascript">
//<![CDATA[
function saveCheck(f)
{
	if (f.s_theme_pc.value == '')
	{
		alert('대표테마를 선택해 주세요.       ');
		f.s_theme_pc.focus();
		return false;
	}

	if (f.use_fileserver)
	{
		if (f.use_fileserver.checked == true)
		{
			if (f.ftp_host.value == '')
			{
				alert('FTP주소를 입력해 주세요.');
				f.ftp_host.focus();
				return false;
			}
			if (f.ftp_port.value == '')
			{
				alert('FTP포트를 입력해 주세요.');
				f.ftp_port.focus();
				return false;
			}
			if (f.ftp_user.value == '')
			{
				alert('아이디를 입력해 주세요.');
				f.ftp_user.focus();
				return false;
			}
			if (f.ftp_pass.value == '')
			{
				alert('패스워드를 입력해 주세요.');
				f.ftp_pass.focus();
				return false;
			}
			if (f.ftp_folder && f.ftp_folder.value == '')
			{
				alert('FTP연결상태 확인 버튼을 클릭하여 첨부폴더를 지정해 주세요.');
				return false;
			}
			if (f.ftp_urlpath && f.ftp_urlpath.value == '')
			{
				alert('URL경로를 입력해 주세요.');
				f.ftp_urlpath.focus();
				return false;
			}
		}
	}

	getIframeForAction(f);
	return confirm('정말로 실행하시겠습니까?         ');
}
//]]>
</script>


