<?php


/**
 * Defines an UploadField for use with ZenFields
 * 
 * @author  UncleCheese <unclecheese@leftandmain.com>
 * @package  ZenFields
 */

class ZenUploadField extends Extension {



	/**
	 * A shortcut for setting the validator to only allow known image extensions.
	 * 
	 * @return   UploadField
	 */
	public function imagesOnly() {
		$this->owner->getValidator()->setAllowedExtensions(array('jpg','jpeg','png','gif'));

		return $this->owner;
	}
}
