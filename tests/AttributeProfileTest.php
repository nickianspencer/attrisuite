<?php

use PHPUnit\Framework\TestCase;
use AttriSuite\Attribute;
use AttriSuite\AttributeProfile;
use AttriSuite\AttributeManager;

class AttributeProfileTest extends TestCase {

    public function testProfileValidation() {
        $brand = new Attribute('brand', 'text');
        $model = new Attribute('model', 'text');
        $warranty = new Attribute('warranty', 'text');
        $powerConsumption = new Attribute('power_consumption', 'number');

        $electronicsProfile = new AttributeProfile('Electronics');
        $electronicsProfile->addAttribute($brand);
        $electronicsProfile->addAttribute($model);
        $electronicsProfile->addAttribute($warranty);
        $electronicsProfile->addAttribute($powerConsumption);

        $manager = new AttributeManager();
        $manager->addProfile($electronicsProfile);

        $validData = [
            'brand' => 'Samsung',
            'model' => 'Galaxy S21',
            'warranty' => '2 years',
            'power_consumption' => 15
        ];

        $this->assertTrue($manager->validateProfile('Electronics', $validData));

        $invalidData = [
            'brand' => 'Samsung',
            'model' => 'Galaxy S21',
            'warranty' => '2 years',
            'power_consumption' => 'invalid'
        ];

        $this->assertFalse($manager->validateProfile('Electronics Invalid', $invalidData));
    }

    public function testDuplicateProfileName() {
        $profile1 = new AttributeProfile('Electronics');
        $profile2 = new AttributeProfile('Electronics');
    
        $manager = new AttributeManager();
        $manager->addProfile($profile1);
    
        $this->expectException(\Exception::class);
        $manager->addProfile($profile2); // This should throw an exception
    }
}