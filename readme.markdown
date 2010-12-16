Forms - a form generator library for CodeIgniter
================================================

What it does
------------
*	Generates forms from your form_validation with just a few additions
*	Generates objects from the input to the form

What it doesn't
-------------------
*	Advanced forms (fieldsets etc)
* 	Related database fields and such

How do i use it?
----------------
1.	Install the library
2.	Create a config file using the same format as for the form_validation library. (see CI's user guide for examples)
3.	Add a `'type'` item to the field definition, with a string representing the form item type
4. 	Replace '`form_validation`' with '`forms`' in your controller (if you have your form validation rules in a config file)
5. 	Use `$this->forms->get($name);` to get the HTML for a form
6.	Use `$this->forms->get_object($name);` to get the object with the form input.

What form item types are available?
-----------------------------------
*	text
* 	password
* 	textarea
*	dropdown (add a 'values' element with an array of alternatives)
*	checkbox

I don't get it, could you show me an example?
------------------------------------------------
application/config/forms.php

	$config = array(
		'post' => array(
			array(
				'field' => 'title',
				'label' => 'Title',
				'rules' => 'trim|xss_clean|required',
				'type' => 'text'
			), array(
				'field' => 'body',
				'label' => 'Body',
				'rules' => 'trim|xss_clean|required',
				'type' => 'textarea'
			)
		)
	);

application/controllers/post.php

	<?php
	class Post extends Controller {
		public function __construct() {
			parent::Controller();
		
			$this->load->library('forms');
			$this->load->helper('url');
		}
	
		public function add() {
			if($this->forms->validate('post')) {
				$post = $this->forms->get_object('post');
				$this->db->insert('posts', $post);
				$post->id = $this->db->insert_id();
				redirect("post/view/{$post->id}");
			} else {
				$form = $this->forms->get('post');
				$this->load->view('post/add', array('form' => $form));
			}
		}
	
		public function edit($id) {
			$post = $this->db->where('id', (int) $id)->get('posts')->row();
			
			if($this->forms->validate('post')) {
				$post = $this->forms->get_object('post');
				$this->db->update('posts', $post, array('id' => (int) $id));
				redirect("post/view/{(int) id}");
			} else {
				$form = $this->forms->get('post', NULL, $post);
				$this->load->view('post/add', array('form' => $form));
			}
		}
	}