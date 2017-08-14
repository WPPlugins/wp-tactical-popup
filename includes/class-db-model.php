<?php 
/**
 * Controller class
 */
abstract class wpptModel{

	/* Process and serializes data to be stored in the database*/
	public abstract function preProcess($data);

	/* Process and derializes data to after it has been fetched from the database*/
	public abstract function postProcess($data);
	/* Clean up data before to be stored in the database*/	
	public abstract function sanitize($data);
	/* Verify data before to be queried against the database*/	
	public abstract function validate($data);

	/* Describing the associated table */
	private $table_name 	= '';

	/* Array of values of options which are checkboxes */
	private $checkboxes 	= array();

	private $data_model 	= array();
	/* Array containing all foreign relations */
	private $foreign 		= array();
	/* Generic select query */
	private $select_query 	= 'SELECT %1$s FROM %2$s WHERE %3$s';
	/* Generic delete query */
	private $delete_query 	= 'DELETE FROM %1$s WHERE %2$s';

	private $arr_data	 	= array();

	private $defaults;
	/**
	 * Save a posted array into the dabase
	 * @param string $id optional id to be fetched. If not specified, query is not constrained
	 * @return array result set of the executed query
	 */
	public function fetch($id=null,$id_key='id'){
		global $wpdb;
		if (is_null($id) && isset($_REQUEST['id']))
			$id = $_REQUEST['id'];

		if (is_null($id))
			return $this->defaults;

		$where 	=  "{$id_key}={$id}";
		$query 	= sprintf($this->select_query,'*', $this->table_name,$where,'');

		$res 	= $wpdb->get_results($query,ARRAY_A);
		if (!empty($res))
			$res = $res[0];

		foreach ($this->foreign as $key => $foreign) {
			$where 		= $foreign['key'] . "= {$id}";
			$query 		= sprintf($this->select_query,'*', $foreign['name'],$where,'');
			$res[$key]	= $wpdb->get_results($query,ARRAY_A);

			if (count($res[$key])==1)
				$res[$key]=$res[$key][0];
		}
		return $this->postProcess($res);
	}


	public function listItems(){
		global $wpdb;
		$where 	= '1=1';
		$query 	= sprintf($this->select_query,'*', $this->table_name, $where,'');
		$res 	= $wpdb->get_results($query,ARRAY_A);
		return $res;
	}

	/**
	 * Save a posted array into the dabase. Saving entails either updating or inserting
	 * @param array $arr_data the data to be saved
	 * @return void
	 */
	public function save($arr_data,$id=null){
		$arr_data = $this->preProcess($arr_data);
		if (null !== $id)
			$arr_data['id'] = $id;
		
		if (isset($arr_data['id'])){
		
			$local_id 	= 	$this->edit($arr_data);
		
		} else{
			
			$local_id 	= 	$this->insert($arr_data);
		}
		return $local_id;
	}

	/**
	 * Update a posted array into the dabase. Specified and included foreign relations are also considered
	 * @param array $arr_data the data to be saved
	 * @return void
	 */
	private function edit($arr_data){
		global $wpdb;
		$main_data 	 	= array_diff_key($arr_data, $this->foreign);
		$local_id 		= $arr_data['id'];

		$where 			= array(
							"id" => $local_id
							);

		$wpdb->update($this->table_name, $main_data, $where);

		foreach ($this->foreign as $key => $foreign) {
				if (isset($arr_data[$key])){
					$foreign_data	 				= $arr_data[$key];
					$foreign_data[$foreign['key']]	= $local_id;
					$where = array(
							$foreign['key'] => $local_id
							);
					$wpdb->update($foreign['name'], $foreign_data, $where);
				}
		}

		return $local_id;
	}
	/**
	 * Save a posted array into the dabase. Saving entails either updating or inserting
	 * @param array $arr_data the data to be saved
	 * @return void
	 */
	public function insert($arr_data){
		global $wpdb;
		$main_data 	= array_diff_key($arr_data, $this->foreign);

		$wpdb->insert($this->table_name, $main_data);
		$local_id 	= $wpdb->insert_id;

		foreach ($this->foreign as $key => $foreign) {
			if (isset($arr_data[$key])){
				$foreign_data	 				= $arr_data[$key];
				$foreign_data[$foreign['key']]	= $local_id;
				$wpdb->insert($foreign['name'], $foreign_data);

			}
		}

		return $local_id;
	}
	
	
	/**
	 * 
	 * 
	 */
	public function delete($id=null){
		foreach ($this->foreign as $key => $foreign) {
			$where 		= $foreign['key'] . "= {$id}";
			$query 		= sprintf($this->select_query,'*', $foreign['name'],$where,'');
			$res[$key]	= $wpdb->get_results($query,ARRAY_A);
		}

	}


	/**
	 * Constructs the class
	 * @param array $arr_data The data set posted
	 */
	function __construct($table_name, $foreign, $defaults){
		$this->table_name 	= $table_name;
		$this->foreign 		= $foreign;
		$this->defaults 	= $defaults;

	}

}


 ?>