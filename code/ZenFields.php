<?php

/**
 * Provides shortcuts to adding fields to the main tab.
 *
 * Usage:
 * <code>
 * $fields->text("MyField");
 * // Result: $fields->addFieldToTab("Root.Main", TextField::create("MyField", "My Field"));
 *
 * $fields->dropdown("MyField", "My label", $source);
 * // Result: $fields->addFieldToTab("Root.Main", DropdownField::create("MyField", "My label", $source));
 *
 * </code>
 * Method names are defined by the FormField class, without the word "Field", with a lowercase first letter.
 * e.g. 
 * CurrencyField -> currency()
 * TreeDropdownField -> treeDropdown()
 *
 *
 * @author Uncle Cheese <unclecheese@leftandmain.com>
 * @package ZenFields
 *
 */
class ZenFields extends Extension {

	protected $tab = "Main";


	protected $field;



	public function __call($method, $args) {
		$formFieldClass = ucfirst($method)."Field";
		if(is_subclass_of($formFieldClass, "FormField")) {
			if(!isset($args[0])) {
				user_error("FieldList::{$method} -- Missing argument 1 for field name", E_ERROR);
			}
			if(!isset($args[1])) {
				$args[1] = FormField::name_to_label($args[0]);
			}
			for($i = 2; $i <= 9; $i++) {
				if(!isset($args[$i])) $args[$i] = null;
			}						
			$field = Object::create($formFieldClass, $args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7], $args[8], $args[9]);
			$this->add($field);
			$this->field = $field;
			$field->FieldList = $this->owner;
			return $this->owner;
		}
		else {
			user_error("FieldList::{$method} -- $formFieldClass is not a FormField.",E_ERROR);
		}
	}



	public function add(FormField $field) {
		if($this->owner->hasTabSet()) {
			$before = ($this->tab == "Main" && $this->owner->dataFieldByName("Content")) ? "Content" : null;
			$this->owner->addFieldToTab("Root.{$this->tab}",$field, $before);
		}
		else {
			$this->owner->push($field);
		}		
	}



	public function tab($tabname) {
		$this->tab = $tabname;
		return $this->owner;
	}



	public function field() {
		return $this->field;
	}




	public function group() {
		$group = FieldGroup::create();
		$group->FieldList = $this->owner;
		$this->add($group);
		return $group;	
	}




	public function allMethodNames() {
		$methods = array (			
			'tab',
			'field',
			'group'
		);
		foreach(SS_ClassLoader::instance()->getManifest()->getDescendantsOf("FormField") as $field) {
			$methods[] = strtolower(str_replace("Field","",$field));
		}

		return $methods;
	}


}