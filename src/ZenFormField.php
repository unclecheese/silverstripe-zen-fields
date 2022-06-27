<?php

namespace UncleCheese\ZenFields;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;

/**
 * Defines a FormField for usage with ZenFields
 *
 * @author Uncle Cheese <unclecheese@leftandmain.com>
 * @package  ZenFields
 */
class ZenFormField extends Extension
{
	/**
	 * Gets the parent FieldList
	 *
	 * @return  FieldList
	 */
	public function end() {
		return $this->owner->FieldList;
	}
}
