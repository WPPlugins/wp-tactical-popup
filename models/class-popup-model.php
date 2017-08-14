<?php 

/**
* 
*/
class wpptPopupModel extends wpptModel
{

	public function sanitize($data){

	}
	
	public function validate($data){

	}

	public function preProcess($data){
		if (!isset($data['popup_data']))
			$data['popup_data'] = array();

		if (!isset($data['b']))
			$data['b'] = array();

		$data['popup_data'] = serialize($data['popup_data']);
		$data['behaviour'] 	= serialize($data['b']);
		$data['active'] 	= true;
		
		if (!isset($data['d']))
				$data['d'] = array();

		if (!isset($data['d']['on_page']))
			$data['d']['on_page']=false;
		
		if (!isset($data['d']['on_post']))
			$data['d']['on_post']=false;
		
		if (!isset($data['d']['on_archive']))
			$data['d']['on_archive']=false;
		
		if (!isset($data['d']['on_home']))
			$data['d']['on_home']=false;
		
		unset($data['b']);

		return $data;
	}

	public function postProcess($data){
		if (!isset($data['popup_data'])){
			$data['popup_data'] = array();
		} else{
			$data['popup_data'] = unserialize($data['popup_data']);		
		}

		if (!isset($data['behaviour'])){
			$data['b'] = array();
		} else{
			$data['b'] = unserialize($data['behaviour']);		
		}
		return $data;
	}



	function __construct($default=array()){
		global $wpdb;
		$popup_data = array(
							'name' => "{$wpdb->prefix}_wpptDisplay_Rules",
							'key'  => 'pid'
						);
		$foreign 	= array(
							"d" => $popup_data,
						);
		

		$checkboxes = array();

		parent::__construct("{$wpdb->prefix}_wpptPopups", $foreign, $default, array());

	}

} ?>