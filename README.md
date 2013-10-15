Zen Fields for SilverStripe
=======================

Syntactic sugar for your SilverStripe FieldLists.

## Basic Usage

```php
<?php
$fields
  ->text("FirstName")
  ->numeric("Age");
```

Is syntactic sugar for:
```php
<?php
$fields->addFieldToTab("Root.Main", TextField::create("FirstName", "First Name"),"Content");
$fields->addFieldToTab("Root.Main", TextField::create("Age", "Age"),"Content");
```

In FieldLists with TabSets, the tab is assumed to be "Root.Main", unless otherwise specified. Labels are automatically generated from field names when not provided. Wildcard field methods are any subclass of FormField, with the "Field" suffix removed, and with a lowercase first letter. Examples:
* text() -> TextField
* currency() -> CurrencyField
* treeDropdown() -> TreeDropdownField

## Specifying tabs
```php
<?php
$fields
  ->tab("PersonalInfo")
    ->text("FirstName")
    ->numeric("Age")
  ->tab("Qualifications")
    ->grid("Qualifications","Qualifications", $this->Qualifications(), GridFieldConfig_RecordEditor::create());
    
```

## Mutating fields after instantiation
For chainability, each method returns the FieldList, so in order to access the FormField object, you must use the configure() accessor, followed by end() to return to the FieldList object.
```php
<?php
$fields
  ->dropdown("PickOne")
    ->configure()
      ->setSource(array('1' => 'One', '2' => 'Two'))
      ->setEmptyString("-- None --")
    ->end()
  ->text("Title");
```

Is syntactic sugar for:
```php
<?php
$fields->addFieldToTab("Root.Main", DropdownField::create("PickOne","Pick One")
  ->setSource(array('1' => 'One', '2' => 'Two'))
  ->setEmptyString("-- None --")
);
$fields->addFieldToTab("Root.Main", TextField::create("Title"));
```

## Grouping fields
You can instantiate a FieldGroup with the group() method.
```php
<?php
$fields
  ->text("Name")
  ->group()
    ->text("Address")
    ->text("City")
    ->text("PostalCode")
  ->end();
```

Is syntactic sugar for:
```php
<?php
$fields->addFieldToTab("Root.Main", TextField::create("Name"));
$fields->addFieldToTab("Root.Main", FieldGroup::create(
  TextField::create("Address"),
  TextField::create("City")
  TextField::create("PostalCode")
));
```
## Extras
There are a few shortcut methods for adding common field configurations.
```php
<?php
$fields
  ->imageUpload("MyImage")
  ->hasManyGrid("RelatedObjects","Related objects", $this->RelatedObjects())
    ->configure()->addDragAndDrop("Sort")->end();
```
