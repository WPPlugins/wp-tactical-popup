<?php 
// ----- Theme ----- 
// Theme_css
// Theme_html
// ----- /

require(dirname(__FILE__) .'/class-themes.php');

class wpptPopupFront
{
	protected $options 	= 	null;
	protected $g_options= array();
	protected $css_spec = '';
	protected $type 	= 	null;
	protected $id 		= 	null;

	protected $skeleton	= 	'';

	protected $css_reset = '';


	/*
	 * Static constructor
	 */
	public static function create($id)
{		if (empty($id))
			return null;
		//security
		$id = preg_replace('/[^0-9,]/', '', $id);
		global $wpdb;
		$o = $wpdb->get_results("SELECT *,dp.id as dpid FROM  {$wpdb->prefix}_wpptDisplay_Rules dp,{$wpdb->prefix}_wpptPopups p WHERE p.active AND p.id in ({$id}) AND dp.pid=p.id ORDER BY p.created DESC",ARRAY_A );

		if (empty($o))
			return null;

		foreach ($o as $key => &$popup) {

		$popup['d'] = wpptSQA::get_ins_inc($popup,array('on_post','on_home','on_archive','url'));

		//$o['d']=$o['d'][0];
		$popup['popup_data'] = (isset($popup['popup_data'])) ? unserialize($popup['popup_data']) : array();
		$popup['b'] 		 = (isset($popup['behaviour'])) ? unserialize($popup['behaviour']) : array();
	}

	

		//Determine best candidate (first candidate when sorted on priority or date created)
	if (empty($o))
		return null;

	$o 	= $o[0];
	$id = $o['id'];
		


		return new wpptPopupFront($o,$o['type'],$id);
	}

	

	public static function popuppreview($ptype,$o){
		 $preview_override = array();

		if (empty($o['popup_data'] )) 
			$o['popup_data']  = array();
		$o['popup_data']  = array_merge($o['popup_data'] , $preview_override);
		return new wpptPopupFront($o,$ptype,$id);
	}
	/*
	 *Determines which popup to use
	 */
	public static function determine_which(){
		global $wpdb;

		$condition  = self::generate_condition();
		$arr_ret 	= array();
		$res  		= $wpdb->get_results("SELECT p.popup_name,p.id,p.type,p.popup_data , dp.url FROM {$wpdb->prefix}_wpptDisplay_Rules dp, {$wpdb->prefix}_wpptPopups p WHERE p.active AND dp.pid = p.id AND {$condition}" , ARRAY_A);	

		$res  	 = self::limit_selection($res);

	

		$arr_ret	= 	$res;

		return $arr_ret;
	}

	private static function limit_selection($o){
		$arr_ret = array();
		if (empty($o))
			return array();
		
		foreach ($o as $id => $res) {
			if(self::iterate_url($res))
				array_push($arr_ret,$res);


		}

		return $arr_ret;
	}


	private static function iterate_url($item){
		$current_url = strtolower(wpptSQA::getCurrentURL());
		$continue = true;
		$item['url'] = preg_split('/[\n\r]+/', $item['url'],-1,PREG_SPLIT_NO_EMPTY);
		foreach ($item['url'] as $key => $value) {
			$value = strtolower($value);
			if (!$continue)
				break;

			if (strcasecmp(substr($value,0,1),'~')===0){
				$continue = ($continue && (strpos(substr($value,1), $current_url)!==false));
			} else {
				$continue = ($continue && (strpos($value, $current_url)>=0));
			}
		}
		return $continue;
	}

	protected static function generate_condition(){

		$condition = "";
		
		if (is_home()){
			$condition = "on_home";
		}
		else if(is_page()){
			$condition = "on_page";	
		}
		else if(is_archive()){
			$condition = "on_archive";	
		}
		else if(is_single()){
			$condition = "on_post";	
		}else{
			$condition = '0=1'; /* No popups */
		}
		return $condition;
	}

	function __construct($options,$type,$id)
	{
		$this->g_options = get_option('wppt-global-options');

		$_POST 	  	= stripslashes_deep($_POST);
		$_REQUEST 	= stripslashes_deep($_REQUEST);
		$_GET  		= stripslashes_deep($_GET);

		$this->options 	= $options;
		$this->id 		= $id;
		$this->type 	= $type;
		$this->css_spec = (empty($this->g_options['css-specificity'])) ? 'html body .mfp-contentcode' : $this->g_options['css-specificity'];
		
		$this->css_reset = wpptThemes::get_css_reset($this->css_spec);
		$this->skeleton  = wpptThemes::get_skeleton();

	}

	public function image_popup($o){
		$pd 		= isset($o['popup_data']) ? $o['popup_data'] : array();
		$image 		= $pd['image'];
		$content 	= "<img src=\"{$image}\"  style=\"width:100%;height:auto;\"/>";
		$new_tab 	= (empty($pd['new_tab'])) ? '' : ' target="_blank "';

		if (!empty($pd['url'])){
			$url 		= $pd['url'];
			$content 	= "<a href=\"{$url}\"{$new_tab}>{$content}</a>";
		}
		$content = "<style type=\"text/css\">{$this->css_reset}</style>" . $content;

		$content = trim(preg_replace('/\s+/', ' ', $content));
		$content = str_replace('\'', '\\\'', $content);

		return $content;
	}


	public function html_popup($o){
		$content = wpptSQA::val('popup_data[html]',$o,false,false);
		
		$content =  $content;

		$content = trim(preg_replace('/\s+/', ' ', $content));

		$content = str_replace('\'', '\\\'', $content);

		

		return $content;
	}

	public function email_popup($o){
		
		$hidden_fields 	= $this->generate_hidden_fields($o);


		$email_action	= wpptSQA::val('popup_data[email-action]',$o);
		$email_field	= wpptSQA::val('popup_data[email-field]',$o);

		$theme_options 	= array('header' 		 => wpptSQA::val('popup_data[email-header]',$o,false,false),						
								'content' 		 =>  wpptSQA::val('popup_data[email-text]',$o,false,false),
								'submit-caption' =>  wpptSQA::val('popup_data[email-submit]',$o),
								'email-field' 	 => $email_field,
								'email-action'	 => $email_action,
								'hidden-fields'	 => $hidden_fields,
								'email-placeholder' => wpptSQA::val('popup_data[email-placeholder]',$o),
								'colour'  		 => wpptSQA::val('popup_data[selected-theme]',$o)
								);
		


		$content 	= wpptThemes::get_email_theme(1,'html body .mfp-contentcode',$theme_options);

		$content = trim(preg_replace('/\s+/', ' ', $content));
		$content = str_replace('\'', '\\\'', $content);
		return $content;
	}

	private function generate_hidden_fields($o){
		if (empty($o['popup_data']['email-hidden']))
			return '';

		$hidden_fields = '';
		foreach ($o['popup_data']['email-hidden'] as $e_name => $e_val) {
			$hidden_fields .= "<input type=\"hidden\" name=\"{$e_name}\" value=\"{$e_val}\" />";
		}

		return $hidden_fields;
	}

	private function generate_open_command($o){

	$arr_conditionals 	= array();
	$final_call 		= '';
	$pageviews 			= wpptSQA::val('b[pageviews]',$o) ;
	$timeout 			= wpptSQA::val('b[seconds]',$o);
	$once_every 		= wpptSQA::val('b[days]',$o);
	

		if (!empty($timeout) && is_numeric($timeout)){
			$timeout = $timeout * 1000;
			$final_call = "window.setTimeout(function(){wppt_do_open();},{$timeout});\n";
		} else{
			$final_call = "wppt_do_open();\n";
		}

		if (!empty($once_every)){
			$final_call .= "wpptsetCookie('wppt-shown-{$this->id}','1');\n";
			array_push($arr_conditionals, "(!wpptgetCookie('wppt-shown-{$this->id}',{$once_every}))");

		}

		if (!empty($pageviews))
			array_push($arr_conditionals, "wppt_pageview_test({$pageviews})");

		if (empty($arr_conditionals))
			return $final_call;

		return 'if (' . $this->generate_show_on($arr_conditionals). "){{$final_call}}";
	}

	private function generate_show_on($c){
		if (empty($c))
			return "";

		$s_ret ="" ;

		foreach ($c as $key => $condition) {
			$s_ret .= " && " .  $condition;
		}

		return substr($s_ret, 4);
	}


	public function get_popup(){
		$o = $this->options;
		$jquery_css = array();

		$p_width 		= wpptSQA::val('popup_data[width]',$o,false,true);
		$p_height 		= wpptSQA::val('popup_data[height]',$o,false,true);
		$close_oc 		= wpptSQA::val('b[close_oc]',$o);
		$close_bu		= wpptSQA::val('b[close_bu]',$o);

		

		$extra_options 	= ",closeOnBgClick : " . (empty($close_oc) ? 'false' : 'true');
		$extra_options 	= ",modal : " . (empty($close_bu) ? 'false' : 'true');
		$style  		= (empty($p_width)) ? '' : "max-width:{$p_width};";
		$style  		.= (empty($p_height)) ? '' : "max-height:{$p_height};";
		$open_command 	= $this->generate_open_command($o);

		

		$content = "";

		switch ($this->type) {
			case 1:
				$content = $this->html_popup($o);
				break;

			case 2:
				$content = $this->image_popup($o);
				break;
			
			case 3:
				$content = $this->email_popup($o);
				break;
			
			
			default:
				# code...
				break;
		}
		$onbeforeopen = '';

		
		//echo sprintf($this->skeleton , $content, $style, implode(',',$jquery_css),$extra_options,$this->generate_open_command($o),$onbeforeopen);

		$options = array(
			 'content' 			=> $content,
			 'style'			=> $style,
			 'jquery_css'		=> implode(',',$jquery_css),
			 'extra_options'	=> $extra_options,
			 'open_command'		=> $open_command,
			 'onbefore_open'	=> $onbeforeopen
			 );

		wpptSQA::arr_val_map($this->skeleton,array($options), true,false);
	}
	
}
 ?>