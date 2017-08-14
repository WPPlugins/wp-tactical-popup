<?php 

class wpptAdminCommon 
{
	public static function sanitize_options(){
		$_POST['o']['d'] = array_merge(array(
			"on_post" 		=> "0",
			"on_page"	 	=> "0",
			"on_home" 		=> "0",
			"on_archive"	=> "0"),$_POST['o']['d']);

	}

	public static function render_faq_line(){
		
		?>
<div style="margin:5px;padding:5px;background:white;border:1px solid #efefef;">

<div style="float:left;">Support us and check out <a href="http://arevico.com/wp-tactical-popup-premium/" target="_new">what the premium version has to offer.</a></div>

<?php 
$o            = get_option('wppt-global-options',array());
$install_date = wpptSQA::val('install_date',$o,false,false);
if (is_numeric($install_date) && (time()-$install_date)>60*60*24*7){
  ?>
<div style="float:right;"><a style="color:green;" href="http://wordpress.org/support/view/plugin-reviews/wp-tactical-popup#postform" target="_new">Rate this plugin</a></div>
  <?php
} else {
 ?>
<div style="float:right;"><a href="http://arevico.com/wp-tactical-f-a-q/" target="_new">Frequently Asked Questions</a></div>
<?php   } ?>
<div style="clear:both;"></div>

</div>
<?php	
}


	public static function render_content($m){
			$m->outputText('o[popup_name]','Name');
			$m->outputSeperator();
			$label	= sprintf($m->getCol4(),' formtextbox','Maximum Width x Height');
			$width 	= $m->getText('o[popup_data][width]');
			$width 	= sprintf($m->getCol3(),'',$width);

			$sep 	= sprintf($m->getCol2(),'','<span style="display:inline-block;text-align:center;width:100%;line-height:35px;">x</span>');

			$height	= $m->getText('o[popup_data][height]');

			$height	= sprintf($m->getCol3(),' last',$height);
			printf($m->getOneRow(),$label . $width . $sep . $height);

			$m->outputSeperator();
		  

		?>

		<?php
	}

	

	public static function render_where($m){
		
		$m->outputCheckbox('o[d][on_page]','Show popup on','On Page');
		$m->outputCheckbox('o[d][on_post]','&nbsp;','On Post');
		$m->outputCheckbox('o[d][on_archive]','&nbsp;','On Archive');
		$m->outputCheckbox('o[d][on_home]','&nbsp;','On Homepage');
		$m->outputTextarea('o[d][url]', 'On specific pages','Only show when the url contains  the above. Prefix ~ for negation! One per line.');
		$m->outputSeperator();
		
		$m->outputSeperator();

	}
	

	


	public static function render_behaviour($m){

			$m->outputTextDesc('o[b][seconds]','Popup after','seconds');
			$m->outputSeperator();

			$input = $m->getText('o[b][pageviews]');
			$input = sprintf($m->getCol(6),' formselect',$input);
			$days  = sprintf($m->getCol(2),' formselect last','<strong>AND</strong> pageviews');
			echo($m->constructCustom('&nbsp;', $input . $days));
			$m->outputSeperator();

			$input = $m->getText('o[b][days]');
			$input = sprintf($m->getCol(6),' formselect',$input);
			$days  = sprintf($m->getCol(2),' formselect last','days');
			echo($m->constructCustom('Once in', $input . $days));

			

			$m->outputSeperator();

			$m->outputCheckbox('o[b][close_oc]','Close on','Overlay Click');
			?>
			      


		<?php
	}
} ?>