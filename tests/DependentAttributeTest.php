<?php

use PHPUnit\Framework\TestCase;
use AttriSuite\SelectAttribute;
use AttriSuite\DependentAttribute;
use AttriSuite\AttributeManager;

class DependentAttributeTest extends TestCase {

    public function testDependentAttributeValidation() {
        $vehicleType = new SelectAttribute('vehicle_type', ['Car', 'Motorcycle']);

        $carModels = new SelectAttribute('model', ['Sedan', 'SUV', 'Coupe']);
        $motorcycleModels = new SelectAttribute('model', ['Sport', 'Cruiser']);

        $model = new DependentAttribute('model', []);
        $model->setParentAttributeName('vehicle_type');
        $model->addDependency('Car', $carModels);
        $model->addDependency('Motorcycle', $motorcycleModels);

        $manager = new AttributeManager();
        $manager->addAttribute($vehicleType);
        $manager->addAttribute($model);

        // This should pass, as 'SUV' is a valid model for 'Car'
        $validData = ['vehicle_type' => 'Car', 'model' => 'SUV'];
        $this->assertTrue($manager->validateAttributes($validData));

        // This should fail, as 'Sport' is not a valid model for 'Car'
        $invalidData = ['vehicle_type' => 'Car', 'model' => 'Sport'];
        $this->assertFalse($manager->validateAttributes($invalidData));
    }
}

