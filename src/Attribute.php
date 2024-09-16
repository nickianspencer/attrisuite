<?php

namespace AttriSuite;

class Attribute implements AttributeInterface {
    protected $name;
    protected $type;

    /**
     * Constructor for the Attribute Class.
     * 
     * @param string $name
     * @param string $type
     */
    public function __construct($name, $type) {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * Get the name of the attribute.
     * 
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Get the type of the attribute.
     * 
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * Validate the value of the attribute based on its type.
     * 
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool {
        // Basic validation logic depending on type
        switch ($this->type) {
            case 'text':
                return is_string($value);
            case 'number':
                return is_numeric($value);
            case 'boolean':
                return is_bool($value);
            case 'select':
                return is_string( $value );
            case 'multiselect':
                return is_array( $value );
            default:
                return false;
        }
    }
}
