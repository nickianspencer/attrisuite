<?php

use PHPUnit\Framework\TestCase;
use AttriSuite\Attribute;
use AttriSuite\SelectAttribute;
use AttriSuite\DateAttribute;
use AttriSuite\BooleanAttribute;
use AttriSuite\AttributeManager;
use AttriSuite\AttributeSet;

class AttributeTest extends TestCase {
    /**
     * Test the creation of an attribute.
     */
    public function testAttributeCreation() {
        $attribute = new Attribute('product_name', 'text');
        $this->assertEquals('product_name', $attribute->getName());
        $this->assertTrue($attribute->validate('Sample Product'));
    }

    /**
     * Test the creation of a SelectAttribute.
     */
    public function testSelectAttributeCreation() {
        $attribute = new SelectAttribute('color', ['Red', 'Blue', 'Green']);
        $this->assertTrue($attribute->validate('Red'));
        $this->assertFalse($attribute->validate('Yellow'));
    }

    /**
     * Test the creation of a DateAttribute.
     */
    public function testDateAttributeCreation() {
        $attribute = new DateAttribute('release_date');
        $this->assertTrue($attribute->validate('2024-09-15'));
        $this->assertFalse($attribute->validate('invalid-date'));
    }

    /**
     * Test the creation of a BooleanAttribute.
     */
    public function testBooleanAttributeCreation() {
        $attribute = new BooleanAttribute('is_active');
        $this->assertTrue($attribute->validate(true));
        $this->assertFalse($attribute->validate('not a boolean'));
    }

    /**
     * Test adding and validating attributes with the AttributeManager.
     */
    public function testAttributeManager() {
        $attribute = new Attribute('price', 'number');
        $manager = new AttributeManager();
        $manager->addAttribute($attribute);

        $data = ['price' => 19.99];
        $this->assertTrue($manager->validateAttributes($data));
    }

    /**
     * Test the creation and validation of an AttributeSet.
     */
    public function testAttributeSet() {
        $attribute1 = new Attribute('product_name', 'text');
        $attribute2 = new SelectAttribute('color', ['Red', 'Blue', 'Green']);
        
        $attributeSet = new AttributeSet('basic_set');
        $attributeSet->addAttribute($attribute1);
        $attributeSet->addAttribute($attribute2);

        $manager = new AttributeManager();
        $manager->addAttributeSet($attributeSet);

        $data = ['product_name' => 'Sample Product', 'color' => 'Blue'];
        $this->assertTrue($manager->validateAttributesSet('basic_set', $data));

        // Verify that the set name is correct
        $this->assertEquals('basic_set', $attributeSet->getName());
    }
}