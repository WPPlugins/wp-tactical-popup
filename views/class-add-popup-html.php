<?php

class wpptAddHTMLPopup extends wpptAdminViewDB{ 

	protected $options 	= null;
	protected $model 	= null;
	/* contains either null or the id*/

	function __construct(){
    $behaviour = array ( 'seconds' => '2', 'pageviews' => '0', 'days' => '0', 'close_oc' => '1' );

    $defaults = array ( 
      'b' => $behaviour, 
      'popup_data' => 
         array ( 
           'width' => '600px', 
           'height' => '600px'
           ), 
      'd' =>
         array ( 
          'id'          => '43', 
          'pid'         => '84', 
          'on_post'     => '1', 
          'on_page'     => '1', 
          'on_home'     => '1', 
          'on_archive'  => '1', 
          'url'         => ''
          ));

		$this->model 	= new wpptPopupModel($defaults);
		$this->err 		= new wpptErrorClass();
	}


	public function render_page(){
		$o=$this->options;
?>	
<div class="wrap arv-opt">


<?php wpptAdminCommon::render_faq_line(); ?>



<?php $this->err->gen_message('<div id="setting-error-settings_updated" class="updated settings-error"> 
<p><strong>Settings saved.</strong></p></div>',''); ?>
<form method="POST" action="?page=wppt-add-html<?php $this->render_id_action(); ?>">

<input type="hidden" name="o[type]" value="1" />
<?php $this->render_id_field();
  wp_nonce_field(-1,'wppt-update-forms');
 ?>



<div id="tabs">
  <nav class="navbar nav-fullwidth">
   <ul>
     <li><a href="#tab-general">General</a> </li>
     <li><a href="#tab-where">Where</a></li>
      
     <li><a href="#tab-behaviour">Behaviour</a></li>

   </ul>
  </nav>
  <!-- General -->
   <div class="arv-tab" id="tab-general">
    <div class="onepcssgrid-1000">
   <?php 
	   	wpptAdminCommon::render_content($this);
   	 	$this->outputTextarea('o[popup_data][html]','HTML Code'); ?>
    </div>
   </div>
  <!-- /General -->

  <!-- Overlay -->
   <div class="arv-tab" id="tab-where" style="display:none;">
    <div class="onepcssgrid-1000">
   <?php 
	wpptAdminCommon::render_where($this); 
	?>
    </div>
   </div>
  <!-- /overlay -->
  

  <!-- /overlay -->

  <!-- Behaviour -->
   <div class="arv-tab" id="tab-behaviour" style="display:none;">
    <div class="onepcssgrid-1000">
   <?php 
	wpptAdminCommon::render_behaviour($this);
	?>
    </div>
   </div>
  <!-- /Behaviour -->

</div>
 	<div class="onepcssgrid-1000">
 	<div class="col4 last"><input class="add-new-h2" style="width:100%;height:35px;" type="submit" value="  Save  "/>
</div>
  	</div>
  	
</form>
   
<script type="text/javascript"><?php $this->err->gen_js_feedback(); ?></script>

</div> <!-- #arevico-opt-page -->
<?php

} //end do_page


}
 ?>