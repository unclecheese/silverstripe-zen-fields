<?php



class ZenFieldGroup extends ZenFields {


	public function end() {
		return $this->FieldList;
	}



	public function add(FormField $field) {
		$this->owner->push($field);
		return $this->owner;
	}

}