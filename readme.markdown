Forms - a form generator library for CodeIgniter
================================================

What it does
------------
*	Generates forms from your form_validation with just a few additions
*	Generates objects from the input to the form

What it does not do
-------------------
*	Advanced forms (fieldsets etc)
* 	Related database fields and such

How do i use it?
----------------
1.	Install the library
2.	Create a config file using the same format as for the form_validation library. (see CI's user guide for examples)
3.	Add a 'type' item to the field definition, with a string representing the form item type
4. 	Replace 'form_validation' with 'forms' in your controller (if you have your form validation rules in a config file)
5. 	Use $this->forms->get($name); to get the HTML for a form
6.	Use $this->forms->get_object($name); to get the object with the form input.

What form item types are available?
-----------------------------------
*	text
* 	password
* 	textarea
*	dropdown (add a 'values' element with an array of alternatives)
*	checkbox

I don't get it, could you show me some examples?
------------------------------------------------
Working on it! :-)