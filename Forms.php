<?php
class Forms {
	protected $forms;
	
	public function __construct($forms = array()) {
		$this->forms = $forms;
		get_instance()->load->helper(array('form', 'url'));
		get_instance()->load->library('form_validation', $forms);
		$this->form_validation = get_instance()->form_validation;
		$this->input = get_instance()->input;
	}
	
	/**
	 * Renders a form as HTML and returns it.
	 *
	 * @param string $name Name of the form to render
	 * @param string $action Form action, defaults to current_url()
	 * @param object $object Object to get default values from, useful for edit forms.
	 * @return string
	 * @author Johnny Karhinen
	 */
	public function get($name, $action = NULL, $object = NULL) {
		if(is_null($action))
			$action = current_url();
			
		$out = form_open($action);
		$this->form_validation->set_error_delimiters('<p class="form-error">', '</p>');
		foreach($this->forms[$name] as $item)
			if(is_callable(array($this, 'form_element_'.$item['type'])))
				$out .= call_user_func(array($this, 'form_element_'.$item['type']), $item, $object);
				
		return $out.form_submit('submit', 'Ok »').form_close();
	}
	
	/**
	 * Returns a stdClass object populated with the values of the form fields.
	 *
	 * @param string $name Name of the form to get object for
	 * @param string $object Name of the object to get
	 * @return object
	 * @author Johnny Karhinen
	 */
	public function get_object($name, $object = NULL) {
		$object = new stdClass;
		foreach($this->forms[$name] as $item)
			if( ! isset($item['object']) || $item['object'] == $object)
				$object->{$item['field']} = $this->input->post($item['field']);
		return $object;
	}
	
	/**
	 * Validates the form, and returns TRUE if it succeeds.
	 *
	 * @param string $name Name of the form
	 * @return bool
	 * @author Johnny Karhinen
	 */
	public function validate($name) {
		return $this->form_validation->run($name);
	}
	
	protected function set_value($field, $object = NULL) {
		return is_null($object) ? set_value($field) : set_value($field, $object->$field);
	}
	
	protected function get_values(Array $item) {
		if( ! isset($item['values']))
			$values = array();
		elseif(is_callable($item['values']))
			$values = call_user_func($item['values']);
		else
			$values = $item['values'];
		return $values;
	}
	
	protected function form_element_text(Array $item, $object = NULL) {
		return $this->form_element_generic('form_input', $item, $object);
	}
	
	protected function form_element_password(Array $item, $object = NULL) {
		return $this->form_element_generic('form_password', $item, $object);		
	}
	
	protected function form_element_textarea(Array $item, $object = NULL) {
		return $this->form_element_generic('form_textarea', $item, $object);
	}
	
	protected function form_element_generic($function, Array $item, $object = NULL) {
		$out = form_label($item['label'], $item['field']);
		$out .= $function(array(
			'name' => $item['field'],
			'value' => $this->set_value($item['field'], $object)
		));
		$out .= form_error($item['field']);	
		return $out;
	}
	
	protected function form_element_dropdown(Array $item, $object = NULL) {
		$out = form_label($item['label'], $item['field']);
		$out .= form_dropdown($item['field'], $this->get_values($item), $this->set_value($item['field'], $object));
		$out .= form_error($item['field']);
		return $out;
	}
	
	protected function form_element_checkbox(Array $item, $object = NULL) {
		$out = form_label(form_checkbox($item['field'], 1, (bool) $this->set_value($item['field'], $object)).' '.$item['label'], $item['field']);
		$out .= form_error($item['field']);
		return $out;
	}
}