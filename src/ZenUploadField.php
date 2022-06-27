<?php

namespace UncleCheese\ZenFields;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Core\Extension;

class ZenUploadField extends Extension
{

	/**
	 * A shortcut for setting the validator to only allow known image extensions.
	 *
	 * @return UploadField
	 */
	public function imagesOnly() {
		$this->owner->setAllowedFileCategories('image');

		return $this->owner;
	}
}
