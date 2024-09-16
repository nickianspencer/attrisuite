<?php

namespace AttriSuite;

class AttributeSet {
    protected $name;
    protected $attributes = array();

    /**
     * Constructor for AttributeSet.
     * 
     * @param string $name
     */
    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * Get the name of the attribute set.
     * 
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Add an attribute to the set.
     * 
     * @param AttributeInterface $attribute
     */
    public function addAttribute(AttributeInterface $attribute) {
        $this->attributes[$attribute->getName()] = $attribute;
    }

    /**
     * Get the attributes in the set.
     * 
     * @return array
     */
    public function getAttributes(): array {
        return $this->attributes;
    }

    /**
     * Validate a set of data against all attributes in the set.
     * 
     * @param array $data
     * @return bool
     */
    public function validateAttributes($data): bool {
        foreach ($this->attributes as $attribute) {
            $name = $attribute->getName();
            if (isset($data[$name]) && !$attribute->validate($data[$name])) {
                return false;
            }
        }
        return true;
    }   
}