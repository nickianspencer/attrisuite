# AttriSuite

**AttriSuite** is a flexible and powerful PHP library designed for managing product attributes in applications such as Product Information Management (PIM) systems. It provides an extensible framework for handling various types of attributes, attribute sets, complex validation logic, attribute relationships, dependent attributes, and attribute profiles.

## Features

- **Attribute Management**: Define and manage various types of attributes such as text, number, select, date, and boolean attributes.
- **Attribute Sets**: Group attributes into sets for easier management and validation.
- **Custom Validation**: Support for custom validation rules to ensure data integrity.
- **Attribute Relationships**: Define relationships between attributes, allowing for dynamic validation rules based on attribute values.
- **Dependent Attributes**: Define attributes whose options or validations depend on the values of other attributes, enabling dynamic and contextual data management.
- **Attribute Profiles**: Group multiple attributes into profiles, making it easy to apply consistent attribute groups to different entities or scenarios.
- **Extensible Design**: Easily extend the library with new attribute types, validation logic, or profile structures.
- **Namespace Support**: Organized under the `AttriSuite` namespace for easy integration into larger projects.

## Installation

Install AttriSuite using Composer:

```bash
composer require nickianspencer/attrisuite
```

Include the Composer autoloader in your project:

```php
require 'vendor/autoload.php';
```

## Getting Started

### Basic Attribute Usage

Start by creating basic attributes such as text, number, or boolean attributes.

```php
use AttriSuite\Attribute;
use AttriSuite\AttributeManager;

$attribute = new Attribute('product_name', 'text');
$manager = new AttributeManager();
$manager->addAttribute($attribute);

$data = ['product_name' => 'Sample Product'];
if ($manager->validateAttributes($data)) {
    echo "All attributes are valid!";
} else {
    echo "Validation failed!";
}
```

### Advanced Attribute Types

AttriSuite supports various attribute types, including select boxes, date fields, and boolean values.

```php
use AttriSuite\SelectAttribute;
use AttriSuite\DateAttribute;
use AttriSuite\BooleanAttribute;

$colorAttribute = new SelectAttribute('color', ['Red', 'Blue', 'Green']);
$dateAttribute = new DateAttribute('release_date');
$booleanAttribute = new BooleanAttribute('is_active');

$manager->addAttribute($colorAttribute);
$manager->addAttribute($dateAttribute);
$manager->addAttribute($booleanAttribute);

$data = [
    'color' => 'Red',
    'release_date' => '2024-09-15',
    'is_active' => true
];

if ($manager->validateAttributes($data)) {
    echo "All attributes are valid!";
} else {
    echo "Validation failed!";
}
```

### Attribute Sets

Group related attributes into sets, allowing for easier management and validation.

```php
use AttriSuite\AttributeSet;

$attribute1 = new Attribute('product_name', 'text');
$attribute2 = new SelectAttribute('color', ['Red', 'Blue', 'Green']);

$attributeSet = new AttributeSet('basic_set');
$attributeSet->addAttribute($attribute1);
$attributeSet->addAttribute($attribute2);

$manager->addAttributeSet($attributeSet);

$data = ['product_name' => 'Sample Product', 'color' => 'Blue'];
if ($manager->validateAttributeSet('basic_set', $data)) {
    echo "Attribute set is valid!";
} else {
    echo "Validation failed!";
}
```

### Attribute Relationships

Define relationships between attributes to enforce complex validation rules based on the values of related attributes.

#### Example: Conditional Validation

Imagine a scenario where the `size` attribute depends on the value of the `color` attribute. If the color is "Red", then the size cannot be "Small".

```php
use AttriSuite\SelectAttribute;
use AttriSuite\AttributeManager;
use AttriSuite\AttributeRelationship;

$colorAttribute = new SelectAttribute('color', ['Red', 'Blue', 'Green']);
$sizeAttribute = new SelectAttribute('size', ['Small', 'Medium', 'Large']);

$relationship = new AttributeRelationship('one-to-one', $colorAttribute);
$relationship->addTargetAttribute($sizeAttribute);

// Define a rule where if color is 'Red', size cannot be 'Small'
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
if ($manager->validateAttributes($dataValid)) {
    echo "Attributes and relationships are valid!";
} else {
    echo "Validation failed due to relationship constraints!";
}

$dataInvalid = ['color' => 'Red', 'size' => 'Small'];
if ($manager->validateAttributes($dataInvalid)) {
    echo "Attributes and relationships are valid!";
} else {
    echo "Validation failed due to relationship constraints!";
}
```

### Dependent Attributes

Define attributes whose options or validation logic depend on the values of other attributes.

#### Example: Vehicle Model Based on Vehicle Type

Let’s define an example where the options for a "Model" attribute depend on the selected "Vehicle Type."

```php
use AttriSuite\SelectAttribute;
use AttriSuite\DependentAttribute;
use AttriSuite\AttributeManager;

// Define parent attribute
$vehicleType = new SelectAttribute('vehicle_type', ['Car', 'Motorcycle']);

// Define child attributes dependent on the parent attribute's value
$carModels = new SelectAttribute('model', ['Sedan', 'SUV', 'Coupe']);
$motorcycleModels = new SelectAttribute('model', ['Sport', 'Cruiser']);

$model = new DependentAttribute('model', []);
$model->setParentAttributeName('vehicle_type');
$model->addDependency('Car', $carModels);
$model->addDependency('Motorcycle', $motorcycleModels);

$manager = new AttributeManager();
$manager->addAttribute($vehicleType);
$manager->addAttribute($model);

// Example data set
$data = ['vehicle_type' => 'Car', 'model' => 'SUV'];

if ($manager->validateAttributes($data)) {
    echo "Attributes and dependencies are valid!";
} else {
    echo "Validation failed due to dependent attributes!";
}
```

### **Profile Management**

**AttriSuite** includes robust profile management features that allow you to group multiple attributes into profiles, making it easy to apply consistent attribute groups to different entities or scenarios. Profiles are uniquely identified by their name, but to prevent accidental overwriting of profiles with the same name, **AttriSuite** includes safeguards.

#### **Adding Profiles**

When adding a new profile, the `addProfile` method checks if a profile with the same name already exists. If it does, an exception is thrown to prevent accidental overwriting, ensuring that your profiles remain distinct and no data is unintentionally lost.

##### **Example: Adding a Profile**

```php
use AttriSuite\Attribute;
use AttriSuite\AttributeProfile;
use AttriSuite\AttributeManager;

// Define attributes
$brand = new Attribute('brand', 'text');
$model = new Attribute('model', 'text');
$warranty = new Attribute('warranty', 'text');

// Create a profile for electronics
$electronicsProfile = new AttributeProfile('Electronics');
$electronicsProfile->addAttribute($brand);
$electronicsProfile->addAttribute($model);
$electronicsProfile->addAttribute($warranty);

$manager = new AttributeManager();
$manager->addProfile($electronicsProfile);

try {
    // Attempting to add another profile with the same name will throw an exception
    $duplicateProfile = new AttributeProfile('Electronics');
    $manager->addProfile($duplicateProfile); // This will throw an exception
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage(); // Output: Error: Profile with the name 'Electronics' already exists.
}
```

#### **Preventing Overwrites**

To prevent profiles with the same name from overwriting each other, **AttriSuite** will:

1. **Check for Existing Profiles:** Before adding a new profile, the `addProfile` method checks if a profile with the same name already exists.
2. **Throw an Exception:** If a duplicate name is detected, an exception is thrown, allowing you to handle the situation appropriately in your application.
3. **Optional Unique Identifiers:** For advanced scenarios, you can implement unique identifiers for profiles, allowing for profiles with the same name but still distinguishing them.

##### **Handling Exceptions**

When an exception is thrown due to a duplicate profile name, you can catch and handle it as needed in your application.

```php
try {
    $manager->addProfile($duplicateProfile);
} catch (\Exception $e) {
    // Handle the exception, such as logging the error or notifying the user
    echo "Profile could not be added: " . $e->getMessage();
}
```

#### **Retrieving Profiles**

You can retrieve and work with profiles by their name, ensuring that the correct profile is used in your operations.

```php
$retrievedProfile = $manager->getProfile('Electronics');
if ($retrievedProfile) {
    echo "Profile retrieved: " . $retrievedProfile->getName();
} else {
    echo "Profile not found.";
}
```

## Directory Structure

```plaintext
AttriSuite/
├── src/
│   ├── Attribute.php
│   ├── AttributeManager.php
│   ├── AttributeSet.php
│   ├── AttributeProfile.php
│   ├── AttributeRelationship.php
│   ├── DependentAttribute.php
│   ├── SelectAttribute.php
│   ├── DateAttribute.php
│   ├── BooleanAttribute.php
│   └── Validators/
│       └── AttributeValidatorInterface.php
├── tests/
│   ├── AttributeTest.php
│   ├── AttributeRelationshipTest.php
│   ├── DependentAttributeTest.php
│   ├── AttributeSetTest.php
│   └── AttributeProfileTest.php
├── composer.json
└── README.md
```

## Running Tests

AttriSuite uses PHPUnit for testing. To run the tests, execute the following command:

```bash
./vendor/bin/phpunit tests
```

Make sure all the tests pass to ensure that your environment is correctly set up.

## Extending AttriSuite

AttriSuite is designed to be extensible. You can easily add new attribute types, custom validation logic, or integrate with external systems. Below is an example of adding a custom validation rule:

```php
use AttriSuite\CustomAttribute;

$customAttribute = new CustomAttribute('custom_field', 'text');
$customAttribute->addValidationRule(function ($value) {


    return strlen($value) > 5;
});

$manager->addAttribute($customAttribute);

$data = ['custom_field' => 'Example'];
if ($manager->validateAttributes($data)) {
    echo "Custom attribute is valid!";
} else {
    echo "Validation failed!";
}
```

## Contributing

Contributions to AttriSuite are welcome! If you'd like to contribute, please follow these steps:

1. Fork the repository.
2. Create a new feature branch.
3. Implement your feature or fix.
4. Write tests for your changes.
5. Submit a pull request with a description of your changes.

## License

AttriSuite is licensed under the MIT License. See the `LICENSE` file for more information.

## Contact

For more information or support, please contact Nicholas Spencer at mrnicholasspencer@gmail.com.