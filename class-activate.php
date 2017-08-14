<?php 
class wpptActivate
{
	

	public static function on_activate(){
		global $wpdb;
			$create_statements = array(

					"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}_wpptPopups (  id INT NOT NULL AUTO_INCREMENT,  popup_name TEXT NULL,  active TINYINT(1) NULL,  type INT NULL,  behaviour BLOB NULL,  popup_data BLOB NULL,  created TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,  prio INT NULL,  PRIMARY KEY (id));",
					"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}_wpptDisplay_Rules ( id INT NOT NULL AUTO_INCREMENT, pid INT NULL, on_post TINYINT(1) NULL, on_page TINYINT(1) NULL, on_home TINYINT(1) NULL, on_archive VARCHAR(45) NULL, url BLOB NULL, PRIMARY KEY (id));"
				);

			foreach ($create_statements as $statement) {
				$wpdb->query($statement);
			}
			
			$o = get_option('wppt-global-options',array());
	
			if (empty($o['delete_data']))
				update_option('wppt-global-options',array(
					'delete-data' 		=> false, 
					'css-specificity' 	=> 'html body .mfp-contentcode',
					'install_date'		=> time()
			));
	
			return ;
	}

		public static function on_deactivate(){
			$o = get_option('wppt-global-options');
				if (empty($o['delete_data']))
				return ;
				
			global $wpdb;
			$delete_statement = array(
				"DROP TABLE {$wpdb->prefix}_wpptPopups;",
				"DROP TABLE {$wpdb->prefix}_wpptDisplay_Rules;"
				);
		
			foreach ($delete_statement as $statement) {
				$wpdb->query($statement);
			}

				delete_option('wppt-global-options');
			return ;
		}
} 


?>