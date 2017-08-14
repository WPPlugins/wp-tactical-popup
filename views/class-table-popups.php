<?php


if( ! class_exists( 'WP_List_Table' ) ) 
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );


class wppt_popup_table extends WP_List_Table {

    protected $example_data = array();
    protected $model        = null;

    function __construct(){
        parent::__construct(
          array(
              'singular'  =>  'Popup',
              'plural'    =>  'Popups'
            ));
    }



  function column_default( $item, $column_name ) {
    switch ($column_name) {

      case 'created':
        return date('F / j / Y',strtotime($item[$column_name]));

      break;
      case 'cb':

      return "";
      break;

      case 'active':
        $state = (empty($item[$column_name])) ? '' :'checked="true"';
      
        return '<input type="checkbox" disabled="disabled" value="1" ' . $state .' />' ;

      break;
      case 'views':
        return rand();

      break;

      case 'popup_type':
  
        return wpptSHARED::$type_2_name[$item['type']];

      break;
      
      case 'popup_name':
      $toggle_capt = (empty($item['active'])) ? 'Activate': 'Deactivate';
       $actions = array(
            'toggle'    => sprintf('<a href="?page=%s&action=toggle&bulk[]=%d&nonce=%s">%s</a>',$_REQUEST['page'],$item['id'],wp_create_nonce('wppttoggle'),$toggle_capt),
            'edit'      => sprintf('<a href="?page=%s&id=%d">Edit</a>',wpptSHARED::$popup_type[$item['type']] ,$item['id'] ,$item['id']),
            'delete'    => sprintf('<a href="?page=%s&action=delete&bulk[]=%d&nonce=%s">Delete</a>',$_REQUEST['page'],$item['id'],wp_create_nonce('wpptdelete')),
        );

        return  $item[$column_name] . $this->row_actions($actions);

      break;

      default:
          return $item[$column_name];
      break;
    }
  }

function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            'bulk',
            $item['id']
        );
    }


function get_columns(){
        $columns = array(
            'cb'          => '<input type="checkbox" />',
            'id'          => 'ID',
            'created'     => 'Created',
            'active'      => 'Enabled',
            'popup_name'  => 'Name',
            'popup_type'  => 'Type'
        );
         return $columns;
    }

function prepare_items($model) {
  global $wpdb;
  $this->model = $model;
  $this->example_data = $model->listItems();  
  

    usort( $this->example_data, array( $this, 'usort_reorder' ) );

  $per_page = 10;
  $current_page = $this->get_pagenum();
  $total_items = count($this->example_data);
  $this->set_pagination_args( array(
    'total_items' => $total_items,                  //We have to calculate the total number of items
    'per_page'    => $per_page                     //We have to determine how many items to show on a page
  ) );

  $paged = (isset($_REQUEST['paged']))  ? $_REQUEST['paged'] - 1 : 0;
  
   $this->example_data = array_slice($this->example_data, $per_page * ($paged) , $per_page);
  

  $columns  = $this->get_columns();
  $hidden   = array();
  $sortable = array(
    'created'     => array('created',false),
    'popup_name'  => array('popup_name',false),
    'id'          => array('id',false),
    'popup_type'  => array('popup_type',false)

    );
  $this->_column_headers = array( $columns, $hidden, $sortable );
  $this->items = $this->example_data;;
}


function get_bulk_actions() {
    $actions = array(

    );
    //Add delete function for admins
    if(current_user_can( 'delete_published_posts' )){
        $actions['delete']      = 'Delete';
        $actions['activate']    = 'Activate';
        $actions['deactivate']  = 'Deactivate';

    }
    return $actions;
 }


public function usort_reorder( $a, $b ) {
  // If no sort, default to title
  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'created';
  // If no order, default to asc
  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'desc';
  // Determine sort order


  if (strcasecmp($orderby, 'popup_name')===0) 
       $result = strcasecmp($a[$orderby], $b[$orderby]);

  if ((strcasecmp($orderby, 'created')===0 )||
      (strcasecmp($orderby, 'id')===0 )
    )        
      $result = $a[$orderby] - $b[$orderby] ;

  if (strcasecmp($orderby, 'popup_type')===0 )        
      $result = $a['type'] - $b['type'] ;
  
    return ( $order === 'asc' ) ? $result : -$result;
}

} //class

