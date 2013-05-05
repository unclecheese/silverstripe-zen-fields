<?php

/**
 * Defines a GridField for use with ZenFields
 * 
 * @author  UncleCheese <unclecheese@leftandmain.com>
 * @package  ZenFields
 */
class ZenGridField extends Extension {




	/**
	 * A shortcut for adding drag-and-drop to a {@link GridField}. This method relies on thirdparty
	 * plugins, which are loosely defined in the ZenFields _config layer.
	 * 
	 * @param   string $fieldName The sortable field name
	 * @return GridFieldConfig
	 */
	public function addDragAndDrop($fieldName = "SortOrder") {
		$supportedClass = null;
		foreach(Config::inst()->get("ZenFields","gridfield_sortable_component_classes") as $c) {
			if(class_exists($c)) {
				$supportedClass = $c;
				break;
			}
		}		
		if(!$supportedClass) {
			user_error("GridField::addDragAndDrop requires that you install a module  to support drag-and-drop ordering for GridField. See undefinedoffset/sortablegridfield or ajshort/silverstripe-gridfieldextensions on pakagist.org.",E_ERROR);
		}

		$component = Injector::inst()->createWithArgs($supportedClass, array($fieldName));
		$this->owner->getConfig()->addComponent($component);

		return $this->owner;
	}





}