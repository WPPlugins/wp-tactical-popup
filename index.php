<?php 
/*
   Plugin Name: WP Tactical Popup
   Plugin URI: http://arevico.com/wp-tactical-popup-update/
   Description:  Lightboxes are a very powerful way to capture your visitors attention. WP Tactical Popups aims to help you do just that. Flexible enough to display HTML advertisements and image lightboxes, yet useable enough to do it in minutes.
   Version: 1.1
   Author: Arevico
   Author URI: http://arevico.com/
   Copyright: 2014, Arevico
*/
require(dirname(__FILE__) .'/includes/class-moscow.php');
require(dirname(__FILE__) .'/includes/class-shared.php');
require(dirname(__FILE__) .'/class-activate.php');

$wpptSHARED 	= new wpptSHARED();

if (is_admin() ){
	require(dirname(__FILE__) .'/admin.php');
	$wpptAdmin 		= new wpptAdmin();

}
// fire on frontend and ajax

if ( (defined('DOING_AJAX') && DOING_AJAX) || !is_admin()) {
    require(dirname(__FILE__) .'/class-popup.php');
    $wpptTacticalPopup = new wpptTacticalPopup();
}



class wpptTacticalPopup 
{
  protected $popup_id     = null;
  protected $popup_type   = null;
  protected $admin_bar    = array();

  function __construct(){
      add_action( 'wp_ajax_wppt-do'       , array($this, 'ajax'));
      add_action( 'wp_ajax_nopriv_wppt-do'  , array($this, 'ajax') );
      add_action('wp', array($this,'load_popups'));
  }  

  public function load_popups(){
    $popup              = wpptPopupFront::determine_which();
    $canditates         = array();

    foreach ($popup as $p) {
      array_push($canditates, $p['id']);
    }

    $canditates     = implode(',',$canditates);
    $this->popup_id     = $canditates;
     $this->admin_bar    = $popup;

    if (!(is_null($this->popup_id) )){
      add_action('wp_enqueue_scripts'           , array($this,'add_frontend') );
      add_action( 'admin_bar_menu'  , array($this,'edit_popup_bar'), 999 );

    }
  }


  public function add_frontend(){
    wp_enqueue_style( 'wpptlb-tact-css', plugins_url('/includes/modal/mp.css',__FILE__) );
    wp_enqueue_script( 'wpptlb-tact-js', plugins_url('/includes/modal/mp.js',__FILE__), array(), '1.0.0', true );
    $frontend_data = array(
                            "ajax_url"    => admin_url("admin-ajax.php?action=wppt-do&id={$this->popup_id}"),
                            "ids"         => "{$this->popup_id}"
                          );
    wp_localize_script('wpptlb-tact-js','wpptlb_tact',$frontend_data);
  }

  public function ajax(){
    Header('Content-Type:application/javascript');
  
   if (isset($_GET['id']) ){
      $popup = wpptPopupFront::create($_GET['id']);
      if (!is_null($popup))
        $popup->get_popup();
    }
    die(); //close of ajax gracefully!
  }


  function edit_popup_bar() {
   if ( ! is_super_admin() || ! is_admin_bar_showing() )
     return;

   global $wp_admin_bar;

  $wp_admin_bar->add_menu( array(
      'parent'  => 'new-content',
      'id'      => 'wppt-add-popup',
      'title'   => 'Popup',
     'href'     => admin_url('admin.php?page=wppt-add-popup')
    ) );


  if (!empty($this->admin_bar)){
    $args = array(
      'id'     => 'wppt-edit-popup',
      'title'  => 'Edit Popup'
    );
  $wp_admin_bar->add_menu( $args );
  }

  foreach ($this->admin_bar as $popup) {
    $args = array(
      'parent' => 'wppt-edit-popup',
      'id'     => 'wppt-edit-popup-' . $popup['id'],
      'title'  => "Edit '{$popup['popup_name']}'",
      'href'   => admin_url('?page=' . wpptSHARED::$popup_type[$popup['type']] . '&id=' . $popup['id'])
    );
  $wp_admin_bar->add_menu( $args );

  }
}

}



function wppt_arv_activate() {
  wpptActivate::on_activate();
}

function wppt_arv_deactivate() {
  wpptActivate::on_deactivate();
}

register_activation_hook( __FILE__, 'wppt_arv_activate' );
//register_deactivation_hook( __FILE__, 'wppt_arv_deactivate' );
register_uninstall_hook(__FILE__, 'wppt_arv_deactivate' );


 ?>