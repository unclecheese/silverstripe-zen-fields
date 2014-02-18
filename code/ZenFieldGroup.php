<?php


/**
 * Defines a FieldGroup for usage with ZenFields
 * 
 * @author Uncle Cheese <unclecheese@leftandmain.com>
 * @package  ZenFields 
 */
class ZenFieldGroup extends ZenFields {



	/**
	 * Gets the parent FieldList
	 * 
	 * @return  FieldList
	 */
	public function end() {
		return $this->FieldList;
	}



	/**
	 * Adds a field to the FieldGroup
	 * 
	 * @param  FormField The field to add
	 * @return  FieldGroup
	 */
	public function add(FormField $field) {
		$this->owner->push($field);
		return $this->owner;
	}

}