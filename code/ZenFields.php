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
 * Method names are defined by the FormField class, without the "Field" suffix removed, with a lowercase first letter.
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


	/**
	 * @var string  The name of the current tab
	 */
	protected $tab = "Main";



	/**
	 * @var  FormField The most recently added FormField
	 */
	protected $field;




	/**
	 * A wildcard method for accepting any FormField object as a method.
	 * Ex: text(), currency(), dropdown(), treeDropdown(), htmlEditor()
	 *
	 * @param   string The method being called
	 * @param   array The arguments to the method
	 * @return  FieldList
	 */
	public function __call($method, $args) {
		$formFieldClass = ucfirst($method)."Field";
		$getter = "get".ucfirst($method);
		if($this->owner->hasMethod($getter)) {
			return $this->owner->$getter();
		}
		if(is_subclass_of($formFieldClass, "FormField")) {
			if(!isset($args[0])) {
				user_error("FieldList::{$method} -- Missing argument 1 for field name", E_ERROR);
			}
			if(!isset($args[1])) {
				$args[1] = FormField::name_to_label($args[0]);
			}

			$field = Injector::inst()->createWithArgs($formFieldClass, $args);
			$this->add($field);
			$this->field = $field;
			$field->FieldList = $this->owner;
			return $this->owner;
		}
		else {
			user_error("FieldList::{$method} -- $formFieldClass is not a FormField.",E_ERROR);
		}
	}




	/**
	 * Adds a FormField to the FieldList
	 *
	 * @param   FormField The field to add
	 */
	public function add(FormField $field) {
		if($this->owner->hasTabSet()) {
			$before = ($this->tab == "Main" && $this->owner->dataFieldByName("Content")) ? "Content" : null;
			$this->owner->addFieldToTab("Root.{$this->tab}",$field, $before);
		}
		else {
			$this->owner->push($field);
		}
	}


	public function addField(FormField $field) {
		$this->add($field);

		return $this->owner;
	}



	/**
	 * Sets the current tab
	 *
	 * @param  string The Tab name
	 * @return  FieldList
	 */
	public function tab($tabname) {
		$this->tab = $tabname;

		return $this->owner;
	}

	/**
	 * A shortcut for creating an {@link UploadField} configured for images
	 *
	 * @return  FieldList
	 */
	public function imageUpload() {
		$field = Injector::inst()->createWithArgs("UploadField",func_get_args());
		$field->imagesOnly();
		$this->add($field);
		$this->field = $field;
		$field->FieldList = $this->owner;

		return $this->owner;
	}




	/**
	 * A shortcut for creating a {@link GridField} configured for a simple has_many relation
	 *
	 * @param  string $name The name of the grid
	 * @param  string $title The title of the grid
	 * @param  SS_List $list The grid data
	 * @return   FieldList
	 */
	public function hasManyGrid($name, $title = null, $list = null) {
		$grid = Injector::inst()->createWithArgs("GridField", array($name, $title, $list, GridFieldConfig_RecordEditor::create()));
		if($list && $list instanceof UnsavedRelationList) {
			$this->add($h = new HeaderField(_t('ZenFields.RELATIONNOTSAVED','You can add records once you have saved for the first time.')));
		}
		else {
			$this->add($grid);
		}

		$this->field = $grid;
		$grid->FieldList = $this->owner;

		return $this->owner;
	}




	/**
	 * Gets a specific field, or the most recently added field
	 * Note: Accessing a FormField object is deprecated as field() conflicts with FormField::Field().
	 * Use configure() instead.
	 *
	 * @param   string $fieldName If passed a field name, get that specific field by name
	 * @return  FormField
	 */
	public function field($fieldName = null) {
		return $fieldName ? $this->owner->dataFieldByName($fieldName) : $this->field;
	}


	/**
	 * Gets the most recently added FormField
	 *
	 * @return FormField
	 */
	public function configure() {
		return $this->field;
	}



	/**
	 * A shortcut that makes FieldList::removeByName() chainable
	 *
	 * @param  string $fieldName The field to remove
	 * @param  bool   $dataFieldOnly If true, do not remove dataless fields
	 * @return   FieldList
	 */
	public function removeField($fieldName = null, $dataFieldOnly = false) {
		$this->owner->removeByName($fieldName, $dataFieldOnly);

		return $this->owner;
	}




	/**
	 * Adds a FieldGroup to the FieldList
	 *
	 * @return  FieldGroup
	 */
	public function group() {
		$group = FieldGroup::create();
		$group->FieldList = $this->owner;
		$this->add($group);
		return $group;
	}




	/**
	 * Defines all possible methods for this class. Used to support wildcard methods
	 *
	 * @return array
	 */
	public function allMethodNames() {
		$methods = array (
			'add',
			'tab',
			'field',
			'configure',
			'group',
			'hasmanygrid',
			'imageupload',
			'removefield',
			'addfield'
		);
		foreach(SS_ClassLoader::instance()->getManifest()->getDescendantsOf("FormField") as $field) {
			$methods[] = strtolower(preg_replace('/Field$/',"",$field));
		}
		return $methods;
	}


}
