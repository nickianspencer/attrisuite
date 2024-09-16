<?php

use PHPUnit\Framework\TestCase;
use AttriSuite\Attribute;
use AttriSuite\AttributeManager;

class AttributeHistoryTest extends TestCase {

    public function testAttributeHistory() {
        $price = new Attribute('price', 'number');
        $manager = new AttributeManager();
        $manager->addAttribute($price);

        // Update the attribute and log changes
        $manager->updateAttribute('price', 19.99, 'admin');
        $manager->updateAttribute('price', 24.99, 'admin');

        // Check that the history is recorded correctly
        $history = $manager->getAttributeHistory('price');
        $this->assertCount(2, $history);
        $this->assertEquals(19.99, $history[0]['new_value']);
        $this->assertEquals(24.99, $history[1]['new_value']);
        $this->assertEquals('admin', $history[1]['changed_by']);
    }
}