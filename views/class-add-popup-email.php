<?php

class wpptAddEmailPopup extends wpptAdminViewDB{ 

	protected $options 	 = null;
	protected $model 	   = null;
	/* contains either null or the id*/

	function __construct(){
    $behaviour = array ( 'seconds' => '2', 'pageviews' => '0', 'days' => '0', 'close_oc' => '1' );

    $defaults = array ( 
      'b' => $behaviour, 
      'popup_data' => 
         array ( 
           'width'        => '600px', 
           'height'       => '600px',
           'email-header' => 'Get all updates!',
           'email-text'   => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
<ul>
  <li>Lorem ipsum dolor sit</li>
  <li>quis nostrud exercitation</li>
  <li>cillum dolore eu</li>
  <li>proident, sunt in</li>
</ul>
',
           'email-placeholder' => 'email@domain.com',
           'email-submit'  => 'Subscribe!'
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
<p><strong>Settings saved.</strong></p></div>','<div class="error below-h2">Failed! Correct the specified errors!</div>'); ?>
<form id="email-form" method="POST" action="?page=wppt-add-email<?php $this->render_id_action(); ?>">

<input type="hidden" name="o[type]" value="3" />
<?php $this->render_id_field(); 
  wp_nonce_field(-1,'wppt-update-forms');
?>

<div id="tabs">
  <nav class="navbar nav-fullwidth">
   <ul>
     <li><a href="#tab-general">General</a> </li>
     <li><a href="#tab-content">Content</a></li>
     <li><a href="#tab-theme">Theme</a></li>
     <li><a href="#tab-overlay">Where</a></li>
     <li><a href="#tab-behaviour">Behaviour</a></li>

   </ul>
  </nav>
  <!-- General -->
   <div class="arv-tab" id="tab-general">
    <div class="onepcssgrid-1000">
   <?php 
	   	wpptAdminCommon::render_content($this);
      $this->outputSeperator();

            $this->outputTextArea('o[popup_data][email_form]','Paste Email Form','','email-form-txt'); 
      $this->outputSeperator();
      $this->outputSeperator();

	?>
    </div>
   </div>
  <!-- /General -->

  <!-- Content -->
   <div class="arv-tab" id="tab-content" style="display:none;">
    <div class="onepcssgrid-1000">
   <?php 

      $this->outputText('o[popup_data][email-header]','Header Text');
      $this->outputSeperator();

      $this->outputText('o[popup_data][email-submit]','Submit Text');
      $this->outputSeperator();

      $this->outputText('o[popup_data][email-placeholder]','Email Placeholder');
      $this->outputSeperator();

      $this->outputTextArea('o[popup_data][email-text]','Popup Text','Can contain HTML!');

      $this->outputSeperator();
      

  ?>
    </div>
   </div>
  <!-- /Content -->

  <!-- Theme -->
   <div class="arv-tab" id="tab-theme" style="display:none;">
    <div class="onepcssgrid-1000">
       <?php 
       
      $themes   =   array(
          'blue'  => 'Blue',
          'green' => 'Green'
        );
      $this->outputSelect('o[popup_data][selected-theme]','Select Theme',$themes);
        ?>

    

    </div>
   </div>

  <!-- /Theme -->

  <!-- Overlay -->
   <div class="arv-tab" id="tab-overlay" style="display:none;">
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
 	<div class="col4 last"><input class="add-new-h2" style="width:100%;height:35px;" type="submit" value="save"/>
</div>
  	</div>
  	
</form>
   
<script type="text/javascript">
jQuery(document).ready(function($){


  $('#email-form').submit(function(){ //listen for submit event
    email_raw_html = $($('#email-form-txt').val());

    extract_hidden_fields(email_raw_html);
    extract_form_action(email_raw_html);
    extract_email_field(email_raw_html);
    return true;
  });

  function extract_hidden_fields(email_raw_html){
    $('input[type="hidden"]',email_raw_html).each(function(i,elem){
     add_value_to_post('o[popup_data][email-hidden][' + $(elem).attr('name') + ']',$(elem).val());
   });

  }

  function extract_form_action(email_raw_html){
   email_action = $('form',email_raw_html).first().attr('action');
     add_value_to_post('o[popup_data][email-action]',email_action);
  }

  function extract_email_field(email_raw_html){
    var email_field = $('input[type="email"],input[name*=\'email\'],input[placeholder*=\'email\'],input[placeholder*=\'@\']',email_raw_html);
      if ($(email_field).length>0){
          add_value_to_post('o[popup_data][email-field]',email_field.first().prop('name'));
        }
  }

  function add_value_to_post(var_name,var_value){
    $('<input type="hidden"/>').attr('name',var_name).val(var_value).appendTo('#email-form');

  }

 


});



<?php $this->err->gen_js_feedback(); ?>

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
