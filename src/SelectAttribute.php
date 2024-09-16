<?php

namespace AttriSuite;

class SelectAttribute extends Attribute {
    protected $options = array();

    /**
     * Constructor for SelectAttribute.
     * 
     * @param string $name
     * @param array $options
     */
    public function __construct($name, array $options = array()) {
        parent::__construct($name, 'select');
        $this->options = $options;
    }

    /**
     * Get the available options for this attribute.
     * 
     * @return array
     */
    public function getOptions(): array {
        return $this->options;
    }

    /**
     * Validate the value by checking if it exists in the options array.
     * 
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool {
        return in_array($value, $this->options);
    }
}