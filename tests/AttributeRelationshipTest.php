<?php

use PHPUnit\Framework\TestCase;
use AttriSuite\SelectAttribute;
use AttriSuite\AttributeManager;
use AttriSuite\AttributeRelationship;

class AttributeRelationshipTest extends TestCase {

    public function testAttributeRelationship() {
        $colorAttribute = new SelectAttribute('color', ['Red', 'Blue', 'Green']);
        $sizeAttribute = new SelectAttribute('size', ['Small', 'Medium', 'Large']);

        $relationship = new AttributeRelationship('one-to-one', $colorAttribute);
        $relationship->addTargetAttribute($sizeAttribute);
        
        // Add rule where 'Red' cannot be 'Small'
        $relationship->addRule(function($source, $targets, $data) {
            $sourceValue = $data[$source->getName()] ?? null;
            foreach ($targets as $target) {
                $targetValue = $data[$target->getName()] ?? null;
                if ($sourceValue === 'Red' && $targetValue === 'Small') {
                    return false;
                }
            }
            return true;
        });

        $manager = new AttributeManager();
        $manager->addAttribute($colorAttribute);
        $manager->addAttribute($sizeAttribute);
        $manager->addRelationship($relationship);

        $dataValid = ['color' => 'Red', 'size' => 'Large'];
        $this->assertTrue($manager->validateAttributes($dataValid));

        $dataInvalid = ['color' => 'Red', 'size' => 'Small'];
        $this->assertFalse($manager->validateAttributes($dataInvalid));
    }
}
