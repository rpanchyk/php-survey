<form action="<?=$config['web_path']?>/handler.php" method="POST">
	<table cellspacing="0" cellpadding="0" border="0" style="width:100%;">
	<tbody>
	<?php
		$strVote = '';
		foreach ($config['vote'] as $k => $vote)
		{
			$strVote .= '<tr><td>';
			$strVote .= '<p><b><span class="style4">'. $vote['question'] . '</span></b></p>';
			$strVote .= '</td></tr>';
			if (is_array($vote['answers']))
				foreach ($vote['answers'] as $answerKey => $answerValue)
				{
					$strVote .= '<tr><td>';
					$strVote .= '<input type="'.$vote['type'].'" name="q'.$k.'[]" id="q'.$k.'" value="a'.$answerKey.'" />' . '<span class="style2">' . $answerValue . '</span>';
					
					if (@$vote['own_answer'] === $answerKey)
					{
						$strVote .= '<input type="text" value="" name="own_answer'.$k.'[]" id="own_answer'.$k.'" style="display:none; width:70%;">';
						$strVote .= '<script type="text/javascript">$(function() { ';
						$strVote .= 'var disp = $(\'input[id="q'.$k.'"][value="a'.$answerKey.'"]\').is(":checked") ? "block" : "none";';
						$strVote .= '$("input[id=\'own_answer'.$k.'\']").css("display",disp);';
						$strVote .= '$("input[id=\'q'.$k.'\']").click(function(){ $("#own_answer'.$k.'").css("display","none"); });';
						$strVote .= '$(\'input[id="q'.$k.'"][value="a'.$answerKey.'"]\').click(function(){ $("#own_answer'.$k.'").css("display","block"); });';
						$strVote .= ' });</script>';
					}
					
					$strVote .= '</td></tr>';
				}
			$strVote .= "\r\n";
		}
		echo $strVote;
	?>
		<tr>
			<td>
				<div>&nbsp;</div><span class="style2">E-mail : <input type="text" maxlength="200" size="64" value="" name="email" style="width:200px;"></span>
			</td>
		</tr>
		<tr>
		  <td valign="middle">
				<div>&nbsp;</div><span class="style2">КОД :</span> 
				<input type="text" name="anti_spam_code" value="" autocomplete="off" size="10" maxlength="10" style="background-image:url('<?=$config['web_path']?>/images/antispam_white.png');  width:15%; text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" />
				<img src="<?=$config['web_path']?>/antispam/antispam2.php" onclick="javascript:this.src='<?=$config['web_path']?>/antispam/antispam2.php'+'?'+Math.random()" width="80" height="21" id="aspamimg" style="border:0px solid; border-color:#606060; vertical-align:bottom; padding-bottom:1px; cursor:pointer;" /></td>
		</tr>
		<tr><td><p><span class="style2"><input type="submit" name="send_vote" value="ОТПРАВИТЬ" style="font-family:'MagistralCBold', Verdana; font-size: 20px; border: solid: 0px; background-color:#999999;"/></span>
		</p></td></tr>
	</tbody>
	</table>
</form>
