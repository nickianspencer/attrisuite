<?php

namespace AttriSuite;

class AttributeProfile {
    protected $name;
    protected $attributes = array();

    /**
     * Constructor for AttributeProfile
     * 
     * @param string $name
     */
    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * Get the name of the profile
     * 
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Add an attribute to the profile
     * 
     * @param AttributeInterface $attribute
     */
    public function addAttribute(AttributeInterface $attribute) {
        $this->attributes[$attribute->getName()] = $attribute;
    }

    /**
     * Get all attributes in the profile
     * 
     * @return array
     */
    public function getAttributes(): array {
        return $this->attributes;
    }

    /**
     * Validate data against all attributes in the profile.
     * 
     * @param array $data
     * @return bool
     */
    public function validate(array $data): bool {
        foreach ($this->attributes as $attribute) {
            $name = $attribute->getName();
            if (isset($data[$name]) && !$attribute->validate($data[$name])) {
                return false;
            }
        }
        return true;
    }
}