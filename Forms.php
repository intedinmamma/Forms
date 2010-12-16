<?php
class Forms {
	protected $forms;
	
	public function __construct($forms = array()) {
		$this->forms = $forms;
		$this->load->helper('form');
		$this->load->library('form_validation', $forms);
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
		foreach($this->forms[$name] as $item) {
			switch($item['type']) {
				case 'text':
					$out .= $this->generic_form_element('form_input', $item);
					break;
				case 'password':
					$out .= $this->generic_form_element('form_password', $item);
					break;
				case 'textarea':
					$out .= $this->generic_form_element('form_textarea', $item);
					break;
				case 'dropdown':
					$out .= form_label($item['label'], $item['field']);
					$out .= form_dropdown($item['field'], $item['values'], $this->set_value($item['field'], $object));
					$out .= form_error($item['field']);
					break;
				case 'checkbox':
					$out .= form_label(form_checkbox($item['field'], 1, (bool) $this->set_value($item['field'], $object)).' '.$item['label'], $item['field']);
					$out .= form_error($item['field']);
					break;
			}
		}
		return $out.form_submit('submit', 'Ok »').form_close();
	}
	
	/**
	 * Returns a generic stdClass object populated with the values of the form fields.
	 *
	 * @param string $name Name of the form to get object for
	 * @return object
	 * @author Johnny Karhinen
	 */
	public function get_object($name) {
		$object = new stdClass;
		foreach($this->forms[$name] as $item)
			if( ! isset($item['object']))
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
	
	protected function generic_form_element($function, $item) {
		$out = form_label($item['label'], $item['field']);
		$out .= $function(array(
			'name' => $item['field'],
			'value' => $this->set_value($item['field'], $object)
		));
		$out .= form_error($item['field']);	
		return $out;
	}
}