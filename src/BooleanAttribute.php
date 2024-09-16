<?php

namespace AttriSuite;

class BooleanAttribute extends Attribute {

    /**
     * Constructor for BooleanAttribute.
     * 
     * @param string $name
     */
    public function __construct($name) {
        parent::__construct($name, 'boolean');
    }

    /**
     * Validate the value by checking if it is a boolean.
     * 
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool {
        return is_bool($value);
    }
}