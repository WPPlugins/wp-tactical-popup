<?php

class wpptAddImagePopup extends wpptAdminViewDB{ 

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
<form method="POST" action="?page=wppt-add-image<?php $this->render_id_action(); ?>">

<input type="hidden" name="o[type]" value="2" />
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
   	 	$this->outputTextDesc('o[popup_data][image]','Image','<input class="add-new-h2" id="arv-med-upl-button" style="width:100%;height:35px;" type="button" value="Select" />','arv-med-upl'); 
      $this->outputSeperator();
   	 	$this->outputText('o[popup_data][url]','Link Url','Leave empty if you don\'t want the image to be linked');
      $this->outputCheckbox('o[popup_data][new_tab]','New Tab','Open link in a new tab');
      $this->outputSeperator();
      $this->outputSeperator();

	?>
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
 	<div class="col4 last"><input class="add-new-h2" style="width:100%;height:35px;" type="submit" />
</div>
  	</div>
  	
</form>
   
<script type="text/javascript"><?php $this->err->gen_js_feedback(); ?>

jQuery(document).ready(function($){
  var _custom_media = true,
      _orig_send_attachment = wp.media.editor.send.attachment;

  $('#arv-med-upl-button').click(function(e) {
    var send_attachment_bkp = wp.media.editor.send.attachment;
    var button = $(this);
    var id = button.attr('id').replace('-button', '');
    _custom_media = true;
    wp.media.editor.send.attachment = function(props, attachment){
      if ( _custom_media ) {
        $("#"+id).val(attachment.url);
        window.___=attachment;
      } else {
        return _orig_send_attachment.apply( this, [props, attachment] );
      };
    }

    wp.media.editor.open(button);
    return false;
  });

});

</script>

</div> <!-- #arevico-opt-page -->
<?php

} //end do_page


}
 ?>