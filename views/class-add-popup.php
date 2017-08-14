<?php
class wpptAddPopup extends wpptAdminViewSimple{ 

	//@ghost $err represent error object
	//@ghost $options represents option object
	private $popup_table = null;

	function __construct(){
		parent::__construct();

	}

	public function sanitize_input(){

	}

	public function check_errors(){

	}

	public function update_settings(){
		$this->determine_action();
	}


	public function render_page(){
?>	
<div class="wrap" id ="arv-opt-page">


<div style="margin:5px;padding:5px;background:white;border:1px solid #efefef;"><a style="text-align:right;" href="http://arevico.com/wp-tactical-f-a-q/" target="_new">Frequently Asked Questions</a></div>

<a href="?page=wppt-add-html" class="add-new-h2 overview">
Add HTML Popup <br />&nbsp;<br />
<img src="<?php echo plugins_url('/../includes/admin-style/img-html.png',__FILE__); ?>" /></a>

<a href="?page=wppt-add-image" class="add-new-h2 overview">
Add Image Popup
<br />&nbsp;<br />
<img src="<?php echo plugins_url('/../includes/admin-style/img-flower.png',__FILE__);?>" /></a>

<a href="?page=wppt-add-email" class="add-new-h2 overview">
Add Email Popup<br />&nbsp;<br />
<img src="<?php echo plugins_url('/../includes/admin-style/img-mail.png',__FILE__);?>" /></a>


<div style="clear:both;"></div>
</div> <!-- #arevico-opt-page -->
<?php

} //end do_page


}
 ?>