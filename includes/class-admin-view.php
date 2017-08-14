<?php 
/**
 * 
 */
abstract class wpptAdminView 
{
	abstract public function process_request();

	private $one_row 		= '<div class="onerow">%1$s</div>';
	private $full_row  		= '<div class="col12 seperator">%1$s</div>';
	private $form_label	 	= '<div class="col4%1$s">%2$s</div>';
	private $row_col3	 	= '<div class="col3%1$s">%2$s</div>';
	private $row_col2	 	= '<div class="col2%1$s">%2$s</div>';
	private $row_col_gen 	= '<div class="col%1$s">%2$s</div>';

	private $form_input 	= '<div class="col8%1$s">%2$s</div>';

	private $elem_textbox 	= '<input type="text" name="%1$s" value="%2$s" %3$s%4$s/>';
	private $elem_checkbox 	= '<input type="checkbox" name="%1$s" value="%2$s" %3$s/> %4$s';
	private $radio 			= '<input type="radio" name="%1$s" value="%2$s" %3$s/> %4$s';

	private $select_list 	= '<select name="%1$s">%2$s</select>';
	private $select_option 	= '<option value="%1$s" %2$s>%3$s</option>';

	private $text_area	 	= '<textarea name="%1$s" %3$s/>%2$s</textarea>';

	public function getCol($n){
		return sprintf($this->row_col_gen,$n.'%1$s','%2$s');
	}

	public function getOneRow(){
		return $this->one_row;
	}
	
	public function getCol4(){
		return $this->form_label;
	}

	public function getCol3(){
		return $this->row_col3;
	}

	public function getCol2(){
		return $this->row_col2;
	}

	public function constructCustom($label='&nbsp;',$other_cols){
		$label_col 	= sprintf($this->form_label, ' formtextbox',$label);
		return sprintf($this->one_row,$label_col . $other_cols);
	}

	public function outputSeperator($seperator = '&nbsp;'){
		$html = sprintf($this->one_row, $seperator);
		echo $html;
	}

	//TODO: Implement
	public function outputTextarea($name,$label='&nbsp;',$explain = '',$id=''){
		$html 			= '';
		$explain 		= $this->getExplain($explain);
		$input 			= $this->getTextarea($name,$id);
		$form_input 	= sprintf($this->form_input, ' last', $input);
		$form_label  	= sprintf($this->form_label, ' formtextbox',$label);

		$html 		= sprintf($this->one_row, $form_label . $form_input) . $explain;
		echo $html;
	}

	public function getTextarea($name,$id=''){
		if (!empty($id))
			$id = " id=\"{$id}\"";
		$value 	= wpptSQA::val($name, $this->options, false, true);
		$html 	= sprintf($this->text_area, $name, $value,$id);
		
		return $html;
	}

	//TODO: Implement
	public function outputRadio($name,$label='&nbsp;',$explain = ''){

		return;
	}

	public function outputTextDesc($name,$label='&nbsp;',$desc='',$id=''){
		$input = $this->getText($name,$id);
		$input = sprintf($this->getCol(6),' formselect',$input);
		$days  = sprintf($this->getCol(2),' formselect last',$desc);
		echo($this->constructCustom($label, $input . $days));
	}
	
	public function output62($label,$first,$second){
		$first = sprintf($this->getCol(6),' formselect',$first);
		$second  = sprintf($this->getCol(2),' formselect last',$second);
		echo($this->constructCustom($label, $first . $second));
	}


	public function outputSelect($name,$label='&nbsp;',$options,$explain = ''){
		$html 			= '';
		$explain 		= $this->getExplain($explain);
		$input 			= $this->getSelect($name, $options);
		$form_input 	= sprintf($this->form_input, ' last', $input);
		$form_label  	= sprintf($this->form_label, ' formtextbox',$label);

		$html 		= sprintf($this->one_row, $form_label . $form_input) . $explain;
		echo $html;
	}

	public function getSelect($name,$options){
		$html_options 	= '';

		foreach ($options as $key => $value) {
			$selected 		 = (strcmp($key, wpptSQA::val($name,$this->options,false,false))===0);
			$selected 		 = ($selected) ? 'selected="selected"' : '';
			$html_options	.= sprintf($this->select_option,$key,$selected, $value);
		}
		$html_options	= sprintf($this->select_list, $name, $html_options);

		return $html_options;
	}

	public function outputText($name,$label='&nbsp;',$explain = '',$id=''){
		$form_label = sprintf($this->form_label, ' formtextbox',$label);
		$input 		=$this->getText($name,$id);
		$explain 	= $this->getExplain($explain);
		$form_input = sprintf($this->form_input, ' last', $input);
		$html 		= sprintf($this->one_row, $form_label . $form_input) . $explain;

		echo $html;
	}

	public function getText($name,$id='',$class=''){
		$input_val	= wpptSQA::val($name,$this->options,false,true);

		if (!empty($id)) $id = " id=\"{$id}\"";
		if (!empty($class)) $class = " class=\"{$class}\"";

		$input 		= sprintf($this->elem_textbox, $name, $input_val,$class,$id);
		return $input;
	}

	public function outputColorText($name,$label='&nbsp;',$explain = ''){
		$form_label = sprintf($this->form_label, ' formtextbox',$label);
		$input 		= $this->getColorText($name);

		$explain 	= $this->getExplain($explain);
		$form_input = sprintf($this->form_input, ' last', $input);
		$html 		= sprintf($this->one_row, $form_label . $form_input) . $explain;
		echo $html;
	}

	public function getColorText($name){
		$input_val	= wpptSQA::val($name,$this->options,false,true);
		$input 		= $this->getText($name,'','colpick {adjust:false}');
		return $input;

	}




	public function outputCheckbox($name,$label='&nbsp;',$explain = ''){
		$form_label = sprintf($this->form_label, ' formtextbox',$label);
		$input_elem = $this->getCheckbox($name, $explain);
		$form_input = sprintf($this->form_input, ' formselect last', $input_elem);
		$html 		= sprintf($this->one_row, $form_label . $form_input);

		echo $html;
	}

	public function getCheckbox($name, $explain){
		$checked	= wpptSQA::checked($name,$this->options,true);
		$input_elem = sprintf($this->elem_checkbox,$name,'1', $checked, $explain);
		return $input_elem;

	}

	public function getExplain($explain=''){
		if (empty($explain))
			return '';

		$e_label 	= sprintf($this->form_label,'','&nbsp;');
		$e_text 	= sprintf($this->form_input,' last explain', $explain);
		$explain	= sprintf($this->one_row, $e_label . $e_text);
		
		return $explain;
	}


}

 ?>