<?php

use PHPUnit\Framework\TestCase;
use AttriSuite\Attribute;
use AttriSuite\SelectAttribute;
use AttriSuite\DateAttribute;
use AttriSuite\BooleanAttribute;
use AttriSuite\AttributeManager;
use AttriSuite\AttributeSet;

class AttributeTest extends TestCase {

    public function testAttributeCreation() {
        $attribute = new Attribute('product_name', 'text');
        $this->assertEquals('product_name', $attribute->getName());
        $this->assertTrue($attribute->validate('Sample Product'));
    }

    public function testSelectAttributeCreation() {
        $attribute = new SelectAttribute('color', ['Red', 'Blue', 'Green']);
        $this->assertTrue($attribute->validate('Red'));
        $this->assertFalse($attribute->validate('Yellow'));
    }

    public function testDateAttributeCreation() {
        $attribute = new DateAttribute('release_date');
        $this->assertTrue($attribute->validate('2024-09-15'));
        $this->assertFalse($attribute->validate('invalid-date'));
    }

    public function testBooleanAttributeCreation() {
        $attribute = new BooleanAttribute('is_active');
        $this->assertTrue($attribute->validate(true));
        $this->assertFalse($attribute->validate('not a boolean'));
    }

    public function testAttributeManager() {
        $attribute = new Attribute('price', 'number');
        $manager = new AttributeManager();
        $manager->addAttribute($attribute);

        $data = ['price' => 19.99];
        $this->assertTrue($manager->validateAttributes($data));
    }

    public function testAttributeSet() {
        $attribute1 = new Attribute('product_name', 'text');
        $attribute2 = new SelectAttribute('color', ['Red', 'Blue', 'Green']);
        
        $attributeSet = new AttributeSet('basic_set');
        $attributeSet->addAttribute($attribute1);
        $attributeSet->addAttribute($attribute2);

        $manager = new AttributeManager();
        $manager->addAttributeSet($attributeSet);

        $data = ['product_name' => 'Sample Product', 'color' => 'Blue'];
        $this->assertTrue($manager->validateAttributeSet('basic_set', $data));
    }
}
