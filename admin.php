<?php
require(dirname(__FILE__) .'/includes/class-error.php');
require(dirname(__FILE__) .'/includes/class-admin-view.php');
require(dirname(__FILE__) .'/includes/class-admin-view-simple.php');
require(dirname(__FILE__) .'/includes/class-admin-view-db.php');
require(dirname(__FILE__) .'/includes/class-db-model.php');

require(dirname(__FILE__) .'/models/class-popup-model.php');

require(dirname(__FILE__) .'/views/class-admin-top.php');
require(dirname(__FILE__) .'/views/class-admin-global-settings.php');
require(dirname(__FILE__) .'/views/class-add-popup-html.php');
require(dirname(__FILE__) .'/views/class-add-popup-image.php');
require(dirname(__FILE__) .'/views/class-add-popup-email.php');

require(dirname(__FILE__) .'/views/class-add-popup.php');
require(dirname(__FILE__) .'/views/class-admin-common.php');



class wpptAdmin
{

	protected $Admintop 	= null;
	protected $admin_view 	= null;

	function __construct(){
		add_action( 'admin_menu' 	, array($this,'add_menus') );
		add_action('current_screen'	, array($this,'process_request'));
	}

	public function process_request(){
		add_action( 'admin_enqueue_scripts', array($this,'load_modal'));

		if ( $this->determine_page() && !empty($_GET['page'])){	
			$_POST 	  	= stripslashes_deep($_POST);
			$_REQUEST 	= stripslashes_deep($_REQUEST);
			$_GET  		= stripslashes_deep($_GET);
		
			add_action( 'admin_enqueue_scripts', array($this,'load_assets'));

			$this->admin_view->process_request();

			if (wpptSQA::is_post()){
				$this->admin_view->save();
			}

		}
	}
	
	public function load_modal(){
	//	wp_enqueue_style( 'arevico-modal-css'		, plugins_url('includes/modal/mp.css',__FILE__) );
	//	wp_enqueue_script( 'arevico-modal-js'	, plugins_url('includes/modal/mp.js',__FILE__) , array('jquery') );
	}

	public function load_assets(){
		wp_enqueue_style( 'arv-admin-css'		, plugins_url('includes/admin-style/admin.css',__FILE__) );
		wp_enqueue_script( 'arevico-tab-js'		, plugins_url('includes/tab/tab-simple.js',__FILE__) , array('jquery') );
		wp_enqueue_script( 'arevico-chart-js'	, plugins_url('includes/chart/chart.js',__FILE__) , array('jquery') );

		wp_enqueue_script( 'arevico-jscolor-js'	, plugins_url('includes/jscolor/jscolor.js',__FILE__) , array('jquery') );

		$this->load_image_selector();
	}

	private function load_image_selector(){

		if(function_exists( 'wp_enqueue_media' )){
    		wp_enqueue_media();
		}else{
		    wp_enqueue_style('thickbox');
    		wp_enqueue_script('media-upload');
    		wp_enqueue_script('thickbox');
		}
		return; //void
	}


	private function determine_page(){
		if (!isset($_GET['page']))
			return;

		switch ($_GET['page']) {
			
			case 'wppt-tld':
				$this->admin_view = new wpptAdminTop();
			break;

			case 'wppt-global':
				$this->admin_view = new wpptAdminAGS();
			break;

			case 'wppt-add-html':
			$this->admin_view = new wpptAddHTMLPopup();

			break;
			
			case 'wppt-add-image':
			$this->admin_view = new wpptAddImagePopup();

			break;

			case 'wppt-add-email':
			$this->admin_view = new wpptAddEmailPopup();

			break;

			case 'wppt-add-popup':
			$this->admin_view = new wpptAddPopup();

			break;
		}

		return !is_null($this->admin_view);
	}	

	public function render_page(){
		$this->admin_view->render_page();
	}

	public function add_menus(){
	    add_menu_page( 'WP Tactical Popup', 'WP Tactical Popup', 'manage_options', 'wppt-tld'				, array($this,'render_page') ); 
	    add_submenu_page( 'wppt-tld','Global Settings', 'Global Settings', 'manage_options', 'wppt-global'		, array($this,'render_page') ); 
	   	add_submenu_page( 'wppt-tld','Add Popup', 'Add Popup', 'manage_options', 'wppt-add-popup'			, array($this,'render_page') ); 

	    add_submenu_page( null,'Add HTML Popup', 'Add HTML Popup', 'manage_options', 'wppt-add-html'		, array($this,'render_page') ); 
	    add_submenu_page( null,'Add Image Popup', 'Add Image Popup', 'manage_options', 'wppt-add-image'		, array($this,'render_page') ); 
	    add_submenu_page( null,'Add Email Popup', 'Add Email Popup', 'manage_options', 'wppt-add-email'		, array($this,'render_page') ); 

	}

}	

?>