<?php

class wpptAdminAGS extends wpptAdminViewSimple{ 


	function __construct(){
    parent::__construct('wppt-global-options');
	}

	public function sanitize_input(){

	}

	public function check_errors(){

	}

	public function update_settings(){

	}


	public function render_page(){
?>	
<div class="wrap arv-opt">

<?php $this->err->gen_message('<div id="setting-error-settings_updated" class="updated settings-error"> 
<p><strong>Settings saved.</strong></p></div>','<div class="error below-h2">Failed! Correct the specified errors!</div>'); ?>


<form method="post">

<div id="tabs">
  <nav class="navbar nav-fullwidth">
   <ul>
     <li><a href="#tab-1">Global Settings</a> </li>
   </ul>
  </nav>
   <div class="arv-tab" id="tab-1">
    <div class="onepcssgrid-1000">
    <?php 
      wp_nonce_field(-1,'wppt-update-forms');

      

      $this->outputCheckbox('o[delete_data]','Cleanup','Delete all data and database tables on deactivation!');
      $this->outputSeperator();

      $this->outputText('o[css-specificity]','CSS specificity ','Do not change, unless you know what you are doing or instructed to by support.');
      $this->outputSeperator();
     $this->outputSeperator();

     ?>

    </div>
   </div>


  <input class="add-new-h2" style="width:300px;height:35px;" type="submit" value="Save" />

</form>

</div> <!-- #arevico-opt-page -->
<?php

} //end do_page


}
 ?>