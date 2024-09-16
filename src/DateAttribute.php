<?php

namespace AttriSuite;

class DateAttribute extends Attribute {

    /**
     * Constructor for DateAttribute
     * 
     * @param string $name
     */
    public function __construct($name) {
        parent::__construct($name, 'date');
    }

    /**
     * Validate the value by checking if it is a valid date string.
     * 
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool {
        return (bool) strtotime($value);
    }
}