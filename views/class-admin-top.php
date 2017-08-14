<?php
require(dirname(__FILE__) .'/class-table-popups.php');

class wpptAdminTop extends wpptAdminViewDB{ 

	//@ghost $err represent error object
	//@ghost $options represents option object
	private $popup_table = null;

	function __construct(){
		/* 	prevent fetching and saving automatically,
			since we only want to use the list, delete and query function */
		parent::__construct(false);
		$this->model 		= new wpptPopupModel;
		$this->popup_table  = new wppt_popup_table();
		$this->determine_action();

	}

	public function sanitize_input(){

	}

	public function check_errors(){

	}

	public function update_settings(){
		$this->determine_action();
	}

	private function determine_action(){
		$action = (isset($_REQUEST['action']) && $_REQUEST['action']!= -1 ) ? 
		$_REQUEST['action'] : null;

		$action = ((empty($action)) && isset($_REQUEST['action2']) && $_REQUEST['action2']!= -1 ) ? 
		$_REQUEST['action2'] : $action;


		if (!is_null($action))
		switch ($action) {
			case 'delete':
				foreach ($_REQUEST['bulk'] as $key => $value) 
					$this->delete_popup($value);
			break;

			case 'activate':
				foreach ($_REQUEST['bulk'] as $key => $value) 
					$this->activate_popup($value);
			break;
			
			case 'deactivate':
				foreach ($_REQUEST['bulk'] as $key => $value) 
					$this->deactivate_popup($value);
			break;

			case 'toggle':
				foreach ($_REQUEST['bulk'] as $key => $value) 
					$this->toggle_popup($value);

			break;

			default:
			break;
		}

	}

	private function deactivate_popup($id){
		global $wpdb;
		if (is_numeric($id))
			$wpdb->query("UPDATE {$wpdb->prefix}_wpptPopups SET active=0 WHERE id=\"{$id}\"");
	}

	private function activate_popup($id){
		global $wpdb;
		if (is_numeric($id))
			$wpdb->query("UPDATE {$wpdb->prefix}_wpptPopups SET active=1 WHERE id=\"{$id}\"");
	}

	private function toggle_popup($id){
		global $wpdb;
		if (is_numeric($id))
			$wpdb->query("UPDATE {$wpdb->prefix}_wpptPopups SET active = NOT active WHERE id=\"{$id}\"");
	}


	private function delete_popup($id){
		global $wpdb;
		if (is_numeric($id)){
			$wpdb->query("DELETE FROM {$wpdb->prefix}_wpptPopups  WHERE id=\"{$id}\"");
			$wpdb->query("DELETE FROM {$wpdb->prefix}_wpptDisplay_Rules WHERE pid=\"{$id}\"");

		}
	}

	public function render_page(){
		$o = $this->options;
?>	
<div class="wrap">



<?php wpptAdminCommon::render_faq_line(); ?>


<h2>Popups <a href="<?php echo admin_url('admin.php?page=wppt-add-popup'); ?>" class="add-new-h2">Add New</a></h2>
<style type="text/css">
	.wp-list-table .column-id { width: 10%; }
	.wp-list-table .column-active { width: 10%;text-align: center; }
	.wp-list-table .column-created { width: 15%; }

</style>
<form method="POST">

	<?php   
		$this->popup_table->prepare_items($this->model); 
  		$this->popup_table->display(); 
 ?>
	</div>


</form>
</div> <!-- #arevico-opt-page -->
<?php

} //end do_page


}
 ?>