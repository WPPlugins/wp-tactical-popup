<?php 

/**
* 
*/
class wpptThemes  

{
	protected static $css_reset =
	 '{$spec} * { margin:0; padding:0; }
	 {$spec} * {
			border-radius: 0;
			font-size:medium;
			font-family:"Times New Roman",Georgia,Serif;
			margin: 0;
			padding: 0;
			text-shadow:none;
			border: 0;
			outline: 0;
			font-size: 100%;
			line-height: 1.5em;
			text-decoration: none;
			font-weight:normal;
			box-shadow:none;
			background:none;
			vertical-align: baseline; 
			-moz-box-sizing: border-box; 
			-webkit-box-sizing: border-box; 
			box-sizing:border-box;
		}
	 {$spec} p { margin:5px 0 10px 0; }
	 {$spec} div{background:transparent;}

	{$spec} img{
		display:block;
		border:0;
	}
	';

	protected static $bullet_style = '{$spec} ul {
    list-style: none;
    padding:0;
    margin:0;
}

{$spec} li { 
    padding-left: 2em; 
    text-indent: 0.5em;
    font-size:1.2em;
    font-weight:800;
}

{$spec} li:before {
    content: "• ";
    color: {$colour5};
}';


protected static $skeleton = "
{\$open_command}
function wppt_do_open(){
	if (wppt_launched==true)
		return;
	wppt_launched=true;

jQuery.mp_arv_scoped.open({ 
  items: {contentcode:'{\$content}'},
  type: 'inline',
  mainClass: 'mfp-fade',
  inline: {
    markup: '<div class=\"white-popup\" style=\"{\$style}\"><div class=\"mfp-close\"></div>'+
            '<div class=\"mfp-contentcode\"></div>'+
            '</div>'
  }
   {\$extra_options},
   closeMarkup: '<div class=\"mfp-set-outside\"><button class=\"mfp-close mfp-new-close\" type=\"button\" title=\"Close (Esc)\"></button></div>'
  ,callbacks: {
    markupParse: function(template, values, item) {},
    beforeOpen: function() {{\$onbefore_open}}
    
  }


});
jQuery('.mfp-bg').css({{\$jquery_css}});
}
";

	protected static $email_themes =array('1' => array(
			'css' =>  
'
{$spec} .triangle-left {padding:0;width: 0;height: 0;border-style: solid;border-width: 15px 15px 0 15px;border-color: {$colour3} transparent transparent transparent;margin: 0 auto;}
{$spec} input[type="text"]{border:1px solid {$colour3};	width:60%;	height:35px;	line-height: 35px;   vertical-align: middle;     -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;}
{$spec} input[type="submit"]{	
		width:30%;	
		height:35px;	
		line-height: 35px;    
		vertical-align: middle;  
		-moz-box-sizing: border-box; 
		-webkit-box-sizing: border-box; 
		box-sizing: border-box;  
		border:0;  
		background: {$colour3};  
		color:white;}

{$spec} input[type="submit"]:hover{
	background:{$colour5};
	color:{$colour6};
}
{$spec} h2 {font-size:1.6em;}
{$spec} .main-ct {font-size:1.4 em;}
'

,'body'=> '<div style="background:{$colour1};">{$css}<div style="background:{$colour3};"><h2 style="color:{$colour4};padding:5px;text-align:center;">{$header}</h2></div><div class="triangle-left" style=""></div>
					<div style="padding:5px;color:{$colour2};">{$content}</div>
					<div style="padding:5px;color:{$colour2};" class="main-ct">
					<form action="{$email-action}" method="POST">
						{$hidden-fields}
						<input style="width:70%;" type="text" name="{$email-field}" placeholder="{$email-placeholder}"/>
						<input style="width:29%;" type="submit" name="{$submit-name}" value="{$submit-caption}"/>
					</form>
					</div>
				</div></div>'
			)
		);

	protected static $colour_shemes = array(
		'blue' => array(
				/* Background */
				"colour1" => "#FFFFFF",
				/* Background text*/
				"colour2" => "#000000",
				/* Block */
				"colour3" => "#21465f",
				/* Block text */
				"colour4" => "#FFFFFF",
				/* accent */
				"colour5" => "#333F4C",
				/* accent text */
				"colour6" => "#FFFFFF"
			),
		'green' => array(				
				/* Background */
				"colour1" => "#FFFFFF",
				/* Background text*/
				"colour2" => "#000000",
				/* Block */
				"colour3" => "#1C4A00",
				/* Block text */
				"colour4" => "#FFFFFF",
				/* accent */
				"colour5" => "#333F4C",
				/* accent text */
				"colour6" => "#FFFFFF")
		);
	
	public static function get_skeleton(){
		return self::$skeleton;
	}

	public static function get_css_reset($specificity){
		//arr val map takes an array  of option arrays
		$spec = array(array('spec'=> $specificity));
		return wpptSQA::arr_val_map(self::$css_reset,$spec ,false,false);
	}

	public static function get_email_theme($key,$specificity,$theme_options){
		$theme 		 = self::$email_themes[$key];
		$css_reset 	 = self::get_css_reset($specificity);
		
		$theme_css 	 = $theme['css'] . self::$bullet_style;

		$css 	 	   			= "<style type=\"text/css\">{$css_reset}{$theme_css}</style>";
		
		$theme_options['css']	= $css;
		$theme_options['spec']	= $specificity;

		if (isset($theme_options['colour']) && isset(self::$colour_shemes[$theme_options['colour']] )){

			$theme_options = array_merge($theme_options,self::$colour_shemes[$theme_options['colour']]);				

		} elseif (isset($theme_options['colour']) && strcasecmp($theme_options['colour'], 'custom')===0){

		} else {
			$theme_options = array_merge($theme_options,self::$colour_shemes['blue']);						
		}
		$theme_options['css']	= wpptSQA::arr_val_map($theme_options['css'],array($theme_options),false,false);

		return wpptSQA::arr_val_map($theme['body'],array($theme_options),false,false);

	}
}
 ?>