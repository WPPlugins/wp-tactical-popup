<?php 

abstract class  wpptAdminViewDB extends wpptAdminView{

	protected $err 		= null;
	protected $options 	= array();
	protected $is_edit 	= null;
	protected $model 	= null;

	/* if disabled, fetching and saving wont be automatically*/
	private $process 	= true;

	public function save(){
		if (isset($_POST['o']) && (!$this->err->has_error())  && (wp_verify_nonce($_POST['wppt-update-forms'])!==false))
			$this->set_edit($this->model->save($_POST['o'],$this->get_id() ));
	}

	public function get_id(){
		return $this->is_edit;
	}

	public function set_edit($id){
		$this->is_edit = $id;
	}

	protected function is_edit(){
		return (!is_null($this->is_edit));
	}

	public function render_id_field(){
		if ($this->is_edit() )
			echo "<input type=\"hidden\" name=\"id\" value=\"{$this->is_edit}\" />";
	}

	public function render_id_action(){
		if ($this->is_edit() )
			echo "&id={$this->is_edit}";
	}

	function __construct($process=true){
		$this->err 		= new wpptErrorClass();
	}

	public function process_request(){
		if (isset($_REQUEST['id'])){
			$this->is_edit = $_REQUEST['id'];
		}

		if (wpptSQA::is_POST()){
			$data 			= (isset($_POST['o'])) ? $_POST['o'] : array();
			$this->options 	= array("o"=> $data);

		} elseif (!wpptSQA::is_POST()){
			$this->options = array("o" => $this->model->fetch($this->get_id()) );
		}
	}

}


 ?>