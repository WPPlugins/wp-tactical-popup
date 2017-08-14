<?php 
abstract class  wpptAdminViewSimple  extends wpptAdminView{
	
	protected $err 			= null;
	protected $options 	 	= array();
	protected $option_name  = '';
	protected $default 		= array();

	public function process_request(){
		if (wpptSQA::is_POST()){
			$this->options = $_POST;

		} elseif (!wpptSQA::is_POST()){
			$this->options = $this->fetch();
		}
	}

	private function fetch(){
		return array('o'=>get_option($this->option_name,$this->default));
	}

	public function save(){
		if (isset($_POST['o']) && (!empty($this->option_name)) && (!$this->err->has_error()) && wp_verify_nonce($_POST['wppt-update-forms']) )
			update_option($this->option_name,$_POST['o']);	
	}

	function __construct($option_name='',$default=array()){
		$this->option_name 	= $option_name;
		$this->err 		 	= new wpptErrorClass();
		$this->default 		= $default;
	}

}

 ?>