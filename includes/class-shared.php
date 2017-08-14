<?php 

class wpptSHARED{

	public static $popup_type = array(
		'1' => 'wppt-add-html',
		'2' => 'wppt-add-image',
		'3' => 'wppt-add-email'

		);

	public static $type_2_name= array(
		'1' => 'HTML',
		'2' => 'Image',
		'3' => 'email'
		);

	public static $default_settings = array();

	public static function normalize_settings($arr_settings){
		$arr_settings = array_merge(array(), $arr_settings);

		return $arr_settings;
	}

}
 ?>